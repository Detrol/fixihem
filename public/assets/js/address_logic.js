let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

$(document).ready(function() {
    let driveLocationData = JSON.parse(document.getElementById('serviceDriveLocationData').textContent);
    if (driveLocationData.some(item => item !== null)) {
        document.getElementById('drive-location-info').style.display = 'block';
    }

    var $sidebar = $("#sidebar"),
        $window = $(window),
        offset = $sidebar.offset(),
        topPadding = 80; // Offset från toppen

    $window.scroll(function() {
        if ($window.width() > 991) { // Applicera endast effekten för skärmar bredare än 991px
            if ($window.scrollTop() > offset.top) {
                $sidebar.stop().animate({
                    marginTop: $window.scrollTop() - offset.top + topPadding
                });
            } else {
                $sidebar.stop().animate({
                    marginTop: 0
                });
            }
        } else { // Återställ marginTop för små skärmar
            $sidebar.stop().animate({
                marginTop: 0
            });
        }
    });
});


$('#datepicker').datepicker({
    startView: 0,
    todayBtn: false,
    keyboardNavigation: false,
    forceParse: true,
    autoclose: false,
    format: "yyyy-mm-dd",
    language: 'sv',
    calendarWeeks: true,
    daysOfWeekDisabled: '6,0',
    todayHighlight: true,
    datesDisabled: '',
    startDate: '',
    endDate: '',
    updateViewDate: false
}).on('changeDate', function () {
    $('#my_hidden_input').val(
        $('#datepicker').datepicker('getFormattedDate')
    );

    $.ajax({
        type: 'GET',
        url: '/check-date',
        dataType: "json",
        data: {
            date: $('#my_hidden_input').val(),
            _token: token,
        },
        success: function (data) {
            $("#time").prop('disabled', false);

            let len = data.length;

            $("#time").empty();
            for (let i = 0; i < len; i++) {
                let name = data[i];
                //alert(name);

                $("#time").append("<option value='" + name + "'>" + name + "</option>");
            }
        }
    });
});

let initialTotalPrice = parseFloat(document.getElementById('price-container').getAttribute('data-total-price'));

(function () {
    new HSStickyBlock('.js-sticky-block', {
        targetSelector: document.getElementById('header').classList.contains('navbar-fixed') ? '#header' : null
    });

    const getById = (id) => document.getElementById(id);
    const getByClass = (className) => document.getElementsByClassName(className);

    const updateUI = (data, serviceTypeForDriveLocation) => {
        if (data) {
            const driveLocationDistance = data.distance;
            const driveLocationPricePerKm = 10;
            const driveLocationTotalPrice = Math.round(driveLocationDistance * driveLocationPricePerKm);

            initialTotalPrice += driveLocationTotalPrice;
            getById('preliminary-price').textContent = initialTotalPrice.toFixed(2) + " kr";

            if (serviceTypeForDriveLocation === 'recycling') {
                getById('recycling-message').style.display = 'block';

                const elements = getByClass('drive-location-name');
                for(let el of elements) {
                    el.textContent = data.location_name;
                }

                const recyclingDistanceElements = getByClass('recycling-distance');
                for(let el of recyclingDistanceElements) {
                    el.textContent = driveLocationDistance.toFixed(2);
                }

                const recyclingPriceElements = getByClass('recycling-price');
                for(let el of recyclingPriceElements) {
                    el.textContent = driveLocationTotalPrice.toFixed(2);
                }

                $("#loading").addClass('invisible');
            }
        }

        getById('address-section').style.display = 'none';
        getById('personal-details-section').style.display = 'block';
        getById('terms-container').style.display = 'block';
        getById('privacy-container').style.display = 'block';
        getById('confirm-button').style.display = 'block';
        getById('price-info').style.display = 'block';
    };

    getById('check-address').addEventListener('click', function () {
        const address = getById('address').value;
        const city = getById('city').value;
        const postalCode = getById('postal_code').value;

        const serviceDriveLocations = JSON.parse(getById('serviceDriveLocationData').textContent);
        const serviceTypeForDriveLocation = serviceDriveLocations.find(location => location) || null;

        fetch('/get-distance-from-origin-to-customer', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                address: address,
                city: city,
                postal_code: postalCode
            })
        })
            .then(response => response.json())
            .then(data => {
                const distanceInKm = data.distance;
                const pricePerKm = 5;
                const totalPrice = Math.round(distanceInKm * pricePerKm * 2);

                initialTotalPrice += totalPrice; // Efter att du har räknat ut totalPrice

                getById('calculated-distance').textContent = distanceInKm.toFixed(2);
                getById('travel-price').textContent = totalPrice.toFixed(2);
                getById('distance-info').style.display = 'block';
                getById('preliminary-price').textContent = initialTotalPrice.toFixed(2) + " kr";

                if (serviceTypeForDriveLocation) {
                    $("#loading").removeClass('invisible');
                    return fetch('/get-nearest-drive-location', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({
                            address: address,
                            city: city,
                            postal_code: postalCode,
                            service_type: serviceTypeForDriveLocation
                        })
                    }).then(response => response.json());
                } else {
                    return null;
                }
            })
            .then(data => updateUI(data, serviceTypeForDriveLocation))
            .catch(error => {
                console.error('Error:', error);
                alert("Något gick fel när vi försökte hämta avståndsinformationen. Försök igen.");
            });
    });
})();

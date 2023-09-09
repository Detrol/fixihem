let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let initialTotalPrice = parseFloat(document.getElementById('price-container').getAttribute('data-total-price')) || 300;
console.log("Initial value of initialTotalPrice:", initialTotalPrice);
let serviceTypeForDriveLocation;

console.log("Initial Total Price:", initialTotalPrice);

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



function calculateDriveCost(distance, duration, serviceType) {
    console.log("calculateDriveCost called with serviceType:", serviceType);
    const drivePricePerKm = 10;
    const driveCost = distance * drivePricePerKm;

    let timeCost = 0;
    if (serviceType === 'recycling' && duration !== undefined) {
        const driveTimeCostPerHour = 299;
        timeCost = (duration/60) * driveTimeCostPerHour;
        console.log("Time cost added:", timeCost);
    }

    return {
        driveCost: driveCost,
        timeCost: timeCost
    };
}

function updateTotalPrice(driveCost, timeCost) {
    console.log("Before update:", initialTotalPrice);
    console.log("Drive Cost:", driveCost);
    console.log("Time Cost:", timeCost);

    initialTotalPrice += driveCost + timeCost;

    console.log("After update:", initialTotalPrice);
}

function updateTimeCost(duration) {
    console.log("updateTimeCost called with duration:", duration);

    const timeCostPerHour = 299;
    const timeCost = (duration/60) * timeCostPerHour;
    console.log("Time cost in updateTimeCost:", timeCost);

    initialTotalPrice += timeCost;
    console.log("After updateTimeCost, total price:", initialTotalPrice);

}

function updateDriveLocationUI(data) {
    console.log("Data in updateDriveLocationUI:", data);
    if (!data || !data.distance) {
        console.error("Distance data is missing!", data);
        return 0;
    }

    const driveLocationDistance = data.distance;
    const driveLocationPricePerKm = 10;
    const driveLocationTotalPrice = Math.round(driveLocationDistance * driveLocationPricePerKm);

    const elements = document.getElementsByClassName('drive-location-name');
    for(let el of elements) {
        el.textContent = data.location_name;
    }

    const recyclingDistanceElements = document.getElementsByClassName('recycling-distance');
    for(let el of recyclingDistanceElements) {
        el.textContent = driveLocationDistance.toFixed(2);
    }

    const recyclingPriceElements = document.getElementsByClassName('recycling-price');
    for(let el of recyclingPriceElements) {
        el.textContent = driveLocationTotalPrice.toFixed(2);
    }

    document.getElementById('loading').classList.add('invisible');

    return driveLocationTotalPrice; // Returnera kostnaden för resan till återvinningscentralen
}


function updateAddressUI(data, serviceTypeForDriveLocation) {
    if (serviceTypeForDriveLocation === 'recycling') {
        updateDriveLocationUI(data);
        document.getElementById('recycling-message').style.display = 'block';
    }

    document.getElementById('address-section').style.display = 'none';
    document.getElementById('personal-details-section').style.display = 'block';
    document.getElementById('terms-container').style.display = 'block';
    document.getElementById('privacy-container').style.display = 'block';
    document.getElementById('confirm-button').style.display = 'block';
    document.getElementById('price-info').style.display = 'block';
}

(function() {
    new HSStickyBlock('.js-sticky-block', {
        targetSelector: document.getElementById('header').classList.contains('navbar-fixed') ? '#header' : null
    });

    const getById = id => document.getElementById(id);

    getById('check-address').addEventListener('click', function() {
        const address = getById('address').value;
        const city = getById('city').value;
        const postalCode = getById('postal_code').value;

        const serviceDriveLocations = JSON.parse(getById('serviceDriveLocationData').textContent);
        serviceTypeForDriveLocation = serviceDriveLocations.find(location => location) || null;

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
                const costs = calculateDriveCost(data.distance, null, serviceTypeForDriveLocation);
                console.log("Costs for customer drive:", costs);
                updateTotalPrice(costs.driveCost, costs.timeCost);

                getById('calculated-distance').textContent = data.distance.toFixed(2);
                getById('travel-price').textContent = costs.driveCost.toFixed(2);
                getById('distance-info').style.display = 'block';

                if (!serviceTypeForDriveLocation) {
                    getById('time-price').textContent = "0.00";
                    getById('preliminary-price').textContent = initialTotalPrice.toFixed(2) + " kr";
                }

                console.log("Service type for drive location:", serviceTypeForDriveLocation);
                if (serviceTypeForDriveLocation) {
                    document.getElementById('loading').classList.remove('invisible');

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
                    })
                        .then(response => response.json())
                        .then(recyclingData => {
                            const recyclingDriveCost = updateDriveLocationUI(recyclingData);

                            const driveTimeCostPerHour = 299;
                            const timeCostForRecycling = (recyclingData.duration/60) * driveTimeCostPerHour;
                            updateTotalPrice(recyclingDriveCost, timeCostForRecycling);

                            getById('time-price').textContent = timeCostForRecycling.toFixed(2) + " kr";
                            getById('preliminary-price').textContent = initialTotalPrice.toFixed(2) + " kr";
                            return recyclingData;
                        });
                } else {
                    getById('preliminary-price').textContent = initialTotalPrice.toFixed(2) + " kr";
                    return data;
                }
            })
            .then(finalData => {
                updateAddressUI(finalData, serviceTypeForDriveLocation);
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Något gick fel när vi försökte hämta avståndsinformationen. Försök igen.");
            });
    });
})();

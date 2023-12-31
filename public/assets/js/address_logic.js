let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let initialTotalPrice = parseFloat(document.getElementById('price-container').getAttribute('data-total-price')) || 300;
let serviceTypeForDriveLocation;
const numberOfTrips = parseInt(document.getElementById('numberOfTrips').getAttribute('data-travels')) || 1;
const getById = id => document.getElementById(id);

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

function calculateTotalPrice() {
    // Summa av de olika delpriserna
    const driveToRecyclingCost = parseFloat(getById('recycling-price').textContent) || 0;
    let driveToYouCost = parseFloat(getById('travel-price').textContent) || 0;

    // Om reseersättning till kund är under 100 kr så ska det vara 0.
    driveToYouCost = driveToYouCost < 100 ? 0 : driveToYouCost;

    const timeCost = parseFloat(getById('time-price').textContent) || 0;

    return {
        totalPrice: initialTotalPrice + driveToRecyclingCost + driveToYouCost + timeCost,
        driveToYouCost: driveToYouCost
    };
}


function calculateDriveCost(distance, duration, serviceType) {
    const drivePricePerKm = 10;
    const driveCost = distance * drivePricePerKm;

    let timeCost = 0;
    if (serviceType === 'recycling' && duration !== undefined) {
        const driveTimeCostPerHour = 249;
        timeCost = (duration/60) * driveTimeCostPerHour;
    }

    return {
        driveCost: driveCost,
        timeCost: timeCost
    };
}

function updateTotalPrice(driveCost, timeCost) {
    initialTotalPrice += (driveCost + timeCost) * numberOfTrips;
}

function updateTimeCost(duration) {
    const timeCostPerHour = 249;
    const timeCost = (duration/60) * timeCostPerHour;

    initialTotalPrice += timeCost * numberOfTrips;
}

function updateDriveLocationUI(data) {
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
    for (let i = 0; i < recyclingPriceElements.length; i++) {
        recyclingPriceElements[i].textContent = (driveLocationTotalPrice * numberOfTrips * 2).toFixed(2);
    }

    const timePriceElement = document.getElementById('time-price');
    if (timePriceElement && data.timeCost !== undefined) {
        timePriceElement.textContent = (data.timeCost * numberOfTrips).toFixed(2);
    }

    document.getElementById('loading').classList.add('invisible');

    const tripsElement = document.getElementById('number-of-trips');
    if (tripsElement) {
        tripsElement.textContent = numberOfTrips;
    }

    // Uppdatera värdet för endast en tur
    const singleTripRecyclingPriceElement = document.getElementById('single-trip-recycling-price');
    if (singleTripRecyclingPriceElement) {
        singleTripRecyclingPriceElement.textContent = driveLocationTotalPrice.toFixed(2);
    }

    return {
        driveLocationTotalPrice: driveLocationTotalPrice,
        timeCostForRecycling: (data.duration/60) * 249 * numberOfTrips * 2
    };
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
                getById('calculated-distance').textContent = data.distance.toFixed(2);
                getById('travel-price').textContent = costs.driveCost.toFixed(2);
                getById('distance-info').style.display = 'block';

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
                            const recyclingCosts = updateDriveLocationUI(recyclingData);
                            getById('time-price').textContent = recyclingCosts.timeCostForRecycling.toFixed(2);
                            return recyclingData;
                        });
                } else {
                    return data;
                }
            })
            .then(finalData => {
                updateAddressUI(finalData, serviceTypeForDriveLocation);

                const calculatedPrices = calculateTotalPrice();
                getById('preliminary-price').textContent = calculatedPrices.totalPrice.toFixed(2) + " kr";
                getById('travel-price').textContent = calculatedPrices.driveToYouCost.toFixed(2);
            })

            .catch(error => {
                console.error('Error:', error);
                alert("Något gick fel när vi försökte hämta avståndsinformationen. Försök igen.");
            });
    });
})();

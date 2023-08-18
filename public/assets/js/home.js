$(document).ready(function() {

    function calculateServicePrice(serviceElement) {
        let basePrice = parseFloat(serviceElement.data('price'));
        let serviceType = serviceElement.data('type');
        let materialPrice = parseFloat(serviceElement.data('material-price'));
        let price = basePrice;

        if (serviceType === 'quantity' || serviceType === 'drive') {
            let quantity = parseFloat(serviceElement.closest('.service-wrapper').find('input[type="number"]').val()) || 1;

            price *= quantity;
            price += materialPrice * quantity;

            if (serviceElement.closest('.service-wrapper').find('.has-material').prop('checked')) {
                price -= materialPrice * quantity;
            }

            serviceElement.closest('.service-wrapper').find('.form-check-input[name="options[]"]:checked').each(function() {
                let optionPrice = parseFloat($(this).data('price'));
                if (!serviceElement.closest('.service-wrapper').find('.has-material').prop('checked')) {
                    price += optionPrice * quantity;
                }
            });
        }

        return price;
    }

    function updateTotalPrice() {
        let totalPrice = 0;
        let anyServiceChecked = false;

        $(".service-checkbox:checked").each(function() {
            anyServiceChecked = true;
            let individualPrice = calculateServicePrice($(this));
            totalPrice += individualPrice;
        });

        if (totalPrice < 300) {
            totalPrice = 300;
        }

        animatePriceChange($('#totalPrice').data('current-price') || 0, totalPrice);

        let priceText = "Preliminärt pris";
        $('#rutText').html(priceText + ": ");

        if(anyServiceChecked) {
            $('#stickyPopup').fadeIn();
        } else {
            $('#stickyPopup').fadeOut();
        }
    }

    let currentInterval = null;

    function animatePriceChange(currentPrice, newPrice) {
        if (currentInterval) {
            clearInterval(currentInterval);
        }

        let duration = 500;
        let difference = newPrice - currentPrice;
        let step = difference / (duration / 10);
        let current = currentPrice;

        currentInterval = setInterval(function() {
            current += step;
            if ((step > 0 && current >= newPrice) || (step < 0 && current <= newPrice)) {
                clearInterval(currentInterval);
                current = newPrice;
            }

            $('#totalPrice').text(current.toFixed(2)).data('current-price', current);
        }, 10);
    }

    $(".service-checkbox").on('change', function() {
        let serviceType = $(this).data('type');

        if ($(this).prop('checked')) {
            $(this).closest('.service-wrapper').find('.service-options').slideDown();
        } else {
            $(this).closest('.service-wrapper').find('.service-options').slideUp();
        }

        updateTotalPrice();
    });

    $(document).on('change input', '.service-options input', function() {
        updateTotalPrice();
    });

    $(document).on('change', '.has-material', function() {
        updateTotalPrice();
    });

});

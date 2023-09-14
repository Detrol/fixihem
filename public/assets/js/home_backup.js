$(document).ready(function() {
    $('#stickyPopup').hide();

    function calculateServicePrice(serviceElement) {
        let basePrice = parseFloat(serviceElement.data('price'));
        let serviceType = serviceElement.data('type');
        let materialPrice = parseFloat(serviceElement.data('material-price'));
        let price = basePrice;

        let quantity = parseFloat(serviceElement.closest('.service-wrapper').find('input[type="number"]').val()) || 1;

        // Hantera tjänstalternativens pris
        serviceElement.closest('.service-wrapper').find('.form-check-input[name="options[]"]:checked').each(function() {
            let optionPrice = parseFloat($(this).data('price')) || 0;  // initialiserat till 0
            let optionQuantity = parseFloat($(this).closest('.form-check').find('input[type="number"]').val()) || 1;

            if (serviceType === 'quantity' || serviceType === 'drive') {
                if (!serviceElement.closest('.service-wrapper').find('.has-material').prop('checked')) {
                    price += optionPrice * quantity;
                }
            } else if (serviceType === 'yes_no') {
                if (!serviceElement.closest('.service-wrapper').find('.has-material').prop('checked')) {
                    price += optionPrice * optionQuantity;
                }
            } else {
                price += optionPrice * optionQuantity;
            }
        });

        // Om tjänsttypen är 'quantity' eller 'drive'
        if (serviceType === 'quantity' || serviceType === 'drive') {
            price *= quantity;
            price += materialPrice * quantity;

            // Minska priset om kunden tillhandahåller eget material
            if (serviceElement.closest('.service-wrapper').find('.has-material').prop('checked')) {
                price -= materialPrice * quantity;
            }
        } else if (serviceType === 'yes_no') {
            // Minska priset om kunden tillhandahåller eget material
            if (serviceElement.closest('.service-wrapper').find('.has-material').prop('checked')) {
                price -= materialPrice;
            }
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

        if (totalPrice < 200) {
            totalPrice = 200;
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

    // Lyssna på förändringar på service-alternativens kryssrutor
    $('.form-check-input[name="options[]"]').change(function() {
        let quantityFieldWrapper = $(this).closest('.form-check').find('.option-quantity-wrapper');
        let quantityField = quantityFieldWrapper.find('input[type="number"]');

        if ($(this).prop('checked')) {
            // Om kryssrutan är ikryssad, använd slideDown för att visa kvantitetsfältets wrapper
            quantityFieldWrapper.slideDown(400);
        } else {
            // Om kryssrutan inte är ikryssad, använd slideUp för att dölja kvantitetsfältets wrapper och återställ dess värde till 1 när animationen är klar
            quantityFieldWrapper.slideUp(function() {
                quantityField.val(1);
            });
        }
    });
});

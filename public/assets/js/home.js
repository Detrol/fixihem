$(document).ready(function() {
    $('#stickyPopup').hide();

    // Uppdatera knappens status när tjänster eller undertjänster ändras
    $(".service-checkbox, .form-check-input[name='options[]']").on('change', function() {
        updateSubmitButtonStatus();
    });

    // Kör denna vid start så att knappen har rätt status från början
    updateSubmitButtonStatus();

    // Uppdaterar knappens status och tooltip
    function updateSubmitButtonStatus() {
        const btn = $("#submitForm");

        if (isValidServiceSelection()) {
            btn.prop('disabled', false);
            btn.tooltip('hide');
        } else {
            btn.prop('disabled', true);
            // Ta bort den här raden -> btn.tooltip('show');
        }
    }

    function isValidServiceSelection() {
        let isValid = true;

        // Loopa igenom alla tjänsterna
        $(".service-checkbox").each(function() {
            // Om tjänsten är vald
            if ($(this).prop('checked')) {
                // Kolla om det finns obligatoriska undertjänster för denna tjänst
                let requiredSubservices = $(this).closest('.service-wrapper').find('.form-check-input.required-option');

                if (requiredSubservices.length > 0) {
                    // Om det finns obligatoriska undertjänster, kolla om minst en av dem är vald
                    let hasRequiredOptionChecked = requiredSubservices.filter(":checked").length > 0;

                    if (!hasRequiredOptionChecked) {
                        isValid = false;
                        return false; // Avbryter .each loopen
                    }
                }
            }
        });

        return isValid;
    }



    function calculateEstimatedTime(serviceElement) {
        let quantity = parseFloat(serviceElement.closest('.service-wrapper').find('input[type="number"]').val()) || 1;
        let baseTime = parseFloat(serviceElement.data('estimated-minutes')) || 0;
        let serviceType = serviceElement.data('type');
        let time = baseTime * quantity;

        // Hantera tjänstalternativens tid
        serviceElement.closest('.service-wrapper').find('.form-check-input[name="options[]"]:checked').each(function() {
            let optionTime = parseFloat($(this).data('estimated-minutes')) || 0;
            let optionHasQuantity = parseInt($(this).data('has-quantity'), 10) === 1;
            let optionQuantity;

            if(optionHasQuantity) {
                optionQuantity = parseFloat($(this).closest('.form-check').find('input[type="number"]').val()) || 1;
            } else {
                optionQuantity = 1;
            }

            if (serviceType === 'quantity' || serviceType === 'drive') {
                time += optionTime * quantity;
            } else {
                time += optionTime * optionQuantity;
            }
        });

        return time;
    }



    function minutesToHoursMinutes(minutes) {
        if (minutes < 60) {
            return minutes + " min";
        }

        let h = Math.floor(minutes / 60);
        let m = minutes % 60;

        return h + " tim" + (m ? " " + m + " min" : "");
    }

    function updateTotalTime() {
        let totalTime = 0;
        let anyServiceChecked = false;

        $(".service-checkbox:checked").each(function() {
            anyServiceChecked = true;
            let individualTime = calculateEstimatedTime($(this));
            totalTime += individualTime;
        });

        animateTimeChange($('#totalTime').data('current-time') || 0, totalTime);

        let timeText = "Uppskattad tid";
        $('#rutText').html(timeText + ": ");

        if(anyServiceChecked) {
            $('#stickyPopup').fadeIn();
        } else {
            $('#stickyPopup').fadeOut();
        }
    }

    let currentInterval = null;

    function animateTimeChange(currentTime, newTime) {
        if (currentInterval) {
            clearInterval(currentInterval);
        }

        let duration = 500;
        let difference = newTime - currentTime;
        let step = difference / (duration / 10);
        let current = currentTime;

        currentInterval = setInterval(function() {
            current += step;
            if ((step > 0 && current >= newTime) || (step < 0 && current <= newTime)) {
                clearInterval(currentInterval);
                current = newTime;
            }

            $('#totalTime').text(minutesToHoursMinutes(Math.round(current))).data('current-time', current);
        }, 10);
    }

    $(".service-checkbox").on('change', function() {
        let serviceType = $(this).data('type');

        if ($(this).prop('checked')) {
            $(this).closest('.service-wrapper').find('.service-options').slideDown();
        } else {
            $(this).closest('.service-wrapper').find('.service-options').slideUp();
        }

        updateTotalTime();
    });

    $(document).on('change input', '.service-options input', function() {
        updateTotalTime();
    });

    $(document).on('change', '.has-material', function() {
        updateTotalTime();
    });

    $('.form-check-input[name="options[]"]').change(function() {
        let quantityFieldWrapper = $(this).closest('.form-check').find('.option-quantity-wrapper');
        let quantityField = quantityFieldWrapper.find('input[type="number"]');

        if ($(this).prop('checked')) {
            quantityFieldWrapper.slideDown(400);
        } else {
            quantityFieldWrapper.slideUp(function() {
                quantityField.val(1);
            });
        }
    });
});

// Set csrf token in header
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Key change text JS
$(document).ready(function() {
    $(document).on('keyup', 'input[name^="array"], textarea[name^="array"], select[name^="array"]', function() {
        var id = $(this).attr('id');
        var previewId = id + '_preview';
        var previewValue = $(this).val();

        $('#publish-theme-btn').prop('disabled', true);
        $('#save-theme-btn').prop('disabled', false);
        // Check if the value contains HTML tags
        var containsHtml = /<[a-z][\s\S]*>/i.test(previewValue);
        if (containsHtml) {
            $('#' + previewId).html(previewValue);
        } else {
            $('#' + previewId).text(previewValue);
        }
    });

    $(document).on('change', 'input[name^="array"], textarea[name^="array"], select[name^="array"]', function() {
        var id = $(this).attr('id');
        var previewId = id + '_preview';
        var previewValue = $(this).val();
        var containsHtml = /<[a-z][\s\S]*>/i.test(previewValue);
        if (containsHtml) {
            $('#' + previewId).html(previewValue);
        } else {
            $('#' + previewId).text(previewValue);
        }
        if ($(this).attr('type') === 'file') {
            previewImage(this, previewId);
        }
        $('#publish-theme-btn').prop('disabled', true);
        $('#save-theme-btn').prop('disabled', false);
    });


    // Section edit div show/hide JS
    showEditSection($(this));


    // Onclick section show toolbar and on focus section script
    $(document).on('click', '.right-content header,.right-content section,.right-content footer', function(e) {
        e.preventDefault();

        var clickedSection = $(this).closest('.right-content header, .right-content section, .right-content footer');

        if (clickedSection.hasClass('highlighted')) {
            // Section is already highlighted, do nothing
            return false;
        }

        // if ($('#save-theme-btn').prop('disabled') === false) {
        //     showConfirmation().then(function (result) {
        //         if (result.isConfirmed) {
        //             return true;
        //         } else {
        //             // Handle 'No' button click
        //            return false;
        //         }
        //     });
        // }

        // Find the input field within the clicked section and focus on it
        $('.right-content header,.right-content section,.right-content footer').removeClass('highlighted');


        $('.custome_tool_bar').html('');
        var is_hide =  parseInt($(this).closest('.right-content header,.right-content section,.right-content footer').attr('data-hide'));
        console.log(is_hide, "custometoolbar");
        if (is_hide == 0) {
            $('#show-section-btn').addClass('d-none').removeClass('');
            $('#hide-section-btn').addClass('').removeClass('d-none');
        } else {
            $('#hide-section-btn').addClass('d-none').removeClass('');
            $('#show-section-btn').addClass('').removeClass('d-none');
        }
        $(this).closest('.right-content header,.right-content section,.right-content footer').addClass('highlighted');
        $(this).closest('.right-content header,.right-content section,.right-content footer').find('.custome_tool_bar').html($('#default_tool_bar').html());
        var toolbar = $(this).closest('.right-content header,.right-content section,.right-content footer').find('.custome_tool_bar');
        //var position = $(this).closest('.right-content header,.right-content section,.right-content footer').position();
        console.log(toolbar.length, "toolbar.length");
        if (toolbar.length > 0) {
            toolbar.removeClass('d-none').addClass('d-flex').css({
                // top: position.top + 10,
                // left: position.left + $(this).width() + 20
            }).fadeIn();
        }
        showEditSection($(this));
        // $(this).closest('.right-content header,.right-content section,.right-content footer').find('.custome_tool_bar').html($('#default_tool_bar').html());
    });

    // Up section script
    $(document).on('click', '#up-section-btn', function(e) {
        e.preventDefault();
        var order = parseInt($(this).closest('.right-content header,.right-content section,.right-content footer').attr('data-index'));
        // Validation
        if (optionOrderValidation('up', order)) {
            // Change Element Positions
            changePosition(order, order-1, 'up');
            $('#save-theme-btn').prop('disabled', false);
        }
    });

    // Down section script
    $(document).on('click', '#down-section-btn', function(e) {
        e.preventDefault();
        var order = parseInt($(this).closest('.right-content header,.right-content section,.right-content footer').attr('data-index'));
        // Validation
        if (optionOrderValidation('down', order)) {

            // Change Element Positions
            changePosition(order, order+1, 'down');
            $('#save-theme-btn').prop('disabled', false);
        }
    });

    // Hide section script
    $(document).on('click', '#hide-section-btn', function(e) {
        e.preventDefault();
        // Hide Section
        hideShowSection($(this), 'hide');
        $('#save-theme-btn').prop('disabled', false);
    });

    // Down section script
    $(document).on('click', '#show-section-btn', function(e) {
        e.preventDefault();
        // Show Section
        hideShowSection($(this), 'show');
        $('#save-theme-btn').prop('disabled', false);
    });

    // Save Theme script
    $(document).on('click', '#save-theme-btn', function(e) {
        e.preventDefault();
        var array = [];
        $('[data-index]').each(function (index) {
            var obj = {};
            obj['order'] = index;
            obj['section'] = $(this).attr('data-section');
            obj['theme'] = $(this).attr('data-theme');
            obj['store'] = $(this).attr('data-store');
            obj['id'] = $(this).attr('data-value');
            obj['is_hide'] = $(this).attr('data-hide');

            array.push(obj);
        });
        $.noConflict();
        $.ajax({
            url: saveThemeRoute,
            data: {
                array: array,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            success: function(data) {
                if (data.error) {
                    show_toastr('Error', data.error, 'error')
                } else {
                    saveThemePageChanges($('.sidebar_form form'), false);

                    //show_toastr('Success', data.msg, 'success');
                    $('#save-theme-btn').prop('disabled', true);
                    if (data.is_publish == 1) {
                        $('#publish-theme-btn').prop('disabled', true);
                    } else {
                        $('#publish-theme-btn').prop('disabled', false);
                    }
                }
            },
            error: function(data) {
                data = data.responseJSON;
                show_toastr('Error', data.error, 'error')
            }

        })
    });

    // Publish Theme script
    $(document).on('click', '#publish-theme-btn', function(e) {
        e.preventDefault();
        var themeUrl = $('#publish_theme_url').val();
        var themeId = $('#publish_theme_id').val();
        var storeId = $('#publish_store_id').val();
        var isPublish = $('#publish_is_publish').val();
        $.noConflict();
        $.ajax({
            url: themeUrl,
            data: {
                theme_id:themeId,
                store_id:storeId,
                is_publish:isPublish,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            success: function(data) {
                if (data.error) {
                    show_toastr('Error', data.error, 'error')
                } else {
                    if (data.data.content) {
                        $('#theme_preview_section').html('');
                        $('#theme_preview_section').html(data.data.content);
                        // Remove unwanted div element
                        //$('.nice-select.product_ids').remove();
                        // Initialize Theme Sliders
                        initializeThemeSliders();
                    }

                    $('#save-theme-btn').prop('disabled', true);
                    if (data.is_publish == 1) {
                        $('#publish-theme-btn').prop('disabled', true);
                    } else {
                        $('#publish-theme-btn').prop('disabled', false);
                    }
                    show_toastr('Success', data.msg, 'success');
                }

            },
            error: function(data) {
                data = data.responseJSON;
                show_toastr('Error', data.error, 'error')
            }
        })
    });

    // Save theme changes in database
    $('button[type="submit"]').on('click', function (e) {
        e.preventDefault(); // Prevent the default form submission
        saveThemePageChanges($(this), true);
    });

    // Add Slider Row
    $(document).on('click', '.add-new-slider-btn', function(e) {
        e.preventDefault(); // Prevent the default form submission
        $('#publish-theme-btn').prop('disabled', true);
        $('#save-theme-btn').prop('disabled', false);


        var firstRow = $('.slider-body-rows .row:first');
        var firstSliderRow = $('.slick-track .home-banner-content:first');
        var firstRowClone = firstRow.clone();
        var firstSliderRowClone = firstSliderRow.clone();
        // Reset input field array index
        var newIndex = $('.slider-body-rows .row').length; // Get the new index

        // Update class name using .filter
        firstRowClone.filter('.selectable').attr('class', function(_, className) {
        return className.replace(/slider_\d+/, 'slider_' + newIndex);
        });
        // Update data-index attribute
        firstRowClone.attr('data-index', newIndex);
        if (newIndex != 0) {
            var slidepixsel = 975 * newIndex + 975;
            $('.slick-track').css('width', slidepixsel+'px');
        }

        firstSliderRowClone.attr('data-slick-index', newIndex);
        firstSliderRowClone.attr('tabindex', -1);
        firstSliderRowClone.attr('aria-hidden', false);
        firstSliderRowClone.removeClass('slick-current');
        firstSliderRowClone.removeClass('slick-active');

        // Update the IDs of specific elements within the cloned element
        firstSliderRowClone.find('#slider_title_0_preview').attr('id', 'slider_title_' + newIndex + '_preview');
        firstSliderRowClone.find('#slider_sub_title_0_preview').attr('id', 'slider_sub_title_' + newIndex + '_preview');
        firstSliderRowClone.find('#slider_description_0_preview').attr('id', 'slider_description_' + newIndex + '_preview');
        firstSliderRowClone.find('#slider_first_button_0_preview').attr('id', 'slider_first_button_' + newIndex + '_preview');
        firstSliderRowClone.find('#slider_second_button_0_preview').attr('id', 'slider_second_button_' + newIndex + '_preview');

        // Check if the cloned element contains the elements you want to update
        firstRowClone.find('#slider_title_0').attr('id', 'slider_title_' + newIndex);
        firstRowClone.find('#slider_sub_title_0').attr('id', 'slider_sub_title_' + newIndex);
        firstRowClone.find('#slider_description_0').attr('id', 'slider_description_' + newIndex);
        firstRowClone.find('#slider_first_button_0').attr('id', 'slider_first_button_' + newIndex);
        firstRowClone.find('#slider_second_button_0').attr('id', 'slider_second_button_' + newIndex);
        //firstRowClone.find('#slider-button-text_0').attr('id', 'slider-button-text_' + newIndex);


        $('.slider-body-rows').append(firstRowClone);

        firstRowClone.find('input[name="array[section][title][text][0]"]').attr('name', 'array[section][title][text]['+ newIndex +']');
        firstRowClone.find('input[name="array[section][sub_title][text][0]"]').attr('name', 'array[section][sub_title][text]['+ newIndex +']');
        firstRowClone.find('textarea[name="array[section][description][text][0]"]').attr('name', 'array[section][description][text]['+ newIndex +']');
        firstRowClone.find('input[name="array[section][button_first][text][0]"]').attr('name', 'array[section][button_first][text]['+ newIndex +']');
        firstRowClone.find('input[name="array[section][button_second][text][0]"]').attr('name', 'array[section][button_second][text]['+ newIndex +']');

        firstRowClone.find('.slider-collspan').attr('data-bs-target', '#slider_' + newIndex);
        firstRowClone.find('.slider-collspan').attr('aria-controls', 'slider_' + newIndex);
        firstRowClone.find('.slider-collspan-body').attr('id', 'slider_' + newIndex);
        firstRowClone.find('.accordion-header').attr('id', 'flush-headingOne' + newIndex);
        firstRowClone.find('.slider-collspan-body').attr('aria-labelledby', 'flush-headingOne' + newIndex);
        // Update the loop number and check if delete button should be disabled
        var loop = parseInt($('#slider-loop-number').val()) + 1;
        $('#slider-loop-number').val(loop);

        $('.delete-slider-btn').prop('disabled', loop < 4);

        // Add the new slide to the Slick slider
        $('.banner-slider').slick('slickAdd', firstSliderRowClone);

        // Refresh the Slick slider to reflect the changes
        $('.banner-slider').slick();
        // Go to the last slide (adjust the index as needed)
        var slickIndex = $('.banner-slider .slick-slide').length - 1;
        $('.banner-slider').slick('slickGoTo', slickIndex);
    });



    // Delete Slider Row
    $(document).on('click', '.delete-slider-btn', function(e) {
        e.preventDefault(); // Prevent the default form submission

        $('#publish-theme-btn').prop('disabled', true);
        $('#save-theme-btn').prop('disabled', false);

        var loop = parseInt($('#slider-loop-number').val());
        if (loop < 4) {
            $('.delete-slider-btn').prop('disabled', true);
            return false;
        }

        $('.slider-body-rows .row:last').remove();

        var totalLoop = loop - 1;
        $('#slider-loop-number').val(totalLoop);

        $('.delete-slider-btn').prop('disabled', totalLoop < 4);

        var slickIndex = parseInt($('.banner-content-slider .banner-content-item:last').attr('data-slick-index'));

        // Remove the slide
        $('.banner-content-slider').slick('slickRemove', slickIndex);
        // Refresh the Slick slider to reflect the changes
        $('.banner-content-slider').slick();
    });

    // Delete Slider Row
    $(document).on('change', 'select#product_type', function(e) {
        e.preventDefault(); // Prevent the default form submission

        var productType = $(this).val();
        showHideCustomProductDropDown(productType);
    });
});

var header_hright = $('.preview-header-main').outerHeight();
$('.preview-header-main').next('.wrapper').css('margin-top', header_hright + 'px');

// Validation
function optionOrderValidation(type, order) {
    if (type == 'up' && order == 0) {
        return false;
    } else if (type == 'down' && order == 3) {
        return false;
    }

    return true;
}

// Hide show section JS
function hideShowSection(element, type) {
    var closestElement = element.closest('.right-content header,.right-content section,.right-content footer');
    if (type == 'show') {
        // Add CSS to the closest section
        closestElement.css('opacity', 1);
        var newHideValue = 0;
        $('#show-section-btn').addClass('d-none').removeClass('');
        $('#hide-section-btn').addClass('').removeClass('d-none');
    } else {
        // Add CSS to the closest section
        closestElement.css('opacity', 0.5);
        var newHideValue = 1;
        $('#hide-section-btn').addClass('d-none').removeClass('');
        $('#show-section-btn').addClass('').removeClass('d-none');
    }
    closestElement.attr('data-hide', newHideValue);
}

// Hide show sidebar section JS
function showEditSection(element) {
    var sectionName = element.data('section') ?? 'header';
    var themeId = $('#publish_theme_id').val();
    var storeId = $('#publish_store_id').val();
    // Serialize the data object
    var requestData = { section_name: sectionName, store_id: storeId, theme_id: themeId};
    var serializedData = JSON.stringify(requestData);
    $.ajax({
        url: sidebarThemeRoute, // Use the form's action attribute as the URL
        type: 'POST', // Use the form's method attribute as the HTTP method
        contentType: 'application/json',
        data: serializedData, // Serialize form data
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            $('.sidebar_form').html('');
            $('.sidebar_form').html(data.data.content);
            $('.product_ids').hide();
            $('.category_ids').hide();

            var customProductSection = $('select#product_type');
            if (customProductSection && customProductSection.length > 0) {
               var productType = customProductSection.val();
               showHideCustomProductDropDown(productType);
            }
            if (sectionName == 'product_category') {
                $('.category_ids').show();
            }
        },
        error: function (error) {
            if (toast) {
                // Handle the error response
               // show_toastr('Error', error.error, 'error')
            }
        }
    });
    // Hide all sections
    $('div[data-section]').hide();
    // sidebarThemeRoute
    // Show the corresponding section based on the data-section attribute
    if (sectionName) {
        $('div[data-section="' + sectionName + '"]').show();
    } else {
        // Show a default section if no matching data-section attribute is found
        $('div[data-section="header"]').show();
    }
}

// Change section postion JS
function changePosition(currentIndex, newPosition, type) {
    const elements = $('[data-index]');
    const targetElement = elements.filter(`[data-index="${currentIndex}"]`);

    if (targetElement.length > 0) {
        if (type == 'up') {
            // Insert the target element at the new position
            elements.eq(newPosition).before(targetElement);
        } else {
            // Insert the target element at the new position
            elements.eq(newPosition).after(targetElement);
        }
        // Update data-index attributes for all elements
        $('[data-index]').each(function (index) {
            $(this).attr('data-index', index);
        });
    } else {
        console.error('Element with data-index ' + currentIndex + ' not found.');
    }
}

// Theme Changes Save function
function saveThemePageChanges(element, toast) {
    // Find the closest form element
    var closestForm = element.closest('form');
    // Create a FormData object to handle file uploads
    var formData = new FormData(closestForm[0]);
    // Perform AJAX request
    $.ajax({
        url: closestForm.attr('action'), // Use the form's action attribute as the URL
        type: closestForm.attr('method'), // Use the form's method attribute as the HTTP method
        data: formData, // Serialize form data
        contentType: false, // Important! Indicates that the request will be sent as multipart/form-data
        processData: false, // Important! Prevents jQuery from automatically processing the data
        success: function (data) {
            if (data.error) {
                show_toastr('Error', data.error, 'error')
            } 
            else {
                if (data.data.content) {
                    $('#theme_preview_section').html('');
                    $('#theme_preview_section').html(data.data.content);
                    // Remove unwanted div element
                    //$('.nice-select.product_ids').remove();
                    // Initialize Theme Sliders
                    initializeThemeSliders();
                }

                if (toast) {
                    // Handle the success response
                    show_toastr('Success', data.msg, 'success')
                }
            }

            if (data.is_publish == 1) {
                $('#publish-theme-btn').prop('disabled', true);
            } else {
                $('#publish-theme-btn').prop('disabled', false);
            }
        },
        error: function (error) {
            if (toast) {
                // Handle the error response
                show_toastr('Error', error.error, 'error')
            }
        }
    });
}

function showHideCustomProductDropDown(productType) {
   if (productType == 'custom_product') {
    $('.category_ids').hide();
    $('.product_ids').show();
   } else if (productType == 'category_wise_product') {
    $('.product_ids').hide();
    $('.category_ids').show();
   }  else {
    $('.product_ids').hide();
    $('.category_ids').hide();
   }
}

// Function to display image preview
function previewImage(element, previewId) {
    var file = element.files[0] ?? null;

    if (file) {
        var reader = new FileReader();
        reader.onload = function (e) {
            // Display the image preview
            $('.'+previewId).attr('src', e.target.result);
            $('#'+previewId).attr('src', e.target.result);
        };

        reader.readAsDataURL(file);
    }
}

// Function to show SweetAlert confirmation
// function showConfirmation() {
//     return Swal.fire({
//         title: 'Save Changes?',
//         text: 'You have unsaved changes. Do you want to save them?',
//         icon: 'warning',
//         showCancelButton: true,
//         confirmButtonText: 'Yes, save changes',
//         cancelButtonText: 'No, discard changes',
//     });
// }

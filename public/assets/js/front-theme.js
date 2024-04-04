$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var site_url = $('meta[name="base-url"]').attr('content');

function getBillingdetail() {
    // $('.delivery_address').html($('input[name="billing_info[delivery_address]"]').val());
    $('.delivery_country').html($('select[name="billing_info[delivery_country]"] option:selected').text());
    $('.delivery_state').html($('select[name="billing_info[delivery_state]"] option:selected').text());
    $('.delivery_city').html($('select[name="billing_info[delivery_city]"] option:selected').text());
    $('.delivery_postcode').html($('input[name="billing_info[delivery_postcode]"]').val());

    if (guest == 1) {
        $('.billing_address').html($('input[name="billing_info[billing_address]"]').val());
        $('.billing_country').html($('select[name="billing_info[billing_country]"] option:selected').text());
        $('.billing_state').html($('select[name="billing_info[billing_state]"] option:selected').text());
        $('.billing_city').html($('select[name="billing_info[billing_city]"] option:selected').text());
        $('.billing_postecode').html($('input[name="billing_info[billing_postecode]"]').val());
    } else {
        $('.billing_address').html($('input[name="billing_info[billing_address]"]').val());
        $('.billing_country').html($('input[name="billing_info[billing_country_name]"]').val());
        $('.billing_state').html($('input[name="billing_info[billing_state_name]"]').val());
        $('.billing_city').html($('input[name="billing_info[billing_city_name]"]').val());
        $('.billing_postecode').html($('input[name="billing_info[billing_postecode]"]').val());
    }
}

var searchData;


$(document).ready(function() {
    var currentRoute = window.location.pathname.split("/").pop();

    range_slide();
    get_cartlist();
    get_wishlist(wishListSidebar, false);
    if (currentRoute == 'my-account') {
        get_wishlist(wishList, true);
    }

    $(".position").change(function() {
        var value = $(this).val();
        var cat_id = $('.tabs .active').attr('data-tab');
        getProducts(value, cat_id);
    });
    $(".on-tab-click").click(function() {
        var value = $(".position").val();
        var cat_id = $(this).attr('data-tab');
        getProducts(value, cat_id);
    });


    var form = $("#formdata");
    if (form.length >0) {
        form.validate({
            rules: {
                'billing_info[firstname]': "required",
                'billing_info[lastname]': "required",
                'billing_info[email]': "required",
                'billing_info[billing_user_telephone]': "required",
                'billing_info[billing_address]': "required",
                'billing_info[billing_postecode]': "required",
                'billing_info[delivery_address]': "required",
                'billing_info[delivery_postcode]': "required",
                'billing_info[billing_country]': "required",

            },
            messages: {
                'billing_info[firstname]': "<span class='text-danger billing_data_error'> please enter first name </span>",
                'billing_info[lastname]': "<span class='text-danger billing_data_error'>please enter last name </span>",
                'billing_info[email]': "<span class='text-danger billing_data_error'>please enter valid email </span>",
                'billing_info[billing_user_telephone]': "<span class='text-danger billing_data_error'>please enter telephone number </span>",
                'billing_info[billing_address]': "<span class='text-danger billing_data_error'>please enter billing address </span>",
                'billing_info[billing_postecode]': "<span class='text-danger billing_data_error'>please enter billing postcode </span>",
                'billing_info[delivery_address]': "<span class='text-danger billing_data_error'>please enter delivery address </span>",
                'billing_info[delivery_postcode]': "<span class='text-danger billing_data_error'>please enter delivery postcode </span>",
                'billing_info[billing_country]': "<span class='text-danger billing_data_error'>please select country </span>",
            }
        });
    }


    $('.delivery_and_billing').trigger('change');
    $('.billing_address_list').trigger('change');

    setTimeout(() => {
        getBillingdetail();
        $('.shipping_change:checked').trigger('change');
        $('.payment_change:checked').trigger('change');
    }, 200);

    $(document).on('click', '.search_product_globaly', function (e) {
        e.preventDefault();
        alert();
        search_data();
     });

    $(".search_input").on('input', function (e) {
        e.preventDefault();
        search_data();
    });

    $(document).on('change', '.search_input', function () {
        var selectedProduct = $(this).val();

        // Find the selected product's URL in the responseData array
        var productUrl = null;
        $.each(searchData, function (key, value) {
            if (value.name === selectedProduct) {
                productUrl = value.url;
                return false; // Exit the loop once found
            }
        });

        // Redirect to the product page when a suggestion is selected
        if (productUrl) {
            window.location.href = productUrl;
        }
    });

    flipdown_popup();

});

$(document).on('click', '.cart-header', function(e) {
    get_cartlist();
});

function get_cartlist() {
    var method_id  = $('input[name="shipping_id"]:checked').data('id');
    var shipping_price  = $('.shipping_final_price').first().text();
    var coupon_code = $('.coupon_code').val();
    var stateId = $("#state_id option:selected").val();
    var countryId = $("#country_id option:selected").val();
    var cityId = $("#city_id option:selected").val();
    var tax_id_value = $('#tax_id_value').val();
    var shipping_final_price = parseInt($('.shipping_final_price').first().text());
    var data = {
        method_id : method_id,
        shipping_price : shipping_price,
        coupon_code : coupon_code,
        stateId : stateId,
        countryId : countryId,
        cityId : cityId,
        tax_id_value : tax_id_value,
        shipping_final_price : shipping_final_price,
    };
    $.ajax({
        url: cartlistSidebar,
        method: 'POST',
        data: data,
        context: this,

        success: function(response) {
            if (response.status == 0) {
                $('.cart-header').css("pointer-events", "auto");
                $('.cart-header .count').html(0);
                $('.cartDrawer .closecart').click();
            }
            if (response.status == 1) {
                $('.cart-header .count').html(response.cart_total_product);
                $('.cartajaxDrawer').html(response.html);
                $('.cart-page-section').html(response.checkout_html);
                $('.checkout_page_cart').html(response.checkout_html_2);
                $('.checkout_products').html(response.checkout_html_products);
                $('.checkout_amount').html(response.checkout_amounts);
                $('#sub_total_checkout_page').attr('value', response.sub_total);
                $('#sub_total_main_page').html(response.sub_total);
            }
        }
    });
}

function getProducts(value, cat_id) {
    $.ajax({
        url: filterBlog,
        type: 'POST',
        data: {
            'value': value,
            'cat_id': cat_id,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
            $('.f_blog').html(data.html);
        }
    });
}

function show_toster(status = '', message = '') {
    if (status == 'Success' || status == 'success') {
        notifier.show('Success', message, 'success', site_url +
            '/public/assets/images/notification/ok-48.png', 4000);
    }
    if (status == 'Error' || status == 'error') {
        notifier.show('Error', message, 'danger', site_url +
            '/public/assets/images/notification/high_priority-48.png', 4000);
    }
}

//add to cart
$(document).on('click', '.addcart-btn-globaly', function(e) {
    var product_id = $(this).attr('product_id');
    var variant_id = $(this).attr('variant_id');
    var qty = $(this).attr('qty');
    var data = {
        product_id: product_id,
        variant_id: variant_id,
        qty: qty,
    };
    $.ajax({
        url: ProductCart,
        method: 'POST',
        data: data,
        context: this,
        success: function(response) {
            $('.cart-header .count').html(response.data.count);
            if (response.status == 0) {
                show_toastr('Error', response.data.message, 'error');
            } else {
                show_toastr('Success', response.data.message, 'success');
            }
            get_cartlist();
        }
    });
});


feather.replace();
var pctoggle = document.querySelector("#pct-toggler");
if (pctoggle) {
    pctoggle.addEventListener("click", function() {
        if (
            !document.querySelector(".pct-customizer").classList.contains("active")
        ) {
            document.querySelector(".pct-customizer").classList.add("active");
        } else {
            document.querySelector(".pct-customizer").classList.remove("active");
        }
    });
}

//checkout

$(document).on('change', '.delivery_and_billing', function(e) {
    e.preventDefault();
    $('.Delivery_Address').hide();
    if ($(this).prop('checked') !== true) {
        $('.Delivery_Address').show();
    }

    console.log(guest, "guest")
    if (guest == 1 && $(this).prop('checked') === true) {
        console.log($(this).prop('checked'), "checked")
        $('.Delivery_Address').show();

        var billing_address = $('input[name="billing_info[billing_address]"]').val();
        $('input[name="billing_info[delivery_address]"]').val(billing_address);

        var billing_country = $('select[name="billing_info[billing_country]"]').val();
        $('select[name="billing_info[delivery_country]"]').next().remove();
        $('select[name="billing_info[delivery_country]"]').val(billing_country);

        var billing_state = $('select[name="billing_info[billing_state]"]').val();
        $('select[name="billing_info[delivery_state]"]').attr('data-select', billing_state);

        var billing_city = $('select[name="billing_info[billing_city]"]').val();
        $('select[name="billing_info[delivery_city]"]').attr('data-select', billing_city);

        var billing_address = $('input[name="billing_info[billing_postecode]"]').val();
        $('input[name="billing_info[delivery_postcode]"]').val(billing_address);
    }
});

$(document).on('keyup', '.getvalueforval', function(e) {
    getBillingdetail();
});

$(document).on('change', '.shipping_change', function(e) {
    $('.shipping_img_path').attr('alt', '');
    var shipping_value = $(this).val();
    var shipping_img_path = $('.shippingimag' + shipping_value).attr('src');
    $('.shipping_img_path').attr('src', shipping_img_path);
    $('.shipping_img_path').attr('alt', shipping_value);
});

$(document).on('change', '.payment_change', function(e) {
    $('.payment_img_path').attr('alt', '');
    var payment_value = $(this).val();
    var payment_img_path = $('.paymentimag' + payment_value).attr('src');
    $('.payment_img_path').attr('src', payment_img_path);
    $('.payment_img_path').attr('alt', payment_value);
    $('.payment_types').attr('value', payment_value);

});

$(document).on('change', '.billing_address_list', function(e) {
    var billing_address_id = $(this).val();

    var data = {
        id: billing_address_id
    };

    $.ajax({
        url: addressbook_data,
        method: 'GET',
        data: data,
        context: this,
        success: function(response) {
            $('.addressbook_checkout_edit').html(response.addressbook_checkout_edit);
            $('.country_change').trigger('change');
            getBillingdetail();
        }
    });
});

function shipping_data(response, temp = 0) {

    var html = '';
    $.each(response.shipping_method, function(index, method) {
        var checked = index === temp ? 'checked' : '';
        html += '<div class="radio-group"><input type="radio" name="shipping_id" data-action ="' + index +
            '" data-id="' + method.id + '" value="' + method.cost + '" id="shipping_price' + index +
            '" class="shipping_mode" ' + checked + '>' +
            ' <label name="shipping_label" for="shipping_price' + index + '"> ' + method.method_name +
            '</label></div>';
    });
    setTimeout(() => {
        $("#shipping_lable").removeClass('d-none');
        $('#shipping_location_content').html(html);
        $('.CURRENCY').html(response.CURRENCY);
        getshippingdata(false);
    }, 500);
}

// guest country wise shipping method
$(document).on('change', '.delivery_list', function(e) {
    setTimeout(() => {
        var stateId = $("#state_id option:selected").val();
        var countryId = $("#country_id option:selected").val();
        var cityId = $("#city_id option:selected").val();
        var product_id = $('#product_id').val();
        var product_qty = $('#product_qty').val();
        var coupon_code = $('.coupon_code').val();
        var data = {
            stateId: stateId,
            countryId: countryId,
            cityId: cityId,
            product_id: product_id,
            product_qty: product_qty,
            coupon_code: coupon_code
        }
        $.ajax({
            url: taxes_data,
            method: 'POST',
            data: data,
            context: this,
            success: function(response) {
               console.log(response.tax_id_value);
                $('#tax-price-amount').html(response.tax_price);
                $('.subtotal').html(response.sale_price);
                $('.final_amount_currency').html(response.final_total_amount);
                $('#tax_id_value').val(response.tax_id_value);
                $('.shipping_total_price').html(response.final_total_amount);
                $('.final_tax_price').html(response.tax_price);
            }
        });

        // Get Default Shipping Data
        getDefaultShippingData(data);


    }, 700);
});


// Auth shipping method
$(document).on('change', '.shipping_list', function(e) {
    var billing_address_id = $(this).val();
    var product_id = $('#product_id').val();
    var product_qty = $('#product_qty').val();
    var coupon_code = $('.coupon_code').val();
    var stateId = $("#state_id option:selected").val();
    var countryId = $("#country_id option:selected").val();
    var cityId = $("#city_id option:selected").val();
    var billing_addres_id = $('.billing_address_list').val();

    var data = {
        address_id: billing_address_id,
        product_id: product_id,
        product_qty: product_qty,
        coupon_code: coupon_code,
        billing_addres_id: billing_addres_id,
        stateId: stateId,
        countryId: countryId,
        cityId: cityId,
    };
    $.ajax({
        url: shippings_data,
        method: 'POST',
        data: data,
        context: this,
        success: function(response) {
            shipping_data(response)
        }
    });
});

function getDefaultShippingData(data) {
    $.ajax({
        url: shippings_data,
        method: 'POST',
        data: data,
        context: this,
        success: function(response) {
            shipping_data(response);
        }
    });
}
function getshippingdata(toastr_status = true) {
    var product_qty = $('#product_qty').val();
    var stateId = $("#state_id option:selected").val();
    var countryId = $("#country_id option:selected").val();
    var cityId = $("#city_id option:selected").val();
    var product_id = $('#product_id').val();
    var coupon_code = $('.coupon_code').val();
    var total_coupon_amount = $('#coupon_amount').val();
    var method_id = $('input[name="shipping_id"]:checked').attr('data-id');

    var billing_address_id = $('.billing_address_list').val();
    var sub_total = $('.final_amount_currency').attr('final_total');
    var theme_id = $('.coupon_code').attr('theme_id');
    var final_sub_total = $('#subtotal').val();
    var cart_product_list = $('#cart_product_list').val();

    var data = {
        product_qty: product_qty,
        method_id: method_id,
        stateId: stateId,
        countryId: countryId,
        cityId: cityId,
        product_id: product_id,
        coupon_code: coupon_code,
        billing_address_id: billing_address_id,
        sub_total: sub_total,
        theme_id: theme_id,
        final_sub_total: final_sub_total,
        cart_product_list: cart_product_list,
        total_coupon_amount:total_coupon_amount,
    };
    $.ajax({
        url: shippings_methods,
        method: 'POST',
        data: data,
        context: this,
        success: function(response) {
            console.log(response);
            $('#shipping_final_price').val(response.shipping_final_price);
            $('.shipping_final_price').html(response.shipping_final_price);
            $('.shipping_total_price').html(response.shipping_total_price);
            $('.final_tax_price').html(response.final_tax_price);
            $('.method_id').attr('value', method_id);

            $('#shipping_final_price').val(response.shipping_final_price);
            $('.shipping_final_price').html(response.shipping_final_price);
            $('.tax-price-amount').html(response.final_tax_price);
            $('.discount_amount_currency').html(response.total_coupon_price);
            $('.final_amount_currency').html(response.shipping_total_price);
            $('.method_id').attr('value', method_id);
            $('#tax_id_value').val(response.tax_id_value);

            if (toastr_status == true) {
                show_toastr('Success', response.message, 'success');
            }
        }
    });
}

function getcoupondata(toastr_status = true) {
    var billing_address_id = $('.billing_address_list').val();
    var sub_total = $('.final_amount_currency').attr('final_total');
    var coupon_code = $('.coupon_code').val();
    var theme_id = $('.coupon_code').attr('theme_id');
    var final_sub_total = $('#subtotal').val();
    var cart_product_list = $('#cart_product_list').val();
    var product_id = $('#product_id').val();
    var temp = $('input[name="shipping_id"]:checked').data('action');
    var stateId = $("#state_id option:selected").val();
    var countryId = $("#country_id option:selected").val();
    var cityId = $("#city_id option:selected").val();
    var tax_id_value = $('#tax_id_value').val();
    var shipping_final_price = parseInt($('.shipping_final_price').first().text());
    var data = {
        sub_total: sub_total,
        coupon_code: coupon_code,
        theme_id: theme_id,
        billing_address_id: billing_address_id,
        final_sub_total: final_sub_total,
        product_id: product_id,
        cart_product_list: cart_product_list,
        stateId: stateId,
        countryId: countryId,
        cityId: cityId,
        tax_id_value: tax_id_value,
        shipping_final_price: shipping_final_price
    }
    $.ajax({
        url: apply_coupon,
        method: 'POST',
        data: data,
        context: this,
        success: function(response) {
            $('#coupon_code').attr('value', '');
            if (response.status == 0) {
                show_toastr('Error', response.data.message, 'error');
            } else {
                console.log(response.data);


                $('.discount_amount_currency').html(' - ' + response.data.discount_amount_currency);
                $('.final_amount_currency1').html(response.data.final_amount_currency);
                $('.shipping_total_price').html(response.data.shipping_total_price);
                $('.final_amount_currency').attr('final_total', response.data.discount_price);
                $('#coupon_code').attr('value', coupon_code);
                $('#tax-price-amount').html(response.data.tax_price);

                $('#sub_total_main_page').html(response.sub_total);
                $('#coupon_amount').val(response.data.amount);
                $('.CURRENCY').html(response.data.CURRENCY);
                //$('#sub_total_checkout_page').val(response.data.original_price);

                $('#coupon_info_id').val(response.data.id);
                $('#coupon_info_name').val(response.data.name);
                $('#coupon_info_code').val(response.data.code);
                $('#coupon_info_discount_type').val(response.data.coupon_discount_type);
                $('#coupon_info_discount_amount').val(response.data.amount);
                $('#coupon_info_discount_number').val(response.data.coupon_discount_amount);
                $('#coupon_info_final_amount').val(response.data.final_price);
                if (response.data.shipping_method !== "") {
                    shipping_data(response.data, temp);
                }

                if (toastr_status == true) {
                    show_toastr('Success', response.data.message, 'success');

                }
            }
        }
    });
}

$(document).on('change', '.change_shipping', function(e) {
    getshippingdata();
    console.log(1);
    var coupon_code = $('.coupon_code').val();
    console.log(coupon_code);
    if (coupon_code) {
        getcoupondata(false);
    }

});

$(document).on('click', '.apply_coupon', function(e) {
    getshippingdata(false);
    console.log(2);
    getcoupondata();

});

$(document).on('click', '.billing_done', function(e) {
    var form_is_valid = $("#formdata").valid();

    if (form_is_valid == false) {
        return false;
    }

    $.ajax({
        url: paymentlist,
        method: 'GET',
        context: this,
        success: function(response) {
            $('.billing_data_tab').removeClass('is-open');
            $('.billing_data_tab_list').hide();
            $('.paymentlist_data').html(response.html_data);
            $('.paymentlist_data_tab').addClass('is-open');
            $('.paymentlist_data').show();
            // $('.Delivery_Method').html(response.html_data);
            // $('.Delivery_Method_tab').addClass('is-open');
            // $('.Delivery_Method').show();
            getshippingdata(false);
            $('.shipping_change:checked').trigger('change');
            $('.payment_change:checked').trigger('change');
        }
    });
});

$(document).on('click', '.additional_notes_tab', function(e) {
    $.ajax({
        url: additionalnote,
        method: 'GET',
        context: this,
        success: function(response) {
            $('.paymentlist_data_tab').removeClass('is-open');
            $('.paymentlist_data').hide();
            $('.additional_notes').html(response.html_data);
            $('.additional_notes_tab').addClass('is-open');
        }
    });
});

$(document).on('click', '.additional_note_done', function(e) {
    $('.additional_notes_tab').removeClass('is-open');
    $('.additional_notes').hide();

    $('.comfirm_list_tab').addClass('is-open');
    $('.comfirm_list_data').show();

});
$(document).on('click', '.payment_done', function(e) {
    var payment_change = $('.payment_change').val();
    if (payment_change === undefined || payment_change === null || payment_change == 0) {
        return false;
    }

    var note = "{{ isset($settings['additional_notes']) ? $settings['additional_notes'] : 'off' }}";
    if (note == 'on') {
        $('.paymentlist_data_tab').removeClass('is-open');
        $('.paymentlist_data').hide();

        $('.additional_notes_tab').addClass('is-open');
        $('.additional_notes_tab').trigger('click');
        $('.additional_notes').show();
    } else {
        $('.paymentlist_data_tab').removeClass('is-open');
        $('.paymentlist_data').hide();

        $('.comfirm_list_tab').addClass('is-open');
        $('.comfirm_list_data').show();

    }

});



$(document).on('click', '.remove_item_from_cart', function(e) {
    var cart_id = $(this).attr('data-id');
    var data = {
        cart_id: cart_id
    }
    $.ajax({
        url: removeCart,
        method: 'POST',
        data: data,
        context: this,
        success: function(response) {
            get_cartlist();
        }
    });
});

$(document).on('click', '.change-cart-globaly', function(e) {
    var cart_id = $(this).attr('cart-id');
    var quantity_type = $(this).attr('quantity_type');
    var coupon_code = $('.coupon_code').val();
    var stateId = $("#state_id option:selected").val();
    var countryId = $("#country_id option:selected").val();
    var cityId = $("#city_id option:selected").val();
    var tax_id_value = $('#tax_id_value').val();
    var shipping_final_price = parseInt($('.shipping_final_price').first().text());
    var data = {
        cart_id: cart_id,
        quantity_type: quantity_type,
        coupon_code: coupon_code,
        stateId:stateId,
        countryId:countryId,
        cityId:cityId,
        tax_id_value:tax_id_value,
        shipping_final_price:shipping_final_price,
    };

    $.ajax({
        url: changeCart,
        method: 'POST',
        data: data,
        context: this,
        success: function(response) {
            if (response.status == 0) {
                show_toastr('Error', response.data.message, 'error');
            } else {
                show_toastr('Success', response.data.message, 'success');
            }

            if ($('.billing_address_list').val() || ($("#country_id option:selected").val() &&  $("#state_id option:selected").val())) {
                var billing_address_id = $('.billing_address_list').val();
                var product_id = $('#product_id').val();
                var product_qty = $('#product_qty').val();
                var coupon_code = $('.coupon_code').val();
                var stateId = $("#state_id option:selected").val();
                var countryId = $("#country_id option:selected").val();
                var cityId = $("#city_id option:selected").val();
                var billing_addres_id = $('.billing_address_list').val();

                var data = {
                    address_id: billing_address_id,
                    product_id: product_id,
                    product_qty: product_qty,
                    coupon_code: coupon_code,
                    billing_addres_id: billing_addres_id,
                    stateId: stateId,
                    countryId: countryId,
                    cityId: cityId,
                };
               // Get Default Shipping Data
                getDefaultShippingData(data);
            }
            get_cartlist();
        }
    });
});

$(document).on('change', '.country_change', function(e) {
    var country_id = $(this).val();
    var data = {
        country_id: country_id
    }
    $.ajax({
        url: state_list,
        method: 'POST',
        data: data,
        context: this,
        success: function(response) {
            $(this).closest('.row').find('.state_chage').html('').show();
            $(this).closest('.row').find('.nice-select.state_chage').remove();
            var state = $(this).closest('.row').find('.state_chage').attr('data-select');

            var option = '';
            $.each(response, function(i, item) {
                var checked = '';
                if (i == state) {
                    var checked = 'checked';
                }
                option += '<option value="' + i + '" ' + checked + '>' + item +
                    '</option>';
            });
            $(this).closest('.row').find('.state_chage').html(option);
            $(this).closest('.row').find('.state_chage').val(state);

            if (state != 0) {
                $(this).closest('.row').find('.state_chage').trigger('change');
            }
            getBillingdetail();
            $('select').niceSelect();
        }
    });
});

$(document).on('change', '.state_chage', function(e) {
    var state_id = $(this).val();
    var data = {
        state_id: state_id
    }
    $.ajax({
        url: city_list,
        method: 'POST',
        data: data,
        context: this,
        success: function(response) {
            $(this).closest('.row').find('.city_change').html('').show();
            $(this).closest('.row').find('.nice-select.city_change').remove();
            var city = $(this).closest('.row').find('.city_change').attr('data-select');


            var option = '';
            $.each(response, function(i, item) {
                var checked = '';
                if (i == city) {
                    var checked = 'checked';
                }
                option += '<option value="' + i + '" ' + checked + '>' + item +
                    '</option>';
            });

            $(this).closest('.row').find('.city_change').html(option);
            $(this).closest('.row').find('.city_change').val(city);

            if (city != 0) {
                $(this).closest('.row').find('.city_change').trigger('change');
            }
            getBillingdetail();
            $('select').niceSelect();

        }
    });
});

$(document).on('change', '.state_chage', function(e) {
    getBillingdetail();
});

$(document).on('click', '.wish-header', function(e) {
    get_wishlist(wishListSidebar, false);
});

function get_wishlist(url, myAccount) {
    var data = { _token: $('meta[name="csrf-token"]').attr('content')};
    if(myAccount) {
        url = url+'?page=1';
    }
    $.ajax({
        url: url,
        method: 'POST',
        data: data,
        context: this,
        success: function(response) {
            if(!myAccount) {
                if (response.status == 0) {

                    // show_toster('error', response.message);
                    $('.wish-header').css("pointer-events", "auto");
                    $('.wish-header .count').html(0);
                    $('.wishDrawer .closewish').click();
                }
                if (response.status == 1) {
                    $('.wish-header .count').html(response.wish_total_product);
                    $('.wishajaxDrawer').html(response.html);

                    $('.wish-page-section').html(response.checkout_html);
                    $('.checkout_page_wish').html(response.checkout_html_2);
                    $('.checkout_products').html(response.checkout_html_products);
                    $('.checkout_amount').html(response.checkout_amounts);
                    $('#sub_total_checkout_page').attr('value', response.sub_total);

                }
            } else {
                $('.wish-list-div').html(response.html);
                $('.wishcount').html('['+response.wishlist_count+']');
            }
        }
    });
}

function range_slide() {
    // Range slider - gravity forms
    if ($('.slider-range').length > 0) {
        $('.slider-range').each(function(index, element) {
            var object_id = $(this).attr('id');
            if (typeof object_id === "undefined") {
                var object_id = 'slider-range';
            }
            var object_id = '#' + object_id;

            var min_price = $(this).attr('min_price');
            if (typeof min_price === "undefined") {
                var min_price = 0;
            }

            var max_price = $(this).attr('max_price');
            if (typeof max_price === "undefined") {
                var max_price = 5000;
            }

            var step = $(this).attr('price_step');
            if (typeof step === "undefined") {
                var step = 1;
            }

            var currency = $(this).attr('currency');
            if (typeof currency === "undefined") {
                var currency = '$';
            }

            $(object_id).slider({
                range: true,
                min: parseInt(min_price),
                max: parseInt(max_price),
                step: parseInt(step),
                values: [parseInt(min_price), parseInt(max_price)],
                slide: function(event, ui) {
                    $(this).parent().parent().find('.min_price_select').attr('price', ui.values[
                        0]).html(currency + '' + ui.values[0]);
                    $(this).parent().parent().find('.max_price_select').attr('price', ui.values[
                        1]).html(currency + '' + ui.values[1]);
                    $("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
                }
            });

        });
    }
}

$(document).on('click', '.delete_wishlist', function() {
    var id = $(this).attr('data-id');
    var url = removeWishlist+'?id=' + id;
    $.ajax({
        url: url,
        method: 'GET',
        context: this,
        success: function(response) {
            var currentRoute = window.location.pathname.split("/").pop();
            if (currentRoute == 'my-account') {
                get_wishlist(wishList, true);
            } else {
                get_wishlist(wishListSidebar, false);
            }

        }
    });
});

$(document).on('click', '.wishbtn-globaly', function(e) {
    var product_id = $(this).attr('product_id');
    var wishlist_type = $(this).attr('in_wishlist');

    // var wishlist_type = $('.wishlist_type').val();

    if (!isAuthenticated) {
        var message = "Please login to continue";
        window.location.href = loginUrl;
    } else {
        var data = {
            product_id: product_id,
            wishlist_type: wishlist_type,
        }
        $.ajax({
            url: addProductWishlist,
            method: 'POST',
            data: data,
            context: this,
            success: function(response) {
                // console.log(response.status);
                if (response.status == 0) {
                   // show_toastr('Error', response.data.message, 'error');
                } else {
                    $(this).find('i').hasClass('ti ti-heart') ? $(this).find('i')
                        .removeClass('ti ti-heart') : $(this).find('i').addClass(
                            'ti ti-heart');
                    $(this).find('i').hasClass('fa fa-heart') ? $(this).find('i')
                        .removeClass('fa fa-heart') : $(this).find('i').addClass(
                            'fa fa-heart');
                    if (wishlist_type == 'add') {
                        $(this).attr('in_wishlist', 'remove');
                    }
                    if (wishlist_type == 'remove') {
                        $(this).attr('in_wishlist', 'add');
                    }

                    show_toastr('Success', response.data.message, 'success');
                }
            }
        });
    }
});

$(document).on("click", ".Question", function() {
    if (!isAuthenticated || isAuthenticated == 'false') {
        var message = "Please login to continue"; // Your desired message
        window.location.href = loginUrl; // Redirect to loginUrl
    }
});

$(document).on("click", ".Query", function() {
    if (!isAuthenticated || isAuthenticated == 'false') {
        var message = "Please login to continue"; // Your desired message
        window.location.href = loginUrl; // Redirect to loginUrl
    }
});

$(document).on('change', '.product_variatin_option', function(e) {
    var productId = $(this).data('product');
    product_price(productId);
});

$(document).on('click', '.change_price', function(e) {
    var productId = $(this).data('product');
    product_price(productId);
});

function product_price(productId) {
    var data = $('.variant_form').serialize();
    var data = data + '&product_id='+productId;

    $.ajax({
        url: productPrice,
        method: 'POST',
        data: data,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        context: this,

        success: function(response) {
            $('.addcart-btn.addcart-btn-globaly').attr('variant_id', '0');
            if (response.status == 'error') {
                show_toastr('Error', response.message, 'error');
                $('.quantity').val(response.qty);
                $('.product_var_option').attr('variant_id', response.variant_id);
            } else {
                $('.product_final_price').html(response.original_price);
                $('.currency').html(response.currency);
                $('.currency-type').html(response.currency_name);
                $('.product_orignal_price').html(response.product_original_price);
                $('.product_tax_price').html(response.total_tax_price + ' ' + response.currency_name);
                $('.addcart-btn.addcart-btn-globaly').attr('variant_id', response.variant_id);
                $('.addcart-btn.addcart-btn-globaly').attr('qty', response.qty);
                $(".enable_option").hide();
                $('.product-variant-description').html(response.description);
                if (response.enable_option_data == true) {
                    if (response.stock <= 0) {
                        $('.stock').parent().hide(); // Hide the parent container of the .stock element
                    } else {
                        $('.stock').html(response.stock);
                        $('.enable_option').show();
                    }
                }
                if (response.stock_status != '') {
                    if (response.stock_status == 'out_of_stock') {
                        $('.price-value').hide();
                        $('.variant_form').hide();
                        $('.price-wise-btn').hide();
                        $('.stock_status').show();
                        var message = '<span class=" mb-0"> Out of Stock.</span>';
                        $('.stock_status').html(message);

                    } else if (response.stock_status == 'on_backorder') {
                        $('.stock_status').show();
                        var message = '<span class=" mb-0">Available on backorder.</span>';
                        $('.stock_status').html(message);

                    } else {
                        $('.stock_status').hide();
                    }
                }
                if (response.variant_product == 1 && response.variant_id == 0) {
                    $('.product_orignal_price').hide();
                    $('.product_final_price').hide();
                    $('.min_max_price').show();
                    $('.product-price-amount').hide();
                    $('.product-price-error').show();
                    var message =
                        '<span class=" mb-0 text-danger"> This product is not available.</span>';
                    $('.product-price-error').html(message);
                } else {
                    $('.product-price-error').hide();
                    $('.product_orignal_price').show();
                    $('.currency').show();
                    $('.product_final_price').show();
                    $('.product-price-amount').show();
                }
                if (response.product_original_price == 0 && response.original_price == 0) {
                    $('.product-price-amount').hide();
                    $('.variant_form').hide();
                    $('.price-wise-btn').hide();
                }
            }
        }
    });
}


$(function () {
    $('.floating-wpp').floatingWhatsApp({
        phone: whatsappNumber,
        popupMessage: 'how may i help you?',
        showPopup: true,
        message: 'Message To Send',
        headerTitle: 'Ask Questions'
    });
});

function search_data(){
    var product = $('.search_input').val();
    var data = {
        product: product,
        _token: $('meta[name="csrf-token"]').attr('content')
    }
    $.ajax({
        url: searchProductGlobaly,
        context: this,
        data: data,
        success: function (response) {
            searchData = response;
            $('#products').empty();

            $.each(response, function (key, value) {
                $('#products').append('<option value="' + value.name + '">');
            });
        }
    });
}

function flipdown_popup() {
    var variants = [];
    // Initialize FlipDown
    var flipdownElement = document.querySelector('.flipdown');
    if (!flipdownElement) {
        return false; // Exit function early if FlipDown element is not found
    }
    $(".product_variatin_option").each(function(index, element) {
        variants.push(element.value);
    });
    if (variants.length > 0) {
        $('.product_orignal_price').hide();
        $('.product_final_price').hide();
        $('.min_max_price').show();
        $(".enable_option").hide();
        $('.currency').hide();
    } else {
        $('.product_orignal_price').show();
        $('.product_final_price').show();
        $('.min_max_price').hide();
    }

    $('.flipdown').hide();
    var start_date = $('.flash_sale_start_date').val();
    var end_date = $('.flash_sale_end_date').val();
    var start_time = $('.flash_sale_start_time').val();
    var end_time = $('.flash_sale_end_time').val();

    var startDates = new Date(start_date + ' ' + start_time);
    var startTimestamps = startDates.getTime();

    var endDates = new Date(end_date + ' ' + end_time);
    var endTimestamps = endDates.getTime();

    var timeRemaining = startDates - new Date().getTime();
    var endTimestamp = endTimestamps / 1000;

    $('.flipdown').show();

    // Check if FlipDown library is defined
    if (typeof FlipDown !== 'undefined') {
        var flipdown = new FlipDown(endTimestamp, {
            theme: 'dark'
        }).start().ifEnded(() => {
            $('.flipdown').hide();
        });
    } else {
        console.error('FlipDown library is not defined or not properly loaded.');
    }
}

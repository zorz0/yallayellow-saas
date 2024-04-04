$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var site_url = $('meta[name="base-url"]').attr('content');

$(document).ready(function () {
    comman_function();
    daterange();
    if ($(".dataTable").length > 0) {
        const dataTable = new simpleDatatables.DataTable(".dataTable");
    }
    if ($(".dataTable-5").length > 0) {
        const dataTable = new simpleDatatables.DataTable(".dataTable-5", {
        })

    }
    if ($(".dataTable-6").length > 0) {
        const dataTable = new simpleDatatables.DataTable(".dataTable-6", {
        })
    }
});


$(document).on('input', '.autogrow', function () {
    $(this).height("auto").height($(this)[0].scrollHeight - 18);
});

$(document).on('click', 'a[data-ajax-popup="true"], button[data-ajax-popup="true"], div[data-ajax-popup="true"]', function () {

    var title = $(this).data('title');
    var size = ($(this).attr('data-size') == '') ? 'md' : $(this).attr('data-size');
    var url = $(this).data('url');
    $("#commanModel .modal-title").html(title);
    $("#commanModel .modal-dialog").removeClass('modal-lg').removeClass('modal-md').removeClass('modal-sm');
    $("#commanModel .modal-dialog").addClass('modal-' + size);

    $.ajax({
        url: url,
        success: function (data) {
            $('#commanModel .modal-body').html(data);
            $("#commanModel").modal('show');

            $('#theme_id').trigger('change');

            // Product Page
            $('#enable_product_variant').trigger('change');
            $('#variant_tag').trigger('change');
            $('#maincategory').trigger('change');

            // Review Page
            $('#category_id').trigger('change');

            // coupone Code Page
            $('.code').trigger('click');

            comman_function();
        },
        error: function (data) {
            data = data.responseJSON;
        }
    });

});

$(document).on('click', 'a[data-ajax-popup-over="true"], button[data-ajax-popup-over="true"], div[data-ajax-popup-over="true"]', function () {
    var title = $(this).data('title');
    var size = ($(this).attr('data-size') == '') ? 'md' : $(this).attr('data-size');
    var url = $(this).data('url');
    $("#commanModelOver .modal-title").html(title);
    $("#commanModelOver .modal-dialog").removeClass('modal-lg').removeClass('modal-md').removeClass('modal-sm');
    $("#commanModelOver .modal-dialog").addClass('modal-' + size);
    $.ajax({
        url: url,
        success: function (data) {
            $('#commanModelOver .modal-body').html(data);
            $("#commanModelOver").modal('show');

            $('#theme_id').trigger('change');

            // Product Page
            $('#enable_product_variant').trigger('change');
            $('#variant_tag').trigger('change');
            $('#maincategory').trigger('change');

            // Review Page
            $('#category_id').trigger('change');

            // coupone Code Page
            $('.code').trigger('click');

            comman_function();
        },
        error: function (data) {
            data = data.responseJSON;
        }
    });

});

$(document).on('click', '.show_confirm', function (e) {
    var form = $(this).closest("form");
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "This action can not be undone. Do you want to continue?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    })
});

function comman_function() {
    if ($('[data-role="tagsinput"]').length > 0) {
        $('[data-role="tagsinput"]').each(function (index, element) {
            var obj_id = $(this).attr('id');
            var textRemove = new Choices(
                document.getElementById(obj_id), {
                delimiter: ',',
                editItems: true,
                removeItemButton: true,
            }
            );
        });
    }

    if ($(".summernote-simple").length) {
        $('.summernote-simple').summernote({
            codemirror: {
                mode: 'text/html',
                htmlMode: true,
                lineNumbers: true,
                theme: 'monokai', // You can choose a different theme if desired
            },
            dialogsInBody: !0,
            minHeight: 200,

            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough']],
                ['list', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'unlink']],
            ]
        });

    }

// use for Product 
    if ($(".summernote-simple-product").length) {
        $('.summernote-simple-product').summernote({
            codemirror: {
                mode: 'text/html',
                htmlMode: true,
                lineNumbers: true,
                theme: 'monokai', // You can choose a different theme if desired
            },
            dialogsInBody: !0,
            minHeight: 200,
            toolbar: [
                ['style', ['style']],
                ["font", ["bold", "italic", "underline", "clear", "strikethrough"]],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ["para", ["ul", "ol", "paragraph"]],
                ]
        });

    }
}

function show_toastr(title, message, type) {
    var o, i;
    var icon = '';
    var cls = '';
    if (type == 'success') {
        cls = 'primary';
        notifier.show('Success', message, 'success', site_url + '/public/assets/images/notification/ok-48.png', 4000);
    } else {
        cls = 'danger';
        notifier.show('Error', message, 'danger', site_url + '/public/assets/images/notification/high_priority-48.png', 4000);
    }
}

PurposeStyle = function () {
    var e = getComputedStyle(document.body);
    return {
        colors: {
            gray: {100: "#f6f9fc", 200: "#e9ecef", 300: "#dee2e6", 400: "#ced4da", 500: "#adb5bd", 600: "#8898aa", 700: "#525f7f", 800: "#32325d", 900: "#212529"},
            theme: {
                primary: e.getPropertyValue("--primary") ? e.getPropertyValue("--primary").replace(" ", "") : "#6e00ff",
                info: e.getPropertyValue("--info") ? e.getPropertyValue("--info").replace(" ", "") : "#00B8D9",
                success: e.getPropertyValue("--success") ? e.getPropertyValue("--success").replace(" ", "") : "#36B37E",
                danger: e.getPropertyValue("--danger") ? e.getPropertyValue("--danger").replace(" ", "") : "#FF5630",
                warning: e.getPropertyValue("--warning") ? e.getPropertyValue("--warning").replace(" ", "") : "#FFAB00",
                dark: e.getPropertyValue("--dark") ? e.getPropertyValue("--dark").replace(" ", "") : "#212529"
            },
            transparent: "transparent"
        }, fonts: {base: "Nunito"}
    }
}

var PurposeStyle = PurposeStyle();

/********* Cart Popup ********/
$('.wish-header').on('click',function(e){
    e.preventDefault();
    setTimeout(function(){
    $('body').addClass('no-scroll wishOpen');
    $('.overlay').addClass('wish-overlay');
    }, 50);
});
$('body').on('click','.overlay.wish-overlay, .closewish', function(e){
    e.preventDefault();
    $('.overlay').removeClass('wish-overlay');
    $('body').removeClass('no-scroll wishOpen');
});

/********* Domain-subdomain tab ********/
$(document).on('change', '.domain_click#enable_storelink', function (e) {
    $('#StoreLink').show();
    $('.sundomain').hide();
    $('.domain').hide();
    $('#domainnote').hide();
    $( "#enable_storelink" ).parent().addClass('active');
    $( "#enable_domain" ).parent().removeClass('active');
    $( "#enable_subdomain" ).parent().removeClass('active');
});
$(document).on('change', '.domain_click#enable_domain', function (e) {
    $('.domain').show();
    $('#StoreLink').hide();
    $('.sundomain').hide();
    $('#domainnote').show();
    $( "#enable_domain" ).parent().addClass('active');
    $( "#enable_storelink" ).parent().removeClass('active');
    $( "#enable_subdomain" ).parent().removeClass('active');
});
$(document).on('change', '.domain_click#enable_subdomain', function (e) {
    $('.sundomain').show();
    $('#StoreLink').hide();
    $('.domain').hide();
    $('#domainnote').hide();
    $( "#enable_subdomain" ).parent().addClass('active');
    $( "#enable_domain" ).parent().removeClass('active');
    $( "#enable_domain" ).parent().removeClass('active');
});

/********* Domain-subdomain tab ********/


/********* POS Module ********/
$(document).on('click', 'a[data-ajax-popup="true"], button[data-ajax-popup="true"], div[data-ajax-popup="true"]', function () {

    var data = {};
    var title = $(this).data('title');
    var size = ($(this).data('size') == '') ? 'md' : $(this).data('size');
    var url = $(this).data('url');
    $("#commonModal .modal-title").html(title);
    $("#commonModal .modal-dialog").addClass('modal-' + size);
    if ($('#vc_name_hidden').length > 0) {
        data['vc_name'] = $('#vc_name_hidden').val();
    }
    if ($('#store_id').length > 0) {
        data['store_id'] = $('#store_id').val();
    }
    if ($('#discount_hidden').length > 0) {
        data['discount'] = $('#discount_hidden').val();
    }
    $.ajax({
        url: url,
        data:data,
        success: function (data) {

            $('#commonModal .modal-body').html(data);
            $("#commonModal").modal('show');

        },
        error: function (data) {
            data = data.responseJSON;
        }
    });

});

function addCommas(num) {
    var number = parseFloat(num).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    return ((site_currency_symbol_position == "pre") ? site_currency_symbol : '') + number + ((site_currency_symbol_position == "post") ? site_currency_symbol : '');
}

function wcqib_refresh_quantity_increments() {
    jQuery("div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)").each(function (a, b) {
        var c = jQuery(b);
        c.addClass("buttons_added"),
            c.children().first().before('<input type="button" value="-" class="minus" />'),
            c.children().last().after('<input type="button" value="+" class="plus" />')
    })
}

String.prototype.getDecimals || (String.prototype.getDecimals = function () {
    var a = this,
        b = ("" + a).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
    return b ? Math.max(0, (b[1] ? b[1].length : 0) - (b[2] ? +b[2] : 0)) : 0
}), jQuery(document).ready(function () {
    wcqib_refresh_quantity_increments()
}), jQuery(document).on("updated_wc_div", function () {
    wcqib_refresh_quantity_increments()
}), jQuery(document).on("click", ".plus, .minus", function () {
    var a = jQuery(this).closest(".quantity").find('input[name="quantity"], input[name="quantity[]"]'),
        b = parseFloat(a.val()),
        c = parseFloat(a.attr("max")),
        d = parseFloat(a.attr("min")),
        e = a.attr("step");
    b && "" !== b && "NaN" !== b || (b = 0), "" !== c && "NaN" !== c || (c = ""), "" !== d && "NaN" !== d || (d = 0), "any" !== e && "" !== e && void 0 !== e && "NaN" !== parseFloat(e) || (e = 1), jQuery(this).is(".plus") ? c && b >= c ? a.val(c) : a.val((b + parseFloat(e)).toFixed(e.getDecimals())) : d && b <= d ? a.val(d) : b > 0 && a.val((b - parseFloat(e)).toFixed(e.getDecimals())), a.trigger("change")
});

$(document).on('click', 'input[name="quantity"], input[name="quantity[]"]', function (e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
        // Allow: Ctrl+A
        (e.keyCode == 65 && e.ctrlKey === true) ||
        // Allow: home, end, left, right
        (e.keyCode >= 35 && e.keyCode <= 39)) {
        // let it happen, don't do anything
        return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
});

/********* POS Module ********/
/********* daterange ********/
function daterange() {
    if ($("#pc-daterangepicker-1").length > 0) {
        document.querySelector("#pc-daterangepicker-1").flatpickr({
            mode: "range"
        });
    }
}
/*********End daterange ********/

if ($(".summernote-simple").length) {
    setTimeout(function (){
        $('.summernote-simple').summernote({
            dialogsInBody: !0,
            minHeight: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough']],
                ['list', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'unlink']],
            ]
        });
    },3000);
}

/************ store setting page **************/
$(window).scroll(function(){
    if ($(this).scrollTop() > 100) {
       $('.theme-set-tab').addClass('sticy-tab');
    } else {
       $('.theme-set-tab').removeClass('sticy-tab');
    }
});



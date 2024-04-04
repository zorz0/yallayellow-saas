<meta name="csrf-token" content="{{ csrf_token() }}">
@if ($product_stock)
    <div class="card-body">
        <div class="faq" id="accordionExample">
            <div class="row">
                <div class="col-12">
                    @foreach ($product_stock as $key => $combination)
                        @php
                            $profile = asset(Storage::url('uploads/profile/'));
                            $str = $combination->variant;
                            $variationOptions = explode(',', $combination->variation_option);
                        @endphp
                        <div class="accordion accordion-flush" id="">
                            <div id="" class="accordion-item card attribute_option_data">
                                <div class="accordion-item card media remove_option_{{ $str }}">
                                    <h2 class="accordion-header" id="COD">
                                        <button class="accordion-button collapsed according-delete-input" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseone_{{ $str }}"
                                            aria-expanded="false" aria-controls="collapseone_{{ $str }}">
                                            <span class="d-flex align-items-center">
                                                <i class="ti ti-credit-card me-2"></i>{{ $combination->variant }}
                                                @if (!empty($combination->id))
                                                    <a href="#"
                                                        class="action-btn btn-danger btn btn-sm d-inline-flex align-items-center delete-option-comment"
                                                        data-url="{{ route('product.attribute.delete', $combination->id) }}"
                                                        data-id="{{ $combination->id }}">
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                @endif
                                            </span>
                                        </button>
                                    </h2>
                                    <div id="collapseone_{{ $str }}" class="accordion-collapse collapse"
                                        aria-labelledby="COD" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="form-group col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input input-primary enable_view"
                                                            value="enabled" type="checkbox"
                                                            name="variation_option_{{ $str }}[]"
                                                            id="enable_view_{{ $str }}"
                                                            @if (in_array('enabled', $variationOptions)) checked @endif>
                                                        <label class="form-check-label"
                                                            for="enable_view_{{ $str }}">{{ __('Enabled') }}</label>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input input-primary downloadable"
                                                            value="downloadable_product"
                                                            name="variation_option_{{ $str }}[]"
                                                            type="checkbox"
                                                            id="downloadable_product_{{ $str }}"
                                                            @if (in_array('downloadable_product', $variationOptions)) checked @endif>
                                                        <label class="form-check-label downloadable"
                                                            for="downloadable_product_{{ $key }}">{{ __('Downloadable') }}</label>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input input-primary virtual_product"
                                                            value="virtual_product" type="checkbox"
                                                            name="variation_option_{{ $str }}[]"
                                                            id="virtual_product_{{ $str }}"
                                                            @if (in_array('virtual_product', $variationOptions)) checked @endif>
                                                        <label class="form-check-label"
                                                            for="virtual_product_{{ $str }}">{{ __('Virtual') }}</label>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input input-primary manage_stock"
                                                            value="manage_stock"
                                                            name="variation_option_{{ $str }}[]"
                                                            type="checkbox" id="manage_stock_{{ $str }}"
                                                            @if (in_array('manage_stock', $variationOptions)) checked @endif>
                                                        <label class="form-check-label"
                                                            for="manage_stock_{{ $str }}">{{ __('Manage stock?') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6 sku">
                                                    {!! Form::label('', __('SKU'), ['class' => 'form-label']) !!}
                                                    {!! Form::text('product_sku_' . $str, '-' . $combination->variant, ['class' => 'form-control']) !!}
                                                </div>
                                                <div class="form-group col-md-6 weight-div product_weights_{{ $str }}"
                                                    data-id="{{ $str }}">
                                                    {!! Form::label('', __('Weight(Kg)'), ['class' => 'form-label ']) !!}
                                                    {!! Form::number('product_weight_' . $str, $combination->weight, [
                                                        'class' => 'form-control',
                                                        'min' => '0',
                                                        'step' => '0.01',
                                                    ]) !!}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    {!! Form::label('', __('Variation Price'), ['class' => 'form-label']) !!}
                                                    {!! Form::number('product_variation_price_' . $str, $combination->variation_price, [
                                                        'class' => 'form-control',
                                                        'min' => '0',
                                                        'step' => '0.01',
                                                    ]) !!}
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {!! Form::label('', __('Sale Price'), ['class' => 'form-label']) !!}
                                                    {!! Form::number('product_sale_price_' . $str, $combination->price, [
                                                        'class' => 'form-control',
                                                        'min' => '0',
                                                        'step' => '0.01',
                                                    ]) !!}
                                                </div>
                                            </div>

                                            <div class="form-group col-md-12 shipping-div">
                                                {!! Form::label('', __('Shipping'), ['class' => 'form-label']) !!}
                                                {!! Form::select('shipping_id_' . $str, $Shipping, $combination->shipping, [
                                                    'class' => 'form-control',
                                                    'data-role' => 'tagsinput',
                                                    'id' => 'Shipping',
                                                ]) !!}
                                            </div>
                                            @if ($combination->stock_status != null)
                                                <div class="form-group col-md-12 stock_status">
                                                    {!! Form::label('', __('Stock Status'), ['class' => 'form-label']) !!}
                                                    {!! Form::select(
                                                        'stock_status_' . $str,
                                                        [
                                                            '' => 'Select option',
                                                            'in_stock' => 'In Stock',
                                                            'out_of_stock' => 'Out Of Stock',
                                                            'on_backorder' => 'On Backorder',
                                                        ],
                                                        $combination->stock_status,
                                                        ['class' => 'form-control'],
                                                    ) !!}
                                                </div>
                                            @endif
                                            <div class="form-group col-md-12">
                                                {!! Form::label('', __('Description'), ['class' => 'form-label']) !!}
                                                {!! Form::textarea('product_description_' . $str, $combination->description, [
                                                    'rows' => 4,
                                                    'class' => 'form-control',
                                                ]) !!}
                                            </div>
                                            <div class="row col-md-12 d-none enable_manage_stock manageble_stock_{{ $str }}"
                                                id="enable_manage_stock" data-id="{{ $str }}">
                                                <div class="form-group col-md-4 ">
                                                    {!! Form::label('', __('Stock'), ['class' => 'form-label']) !!}
                                                    {!! Form::number('product_stock_' . $str, $combination->stock, ['class' => 'form-control']) !!}
                                                </div>
                                                <div class="form-group col-md-5">
                                                    {!! Form::label('', __('Allow BackOrders:'), ['class' => 'form-label']) !!}
                                                    <div class="form-check m-1">
                                                        <input type="radio" id="not_allow" value="not_allow"
                                                            name="stock_order_status_{{ $str }}"
                                                            class="form-check-input code"
                                                            {{ $combination->stock_order_status == 'not_allow' ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="not_allow">{{ __('Do Not Allow') }}</label>
                                                    </div>
                                                    <div class="form-check m-1">
                                                        <input type="radio" id="notify_customer"
                                                            value="notify_customer"
                                                            name="stock_order_status_{{ $str }}"
                                                            class="form-check-input code"
                                                            {{ $combination->stock_order_status == 'notify_customer' ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="notify_customer">{{ __('Allow, But notify customer') }}</label>
                                                    </div>
                                                    <div class="form-check m-1">
                                                        <input type="radio" id="allow" value="allow"
                                                            name="stock_order_status_{{ $str }}"
                                                            class="form-check-input code"
                                                            {{ $combination->stock_order_status == 'allow' ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="allow">{{ __('Allow') }}</label>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-3 ">
                                                    {!! Form::label('', __('Low stock threshold'), ['class' => 'form-label ']) !!}
                                                    {!! Form::number('low_stock_threshold_' . $str, $combination->low_stock_threshold, [
                                                        'class' => 'form-control',
                                                        'min' => '0',
                                                        'step' => '0.01',
                                                    ]) !!}
                                                </div>
                                            </div>
                                            <input type="hidden" class="options_data_{{ $str }}[]"
                                                name="options_datas[]" value="{{ $str }}">
                                            <div class="row download-product d-none down_product_{{ $str }}"
                                                data-id="{{ $str }}" id="download-product">
                                                <div class="form-group mb-0">
                                                    <label for="downloadable_product"
                                                        class="form-label">{{ __('Downloadable Product') }}</label>
                                                    <input type="file"
                                                        name="downloadable_product_{{ $str }}"
                                                        id="downloadable_product" class="form-control"
                                                        onchange="document.getElementById('down_product').src = window.URL.createObjectURL(this.files[0])">

                                                    <img src="{{ !empty($combination->downloadable_product) ? get_file($combination->downloadable_product, APP_THEME()) : $profile . '/avatsar.png' }}"
                                                        class=" rounded-circle-avatar" width="100px">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endif
<script>
    $(document).on('click', '.delete_option', function(event) {
        event.preventDefault(); // Prevent the default behavior of the button

        var id = $(this).attr('data-id');
        $('.remove_option_' + id).remove();
    });


    $('.enable_view').change(function() {
        if ($(this).prop('checked') == true) {
            var optionValue = $(this).val();
            if (optionValue == 'enabled') {}
        }
    });

    $(document).ready(function() {
        if ($('.enable_product_stock').prop('checked') == true) {
            $('.stock_status').hide();
        }
        if ($('.virtual_product').prop('checked') == true) {
            $('.shipping-div').hide();
        }

    });
    $('#enable_product_stock').change(function() {

        if ($(this).prop('checked') == true) {
            $('.stock_status').hide();
        }
    });

    $(document).on('change', '.downloadable', function() {
        var optionValue = $(this).val();
        var container = $(this).closest('.accordion-item');

        if ($(this).prop('checked')) {
            if (optionValue == 'downloadable_product') {
                container.find('.download-product').removeClass('d-none');
            }
        } else {
            container.find('.download-product').addClass('d-none');
        }
    });

    $(document).on('change', '.virtual_product', function() {
        var optionValue = $(this).val();
        var container = $(this).closest('.accordion-item');

        if ($(this).prop('checked')) {
            if (optionValue == 'virtual_product') {
                container.find('.weight-div').addClass('d-none');
                container.find('.shipping-div').addClass('d-none');
            }
        } else {
            container.find('.weight-div').removeClass('d-none');
            container.find('.shipping-div').removeClass('d-none');
        }
    });

    $(document).on('change', '.manage_stock', function() {
        var optionValue = $(this).val();
        var container = $(this).closest('.accordion-item');

        if ($(this).prop('checked')) {
            if (optionValue == 'manage_stock') {
                container.find('.enable_manage_stock').removeClass('d-none');
                container.find('.stock_status').addClass('d-none');

            }
        } else {
            container.find('.enable_manage_stock').addClass('d-none');
            container.find('.stock_status').removeClass('d-none');
        }
    });


    $('.manage_stock').change(function() {
        if ($(this).prop('checked') == true) {
            var optionValue = $(this).val();
            if (optionValue == 'manage_stock') {
                $('.enable_manage_stock').removeClass('d-none');
            }
        } else {
            $('.enable_manage_stock').addClass('d-none');
        }
    });


    $(document).ready(function() {
        $('.accordion-item.card.attribute_option_data').each(function() {

            $('.download-product').each(function() {
                var downProduct = $(this);
                var downProductId = downProduct.attr('data-id');
                var downloadableCheckbox = downProduct.closest('.accordion-item').find(
                    '.downloadable');
                if (downloadableCheckbox.prop('checked') == true) {
                    $('.down_product_' + downProductId).removeClass('d-none');
                } else {
                    $('.down_product_' + downProductId).addClass('d-none');
                }
            });

            // Handle virtual product checkbox
            $('.weight-div').each(function() {
                var downProduct = $(this);
                var downProductId = downProduct.attr('data-id');
                var downloadableCheckbox = downProduct.closest('.accordion-item').find(
                    '.virtual_product');
                if (downloadableCheckbox.prop('checked') == true) {
                    $('.product_weights_' + downProductId).addClass('d-none');
                } else {
                    $('.product_weights_' + downProductId).removeClass('d-none');
                }
            });

            // Handle manage stock checkbox
            $('.enable_manage_stock').each(function() {
                var downProduct = $(this);
                var downProductId = downProduct.attr('data-id');
                var downloadableCheckbox = downProduct.closest('.accordion-item').find(
                    '.manage_stock');
                if (downloadableCheckbox.prop('checked') == true) {
                    $('.manageble_stock_' + downProductId).removeClass('d-none');
                    $('.stock_status').addClass('d-none');


                } else {
                    $('.manageble_stock_' + downProductId).addClass('d-none');
                    $('.stock_status').removeClass('d-none');

                }
            });


        });
    });


    $(function() {
        $('body').on('click', '.delete-option-comment', function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            var url = $(this).attr('data-url');

            var data = {
                'data': id
            };
            // now make the ajax request
            $.ajax({
                type: "DELETE",
                url: url,
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content') // Include CSRF token
                },
                context: this,
                success: function(data) {
                    show_toastr('{{ __('Success') }}',
                        '{{ __('Variant Deleted Successfully!') }}', 'success');
                    $(this).closest('.media').remove();
                }
            });
        });
    });

    $('#enable_product_stock').change(function() {
        $('.stock_status').show();
        if ($(this).prop('checked') == true) {
            $('.stock_status').hide();
        }
    });

    $(document).ready(function() {
        if ($('.enable_product_stock').prop('checked') == true) {
            $('.stock_status').hide();
        }

    });
</script>

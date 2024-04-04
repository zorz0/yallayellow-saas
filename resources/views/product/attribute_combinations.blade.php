@if (count($combinations[0]) > 0)
    <div class="card-body">
        <div class="faq" id="accordionExample">
            <div class="row">
                <div class="col-12">
                        @foreach ($combinations as $key => $combination)
                            @php
                                $sku = $product_name;

                                $str = '';
                                foreach ($combination as $key => $item) {
                                    if ($key > 0) {
                                        $str .= '-' . str_replace(' ', '', $item);
                                        $sku .= '-' . str_replace(' ', '', $item);
                                    } else {
                                        $str .= str_replace(' ', '', $item);
                                        $sku .= '-' . str_replace(' ', '', $item);
                                    }
                                }
                                $option = \App\Models\ProductAttributeOption::where('terms', $str)->first();
                            @endphp
                            <div class="accordion accordion-flush" id="">
                                <div id="" class="accordion-item card attribute_options_datas ">
                                    @if (strlen($str) > 0)
                                    <div class="accordion-item card remove_option_{{ $str }}">
                                            <h2 class="accordion-header" id="COD">
                                                <button class="accordion-button collapsed according-delete-input" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseone_{{ $str }}"
                                                    aria-expanded="false" aria-controls="collapseone_{{ $str }}">
                                                    <span class="d-flex align-items-center">
                                                        <i class="ti ti-credit-card me-2"></i>{{ $str }}
                                                            <a class="btn btn-sm btn-danger delete_option" data-id="{{ $str }}" >
                                                                <i class="ti ti-trash text-white py-1"></i>
                                                            </a>
                                                    </span>
                                                </button>
                                            </h2>
                                            <div id="collapseone_{{ $str }}" class="accordion-collapse collapse"
                                                aria-labelledby="COD" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="form-group col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input input-primary enable_view" value="enabled" type="checkbox" name="variation_option_{{ $str }}[]" id="enable_view_{{ $str }}">
                                                                <label class="form-check-label" for="enable_view_{{ $str }}">{{__('Enabled')}}</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input input-primary downloadable" value="downloadable_product" name="variation_option_{{ $str }}[]" type="checkbox" id="downloadable_product_{{ $str }}">
                                                                <label class="form-check-label downloadable" for="downloadable_product_{{ $key }}">{{__('Downloadable')}}</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input input-primary virtual_product" value="virtual_product" type="checkbox" name="variation_option_{{ $str }}[]" id="virtual_product_{{ $str }}">
                                                                <label class="form-check-label" for="virtual_product_{{ $str }}">{{__('Virtual')}}</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input input-primary manage_stock" value="manage_stock" name="variation_option_{{ $str }}[]" type="checkbox" id="manage_stock_{{ $str }}">
                                                                <label class="form-check-label" for="manage_stock_{{ $str }}">{{__('Manage stock?')}}</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-6 sku">
                                                            {!! Form::label('', __('SKU'), ['class' => 'form-label']) !!}
                                                            {!! Form::text('product_sku_' . $str, $sku, ['class' => 'form-control']) !!}
                                                        </div>
                                                        <div class="form-group col-md-6 weight-div ">
                                                            {!! Form::label('', __('Weight(Kg)'), ['class' => 'form-label ']) !!}
                                                            {!! Form::number('product_weight_' . $str, null, ['class' => 'form-control', 'min' => '0', 'step' => '0.01']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            {!! Form::label('', __('Variation Price'), ['class' => 'form-label']) !!}
                                                            {!! Form::number('product_variation_price_' . $str, null, ['class' => 'form-control', 'min' => '0', 'step' => '0.01']) !!}
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            {!! Form::label('', __('Sale Price'), ['class' => 'form-label']) !!}
                                                            {!! Form::number('product_sale_price_' . $str, $unit_price, ['class' => 'form-control', 'min' => '0', 'step' => '0.01']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-12 shipping-div">
                                                        {!! Form::label('', __('Shipping'), ['class' => 'form-label']) !!}
                                                        {!! Form::select('shipping_id_' . $str, $Shipping, null, [
                                                            'class' => 'form-control',
                                                            'data-role' => 'tagsinput',
                                                            'id' => 'Shipping',
                                                        ]) !!}
                                                    </div>
                                                    <div class="form-group col-md-12 stock_status">
                                                        {!! Form::label('', __('Stock Status'), ['class' => 'form-label']) !!}
                                                        {!! Form::select('stock_status_' . $str, ['' => 'Select option','in_stock' => 'In Stock', 'out_of_stock' => 'Out Of Stock', 'on_backorder' => 'On Backorder'], null, ['class' => 'form-control']) !!}
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        {!! Form::label('', __('Description'), ['class' => 'form-label']) !!}
                                                        {!! Form::textarea('product_description_' . $str, null, ['rows' => 4, 'class'=>'form-control']) !!}
                                                    </div>
                                                    <div class="row col-md-12 d-none enable_manage_stock manageble_stock_{{ $str }}" id="enable_manage_stock" data-id="{{ $str }}">
                                                        <div class="form-group col-md-4 " >
                                                            {!! Form::label('', __('Stock'), ['class' => 'form-label']) !!}
                                                            {!! Form::number('product_stock_' . $str, $stock, ['class' => 'form-control']) !!}
                                                        </div>
                                                        <div class="form-group col-md-5">
                                                        {!! Form::label('', __('Allow BackOrders:'), ['class' => 'form-label']) !!}
                                                            <div class="form-check m-1">
                                                                <input type="radio" id="not_allow" value="not_allow" name="stock_order_status_{{ $str }}" class="form-check-input code"
                                                                    checked="checked">
                                                                <label class="form-check-label" for="not_allow">{{ __('Do Not Allow') }}</label>
                                                            </div>
                                                            <div class="form-check m-1">
                                                                <input type="radio" id="notify_customer" value="notify_customer" name="stock_order_status_{{ $str }}" class="form-check-input code">
                                                                <label class="form-check-label" for="notify_customer">{{ __('Allow, But notify customer') }}</label>
                                                            </div>
                                                            <div class="form-check m-1">
                                                                <input type="radio" id="allow" value="allow" name="stock_order_status_{{ $str }}" class="form-check-input code">
                                                                <label class="form-check-label" for="allow">{{ __('Allow') }}</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-3 ">
                                                            {!! Form::label('', __('Low stock threshold'), ['class' => 'form-label ']) !!}
                                                            {!! Form::number('low_stock_threshold_' . $str, null, ['class' => 'form-control', 'min' => '0', 'step' => '0.01']) !!}
                                                        </div>
                                                    </div>
                                                    <input type="hidden" class="options_data_{{ $str }}[]" name ="options[]" value="{{ $str }}">
                                                    <div class="row download-product d-none down_product_{{ $str }}" data-id="{{ $str }}" id="download-product">
                                                        <div class="form-group mb-0">
                                                            <label for="downloadable_product"
                                                                class="form-label">{{ __('Downloadable Product') }}</label>
                                                            <input type="file" name="downloadable_product_{{ $str }}" id="downloadable_product" data-value="downloadable_product_{{ $str }}"
                                                                class="form-control downloadable_product_variant"
                                                                onchange="document.getElementById('down_product').src = window.URL.createObjectURL(this.files[0])">
                                                            <img id="down_product" src="" width="20%" class="mt-2" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
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
            if (optionValue == 'enabled') {
            }
        }
    });

    $(document).ready(function() {
        if($('.enable_product_stock').prop('checked') == true)
        {
            $('.stock_status').hide();
        }

    });
    $('#enable_product_stock').change(function() {
        $('.stock_status').show();
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
                container.find('.stock_status').hide();
            }
        } else {
            container.find('.enable_manage_stock').addClass('d-none');
            container.find('.stock_status').show();
        }
    });

</script>

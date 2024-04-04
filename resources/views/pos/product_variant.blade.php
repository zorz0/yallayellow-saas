
    <input type="hidden" id="product_id" value="{{ $product->id }}">
    <input type="hidden" id="variant_id" value="">
    <input type="hidden" id="variant_qty" value="">
    <div class="cart-variant-body">
        <div class="row">
            <div class="col-lg-4 col-md-5 col-12">
                <div class="cart-variant-img">
                    <div class="variant-main-media">
                        <img src="{{ asset((isset($product->cover_image_path) && !empty($product->cover_image_path)) ? $product->cover_image_path : 'default_img.png') }}"
                            class="default-img"  width="100%" target="_blank" alt="logitech Keys">
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-7 col-12">
                <div class="cart-variant-detail">
                    <span
                        class="ctg-badge">{{ isset($product->categories) && !empty($product->categories) ? $product->categories->name : '' }}</span>
                    <h3>{{ $product->name }}</h3>
                    <p class="pt-2">{{__('VARIATION:')}}</p>
                    <div class="pv-selection">
                    @php
                        $variant = json_decode($product->product_attribute);
                        
                        $varint_name_array = [];
                        if (!empty($product->DefaultVariantData->variant)) {
                            $varint_name_array = explode('-', $product->DefaultVariantData->variant);
                        }
                    @endphp
                    @foreach ($variant as $key => $value)
                    @php
                        $p_variant = App\Models\Utility::ProductAttribute($value->attribute_id);
                        $attribute = json_decode($p_variant);
                        $propertyKey = 'for_variation_' . $attribute->id;
                        $variation_option = $value->$propertyKey;
                    @endphp

                    @if ($variation_option == 1)
                    <label for="" class="pt-2">{{ ucfirst($attribute->name) }}</label>
                    <select name="product[{{ $key }}]" id="pro_variants_name"
                                class="form-control custom-select variant-selection pro_variants_name{{ $key }}">
                                <option value="0">{{ __('Select Option') }}</option>
                                @php
                                    $optionValues = [];
                                @endphp

                                @foreach ($value->values as $variant1)
                                    @php
                                        $parts = explode('|', $variant1);
                                    @endphp
                                    @foreach ($parts as $p)
                                        @php
                                            $id = App\Models\ProductAttributeOption::where('id', $p)->first();
                                            $optionValues[] = $id->terms;
                                        @endphp
                                    @endforeach
                                @endforeach

                                @if (is_array($optionValues))
                                    @foreach ($optionValues as $optionValue)
                                        <option value="{{ $optionValue }}">{{ $optionValue }}</option>
                                    @endforeach
                                @endif

                               
                            </select>
                    @endif
                @endforeach
                    </div>
                    <div class="cart-variable row pt-3">
                        <div class="col-md-6">
                            <div class="variant_qty" style=" font-size: large; ">
                                {{__('QTY')}}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="variation_price1 text-end" style=" font-size: large; ">
                                @if ($product->enable_product_variant == 'on')
                                    <p style=" font-size: large; ">{{__('Please Select Variants')}}</p>
                                @else
                                    <p>{{ currency_format_with_sym($product->price, getCurrentStore(), APP_THEME()) }}</p>

                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="col-12 d-flex justify-content-end col-form-label">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <a href="#!" type="submit" class="btn btn-primary add_to_cart_variant toacartvariant ms-2" data-toggle="tooltip" data-id="{{ $product->id }}" >
        {{ __('Add To Cart') }}
        <i class="fas fa-shopping-basket ms-1" style="font-size: initial;"></i>
    </a>
</div>
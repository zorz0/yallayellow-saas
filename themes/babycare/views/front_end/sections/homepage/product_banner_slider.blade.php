<section class="two-colum-banner-section padding-top padding-bottom" style="position: relative;@if(isset($option) && $option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif" data-index="{{ $option->order ?? '' }}" data-id="{{ $option->order ?? '' }}" data-value="{{ $option->id ?? '' }}" data-hide="{{ $option->is_hide ?? '' }}" data-section="{{ $option->section_name ?? '' }}"  data-store="{{ $option->store_id ?? '' }}" data-theme="{{ $option->theme_id ?? '' }}">
    <div class="custome_tool_bar"></div>
    <img src="{{ asset('themes/' . $currentTheme. '/assets/img/left-img.png') }}" class="left-img">
    <img src="{{ asset('themes/' . $currentTheme . '/assets/img/center.png') }}" class="center-img">
    <img src="{{ asset('themes/' . $currentTheme . '/assets/img/left-img.png') }}" class="right-img">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-4 col-12">
                <div class="two-colum-inner">
                    <div class="label" id="{{ ($section->product_banner_slider->section->sub_title->slug ?? '') }}_preview">{!! $section->product_banner_slider->section->sub_title->text ?? '' !!}</div>
                    <h2 id="{{ ($section->product_banner_slider->section->title->slug ?? '') }}_preview">{!! $section->product_banner_slider->section->title->text ?? '' !!}</h2>
                    <p id="{{ ($section->product_banner_slider->section->description->slug ?? '') }}_preview">{!! $section->product_banner_slider->section->description->text ?? '' !!}</p>
                    <a href="#" class="btn" id="{{ ($section->product_banner_slider->section->button->slug ?? '') }}_preview">
                        {!! $section->product_banner_slider->section->button->text ?? '' !!}
                        <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M1.77886 1.38444C2.62144 2.10579 3.18966 3.13838 3.3054 4.30408H4.21204V0.344353C4.35754 0.329906 4.50512 0.32251 4.65443 0.32251C4.80374 0.32251 4.95132 0.329906 5.09683 0.344353V4.30408H6.00346C6.1192 3.13838 6.68742 2.10579 7.53001 1.38444C7.7549 1.57698 7.96024 1.79168 8.14265 2.02517C7.4696 2.58546 7.00769 3.39073 6.89379 4.30408H9.05655C9.071 4.44958 9.07839 4.59716 9.07839 4.74647C9.07839 4.89578 9.071 5.04336 9.05655 5.18887H6.89379C7.00769 6.10222 7.4696 6.90748 8.14265 7.46778C7.96024 7.70127 7.7549 7.91597 7.53001 8.1085C6.68742 7.38715 6.1192 6.35457 6.00346 5.18887H5.09683V9.14859C4.95132 9.16304 4.80374 9.17044 4.65443 9.17044C4.50512 9.17044 4.35754 9.16304 4.21204 9.14859V5.18887H3.3054C3.18966 6.35457 2.62144 7.38715 1.77886 8.1085C1.55397 7.91597 1.34862 7.70127 1.16621 7.46778C1.83926 6.90748 2.30118 6.10222 2.41507 5.18887H0.252312C0.237865 5.04336 0.230469 4.89578 0.230469 4.74647C0.230469 4.59716 0.237865 4.44958 0.252312 4.30408H2.41507C2.30118 3.39073 1.83926 2.58546 1.16621 2.02517C1.34862 1.79168 1.55397 1.57698 1.77886 1.38444Z"
                                fill="#12131A"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M4.65443 8.28564C6.60906 8.28564 8.1936 6.7011 8.1936 4.74647C8.1936 2.79184 6.60906 1.2073 4.65443 1.2073C2.6998 1.2073 1.11526 2.79184 1.11526 4.74647C1.11526 6.7011 2.6998 8.28564 4.65443 8.28564ZM4.65443 9.17044C7.09772 9.17044 9.07839 7.18976 9.07839 4.74647C9.07839 2.30319 7.09772 0.32251 4.65443 0.32251C2.21114 0.32251 0.230469 2.30319 0.230469 4.74647C0.230469 7.18976 2.21114 9.17044 4.65443 9.17044Z"
                                fill="#12131A"></path>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="col-lg-6 col-md-8 col-12">
                <div class="two-colum-slider">
                    @foreach ($modern_products as $m_product)
                    <div class="product-card">
                        <div class="product-card-inner">
                            <div class="product-content-top text-center">
                                <div class="label">
                                    {{ $m_product->label->name ?? '' }}
                                </div>
                                <h4><a href="#">{{ $m_product->name }}</a>
                                </h4>
                            </div>
                            <div class="favorite-icon">
                                <a href="javascript:void(0)" class="wishbtn-globaly" product_id="{{ $m_product->id }}"
                                    in_wishlist="{{ $m_product->in_whishlist ? 'remove' : 'add' }}">
                                    <i class="{{ $m_product->in_whishlist ? 'fa fa-heart' : 'ti ti-heart' }}"
                                        style='color: white'></i>
                                </a>
                                <div class="product-btn-wrp">
                                    @php
                                        $module = Nwidart\Modules\Facades\Module::find('ProductQuickView');
                                        $compare_module = Nwidart\Modules\Facades\Module::find('ProductCompare');
                                    @endphp
                                    @if(isset($module) && $module->isEnabled())
                                        {{-- Include the module blade button --}}
                                        @include('productquickview::pages.button', ['product_slug' => $m_product->slug ?? null, 'slug' => $slug ?? null, 'currentTheme' => $currentTheme])
                                    @endif
                                    @if(isset($compare_module) && $compare_module->isEnabled())
                                        {{-- Include the module blade button --}}
                                        @include('productcompare::pages.button', ['product_slug' => $m_product->slug ?? null, 'slug' => $slug ?? null, 'currentTheme' => $currentTheme])
                                    @endif
                                </div>
                            </div>

                            <div class="product-img">
                                <a href="#">
                                    <img src="{{ asset($m_product->cover_image_path ?? '') }}"
                                        alt="product-img">
                                </a>
                            </div>
                            <div class="product-content-bottom text-center">
                                <div class="price-label2">
                                    @if ($m_product->variant_product == 0)
                                    <div class="price">
                                        <ins>{{ currency_format_with_sym( ($m_product->sale_price  ?? $m_product->price ), $store->id, $currentTheme)  }} </ins>
                                    </div>
                                    @else
                                    <div class="price">
                                        <ins>{{ __('In Variant') }}</ins>
                                    </div>
                                    @endif
                                    {!! \App\Models\Product::productSalesPage($currentTheme, $slug, $m_product->id) !!}
                                </div>
                                <a href="javascript:void(0)" class="btn theme-btn addcart-btn-globaly"
                                    product_id="{{ $m_product->id }}" variant_id="0" qty="1">
                                    {{ __('Add to cart') }}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15"
                                        fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M4.22012 7.5C4.02701 7.5 3.88012 7.6734 3.91187 7.86387L4.51047 11.4555C4.61093 12.0582 5.13242 12.5 5.74347 12.5H10.2578C10.8689 12.5 11.3904 12.0582 11.4908 11.4555L12.0894 7.86387C12.1212 7.6734 11.9743 7.5 11.7812 7.5H4.22012ZM3.11344 6.25C2.72722 6.25 2.43345 6.59679 2.49694 6.97775L3.27748 11.661C3.47839 12.8665 4.52137 13.75 5.74347 13.75H10.2578C11.4799 13.75 12.5229 12.8665 12.7238 11.661L13.5044 6.97775C13.5678 6.59679 13.2741 6.25 12.8879 6.25H3.11344Z"
                                            fill="#12131A" />
                                        <path
                                            d="M6.75 8.75C6.40482 8.75 6.125 9.02982 6.125 9.375V10.625C6.125 10.9702 6.40482 11.25 6.75 11.25C7.09518 11.25 7.375 10.9702 7.375 10.625V9.375C7.375 9.02982 7.09518 8.75 6.75 8.75Z"
                                            fill="#12131A" />
                                        <path
                                            d="M9.25 8.75C8.90482 8.75 8.625 9.02982 8.625 9.375V10.625C8.625 10.9702 8.90482 11.25 9.25 11.25C9.59518 11.25 9.875 10.9702 9.875 10.625V9.375C9.875 9.02982 9.59518 8.75 9.25 8.75Z"
                                            fill="#12131A" />
                                        <path
                                            d="M7.19194 2.31694C7.43602 2.07286 7.43602 1.67714 7.19194 1.43306C6.94786 1.18898 6.55214 1.18898 6.30806 1.43306L3.80806 3.93306C3.56398 4.17714 3.56398 4.57286 3.80806 4.81694C4.05214 5.06102 4.44786 5.06102 4.69194 4.81694L7.19194 2.31694Z"
                                            fill="#12131A" />
                                        <path
                                            d="M8.80806 2.31694C8.56398 2.07286 8.56398 1.67714 8.80806 1.43306C9.05214 1.18898 9.44786 1.18898 9.69194 1.43306L12.1919 3.93306C12.436 4.17714 12.436 4.57286 12.1919 4.81694C11.9479 5.06102 11.5521 5.06102 11.3081 4.81694L8.80806 2.31694Z"
                                            fill="#12131A" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M12.375 5H3.625C3.27982 5 3 5.27982 3 5.625C3 5.97018 3.27982 6.25 3.625 6.25H12.375C12.7202 6.25 13 5.97018 13 5.625C13 5.27982 12.7202 5 12.375 5ZM3.625 3.75C2.58947 3.75 1.75 4.58947 1.75 5.625C1.75 6.66053 2.58947 7.5 3.625 7.5H12.375C13.4105 7.5 14.25 6.66053 14.25 5.625C14.25 4.58947 13.4105 3.75 12.375 3.75H3.625Z"
                                            fill="#12131A" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

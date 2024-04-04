<section class="best-seller-section padding-bottom" style="position: relative;@if(isset($option) && $option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif" data-index="{{ $option->order ?? '' }}" data-id="{{ $option->order ?? '' }}" data-value="{{ $option->id ?? '' }}" data-hide="{{ $option->is_hide ?? '' }}" data-section="{{ $option->section_name ?? '' }}"  data-store="{{ $option->store_id ?? '' }}" data-theme="{{ $option->theme_id ?? '' }}">
    <div class="custome_tool_bar"></div>
    <div class="container">
        <div class="common-title d-flex align-items-center justify-content-between">
            <div class="section-title">
            <h2 class="title" id="{{ $section->bestseller_slider->section->title->slug ?? ''}}_preview">
                {!! $section->bestseller_slider->section->title->text ?? ''!!}</h2>

            </div>
            <a href="{{ route('page.product-list', $slug) }}" class="btn theme-btn" id="{{ $section->bestseller_slider->section->button->slug ?? ''}}_preview">
                {!! $section->bestseller_slider->section->button->text ?? '' !!}
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M9.22466 5.62411C8.87997 5.64249 8.58565 5.37796 8.56728 5.03327C8.5422 4.56284 8.51152 4.19025 8.48081 3.89909C8.43958 3.50807 8.19939 3.27049 7.85391 3.23099C7.35787 3.17429 6.60723 3.125 5.49957 3.125C4.39192 3.125 3.64128 3.17429 3.14524 3.23099C2.79917 3.27055 2.55966 3.50754 2.51845 3.89797C2.44629 4.58174 2.37457 5.71203 2.37457 7.5C2.37457 9.28797 2.44629 10.4183 2.51845 11.102C2.55966 11.4925 2.79917 11.7294 3.14524 11.769C3.64128 11.8257 4.39192 11.875 5.49957 11.875C6.60723 11.875 7.35787 11.8257 7.85391 11.769C8.19939 11.7295 8.43958 11.4919 8.48081 11.1009C8.51152 10.8098 8.5422 10.4372 8.56728 9.96673C8.58565 9.62204 8.87997 9.35751 9.22466 9.37589C9.56935 9.39426 9.83388 9.68858 9.8155 10.0333C9.78942 10.5225 9.75716 10.9168 9.72392 11.232C9.62607 12.1598 8.96789 12.8998 7.99588 13.0109C7.4419 13.0742 6.64237 13.125 5.49957 13.125C4.35677 13.125 3.55725 13.0742 3.00327 13.0109C2.03184 12.8999 1.37333 12.1616 1.27536 11.2332C1.19744 10.495 1.12457 9.31924 1.12457 7.5C1.12457 5.68076 1.19744 4.50504 1.27536 3.76677C1.37333 2.83844 2.03184 2.10013 3.00327 1.98908C3.55725 1.92575 4.35677 1.875 5.49957 1.875C6.64237 1.875 7.4419 1.92575 7.99588 1.98908C8.96789 2.1002 9.62607 2.84023 9.72392 3.76798C9.75716 4.08316 9.78942 4.47745 9.8155 4.96673C9.83388 5.31142 9.56935 5.60574 9.22466 5.62411Z"
                        fill="black" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M11.6206 5.75444C11.3765 5.51036 11.3765 5.11464 11.6206 4.87056C11.8646 4.62648 12.2604 4.62648 12.5044 4.87056L14.6919 7.05806C14.936 7.30214 14.936 7.69786 14.6919 7.94194L12.5044 10.1294C12.2604 10.3735 11.8646 10.3735 11.6206 10.1294C11.3765 9.88536 11.3765 9.48964 11.6206 9.24556L12.7411 8.125L6.75 8.125C6.40482 8.125 6.125 7.84518 6.125 7.5C6.125 7.15482 6.40482 6.875 6.75 6.875L12.7411 6.875L11.6206 5.75444Z"
                        fill="black" />
                </svg>
            </a>
        </div>
        <div class="best-seller-slider">
            @foreach ($products as $data)
            <div class="product-card">
                <div class="product-card-inner">
                    <div class="product-content-top text-center">
                        <h4><a href="{{ url($slug.'/product/'.$data->slug) }}">{{ $data->name }}</a>
                        </h4>
                        <div class="favorite-icon">
                            <a href="javascript:void(0)" class="wishbtn-globaly" product_id="{{ $data->id }}"
                                in_wishlist="{{ $data->in_whishlist ? 'remove' : 'add' }}">
                                <i class="{{ $data->in_whishlist ? 'fa fa-heart' : 'ti ti-heart' }}"
                                    style='color: white'></i>
                            </a>
                            <div class="product-btn-wrp">
                                @php
                                    $module = Nwidart\Modules\Facades\Module::find('ProductQuickView');
                                    $compare_module = Nwidart\Modules\Facades\Module::find('ProductCompare');
                                @endphp
                                @if(isset($module) && $module->isEnabled())
                                    {{-- Include the module blade button --}}
                                    @include('productquickview::pages.button', ['product_slug' => $data->slug ?? null, 'slug' => $slug ?? null, 'currentTheme' => $currentTheme])
                                @endif
                                @if(isset($compare_module) && $compare_module->isEnabled())
                                    {{-- Include the module blade button --}}
                                    @include('productcompare::pages.button', ['product_slug' => $data->slug ?? null, 'slug' => $slug ?? null, 'currentTheme' => $currentTheme])
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="product-img">
                        <div class="new-labl">
                        </div>

                        <a href="{{ url($slug.'/product/'.$data->slug) }}">
                            <img src="{{ asset($data->cover_image_path) }}" alt="product-img">
                        </a>
                    </div>
                    <div class="product-content-bottom text-center">
                        <div class="price-label2">
                            @if ($data->variant_product == 0)
                            <div class="price">
                                <ins>{{ currency_format_with_sym( ($data->sale_price  ?? $data->price ), $store->id, $currentTheme) }} </ins>
                            </div>
                            @else
                            <div class="price">
                                <ins>{{ __('In Variant') }}</ins>
                            </div>
                            @endif


                            {!! \App\Models\Product::productSalesPage($currentTheme, $slug, $data->id) !!}
                        </div>
                        <a href="javascript:void(0)" class="btn theme-btn addcart-btn-globaly"
                            product_id="{{ $data->id }}" variant_id="0" qty="1">
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
</section>

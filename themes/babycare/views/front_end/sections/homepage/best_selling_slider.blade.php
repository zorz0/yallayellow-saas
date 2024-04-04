<section class="best-selling-section padding-top" style="position: relative;@if(isset($option) && $option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif" data-index="{{ $option->order ?? '' }}" data-id="{{ $option->order ?? '' }}" data-value="{{ $option->id ?? '' }}" data-hide="{{ $option->is_hide ?? '' }}" data-section="{{ $option->section_name ?? '' }}"  data-store="{{ $option->store_id ?? '' }}" data-theme="{{ $option->theme_id ?? '' }}">
    <div class="custome_tool_bar"></div>
    <div class="container">
        <div class="row">
            @foreach ($categories as $cat)
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="best-selling-card">
                        <div class="best-selling-card-inner">
                            <div class="best-card-image">
                                <a href="{{ route('page.product-list', [$slug, 'main_category' => $cat->id]) }}"
                                    class="best-img">
                                    <img src="{{ asset($cat->image_path) }}" alt="bestproduct">
                                </a>
                                <div class="best-content">
                                    <div class="product-content-top">
                                        <div class="sub-title"> {{ __('CATEGORIES') }} </div>
                                        <h4 class="product-title">
                                            <a
                                                href="{{ route('page.product-list', [$slug, 'main_category' => $cat->id]) }}">
                                                {{ $cat->name }}
                                            </a>
                                        </h4>
                                    </div>
                                </div>
                                <div class="best-card-btn">
                                    <a href="{{ route('page.product-list', [$slug, 'main_category' => $cat->id]) }}"
                                        class="btn-secondary white-btn">
                                        {{ __('Go to categories') }}
                                        <svg width="10" height="10" viewBox="0 0 10 10" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
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
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
<section class="two-col-variant-section"style="@if (isset($option) && $option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif"
    data-index="{{ $option->order ?? '' }}" data-id="{{ $option->order ?? '' }}" data-value="{{ $option->id ?? '' }}"
    data-hide="{{ $option->is_hide ?? '' }}" data-section="{{ $option->section_name ?? '' }}"
    data-store="{{ $option->store_id ?? '' }}" data-theme="{{ $option->theme_id ?? '' }}">
    <div class="custome_tool_bar"></div>
    <div class="right-side-image" style="top: -18%; right: 18%; z-index: 2;">
        <img src="{{asset('themes/'.$currentTheme.'/assets/images/hat-gift.png') }}" alt="Gifts.">
    </div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 col-sm-6 col-12">
                <div class="two-column-variant-left-content">
                    <h2
                        class="section-title"id="{{ $section->variant_background->section->title->slug ?? '' }}_preview">
                        {!! $section->variant_background->section->title->text ?? '' !!}
                    </h2>
                    <p id="{{ $section->variant_background->section->description->slug ?? '' }}_preview">
                        {!! $section->variant_background->section->description->text ?? '' !!}</p>
                    <a href="{{ route('page.product-list', $slug) }}" class="btn-secondary btn-secondary-theme-color"
                        id="{{ $section->variant_background->section->button->slug ?? '' }}_preview">
                        {!! $section->variant_background->section->button->text ?? '' !!}
                        <svg xmlns="http://www.w3.org/2000/svg" width="8" height="10" viewBox="0 0 4 6"
                            fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M0.65976 0.662719C0.446746 0.879677 0.446746 1.23143 0.65976 1.44839L2.18316 3L0.65976 4.55161C0.446747 4.76856 0.446747 5.12032 0.65976 5.33728C0.872773 5.55424 1.21814 5.55424 1.43115 5.33728L3.34024 3.39284C3.55325 3.17588 3.55325 2.82412 3.34024 2.60716L1.43115 0.662719C1.21814 0.445761 0.872773 0.445761 0.65976 0.662719Z"
                                fill=""></path>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12">
                <div class="two-column-variant-right">
                    <img src="{{ asset($section->variant_background->section->image->image) }}" alt="product">
                </div>
            </div>
        </div>
    </div>
</section>

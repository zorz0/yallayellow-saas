<section class="shop-categories" style="position: relative;@if(isset($option) && $option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif" data-index="{{ $option->order ?? '' }}" data-id="{{ $option->order ?? '' }}" data-value="{{ $option->id ?? '' }}" data-hide="{{ $option->is_hide ?? '' }}" data-section="{{ $option->section_name ?? '' }}"  data-store="{{ $option->store_id ?? '' }}" data-theme="{{ $option->theme_id ?? '' }}">
    <div class="custome_tool_bar"></div>    
    <div class="container">
        <div class="section-title d-flex align-items-center justify-content-between">
            <h2 class="title" id="{{ ($section->category->section->title->slug ?? '') }}_preview">{!! $section->category->section->title->text ?? "" !!}</h2>
            <a href="{{route('page.product-list',$slug)}}" class="btn desk-only" id="{{ ($section->category->section->button->slug ?? '') }}_preview">
            {!! $section->category->section->button->text ?? "" !!}
                <svg xmlns="http://www.w3.org/2000/svg" width="4" height="6" viewBox="0 0 4 6" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0.65976 0.662719C0.446746 0.879677 0.446746 1.23143 0.65976 1.44839L2.18316 3L0.65976 4.55161C0.446747 4.76856 0.446747 5.12032 0.65976 5.33728C0.872773 5.55424 1.21814 5.55424 1.43115 5.33728L3.34024 3.39284C3.55325 3.17588 3.55325 2.82412 3.34024 2.60716L1.43115 0.662719C1.21814 0.445761 0.872773 0.445761 0.65976 0.662719Z" fill="white"></path>
                </svg>
            </a>
        </div>
        <div class="row row-gap">
            {!! \App\Models\MainCategory::HomePageBestCategory($currentTheme, $slug, $section, 4) !!}
        </div>
    </div>
    <div class="section-left-shape">
        <img src="{{ asset('themes/'. $currentTheme .'/assets/images/section-left-img.png') }}" alt="">
    </div>
</section>
<section class="home-banner-section" style="position: relative;@if(isset($option) && $option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif" data-index="{{ $option->order ?? '' }}" data-id="{{ $option->order ?? '' }}" data-value="{{ $option->id ?? '' }}" data-hide="{{ $option->is_hide ?? '' }}" data-section="{{ $option->section_name ?? '' }}"  data-store="{{ $option->store_id ?? '' }}" data-theme="{{ $option->theme_id ?? '' }}">
<div class="custome_tool_bar"></div>            
<div class="home-main-slider">
                <div class="hero-slider-item">
                                   
                @for ($i = 0; $i < $section->slider->loop_number ?? 1; $i++)
                        <div class="hero-item-inner">
                            <img src="{{ asset($section->slider->section->background_image->image ?? '') }}" class="banner {{ ($section->slider->section->background_image->slug ?? '').'_preview'}}" alt="banner">
                            <div class="banner-content">
                                <div class="container">
                                    <div class="banner-content-inner comman-title-white">
                                        <h2 class="banner-heading" id="{{ ($section->slider->section->title->slug ?? '').'_'. $i }}_preview"> {!! $section->slider->section->title->text->{$i} ?? "" !!}</h2>
                                        <p id="{{ ($section->slider->section->description->slug ?? '').'_'. $i }}_preview">{!! ($section->slider->section->description->text->{$i} ?? '') !!}</p>
                                        <div class="banner-links">
                                            <a href="#" class="categories-link" id="{{ ($section->slider->section->button_first->slug ?? '').'_'. $i }}_preview">
                                            {!! $section->slider->section->button_first->text->{$i} ?? "" !!}
                                             {{--   <img src="{{ asset($homepage_banner_icon_img1) }}"
                                                    alt="icon" class="banner_icon"> --}}
                                            </a>
                                            <a href="#" class="sellers-link" id="{{ ($section->slider->section->button_second->slug ?? '').'_'. $i }}_preview">
                                            {!! $section->slider->section->button_second->text->{$i} ?? "" !!}
                                            {{--<img src="{{ asset($homepage_banner_icon_img2) }}"
                                                    alt="icon" class="banner_icon"> --}}
                                            </a>
                                        </div>
                                        <div class="offer-btn d-flex align-items-center">
                                            <div class="offer-btn-left d-flex align-items-center">
                                                <div class="offer-img">
                                                    <img src="{{ asset('themes/'. $currentTheme .'/assets/img/promotion.png' ?? '') }}"
                                                        alt="icon">
                                                </div>
                                                <p><b>14-days</b> return guarantee</p>
                                            </div>
                                            <div class="offer-btn-left d-flex align-items-center">
                                                <div class="offer-img">
                                                    <img src="{{ asset('themes/'. $currentTheme .'/assets/img/viewer-outputtt.svg' ?? '') }}"
                                                        alt="icon">
                                                </div>
                                                <p><b>Get 35% off</b> for first order</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </section>








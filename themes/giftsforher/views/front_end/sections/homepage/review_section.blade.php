<section class="review-section" style="@if (isset($option) && $option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif"
    data-index="{{ $option->order ?? '' }}" data-id="{{ $option->order ?? '' }}" data-value="{{ $option->id ?? '' }}"
    data-hide="{{ $option->is_hide ?? '' }}" data-section="{{ $option->section_name ?? '' }}"
    data-store="{{ $option->store_id ?? '' }}" data-theme="{{ $option->theme_id ?? '' }}">
    <div class="custome_tool_bar"></div>
    <div class="container">
        <div class="section-title d-flex align-items-center justify-content-between">
            <h2 id="{{ $section->review->section->title->slug ?? '' }}_preview">
                {!! $section->review->section->title->text ?? '' !!}</h2>
            <a href="{{ route('page.product-list', $slug) }}" class="btn-secondary m-0 w-100"
                tabindex="0"id="{{ $section->review->section->button->slug ?? '' }}_preview">
                {!! $section->review->section->button->text ?? '' !!}
                <svg xmlns="http://www.w3.org/2000/svg" width="8" height="10" viewBox="0 0 4 6" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M0.65976 0.662719C0.446746 0.879677 0.446746 1.23143 0.65976 1.44839L2.18316 3L0.65976 4.55161C0.446747 4.76856 0.446747 5.12032 0.65976 5.33728C0.872773 5.55424 1.21814 5.55424 1.43115 5.33728L3.34024 3.39284C3.55325 3.17588 3.55325 2.82412 3.34024 2.60716L1.43115 0.662719C1.21814 0.445761 0.872773 0.445761 0.65976 0.662719Z"
                        fill=""></path>
                </svg>
            </a>
        </div>
        <div class="review-slider">
            @foreach ($reviews as $review)
                <div class="review-section-slider">
                    <div class="review-itm-inner">
                        <div class="review-itm-image">
                            <a href="#" tabindex="0">
                                <img src="{{ asset(!empty($review->ProductData) ? asset($review->ProductData->cover_image_path) : '') }}"
                                    class="default-img" alt="best review">
                            </a>
                        </div>
                        <div class="review-itm-content">
                            <div class="review-itm-content-top">
                                <h3 class="review-title">
                                    {{ $review->title }}
                                </h3>
                                <p class="description">{{ $review->description }}</p>
                            </div>
                            <div class="review-card-bottom">
                                <div class="review-star">
                                    <div class="d-flex align-items-center">
                                        @for ($i = 0; $i < 5; $i++)
                                            <i
                                                class="fa fa-star {{ $i < $review->rating_no ? 'text-warning' : '' }} "></i>
                                        @endfor
                                        <span class="star-count">{{ $review->rating_no }}.5 / <b> 5.0</b></span>
                                    </div>
                                </div>
                                <p>
                                    <b>{{ !empty($review->UserData) ? $review->UserData->first_name : '' }},</b>
                                    Client about Metalic Wall Lamp
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

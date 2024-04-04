<section class="testimonial-section padding-top" style="position: relative;@if(isset($option) && $option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif" data-index="{{ $option->order ?? '' }}" data-id="{{ $option->order ?? '' }}" data-value="{{ $option->id ?? '' }}" data-hide="{{ $option->is_hide ?? '' }}" data-section="{{ $option->section_name ?? '' }}"  data-store="{{ $option->store_id ?? '' }}" data-theme="{{ $option->theme_id ?? '' }}">
    <div class="custome_tool_bar"></div>
        <div class="container">
            
                <div class="common-title d-flex align-items-center justify-content-between">
                    <div class="section-title">
                    <h2 id="{{ $section->review->section->title->slug ?? '' }}_preview">{!!
                            $section->review->section->title->text ?? '' !!}</h2>
                    </div>
                </div>

            <div class="testimonial-slider flex-slider">
                @foreach ($reviews as $review)
                    <div class="testimonial-itm">
                        <div class="testimonial-inner card-inner">
                            @if(isset($review->ProductData->cover_image_path))
                            <div class="testimonial-img">
                                <a href="#">
                                    <img
                                        src="{{ asset($review->ProductData->cover_image_path) }}">
                                </a>
                            </div>
                            @endif
                            <div class="testimonial-right">
                                <div class="star">
                                    @for ($i = 0; $i < 5; $i++)
                                        <i class="ti ti-star {{ $i < $review->rating_no ? 'text-warning' : '' }} "></i>
                                    @endfor
                                </div>
                                <div class="bottom-content">
                                    <h5>{{ $review->title }}</h5>
                                    <p>{{ $review->description }}</p>
                                    <p>{{ !empty($review->UserData) ? $review->UserData->first_name : '' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </section>



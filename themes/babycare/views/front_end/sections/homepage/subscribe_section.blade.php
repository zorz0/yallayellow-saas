<section class="custom-banner-section-two padding-bottom" style="position: relative;@if(isset($option) && $option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif" data-index="{{ $option->order ?? '' }}" data-id="{{ $option->order ?? '' }}" data-value="{{ $option->id ?? '' }}" data-hide="{{ $option->is_hide ?? '' }}" data-section="{{ $option->section_name ?? '' }}"  data-store="{{ $option->store_id ?? '' }}" data-theme="{{ $option->theme_id ?? '' }}">
    <div class="custome_tool_bar"></div>  
    <div class="container">
        <div class="custom-banner-two">
        <img id="{{ $section->subscribe->section->image->slug ?? '' }}_preview" src="{{ asset($section->subscribe->section->image->image ?? '') }}" class="subscribe-bnr">
            <div class="custom-banner-inner">
                <div class="label"></div>
                <h2 id="{{ $section->subscribe->section->title->slug ?? '' }}_preview"> {!! $section->subscribe->section->title->text ?? '' !!} </h2>
                <p id="{{ $section->subscribe->section->description->slug ?? '' }}_preview">{!! $section->subscribe->section->description->text ?? '' !!}</p>
                <form class="footer-subscribe-form" action="{{ route("newsletter.store",$slug) }}"
                    method="post">
                    @csrf
                    <div class="input-box">
                        <input type="email" placeholder="Type your address email......" name="email">
                        <button class="btn-subscibe">
                            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17"
                                viewBox="0 0 17 17" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12.6883 2.12059C14.0686 1.54545 15.4534 2.93023 14.8782 4.31056L10.9102 13.8338C10.1342 15.6962 7.40464 15.3814 7.07295 13.3912L6.5779 10.4209L3.60764 9.92589C1.61746 9.5942 1.30266 6.8646 3.16509 6.08859L12.6883 2.12059ZM13.6416 3.79527C13.7566 3.51921 13.4796 3.24225 13.2036 3.35728L3.68037 7.32528C3.05956 7.58395 3.1645 8.49381 3.82789 8.60438L6.79816 9.09942C7.36282 9.19353 7.80531 9.63602 7.89942 10.2007L8.39446 13.171C8.50503 13.8343 9.41489 13.9393 9.67356 13.3185L13.6416 3.79527Z"
                                    fill="#12131A" />
                            </svg>
                        </button>
                    </div>
                    <div class="checkbox">
                        {{-- <input type="checkbox" id="footercheck" name="footercheck"> --}}
                      
                        <label for="footercheck" id="{{ $section->subscribe->section->sub_title->slug ?? '' }}_preview">
                            {!! $section->subscribe->section->sub_title->text ?? '' !!}
                            </label>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>






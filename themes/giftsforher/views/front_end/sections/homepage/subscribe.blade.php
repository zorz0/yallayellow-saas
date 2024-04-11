<section
    class="home-custom-banner-section"tyle="@if (isset($option) && $option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif"
    data-index="{{ $option->order ?? '' }}" data-id="{{ $option->order ?? '' }}" data-value="{{ $option->id ?? '' }}"
    data-hide="{{ $option->is_hide ?? '' }}" data-section="{{ $option->section_name ?? '' }}"
    data-store="{{ $option->store_id ?? '' }}" data-theme="{{ $option->theme_id ?? '' }}">
    <div class="custome_tool_bar"></div>
    <div class="left-side-image">
        <img src="{{ asset('themes/' . $currentTheme . '/assets/images/left-satr.png') }}" alt="subscribe">
    </div>
    <div class="container">
        <div class="custom-banner">
            <div class="custom-banner-image">
                <img src="{{ asset($section->subscribe->section->image->image) }}" class="card_banner1.png">
                <div class="custom-banner-image-main">
                    <h3 id="{{ $section->subscribe->section->title->slug ?? '' }}_preview">
                        {!! $section->subscribe->section->title->text ?? '' !!}
                    </h3>
                    <p id="{{ $section->subscribe->section->sub_title->slug ?? '' }}_preview">
                        {!! $section->subscribe->section->sub_title->text ?? '' !!}
                    </p>
                    <div class="search-form-wrapper banner-search-form">
                        <form class="footer-subscribe-form" action="{{ route('newsletter.store', $slug) }}"
                            method="post">
                            @csrf
                            <div class="input-wrapper">
                                <input type="email" placeholder="Type your address email..." name="email">
                                <button type="submit"
                                    class="btn-subscibe id="{{ $section->subscribe->section->button->slug ?? '' }}_preview">
                                    {!! $section->subscribe->section->button->text ?? '' !!}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="12"
                                        viewBox="0 0 4 6" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M0.65976 0.662719C0.446746 0.879677 0.446746 1.23143 0.65976 1.44839L2.18316 3L0.65976 4.55161C0.446747 4.76856 0.446747 5.12032 0.65976 5.33728C0.872773 5.55424 1.21814 5.55424 1.43115 5.33728L3.34024 3.39284C3.55325 3.17588 3.55325 2.82412 3.34024 2.60716L1.43115 0.662719C1.21814 0.445761 0.872773 0.445761 0.65976 0.662719Z"
                                            fill="white"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="checkbox">
                                <label for="subscibecheck1"
                                    id="{{ $section->subscribe->section->description->slug ?? '' }}_preview">
                                    {!! $section->subscribe->section->description->text ?? '' !!}
                                </label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</section>

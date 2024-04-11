<!--footer start here-->
<footer class="site-footer"  style="position: relative;@if(isset($option) && $option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif" data-index="{{ $option->order ?? '' }}" data-id="{{ $option->order ?? '' }}" data-value="{{ $option->id ?? '' }}" data-hide="{{ $option->is_hide  ?? '' }}" data-section="{{ $option->section_name  ?? '' }}"  data-store="{{ $option->store_id  ?? '' }}" data-theme="{{ $option->theme_id ?? '' }}">
    <div class="custome_tool_bar"></div>
        <div class="container">
            @if($whatsapp_setting_enabled)
            <div class="floating-wpp"></div>
            @endif
            <div class="footer-row">
                @if(isset($section->footer->section->footer_menu_type))
                @for ($i = 0; $i < $section->footer->section->footer_menu_type->loop_number ?? 1; $i++)
                <div class="footer-col footer-link footer-link-{{$i+1}}">
                    <div class="footer-widget">
                        <h4> {{ $section->footer->section->footer_menu_type->footer_title->{$i} ?? ''}} </h4>
                        @php
                            $footer_menu_id = $section->footer->section->footer_menu_type->footer_menu_ids->{$i} ?? '';
                            $footer_menu = get_nav_menu($footer_menu_id);
                        @endphp
                        <ul>
                            @if (!empty($footer_menu))
                                @foreach ($footer_menu as $key => $nav)
                                    @if ($nav->type == 'custom')
                                        <li><a href="{{ url($nav->slug) }}"
                                                target="{{ $nav->target }}">
                                                @if ($nav->title == null)
                                                    {{ $nav->title }}
                                                @else
                                                    {{ $nav->title }}
                                                @endif
                                            </a></li>
                                    @elseif($nav->type == 'category')
                                        <li><a href="{{ route('themes.page', [$currentTheme, $nav->slug]) }}"
                                                target="{{ $nav->target }}">
                                                @if ($nav->title == null)
                                                    {{ $nav->title }}
                                                @else
                                                    {{ $nav->title }}
                                                @endif
                                            </a></li>
                                    @else
                                        <li><a href="{{ route('themes.page', [$currentTheme, $nav->slug]) }}"
                                                target="{{ $nav->target }}">
                                                @if ($nav->title == null)
                                                    {{ $nav->title }}
                                                @else
                                                    {{ $nav->title }}
                                                @endif
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
                @endfor
            @endif
                    <div class="footer-col footer-link-3">
                        <div class="footer-widget footer-social-links">
                            <h4>{!! __('Share') !!}</h4>
                            <ul>
                                @for ($i = 0; $i < $section->footer->section->footer_link->loop_number ?? 1; $i++)
                                    <li>
                                        <a href="{{ $section->footer->section->footer_link->social_link->{$i} ?? '#'}}" target="_blank" id="social_link_{{ $i }}">
                                            <img src="{{ asset($section->footer->section->footer_link->social_icon->{$i}->image ?? 'themes/' . $currentTheme . '/assets/images/youtube.svg') }}" class="{{ 'social_icon_'. $i .'_preview' }}" alt="icon" id="social_icon_{{ $i }}">
                                        </a>
                                    </li>
                                @endfor
                            </ul>
                        </div>
                    </div>

                    <div class="footer-col footer-subscribe-col">
                        <div class="footer-widget">
                            <h4 id="{{ $section->footer->section->newsletter_title->slug ?? '' }}_preview"> {!! $section->footer->section->newsletter_title->text ?? '' !!}</h4>
                            <p id="{{ $section->footer->section->newsletter_description->slug ?? '' }}_preview"> {!! $section->footer->section->newsletter_description->text ?? '' !!}</p>
                            <form class="footer-subscribe-form" action="{{ route("newsletter.store",$slug) }}" method="post">
                                @csrf
                                <div class="input-wrapper">
                                    <input type="email" placeholder="Type your address email..." name="email">
                                    <button type="submit" class="btn-subscibe">
                                        {{__('Subscribe')}}
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="12" viewBox="0 0 4 6"
                                            fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M0.65976 0.662719C0.446746 0.879677 0.446746 1.23143 0.65976 1.44839L2.18316 3L0.65976 4.55161C0.446747 4.76856 0.446747 5.12032 0.65976 5.33728C0.872773 5.55424 1.21814 5.55424 1.43115 5.33728L3.34024 3.39284C3.55325 3.17588 3.55325 2.82412 3.34024 2.60716L1.43115 0.662719C1.21814 0.445761 0.872773 0.445761 0.65976 0.662719Z"
                                                fill="white" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="checkbox">
                                    <input type="checkbox" id="subscibecheck" style="display: none;">
                                    <label for="subscibecheck" id="{{ $section->footer->section->description->slug ?? '' }}_preview"> {!! $section->footer->section->description->text ?? '' !!}
                                    </label>
                                </div>
                            </form>
                        </div>
                    </div>
            </div>

            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-12 col-md-6">
                        <p id="{{ $section->footer->section->copy_right->slug ?? '' }}_preview">
                        {!! $section->footer->section->copy_right->text ?? '' !!}</p>
                    </div>
                </div>
            </div>
        </div>
</footer>
<!--footer end here-->
{{-- <div class="overlay "></div> --}}

<!--cart popup start here-->
<div class="overlay cart-overlay"></div>
<div class="cartDrawer cartajaxDrawer">

</div>

<div class="overlay wish-overlay"></div>
<div class="wishDrawer wishajaxDrawer">
</div>
<!--cart popup ends here-->

@push('page-script')

@endpush

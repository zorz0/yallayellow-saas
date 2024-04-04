<!--footer start here-->
<footer class="site-footer" style="position: relative;@if(isset($option) && $option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif" data-index="{{ $option->order ?? '' }}" data-id="{{ $option->order ?? '' }}" data-value="{{ $option->id ?? '' }}" data-hide="{{ $option->is_hide ?? '' }}" data-section="{{ $option->section_name ?? '' }}"  data-store="{{ $option->store_id ?? '' }}" data-theme="{{ $option->theme_id ?? '' }}">
    <div class="custome_tool_bar"></div>
        <div class="footer-top">
            <div class="container">
                @if(isset($whatsapp_setting_enabled) && !empty($whatsapp_setting_enabled))
                    <div class="floating-wpp"></div>
                @endif
                <div class="footer-row">
                    
                        <div class="footer-col footer-link footer-link-1">
                            <div class="footer-widget">
                                <div class="logo-col">
                                    <img src="{{ asset((isset($theme_logo) && !empty($theme_logo)) ? $theme_logo : 'themes/'.$currentTheme.'/assets/images/logo.svg') }}">
                                </div>
                                <p id="{{ $section->footer->section->description->slug ?? '' }}_preview"> {!! $section->footer->section->description->text ?? '' !!}</p>
                               
                                <ul class="footer-list-social" role="list">
                                    @if(isset($section->footer->section->footer_link))
                                        @for ($i = 0; $i < $section->footer->section->footer_link->loop_number ?? 1; $i++)
                                            <li> 
                                                <a href="{{ $section->footer->section->footer_link->social_link->{$i} ?? '#'}}" target="_blank" id="social_link_{{ $i }}"> 
                                                    <img src="{{ asset($section->footer->section->footer_link->social_icon->{$i}->image ?? 'themes/' . $currentTheme . '/assets/img/youtube.png') }}" class="{{ 'social_icon_'. $i .'_preview' }}" alt="icon" id="social_icon_{{ $i }}"> 
                                                </a> 
                                            </li>                                    
                                        @endfor
                                    @endif
                                </ul>
                            </div>
                        </div>


                        @if(isset($section->footer->section->footer_menu_type))
                            @for ($i = 0; $i < $section->footer->section->footer_menu_type->loop_number ?? 1; $i++)
                            <div class="footer-col footer-link footer-link-1">
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
                       
                        <div class="footer-col footer-subscribe-col">
                            <div class="footer-widget">
                                <h4>Subscribe newsletter and get -20% off</h4>
                                <form class="footer-subscribe-form" action="{{ route("newsletter.store",$slug) }}" method="post">
                                    @csrf
                                    <div class="input-box">
                                    <input type="email" placeholder="Type your address email......" name="email">
                                    <button class="btn-subscibe">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.6883 2.12059C14.0686 1.54545 15.4534 2.93023 14.8782 4.31056L10.9102 13.8338C10.1342 15.6962 7.40464 15.3814 7.07295 13.3912L6.5779 10.4209L3.60764 9.92589C1.61746 9.5942 1.30266 6.8646 3.16509 6.08859L12.6883 2.12059ZM13.6416 3.79527C13.7566 3.51921 13.4796 3.24225 13.2036 3.35728L3.68037 7.32528C3.05956 7.58395 3.1645 8.49381 3.82789 8.60438L6.79816 9.09942C7.36282 9.19353 7.80531 9.63602 7.89942 10.2007L8.39446 13.171C8.50503 13.8343 9.41489 13.9393 9.67356 13.3185L13.6416 3.79527Z" fill="#12131A"></path>
                                        </svg>
                                    </button>
                                </div>
                           
                                </form>
                            </div>
                        </div>
                    </div>
                   
            </div>

        </div>
    </footer>
<!--footer end here-->

<div class="overlay cart-overlay"></div>
<div class="cartDrawer cartajaxDrawer">

</div>


























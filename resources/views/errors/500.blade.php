@php
    $settings = \Modules\LandingPage\Entities\LandingPageSetting::settings();
    $logo = get_file('storage/uploads/landing_page_image', 'grocery');

    $sup_logo = get_file('storage/uploads/logo', 'grocery');
    $superadmin = \App\Models\User::where('type', 'super admin')->first();
    $setting = getSuperAdminAllSetting();
    $SITE_RTL = ($setting['SITE_RTL'] ?? 'off');

    $color = !empty($setting['color']) ? $setting['color'] : 'theme-3';

@endphp
<!DOCTYPE html>
<html lang="en" dir="{{ (isset($setting['SITE_RTL']) && $setting['SITE_RTL'] == 'on') ? 'rtl' : '' }}">

<head>
    <title>
        {{ \App\Models\Utility::GetValueByName('title_text', APP_THEME()) ? \App\Models\Utility::GetValueByName('title_text', APP_THEME()) : 'Yalla Yellow' }}
    </title>
    <!-- Meta -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Dashboard Template Description" />
    <meta name="keywords" content="Dashboard Template" />
    <meta name="author" content="Rajodiya Infotech" />

    <!-- Favicon icon -->
    <link rel="icon" href="{{ get_file($setting['favicon'] . '?timestamp=' . time(), 'grocery') }}"
        type="image/x-icon" />

    <!-- font css -->
    <link rel="stylesheet" href=" {{ asset('Modules/LandingPage/Resources/assets/fonts/tabler-icons.min.css') }}" />
    <link rel="stylesheet" href=" {{ asset('Modules/LandingPage/Resources/assets/fonts/feather.css') }}" />
    <link rel="stylesheet" href="  {{ asset('Modules/LandingPage/Resources/assets/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('Modules/LandingPage/Resources/assets/fonts/material.css') }}" />

    <!-- vendor css -->
    <link rel="stylesheet" href="  {{ asset('Modules/LandingPage/Resources/assets/css/style.css') }}" />
    <link rel="stylesheet" href=" {{ asset('Modules/LandingPage/Resources/assets/css/customizer.css') }}" />
    <link rel="stylesheet" href=" {{ asset('Modules/LandingPage/Resources/assets/css/landing-page.css') }}" />
    <link rel="stylesheet" href=" {{ asset('Modules/LandingPage/Resources/assets/css/custom.css') }}" />

    {{-- @if ($SITE_RTL == 'on')
    <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}">
    @endif

    @if (isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
    @else
        <link rel="stylesheet" href="{{ Module::asset('LandingPage:Resources/assets/css/style.css')}}" id="main-style-link">
    @endif --}}

    @if ($SITE_RTL == 'on')
        <link rel="stylesheet" href="{{ asset('Modules/LandingPage/Resources/assets/css/style-rtl.css') }}">
    @elseif (isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on')
        <link rel="stylesheet" href="{{ asset('Modules/LandingPage/Resources/assets/css/style-dark.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('Modules/LandingPage/Resources/assets/css/style.css') }}"
            id="main-style-link">
    @endif


</head>
@if (isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on')

    <body class="{{ $color }} landing-dark landing-page">
    @else

        <body class="{{ $color }} landing-page">
@endif
<!-- [ Header ] start -->
<header class="main-header mb-5">
    @if (isset($settings['topbar_status']) && $settings['topbar_status'] == 'on')
        <div class="announcement bg-dark text-center p-2">

            <p class="mb-0">
                {!! (app()->getLocale() == 'en' ? ($settings['topbar_notification_msg_en'] ?? null) : ($settings['topbar_notification_msg'] ?? null)) !!}
            </p>
            
        </div>
    @endif
    @if (isset($settings['menubar_status']) && $settings['menubar_status'] == 'on')
        <div class="container">
            <nav class="navbar navbar-expand-md  default top-nav-collapse">
                <div class="header-left custom-header-logo">
                    <a class="navbar-brand bg-transparent logo" href="#">
                        <img src="{{ $logo . '/' . $settings['site_logo'] . '?timestamp=' . time() }}" class="logo"
                            alt="logo">
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" href="#home">
                                
                                {!! (app()->getLocale() == 'en' ? ($settings['home_title_en'] ?? null) : ($settings['home_title'] ?? null)) !!}

                                 </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#features">
                                {!!  (app()->getLocale() == 'en' ? ($settings['feature_title_en'] ?? null) : ($settings['feature_title'] ?? null)) !!}

                               </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#plan">
                                
                                {!! (app()->getLocale() == 'en' ? ($settings['plan_title_en'] ?? null) : ($settings['plan_title'] ?? null)) !!}

                                </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#faq">
                                

                                {!! (app()->getLocale() == 'en' ? ($settings['faq_title_en'] ?? null) : ($settings['faq_title'] ?? null)) !!}

                            </a>
                        </li>
                        @if (is_array(json_decode($settings['menubar_page'])) || is_object(json_decode($settings['menubar_page'])))
                            @foreach (json_decode($settings['menubar_page']) as $key => $value)
                                @if ($value->header == 'on')
                                    <li class="nav-item">
                                        @if (!empty($value->page_url))
                                            <a class="nav-link"
                                                href="{{ $value->page_url }}">{{ $value->menubar_page_name }}</a>
                                        @else
                                            <a class="nav-link"
                                                href="{{ route('custom.pages', $value->page_slug) }}">{{ $value->menubar_page_name }}</a>
                                        @endif
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    </ul>
                    <button class="navbar-toggler bg-primary" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
                <div class="ms-auto d-flex justify-content-end gap-2">
                    <a href="{{ route('login') }}" class="btn btn-outline-dark rounded"><span
                            class="hide-mob me-2">{{__('Login')}}</span> <i data-feather="log-in"></i></a>
                            @if ($setting['SIGNUP'] == 'on')
                            <a href="{{ route('register') }}" class="btn btn-outline-dark rounded"
                               onclick="event.preventDefault(); document.getElementById('plan_div').scrollIntoView({ behavior: 'smooth' });">
                                <span class="hide-mob me-2">{{ __('Register')}}</span> <i data-feather="user-check"></i>
                            </a>
                        @endif
                    <button class="navbar-toggler " type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    @php
                    $lang = app()->getLocale() ?? $setting['defult_language']  ;
                    if (empty($lang)) {
                        $lang = 'en';
                    }
                    
                    if ($lang == 'ar' || $lang == 'he') {
                        $SITE_RTL = 'on';
                    }
                
                    $displaylang = App\Models\Utility::languages();
                
                                @endphp
                                <div class="dropdown dash-h-item drp-language ecom-lang-drp  btn btn-outline-white rounded ml-1">
                                    <a class="dash-head-link dropdown-toggle arrow-none me-0 bg-primary"
                                        data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                                        aria-expanded="false">
                                        <i class="ti ti-world nocolor d-none d-md-inline"></i>
                                        <span class="drp-text">{{ strtoupper($lang) }}</span>
                                        <i class="ti ti-chevron-down drp-arrow nocolor d-none d-md-inline"></i>
                                    </a>
                
                                    <div class="dropdown-menu dash-h-dropdown dropdown-menu-end"
                                        onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                                       
                                        @foreach($displaylang as $key => $language)
                                            <a href="{{ route('changelanguage', $key) }}"
                                                class="dropdown-item {{ $lang == $key ? 'text-primary' : '' }}">
                                                <span>{{Str::ucfirst($language)}}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                </div>
          
            </nav>
        </div>
    @endif

</header>


<section class="mt-5 mb-5 bg-primary p-5" id="home">
    <div class="container-offset d-flex justify-content-center">
        <div class="row mt-5">
            <img style="width: 500px" src="/assets/images/500.webp" alt="500 Error Image">
        </div>
        
    </div>
    <div class="container-offset mt-5">

        <div class="row">
         <div class="col-md-4 d-flex justify-content-end">
             <svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" fill="#000000" height="113.42" viewBox="0 0 324.74 113.42" width="324.74"><path d="M6.69,3.34A3.35,3.35,0,1,1,3.34,0,3.35,3.35,0,0,1,6.69,3.34ZM43.1,0a3.35,3.35,0,1,0,3.34,3.34A3.34,3.34,0,0,0,43.1,0ZM82.86,0A3.35,3.35,0,1,0,86.2,3.34,3.35,3.35,0,0,0,82.86,0Zm39.75,0A3.35,3.35,0,1,0,126,3.34,3.35,3.35,0,0,0,122.61,0Zm39.76,0a3.35,3.35,0,1,0,3.34,3.34A3.34,3.34,0,0,0,162.37,0Zm39.76,0a3.35,3.35,0,1,0,3.34,3.34A3.34,3.34,0,0,0,202.13,0Zm39.76,0a3.35,3.35,0,1,0,3.34,3.34A3.35,3.35,0,0,0,241.89,0Zm39.75,0A3.35,3.35,0,1,0,285,3.34,3.35,3.35,0,0,0,281.64,0ZM321.4,0a3.35,3.35,0,1,0,3.34,3.34A3.34,3.34,0,0,0,321.4,0ZM3.34,35.58a3.34,3.34,0,1,0,3.35,3.34A3.35,3.35,0,0,0,3.34,35.58Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.34,3.34,0,0,0,43.1,35.58Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.35,3.35,0,0,0,82.86,35.58Zm39.75,0A3.34,3.34,0,1,0,126,38.92,3.35,3.35,0,0,0,122.61,35.58Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.34,3.34,0,0,0,162.37,35.58Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.34,3.34,0,0,0,202.13,35.58Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.35,3.35,0,0,0,241.89,35.58Zm39.75,0A3.34,3.34,0,1,0,285,38.92,3.35,3.35,0,0,0,281.64,35.58Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.34,3.34,0,0,0,321.4,35.58ZM3.34,71.16A3.34,3.34,0,1,0,6.69,74.5,3.34,3.34,0,0,0,3.34,71.16Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.33,3.33,0,0,0,43.1,71.16Zm39.76,0A3.34,3.34,0,1,0,86.2,74.5,3.34,3.34,0,0,0,82.86,71.16Zm39.75,0A3.34,3.34,0,1,0,126,74.5,3.34,3.34,0,0,0,122.61,71.16Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.33,3.33,0,0,0,162.37,71.16Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.33,3.33,0,0,0,202.13,71.16Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.34,3.34,0,0,0,241.89,71.16Zm39.75,0A3.34,3.34,0,1,0,285,74.5,3.34,3.34,0,0,0,281.64,71.16Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.33,3.33,0,0,0,321.4,71.16ZM3.34,106.74a3.34,3.34,0,1,0,3.35,3.34A3.34,3.34,0,0,0,3.34,106.74Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.33,3.33,0,0,0,43.1,106.74Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.34,3.34,0,0,0,82.86,106.74Zm39.75,0a3.34,3.34,0,1,0,3.35,3.34A3.34,3.34,0,0,0,122.61,106.74Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.33,3.33,0,0,0,162.37,106.74Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.33,3.33,0,0,0,202.13,106.74Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.34,3.34,0,0,0,241.89,106.74Zm39.75,0a3.34,3.34,0,1,0,3.35,3.34A3.34,3.34,0,0,0,281.64,106.74Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.33,3.33,0,0,0,321.4,106.74Z" fill="#f3bc29"/></svg>   
         </div>
         <div class="col-md-4">
            <h2 style="color:#f3bc29;font-weight:700;text-align:center;">{{ __('Sorry_500')}}</h2>
         </div>
         <div class="col-md-4">
             <svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" fill="#000000" height="113.42" viewBox="0 0 324.74 113.42" width="324.74"><path d="M6.69,3.34A3.35,3.35,0,1,1,3.34,0,3.35,3.35,0,0,1,6.69,3.34ZM43.1,0a3.35,3.35,0,1,0,3.34,3.34A3.34,3.34,0,0,0,43.1,0ZM82.86,0A3.35,3.35,0,1,0,86.2,3.34,3.35,3.35,0,0,0,82.86,0Zm39.75,0A3.35,3.35,0,1,0,126,3.34,3.35,3.35,0,0,0,122.61,0Zm39.76,0a3.35,3.35,0,1,0,3.34,3.34A3.34,3.34,0,0,0,162.37,0Zm39.76,0a3.35,3.35,0,1,0,3.34,3.34A3.34,3.34,0,0,0,202.13,0Zm39.76,0a3.35,3.35,0,1,0,3.34,3.34A3.35,3.35,0,0,0,241.89,0Zm39.75,0A3.35,3.35,0,1,0,285,3.34,3.35,3.35,0,0,0,281.64,0ZM321.4,0a3.35,3.35,0,1,0,3.34,3.34A3.34,3.34,0,0,0,321.4,0ZM3.34,35.58a3.34,3.34,0,1,0,3.35,3.34A3.35,3.35,0,0,0,3.34,35.58Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.34,3.34,0,0,0,43.1,35.58Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.35,3.35,0,0,0,82.86,35.58Zm39.75,0A3.34,3.34,0,1,0,126,38.92,3.35,3.35,0,0,0,122.61,35.58Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.34,3.34,0,0,0,162.37,35.58Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.34,3.34,0,0,0,202.13,35.58Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.35,3.35,0,0,0,241.89,35.58Zm39.75,0A3.34,3.34,0,1,0,285,38.92,3.35,3.35,0,0,0,281.64,35.58Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.34,3.34,0,0,0,321.4,35.58ZM3.34,71.16A3.34,3.34,0,1,0,6.69,74.5,3.34,3.34,0,0,0,3.34,71.16Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.33,3.33,0,0,0,43.1,71.16Zm39.76,0A3.34,3.34,0,1,0,86.2,74.5,3.34,3.34,0,0,0,82.86,71.16Zm39.75,0A3.34,3.34,0,1,0,126,74.5,3.34,3.34,0,0,0,122.61,71.16Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.33,3.33,0,0,0,162.37,71.16Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.33,3.33,0,0,0,202.13,71.16Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.34,3.34,0,0,0,241.89,71.16Zm39.75,0A3.34,3.34,0,1,0,285,74.5,3.34,3.34,0,0,0,281.64,71.16Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.33,3.33,0,0,0,321.4,71.16ZM3.34,106.74a3.34,3.34,0,1,0,3.35,3.34A3.34,3.34,0,0,0,3.34,106.74Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.33,3.33,0,0,0,43.1,106.74Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.34,3.34,0,0,0,82.86,106.74Zm39.75,0a3.34,3.34,0,1,0,3.35,3.34A3.34,3.34,0,0,0,122.61,106.74Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.33,3.33,0,0,0,162.37,106.74Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.33,3.33,0,0,0,202.13,106.74Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.34,3.34,0,0,0,241.89,106.74Zm39.75,0a3.34,3.34,0,1,0,3.35,3.34A3.34,3.34,0,0,0,281.64,106.74Zm39.76,0a3.34,3.34,0,1,0,3.34,3.34A3.33,3.33,0,0,0,321.4,106.74Z" fill="#f3bc29"/></svg>   
         </div>
        </div>
         </div>
</section>

<!-- [ Footer ] start -->
<footer class="site-footer bg-gray-100">
    <div class="container">
        <div class="footer-row">
            <div class="ftr-col cmp-detail">
                <div class="footer-logo mb-3">
                    <a href="#" class="logo">
                        <img src="{{ $logo . '/' . $settings['site_logo'] . '?timestamp=' . time() }}"
                            alt="logo">
                    </a>
                </div>
                <p>
                    {!! (app()->getLocale() == 'en' ? ($settings['site_description_en'] ?? null) : ($settings['site_description'] ?? null)) !!}

                </p>

            </div>
            <div class="ftr-col">
                <ul class="list-unstyled">

                    @if (isset($settings['menubar_page']) && (is_array(json_decode($settings['menubar_page'])) || is_object(json_decode($settings['menubar_page']))))
                        @foreach (json_decode($settings['menubar_page']) as $key => $value)
                            @if ($value->footer == 'on' && $value->header == 'off')
                                <li>
                                    @if (!empty($value->page_url))
                                        <a href="{{ $value->page_url }}">{!! $value->menubar_page_name !!}</a>
                                    @else
                                        <a
                                            href="{{ route('custom.pages', $value->page_slug) }}">{!! $value->menubar_page_name !!}</a>
                                    @endif

                                </li>
                            @endif
                            @if ($value->footer == 'on' && $value->header == 'on')
                                <li>
                                    @if (!empty($value->page_url))
                                        <a href="{{ $value->page_url }}">{!! $value->menubar_page_name !!}</a>
                                    @else
                                        <a
                                            href="{{ route('custom.pages', $value->page_slug) }}">{!! $value->menubar_page_name !!}</a>
                                    @endif
                                </li>
                            @endif
                        @endforeach
                    @endif



                </ul>
            </div>
            <div class="ftr-col">
                <ul class="list-unstyled">
                    @if (isset($settings['menubar_page']) && (is_array(json_decode($settings['menubar_page'])) || is_object(json_decode($settings['menubar_page']))))
                        @foreach (json_decode($settings['menubar_page']) as $key => $value)
                            @if ($value->header == 'on' && $value->footer == 'off')
                                <li class="nav-item">
                                    @if (!empty($value->page_url))
                                        <a href="{{ $value->page_url }}">{!! $value->menubar_page_name !!}</a>
                                    @else
                                        <a
                                            href="{{ route('custom.pages', $value->page_slug) }}">{!! $value->menubar_page_name !!}</a>
                                    @endif
                                </li>
                            @endif
                        @endforeach
                    @endif


                </ul>
            </div>

            @if (isset($settings['joinus_status']) && $settings['joinus_status'] == 'on')
                <div class="ftr-col ftr-subscribe">
                    <h2>{!! $settings['joinus_heading'] !!}</h2>
                    <p>{!! $settings['joinus_description'] !!}</p>
                    <form method="post" action="{{ route('join_us_store') }}">
                        @csrf
                        <div class="input-wrapper border border-dark">
                            <input type="text" name="email" placeholder="Type your email address...">
                            <button type="submit" class="btn btn-dark rounded-pill">{{ __('Join Us!')}}</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
    <div class="border-top border-dark text-center p-2">
        {{-- <p class="mb-0">
                Copyright © 2022 | Design By ERPGo
            </p> --}}



            <p class="mb-0"> &copy;  {{ date('Y') }}
                {{ $setting['footer_text_' . app()->getLocale()] ?? config('app.name', 'E-CommerceGo') }}
            </p>


    </div>
</footer>
@if (isset($setting['enable_cookie']) && ($setting['enable_cookie']) == 'on')
    @include('layouts.cookie_consent')
@endif
<!-- [ Footer ] end -->
<!-- Required Js -->


<script src="{{ asset('Modules/LandingPage/Resources/assets/js/plugins/popper.min.js') }}"></script>
<script src="{{ asset('Modules/LandingPage/Resources/assets/js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ asset('Modules/LandingPage/Resources/assets/js/plugins/feather.min.js') }}"></script>

<script>
    // Start [ Menu hide/show on scroll ]
    let ost = 0;
    document.addEventListener("scroll", function() {
        let cOst = document.documentElement.scrollTop;
        if (cOst == 0) {
            document.querySelector(".navbar").classList.add("top-nav-collapse");
        } else if (cOst > ost) {
            document.querySelector(".navbar").classList.add("top-nav-collapse");
            document.querySelector(".navbar").classList.remove("default");
        } else {
            document.querySelector(".navbar").classList.add("default");
            document
                .querySelector(".navbar")
                .classList.remove("top-nav-collapse");
        }
        ost = cOst;
    });
    // End [ Menu hide/show on scroll ]

    var scrollSpy = new bootstrap.ScrollSpy(document.body, {
        target: "#navbar-example",
    });
    feather.replace();
</script>

</body>

</html>

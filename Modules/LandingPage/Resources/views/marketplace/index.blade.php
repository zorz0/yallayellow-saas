@extends('landingpage::layouts.marketplace')
@php
    $old_path = ("/Modules/".$module->getName()."/marketplace/");
    $marketplace = \Modules\LandingPage\Entities\MarketplacePageSetting::settings($module->getName());
    $admin_settings = getAdminAllSetting();
    $plans = \App\Models\Plan::get();
@endphp
@section('page-title')
    {{ __('Software Details') }}
@endsection

@section('content')
<!-- wrapper start -->
<div class="wrapper">

    <!-- product main start -->
    @if ($marketplace['product_main_status'] == 'on')
        <section class="product-main-section padding-bottom padding-top">
            <div class="offset-container offset-left">
                <div class="row row-gap align-items-center pdp-summery-row">
                    <div class="col-lg-6 col-md-6 col-12 pdp-left-side">
                        <div class="pdp-summery">
                            <div class="section-title">
                                <h2>{{Module_Alias_Name($module->getName())}}</h2>
                            </div>
                            <p>{!! $marketplace['product_main_description'] !!}</p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12 pdp-left-side">
                        <div class="pdp-image-wrapper">
                            <div class="pdp-media banner-img-wrapper">
                                <img src="{{ asset('public/market_assets/images/banner-image.png')}}" alt="">
                                <img src="{{ check_file($marketplace['product_main_banner']) ? get_file($marketplace['product_main_banner']) : get_file($old_path."/image1.png") }}" alt="" class="inner-frame-img">
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!-- product main end -->

    <!-- dedicated-themes start -->
    @if ($marketplace['dedicated_theme_section_status'] == 'on')
        <section class="dedicated-themes-section ">
            <div class="container">
                <div class="section-title text-center">
                    <h2>{!! $marketplace['dedicated_theme_heading'] !!}</h2>
                    <p>{!! $marketplace['dedicated_theme_description'] !!}</p>
                </div>
                @if (is_array(json_decode($marketplace['dedicated_theme_sections'], true)) || is_object(json_decode($marketplace['dedicated_theme_sections'], true)))
                    @php
                        $img = 2 ;
                    @endphp
                    @foreach (json_decode($marketplace['dedicated_theme_sections'], true) as $key => $value)
                        @if ($key % 2 == 0)
                            <div class="row row-gap padding-bottom ">
                                <div class="col-lg-5 col-md-6 col-12">
                                    <div class="abt-theme">
                                        <div class="section-title">
                                            <div class="subtitle">
                                            </div>
                                            <h2>{!! $value['dedicated_theme_section_heading'] !!}</h2>
                                            <p> {!! $value['dedicated_theme_section_description'] !!} </p>
                                        </div>
                                        @if (!empty($value['dedicated_theme_section_cards'][1]['title']) && $value['dedicated_theme_section_cards'][1]['title'] != null && $value['dedicated_theme_section_cards'][1]['title'] != '')
                                            <div class="theme-acnav">
                                                    @foreach ($value['dedicated_theme_section_cards'] as $key => $card)
                                                        <div class="set has-children">
                                                            <a href="javascript:;" class="acnav-label">
                                                                <span class="acnav-icon">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="33"
                                                                        viewBox="0 0 30 33" fill="none">
                                                                        <path
                                                                            d="M15 2C9.48 2 5 6.48 5 12C5 17.52 9.48 22 15 22C20.52 22 25 17.52 25 12C25 6.48 20.52 2 15 2ZM19.03 10.2L14.36 14.86C14.22 15.01 14.03 15.08 13.83 15.08C13.64 15.08 13.45 15.01 13.3 14.86L10.97 12.53C10.68 12.24 10.68 11.76 10.97 11.47C11.26 11.18 11.74 11.18 12.03 11.47L13.83 13.27L17.97 9.14001C18.26 8.84001 18.74 8.84001 19.03 9.14001C19.32 9.43001 19.32 9.90001 19.03 10.2Z"
                                                                            fill="#002332" />
                                                                        <g filter="url(#filter0_d_14_1908)">
                                                                            <circle cx="15" cy="12.5596" r="11" fill="#6FD943" />
                                                                        </g>
                                                                        <path opacity="0.4" fill-rule="evenodd" clip-rule="evenodd"
                                                                            d="M19.668 8.91699C19.668 8.91699 19.668 10.7131 19.668 10.7819C19.668 10.8508 19.6143 10.9587 19.4929 10.9587C19.3372 10.9587 16.7104 10.9587 16.7104 10.9587C15.4738 10.9587 14.7096 11.7286 14.7096 12.9653V13.0003H9.16797V8.91699C9.16797 7.75033 9.7513 7.16699 10.918 7.16699H17.918C19.0846 7.16699 19.668 7.75033 19.668 8.91699Z"
                                                                            fill="white" />
                                                                        <path
                                                                            d="M14.7096 17.7017C14.7096 17.8417 14.7213 17.9758 14.7388 18.1042H11.793C11.5538 18.1042 11.3555 17.9058 11.3555 17.6667C11.3555 17.4275 11.5538 17.2292 11.793 17.2292H13.2513V15.3333H10.918C9.7513 15.3333 9.16797 14.75 9.16797 13.5833V13H14.7096V17.7017Z"
                                                                            fill="white" />
                                                                        <path opacity="0.4"
                                                                            d="M16.7067 18.8336H19.7068C20.457 18.8336 20.8315 18.4562 20.8315 17.7008V12.9658C20.8315 12.2109 20.4564 11.833 19.7068 11.833H16.7067C15.9565 11.833 15.582 12.2104 15.582 12.9658V17.7008C15.582 18.4562 15.9571 18.8336 16.7067 18.8336Z"
                                                                            fill="white" />
                                                                        <path
                                                                            d="M18.2083 17.9587C18.5305 17.9587 18.7917 17.6975 18.7917 17.3753C18.7917 17.0532 18.5305 16.792 18.2083 16.792C17.8862 16.792 17.625 17.0532 17.625 17.3753C17.625 17.6975 17.8862 17.9587 18.2083 17.9587Z"
                                                                            fill="white" />
                                                                        <defs>
                                                                            <filter id="filter0_d_14_1908" x="0" y="1.55957" width="30"
                                                                                height="31" filterUnits="userSpaceOnUse"
                                                                                color-interpolation-filters="sRGB">
                                                                                <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                                                                <feColorMatrix in="SourceAlpha" type="matrix"
                                                                                    values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
                                                                                    result="hardAlpha" />
                                                                                <feOffset dy="5" />
                                                                                <feGaussianBlur stdDeviation="2" />
                                                                                <feComposite in2="hardAlpha" operator="out" />
                                                                                <feColorMatrix type="matrix"
                                                                                    values="0 0 0 0 0.435294 0 0 0 0 0.85098 0 0 0 0 0.262745 0 0 0 0.31 0" />
                                                                                <feBlend mode="normal" in2="BackgroundImageFix"
                                                                                    result="effect1_dropShadow_14_1908" />
                                                                                <feBlend mode="normal" in="SourceGraphic"
                                                                                    in2="effect1_dropShadow_14_1908" result="shape" />
                                                                            </filter>
                                                                        </defs>
                                                                    </svg>
                                                                </span>
                                                                <span>{!! $card['title'] !!}</span>
                                                            </a>
                                                            <div class="acnav-list">
                                                                <p>{!! $card['description'] !!}</p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                            </div>
                                        @endif
                                        </div>
                                </div>
                                <div class="col-lg-7 col-md-6 col-12">
                                    <div class="dash-theme-preview">
                                        <img src="{{ check_file($value['dedicated_theme_section_image']) ? get_file($value['dedicated_theme_section_image']) : (check_file($old_path."/image".$img.".png") ? get_file($old_path."/image".$img.".png") : get_file($old_path."/image1.png")) }}" alt="" class="inner-frame-img">
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="row row-gap  padding-bottom">
                                <div class="col-lg-7  col-md-6 col-12">
                                    <div class="dash-theme-preview">
                                        <img src="{{ check_file($value['dedicated_theme_section_image']) ? get_file($value['dedicated_theme_section_image']) : (check_file($old_path."/image".$img.".png") ? get_file($old_path."/image".$img.".png") : get_file($old_path."/image1.png")) }}" alt="" class="inner-frame-img">
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-6 col-12">
                                    <div class="abt-theme">
                                        <div class="section-title">
                                            <div class="subtitle">

                                            </div>
                                            <h2>{!! $value['dedicated_theme_section_heading'] !!}</h2>
                                            <p> {!! $value['dedicated_theme_section_description'] !!} </p>
                                        </div>
                                        @if (!empty($value['dedicated_theme_section_cards'][1]['title']) && $value['dedicated_theme_section_cards'][1]['title'] != null && $value['dedicated_theme_section_cards'][1]['title'] != '')
                                            <div class="theme-acnav">
                                                @foreach ($value['dedicated_theme_section_cards'] as $key => $card)
                                                    <div class="set has-children">
                                                        <a href="javascript:;" class="acnav-label">
                                                            <span class="acnav-icon">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="33"
                                                                    viewBox="0 0 30 33" fill="none">
                                                                    <path
                                                                        d="M15 2C9.48 2 5 6.48 5 12C5 17.52 9.48 22 15 22C20.52 22 25 17.52 25 12C25 6.48 20.52 2 15 2ZM19.03 10.2L14.36 14.86C14.22 15.01 14.03 15.08 13.83 15.08C13.64 15.08 13.45 15.01 13.3 14.86L10.97 12.53C10.68 12.24 10.68 11.76 10.97 11.47C11.26 11.18 11.74 11.18 12.03 11.47L13.83 13.27L17.97 9.14001C18.26 8.84001 18.74 8.84001 19.03 9.14001C19.32 9.43001 19.32 9.90001 19.03 10.2Z"
                                                                        fill="#002332"></path>
                                                                    <g filter="url(#filter0_d_14_1908)">
                                                                        <circle cx="15" cy="12.5596" r="11" fill="#6FD943"></circle>
                                                                    </g>
                                                                    <path opacity="0.4" fill-rule="evenodd" clip-rule="evenodd"
                                                                        d="M19.668 8.91699C19.668 8.91699 19.668 10.7131 19.668 10.7819C19.668 10.8508 19.6143 10.9587 19.4929 10.9587C19.3372 10.9587 16.7104 10.9587 16.7104 10.9587C15.4738 10.9587 14.7096 11.7286 14.7096 12.9653V13.0003H9.16797V8.91699C9.16797 7.75033 9.7513 7.16699 10.918 7.16699H17.918C19.0846 7.16699 19.668 7.75033 19.668 8.91699Z"
                                                                        fill="white"></path>
                                                                    <path
                                                                        d="M14.7096 17.7017C14.7096 17.8417 14.7213 17.9758 14.7388 18.1042H11.793C11.5538 18.1042 11.3555 17.9058 11.3555 17.6667C11.3555 17.4275 11.5538 17.2292 11.793 17.2292H13.2513V15.3333H10.918C9.7513 15.3333 9.16797 14.75 9.16797 13.5833V13H14.7096V17.7017Z"
                                                                        fill="white"></path>
                                                                    <path opacity="0.4"
                                                                        d="M16.7067 18.8336H19.7068C20.457 18.8336 20.8315 18.4562 20.8315 17.7008V12.9658C20.8315 12.2109 20.4564 11.833 19.7068 11.833H16.7067C15.9565 11.833 15.582 12.2104 15.582 12.9658V17.7008C15.582 18.4562 15.9571 18.8336 16.7067 18.8336Z"
                                                                        fill="white"></path>
                                                                    <path
                                                                        d="M18.2083 17.9587C18.5305 17.9587 18.7917 17.6975 18.7917 17.3753C18.7917 17.0532 18.5305 16.792 18.2083 16.792C17.8862 16.792 17.625 17.0532 17.625 17.3753C17.625 17.6975 17.8862 17.9587 18.2083 17.9587Z"
                                                                        fill="white"></path>
                                                                    <defs>
                                                                        <filter id="filter0_d_14_1908" x="0" y="1.55957" width="30"
                                                                            height="31" filterUnits="userSpaceOnUse"
                                                                            color-interpolation-filters="sRGB">
                                                                            <feFlood flood-opacity="0" result="BackgroundImageFix">
                                                                            </feFlood>
                                                                            <feColorMatrix in="SourceAlpha" type="matrix"
                                                                                values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
                                                                                result="hardAlpha"></feColorMatrix>
                                                                            <feOffset dy="5"></feOffset>
                                                                            <feGaussianBlur stdDeviation="2"></feGaussianBlur>
                                                                            <feComposite in2="hardAlpha" operator="out"></feComposite>
                                                                            <feColorMatrix type="matrix"
                                                                                values="0 0 0 0 0.435294 0 0 0 0 0.85098 0 0 0 0 0.262745 0 0 0 0.31 0">
                                                                            </feColorMatrix>
                                                                            <feBlend mode="normal" in2="BackgroundImageFix"
                                                                                result="effect1_dropShadow_14_1908"></feBlend>
                                                                            <feBlend mode="normal" in="SourceGraphic"
                                                                                in2="effect1_dropShadow_14_1908" result="shape"></feBlend>
                                                                        </filter>
                                                                    </defs>
                                                                </svg>
                                                            </span>
                                                            <span>{!! $card['title'] !!}</span>
                                                        </a>
                                                        <div class="acnav-list">
                                                            <p>{!! $card['description'] !!}</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                        @php
                            $img = $img + 1;
                        @endphp
                    @endforeach
                @endif



            </div>
        </section>
    @endif
    <!-- dedicated-themes end -->

    <!-- why-choose start -->
    @if ($marketplace['whychoose_sections_status'] == 'on')
        <section class="why-choose-section padding-top">
            <div class="container">
                <div class="section-title text-center">
                    <h2>{!! $marketplace['whychoose_heading'] !!}</h2>
                    <p>{!! $marketplace['whychoose_description'] !!}</p>
                </div>
                <div class="pricing-area">
                    <div class="row row-gap">
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="section-title">
                                <h2>{!! $marketplace['pricing_plan_heading'] !!}</h2>
                                <p>{!! $marketplace['pricing_plan_description'] !!}</p>

                            </div>
                            <ul class="variable-list">
                                @if (!empty($marketplace['pricing_plan_text']))
                                    @foreach(json_decode($marketplace['pricing_plan_text'], true) as $key => $value)
                                        <li>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                fill="none">
                                                <path
                                                    d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM16.03 10.2L11.36 14.86C11.22 15.01 11.03 15.08 10.83 15.08C10.64 15.08 10.45 15.01 10.3 14.86L7.97 12.53C7.68 12.24 7.68 11.76 7.97 11.47C8.26 11.18 8.74 11.18 9.03 11.47L10.83 13.27L14.97 9.14001C15.26 8.84001 15.74 8.84001 16.03 9.14001C16.32 9.43001 16.32 9.90001 16.03 10.2Z"
                                                    fill="white" />
                                            </svg>
                                            {!! $value['title'] !!}
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="bg-white tabs-wrapper pricing-tab">
                                <ul class="tabs">
                                    @foreach ($plans as $key=>$plan)
                                        <li class="tab-link {{ $key == 0 ? 'active' : '' }}" data-tab="{{ $key }}">
                                            <a href="javascript:;">{{ $plan->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="tabs-container">
                                    @foreach ($plans as $key =>$plan)
                                        <div class="tab-content {{ $key == 0 ? 'active' : '' }}" id="{{ $key }}">
                                            <div class="pricing-content">
                                                <div class="price">
                                                    <ins><span class="currency-type">{{ currency_format_with_sym($plan->price) }}</span> <span class="time-lbl text-muted">{{ $plan->duration }}</span></ins>
                                                </div>
                                                <p class="text-center">{{ $plan->description }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!-- why-choose end -->

    <!-- screenshot-section start -->
    <section class="screenshot-section padding-top">
        <div class="container">
            <div class="screenshot-slider">
                @if (is_array(json_decode($marketplace['screenshots'], true)) || is_object(json_decode($marketplace['screenshots'], true)))
                    @foreach (json_decode($marketplace['screenshots'], true) as $key => $value)
                        @php
                            $imageKey = $key + 1 ;
                        @endphp
                            <div class="screenshot-itm">
                                <div class="screenshot-image">
                                    <a href="{{ check_file($value['screenshots']) ? get_file($value['screenshots']) :(check_file($old_path."/image".$imageKey.".png") ? get_file($old_path."/image".$imageKey.".png") : get_file($old_path."/image3.png")) }}" target="_blank" class="img-zoom">
                                        <img src="{{ check_file($value['screenshots']) ? get_file($value['screenshots']) :(check_file($old_path."/image".$imageKey.".png") ? get_file($old_path."/image".$imageKey.".png") : get_file($old_path."/image3.png")) }}" alt="" class="inner-frame-img">
                                    </a>
                                </div>
                            </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
    <!-- screenshot-section end -->

    <!-- addon section start -->
    @if ($marketplace['addon_section_status'] == 'on')
        <section class="bg-white padding-top padding-bottom ">
            <div class="container">
                <div class="section-title text-center">
                    <h2>{!! $marketplace['addon_heading'] !!}</h2>
                    <p>{!! $marketplace['addon_description'] !!}</p>
                </div>
                @if (count($modules) > 0)
                    <div class="row product-row">
                        @foreach ($modules as $module)
                            @php
                                $path = $module->getPath() . '/module.json';
                                $json = json_decode(file_get_contents($path), true);
                            @endphp
                            @if (!isset($json['display']) || $json['display'] == true)
                            <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-12 product-card">
                                <div class="product-card-inner">
                                    <div class="product-img">
                                        <a href="#">
                                            <img src="assets/images/Custom-Fields.png" alt="">
                                        </a>
                                    </div>
                                    <div class="product-content">
                                        <h4> <a href="#">{{ Module_Alias_Name($module->getName()) }}</a> </h4>
                                        {{-- <div class="price">
                                            <ins><span class="currency-type">{{ currency_format_with_sym(ModulePriceByName($module->getName())['monthly_price']) }}</span> <span class="time-lbl text-muted">{{ __('/Month') }}</span></ins>
                                                        <ins><span class="currency-type">{{ currency_format_with_sym(ModulePriceByName($module->getName())['yearly_price']) }}</span> <span class="time-lbl text-muted">{{ __('/Year') }}</span></ins>
                                        </div> --}}
                                        <a href="{{ route('software.details',Module_Alias_Name($module->getName())) }}" target="_new"  class="btn cart-btn">View Details</a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    @endif
    <!-- addon section end -->


</div>
<!-- wrapper end -->
@endsection


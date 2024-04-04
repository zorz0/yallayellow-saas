@php
    $theme_favicon = \App\Models\Utility::GetValueByName('theme_favicon', $currentTheme);
    $theme_favicons = get_file($theme_favicon, $currentTheme);
    $theme_logo = \App\Models\Utility::GetValueByName('theme_logo', $currentTheme);
    $theme_logo = get_file($theme_logo, $currentTheme);
    $currantLang = Cookie::get('LANGUAGE');
    if (!isset($currantLang)) {
        $currantLang = $store->default_language;
    }

@endphp
<!DOCTYPE html>
<html lang="en" dir="{{ isset($SITE_RTL) && $SITE_RTL == 'on' ? 'rtl' : '' }}">

<head>
    <title>{{ __('User Order') }} -
        {{ \App\Models\Utility::GetValueByName('theme_name', $currentTheme) ? \App\Models\Utility::GetValueByName('theme_name', $currentTheme) : 'Foodmart' }}
    </title> <!-- Meta -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="author"
        content="Style - The Impressive Fashion Shopify Theme complies with contemporary standards. Meet The Most Impressive Fashion Style Theme Ever. Well Toasted, and Well Tested.">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <meta name="base-url" content="{{ URL::to('/') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon icon -->
    <link rel="shortcut icon"
        href="{{ isset($theme_favicon) && !empty($theme_favicon) ? $theme_favicons : 'themes/' . $currentTheme . '/assets/images/Favicon.png' }}">

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Dela+Gothic+One&family=Outfit:wght@100;200;300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- notification css -->
    <link rel="stylesheet" href="{{ asset('public/assets/css/plugins/notifier.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap-switch-button.min.css') }}">
    <script src="{{ asset('public/assets/js/plugins/bootstrap.min.js') }}"></script>

    @if ($currantLang == 'ar' || $currantLang == 'he')
        <link rel="stylesheet" href="{{ asset('themes/' . $currentTheme . '/assets/css/rtl-main-style.css') }}">
        <link rel="stylesheet" href="{{ asset('themes/' . $currentTheme . '/assets/css/rtl-responsive.css') }}">
        <link rel="stylesheet" href="{{ asset('css/rtl-custom.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('themes/' . $currentTheme . '/assets/css/main-style.css') }}">
        <link rel="stylesheet" href="{{ asset('themes/' . $currentTheme . '/assets/css/responsive.css') }}">
        <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    @endif
    <!-- datatable css -->
    <link rel="stylesheet" href="{{ asset('public/assets/css/plugins/flatpickr.min.css') }}">
    <!-- font css -->
    <link rel="stylesheet" href="{{ asset('public/assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/notifier.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/floating-wpp.min.css') }}">
    <!-- vendor css -->
    <link rel="stylesheet" href="{{ asset('public/css/custom.css') }}{{ '?v=' . time() }}">
</head>

<body>
    <header class="site-header header-style-one">
        <div class="container top-header-wrapper">
            <div class="top-header">
                <div class="logo-col">
                    <h1>
                        <a href="#">
                            <img src="{{ isset($theme_logo) && !empty($theme_logo) ? $theme_logo : asset('themes/' . $currentTheme . '/assets/images/logo.png') }}"
                                alt="">
                        </a>
                    </h1>
                </div>
                <span class="store-info-block">{{ $store->name }}</span>
                <div class="order-detail">
                    <div class="right-side-header">
                        <div class="header-info-end">
                            <ul class="menu-right d-flex align-items-center  justify-content-end">
                                <li class="menu-lnk has-item lang-dropdown">
                                    <a href="#">
                                        <span class="link-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"
                                                width="24px">
                                                <path
                                                    d="M160 243.1L147.2 272h25.69L160 243.1zM576 63.1L336 64v384l240 0c35.35 0 64-28.65 64-64v-256C640 92.65 611.3 63.1 576 63.1zM552 232h-1.463c-8.082 27.78-21.06 49.29-35.06 66.34c7.854 4.943 13.33 7.324 13.46 7.375c12.22 5 18.19 18.94 13.28 31.19C538.4 346.3 529.5 352 519.1 352c-2.906 0-5.875-.5313-8.75-1.672c-1-.3906-14.33-5.951-31.26-18.19c-16.69 12.04-29.9 17.68-31.18 18.19C445.9 351.5 442.9 352 440 352c-9.562 0-18.59-5.766-22.34-15.2c-4.844-12.3 1.188-26.19 13.44-31.08c.748-.3047 6.037-2.723 13.25-7.189c-3.375-4.123-6.742-8.324-9.938-13.03c-7.469-10.97-4.594-25.89 6.344-33.34c11.03-7.453 25.91-4.594 33.34 6.375c1.883 2.77 3.881 5.186 5.854 7.682C487.3 256.8 494.1 245.5 499.5 232H408C394.8 232 384 221.3 384 208S394.8 184 408 184h48c0-13.25 10.75-24 24-24S504 170.8 504 184h48c13.25 0 24 10.75 24 24S565.3 232 552 232zM0 127.1v256c0 35.35 28.65 64 64 64L304 448V64L64 63.1C28.65 63.1 0 92.65 0 127.1zM74.06 318.3l64-144c7.688-17.34 36.19-17.34 43.88 0l64 144c5.375 12.11-.0625 26.3-12.19 31.69C230.6 351.3 227.3 352 224 352c-9.188 0-17.97-5.312-21.94-14.25L193.1 319.6C193.3 319.7 192.7 320 192 320H128c-.707 0-1.305-.3418-1.996-.4023l-8.066 18.15c-5.406 12.14-19.69 17.55-31.69 12.19C74.13 344.5 68.69 330.4 74.06 318.3z"
                                                    fill="#FEBD2F" />
                                            </svg>
                                        </span>
                                        <span class="drp-text">{{ Str::upper($currantLang) }}</span>
                                        <div class="lang-icn">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                xmlns:svg="http://www.w3.org/2000/svg" version="1.1" id="svg2223"
                                                xml:space="preserve" width="682.66669" height="682.66669"
                                                viewBox="0 0 682.66669 682.66669">
                                                <g id="g2229"
                                                    transform="matrix(1.3333333,0,0,-1.3333333,0,682.66667)">
                                                    <g id="g2231">
                                                        <g id="g2233" clip-path="url(#clipPath2237)">
                                                            <g id="g2239" transform="translate(497,256)">
                                                                <path
                                                                    d="m 0,0 c 0,-132.548 -108.452,-241 -241,-241 -132.548,0 -241,108.452 -241,241 0,132.548 108.452,241 241,241 C -108.452,241 0,132.548 0,0 Z"
                                                                    style="fill:none;stroke:#FEBD2F;stroke-width:30;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                                    id="path2241" />
                                                            </g>
                                                            <g id="g2243" transform="translate(376,256)">
                                                                <path
                                                                    d="m 0,0 c 0,-132.548 -53.726,-241 -120,-241 -66.274,0 -120,108.452 -120,241 0,132.548 53.726,241 120,241 C -53.726,241 0,132.548 0,0 Z"
                                                                    style="fill:none;stroke:#FEBD2F;stroke-width:30;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                                    id="path2245" />
                                                            </g>
                                                            <g id="g2247" transform="translate(256,497)">
                                                                <path d="M 0,0 V -482"
                                                                    style="fill:none;stroke:#FEBD2F;stroke-width:30;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                                    id="path2249" />
                                                            </g>
                                                            <g id="g2251" transform="translate(15,256)">
                                                                <path d="M 0,0 H 482"
                                                                    style="fill:none;stroke:#FEBD2F;stroke-width:30;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                                    id="path2253" />
                                                            </g>
                                                            <g id="g2255" transform="translate(463.8926,136)">
                                                                <path d="M 0,0 H -415.785"
                                                                    style="fill:none;stroke:#FEBD2F;stroke-width:30;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                                    id="path2257" />
                                                            </g>
                                                            <g id="g2259" transform="translate(48.1079,377)">
                                                                <path d="M 0,0 H 415.785"
                                                                    style="fill:none;stroke:#FEBD2F;stroke-width:30;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                                    id="path2261" />
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </svg>
                                        </div>
                                    </a>
                                    <div class="menu-dropdown">
                                        <ul>
                                            @foreach ($languages as $code => $language)
                                                <li><a href="{{ route('change.languagestore', [$code]) }}"
                                                        class="@if ($language == $currantLang) active-language text-primary @endif">{{ ucFirst($language) }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 col-12">
                    <div class="common-banner-content">
                        <div class="row">
                            <div class="col-xl-5">
                                <div class="section-title">
                                    <h2>{{ __('Your Order Details') }}</h2>
                                </div>
                            </div>
                            <div class="col-xl-7">
                                <div
                                    class=" d-flex all-button-box justify-content-md-end justify-content-end text-end">
                                    <button type="submit" onclick="saveAsPDF();" title="Print" aria-label="Print"
                                        class="btn continue-btn  ">
                                        <i class="ti ti-printer" style="font-size:20px"> </i>
                                        <span class="btn-inner--text text-white">{{ __('Print') }}</span>

                                    </button>
                                    <button
                                        class="btn btn-sm btn-secondary "style="margin-left: 5px">{{ $order['order_status_text'] }}</button>
                                    @if (
                                        $order['payment_status'] == 'Unpaid' &&
                                            $order['order_status_text'] != 'Cancel' &&
                                            $order_data['delivered_status'] == 0)
                                        <a class="delstatus btn btn-sm btn-primary me-2 " style="margin-left: 5px"
                                            data-id="{{ $order['id'] }}">
                                            <i class="ti ti-trash " style="font-size:20px"></i>
                                            <span class="btn-inner--text text-white">{{ __('Order Cencel') }}</span>
                                        </a>
                                    @endif
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <section class="product-listing-section padding-top">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row order-details-modal product-modal-detail"  id="printableArea">
                            <div class="col-xxl-7">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between">
                                        <p class="mb-0"><b>{{ __('Items from Order') }} {{ $order['order_id'] }}</b>
                                        </p>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('Item') }}</th>
                                                        <th>{{ __('Quantity') }}</th>
                                                        <th>{{ __('Total') }}</th>
                                                        @if ($order['order_status'] == 1 && $order['is_guest'] == 0)
                                                            <th>{{ __('Return') }}</th>
                                                        @endif
                                                        @if ($order['order_status'] == 1)
                                                            <th>{{ __('Downloadable Product') }}</th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($order['product'] as $item)
                                                        @php
                                                            $download_prod = \App\Models\ProductVariant::where('id', $item['variant_id'])->first();
                                                            $download_product = \App\Models\Product::where('id', $item['product_id'])->first();
                                                        @endphp
                                                        <tr>
                                                            <td class="total">
                                                                <span class="p text-sm"> <a
                                                                        href="#">{{ $item['name'] }}</a> </span>
                                                                <br>
                                                                <span class="text-sm"> {{ $item['variant_name'] }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                @if ($order['paymnet_type'] == 'POS')
                                                                    {{ $item['quantity'] }}
                                                                @else
                                                                    {{ $item['qty'] }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($order['paymnet_type'] == 'POS')
                                                                    {{ currency_format_with_sym( ($item['orignal_price'] ?? 0), $store->id, $store->theme_id) ?? SetNumberFormat($item['orignal_price']) }}
                                                                @else
                                                                    {{ currency_format_with_sym( ($item['final_price'] ?? 0), $store->id, $store->theme_id) ?? SetNumberFormat($item['final_price']) }}
                                                                @endif
                                                            </td>
                                                            @if ($order['order_status'] == 1 && $order['is_guest'] == 0)
                                                                <td> - </td>
                                                            @endif
                                                            @if ($order['order_status_text'] == 'Delivered')
                                                                @if (!empty($download_prod->downloadable_product) || !empty($download_product->downloadable_product))
                                                                <td>
                                                                        <div class="detail-bottom">
                                                                            @if (!empty($download_product->downloadable_product))
                                                                                <a class="download_prod_{{ $item['product_id'] }}"
                                                                                    href="{{ get_file($download_product->downloadable_product) }}"
                                                                                    download style="display: none;"></a>
                                                                            @endif
                                                                            @if (!empty($download_prod->downloadable_product))
                                                                                <a class="download_prod_{{ $item['product_id'] }}"
                                                                                    href="{{ get_file($download_prod->downloadable_product) }}"
                                                                                    download style="display: none;"></a>
                                                                            @endif
                                                                            <button data-id="{{ $order['id'] }}"
                                                                                    class="btn cart-btn downloadable_product_variant"
                                                                                    data-product-id="{{ $item['product_id'] }}">
                                                                                {{ __('Download') }}
                                                                                <i class="fas fa-shopping-basket"></i>
                                                                            </button>
                                                                        </div>
                                                                    </td>
                                                                @endif
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 col-lg-6 ">
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between">
                                                <b class="">{{ __('Shipping Information') }}</b>
                                            </div>
                                            <div class="card-body pt-0">
                                                <address class="mb-0 text-sm">
                                                    <ul class="row mt-4 align-items-center">
                                                        <li class="col-sm-5 text-sm"><b>{{ __('Name') }}</b></li>
                                                        <li class="col-sm-7 text-sm">
                                                            {{ !empty($order['delivery_informations']['name']) ? $order['delivery_informations']['name'] : '' }}
                                                        </li>
                                                        <li class="col-sm-5 text-sm"><b>{{ __('Email') }}</b></li>
                                                        <li class="col-sm-7 text-sm">
                                                            {{ !empty($order['delivery_informations']['email']) ? $order['delivery_informations']['email'] : '' }}
                                                        </li>
                                                        <li class="col-sm-5 text-sm"><b>{{ __('City') }}</b></li>
                                                        <li class="col-sm-7 text-sm">
                                                            {{ !empty($order['delivery_informations']['city']) ? $order['delivery_informations']['city'] : '' }}
                                                        </li>
                                                        <li class="col-sm-5 text-sm"><b>{{ __('State') }}</b></li>
                                                        <li class="col-sm-7 text-sm">
                                                            {{ !empty($order['delivery_informations']['state']) ? $order['delivery_informations']['state'] : '' }}
                                                        </li>
                                                        <li class="col-sm-5 text-sm"><b>{{ __('Country') }}</b></li>
                                                        <li class="col-sm-7 text-sm">
                                                            {{ !empty($order['delivery_informations']['country']) ? $order['delivery_informations']['country'] : '' }}
                                                        </li>
                                                        <li class="col-sm-5 text-sm"><b>{{ __('Postal Code') }}</b>
                                                        </li>
                                                        <li class="col-sm-7 text-sm">
                                                            {{ !empty($order['delivery_informations']['post_code']) ? $order['delivery_informations']['post_code'] : '' }}
                                                        </li>
                                                        <li class="col-sm-5 text-sm"><b>{{ __('Phone') }} </b></li>
                                                        <li class="col-sm-7 text-sm">
                                                            <a href="https://api.whatsapp.com/send?phone={{ !empty($order['delivery_informations']['phone']) ? $order['delivery_informations']['phone'] : '' }}&amp;text=Hi"
                                                                target="_blank">
                                                                {{ !empty($order['delivery_informations']['phone']) ? $order['delivery_informations']['phone'] : '' }}
                                                            </a>
                                                        </li>

                                                    </ul>
                                                </address>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-lg-6 ">
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between">
                                                <b class="">{{ __('Billing Information') }}</b>
                                            </div>
                                            <div class="card-body pt-0">
                                                <address class="mb-0 text-sm">
                                                    <ul class="row mt-4 align-items-center">
                                                        <li class="col-sm-5 text-sm"><b>{{ __('Name') }}</b></li>
                                                        <dd class="col-sm-7 text-sm pb-2">
                                                            {{ !empty($order['billing_informations']['name']) ? $order['billing_informations']['name'] : '' }}
                                                        </dd>
                                                        <li class="col-sm-5 text-sm"><b>{{ __('Email') }}</b></li>
                                                        <dd class="col-sm-7 text-sm">
                                                            {{ !empty($order['billing_informations']['email']) ? $order['billing_informations']['email'] : '' }}
                                                        </dd>
                                                        <li class="col-sm-5 text-sm"><b>{{ __('City') }}</b></li>
                                                        <dd class="col-sm-7 text-sm">
                                                            {{ !empty($order['billing_informations']['city']) ? $order['billing_informations']['city'] : '' }}
                                                        </dd>
                                                        <li class="col-sm-5 text-sm"><b>{{ __('State') }}</b></li>
                                                        <dd class="col-sm-7 text-sm">
                                                            {{ !empty($order['billing_informations']['state']) ? $order['billing_informations']['state'] : '' }}
                                                        </dd>
                                                        <li class="col-sm-5 text-sm"><b>{{ __('Country') }}</b></li>
                                                        <dd class="col-sm-7 text-sm">
                                                            {{ !empty($order['billing_informations']['country']) ? $order['billing_informations']['country'] : '' }}
                                                        </dd>
                                                        <li class="col-sm-5 text-sm"><b>{{ __('Postal Code') }}</b>
                                                        </li>
                                                        <dd class="col-sm-7 text-sm">
                                                            {{ !empty($order['billing_informations']['post_code']) ? $order['billing_informations']['post_code'] : '' }}
                                                        </dd>
                                                        <li class="col-sm-5 text-sm"><b>{{ __('Phone') }}</b></li>
                                                        <li class="col-sm-7 text-sm">
                                                            <a href="https://api.whatsapp.com/send?phone={{ !empty($order['billing_informations']['phone']) ? $order['billing_informations']['phone'] : '' }}&amp;text=Hi"
                                                                target="_blank">
                                                                {{ !empty($order['billing_informations']['phone']) ? $order['billing_informations']['phone'] : '' }}
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </address>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-5 col-md-6 col-12">
                                <div class="card  p-0">
                                    <div class="card-header d-flex justify-content-between pb-0">
                                        <b class="mb-4">{{ __('Extra Information') }}</b>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <td>{{ __('Sub Total') }} :</td>
                                                        <td>{{ currency_format_with_sym( ($order['sub_total'] ?? 0), $store->id, $store->theme_id) ?? SetNumberFormat($order['sub_total']) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ __('Estimated Tax') }} :</td>
                                                        <td>
                                                            @if ($order['paymnet_type'] == 'POS')
                                                                {{ currency_format_with_sym( ($order['tax_price'] ?? 0), $store->id, $store->theme_id) ?? SetNumberFormat($order['tax_price']) }}
                                                            @else
                                                                {{-- {{ SetNumberFormat(array_sum(array_column($order['tax'], 'amountstring'))) }} --}}
                                                                {{ currency_format_with_sym( ($order['tax_price'] ?? 0), $store->id, $store->theme_id) ?? SetNumberFormat($order['tax_price']) }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @if ($order['paymnet_type'] == 'POS')
                                                        <tr>
                                                            <td>{{ __('Discount') }} :</td>
                                                            <td>{{  currency_format_with_sym( ($order['coupon_price'] ?? 0), $store->id, $store->theme_id) ?? (!empty($order['coupon_price']) ? SetNumberFormat($order['coupon_price']) : SetNumberFormat(0) )}}
                                                            </td>
                                                        </tr>
                                                    @else
                                                        <tr>
                                                            <td>{{ __('Apply Coupon') }} :</td>
                                                            <td>{{ currency_format_with_sym( ($order['coupon_info']['discount_amount'] ?? 0), $store->id, $store->theme_id) ?? ( !empty($order['coupon_info']['discount_amount']) ? SetNumberFormat($order['coupon_info']['discount_amount']) : SetNumberFormat(0)) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    <tr>
                                                        <td>{{ __('Delivered Charges') }} :</td>
                                                        <td>{{ currency_format_with_sym( ($order['delivered_charge'] ?? 0), $store->id, $store->theme_id) ?? SetNumberFormat($order['delivered_charge']) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ __('Grand Total') }} :</td>
                                                        <td><b>{{ currency_format_with_sym( ($order['final_price'] ?? 0), $store->id, $store->theme_id) ?? SetNumberFormat($order['final_price']) }}</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ __('Payment Type') }} :</td>
                                                        <td> {{ $order['paymnet_type'] }} </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ __('Order Status') }} :</td>
                                                        <td>{{ $order['order_status_text'] }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @if (!empty($order_note))
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between">
                                            <p class="mb-0"><b>{{ __('Order updates for') }}
                                                    {{ $order['order_id'] }}</b>
                                            </p>
                                        </div>
                                        <div class="card-body">
                                            @php
                                                $i = 1;
                                            @endphp
                                            @foreach ($order_note as $note)
                                                <div class="card">
                                                    <div class="card-header">
                                                        <span class="time">
                                                            {{ $i }} .
                                                            {{ $note->created_at->format('l jS \\of F Y, h:ia') }}
                                                        </span>
                                                        <span class="tl-btn licence-btn">
                                                            {{ $note->notes }}
                                                        </span>
                                                    </div>
                                                </div>
                                                @php
                                                    $i++;
                                                @endphp
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="social-media">
            @if(isset($section->footer->section->footer_link))
                <div class="container">
                    <ul class="social-links justify-content-end">
                        @for ($i = 0; $i < $section->footer->section->footer_link->loop_number ?? 1; $i++)
                            <li>
                                <a href="{{ $section->footer->section->footer_link->social_link->{$i} ?? '#'}}" target="_blank" id="social_link_{{ $i }}">
                                    <img src="{{ asset($section->footer->section->footer_link->social_icon->{$i}->image ?? 'themes/' . $currentTheme . '/assets/images/youtube.svg') }}" class="{{ 'social_icon_'. $i .'_preview' }}" alt="icon" id="social_icon_{{ $i }}">
                                </a>
                            </li>
                        @endfor
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <script src="{{ asset('public/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/plugins/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap-switch-button.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/dash.js') }}"></script>
    <script src="{{ asset('public/assets/js/plugins/apexcharts.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/plugins/flatpickr.min.js') }}"></script>

    <script src="{{ asset('public/assets/js/plugins/simple-datatables.js') }}"></script>
    <script src="{{ asset('public/assets/js/plugins/notifier.js') }}"></script>
    <script src="{{ asset('public/assets/js/pages/ac-notification.js') }}"></script>
    <script src="{{ asset('public/assets/js/plugins/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/plugins/choices.min.js') }}{{ '?' . time() }}"></script>
    <script src="{{ asset('public/js/custom.js') }}{{ '?' . time() }}"></script>
    <script src="{{ asset('public/js/jquery.form.js') }}"></script>
    <script src="{{ asset('assets/css/summernote/summernote-bs4.js') }}"></script>


    <script src="{{ asset('js/html2pdf.bundle.min.js') }}{{ '?' . time() }}"></script>
    <script>
        var filename = $('#filesname').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {
                    type: 'jpeg',
                    quality: 1
                },
                html2canvas: {
                    scale: 4,
                    dpi: 72,
                    letterRendering: true
                },
                jsPDF: {
                    unit: 'in',
                    format: 'A2'
                }
            };
            html2pdf().set(opt).from(element).save();


        }
        $(document).on('click', '.delstatus', function() {

            var order_id = $(this).attr('data-id');
            var data = {
                order_id: order_id,
                order_status: 'cancel',
            }
            $.ajax({
                url: '{{ route('status.cancel', $store->slug) }}',
                data: data,
                type: 'post',
                success: function(data) {
                    if (data.status == 'error') {
                        show_toastr('{{ __('Error') }}', data.message, 'error')
                    } else {
                        show_toastr('{{ __('Success') }}', data.message, 'success')
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    }
                }
            });
        });
        
        document.querySelectorAll('.downloadable_product_variant').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const downloadLink = document.querySelector('.download_prod_' + productId);
                if (downloadLink) {
                    downloadLink.click();
                } else {
                    console.error('Download link not found for product ID:', productId);
                }
            });
        });
    </script>

    <body>

</html>

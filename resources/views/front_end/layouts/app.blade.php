<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Perfume - The perfume accord is the 'backbone' of the fragrance and consists of the main building blocks of the scent or olfactive creation..">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>@yield('page-title')</title>
    <meta name="base-url" content="{{URL::to('/')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {!! metaKeywordSetting($metakeyword ?? '',$metadesc ?? '',$metaimage ?? '',$slug) !!}

<link rel="shortcut icon"
    href="{{ isset($theme_favicon) && !empty($theme_favicon) ? $theme_favicon : asset('themes/' . $currentTheme  . '/assets/images/Favicon.png') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&amp;family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700;1,800&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="{{ asset('themes/' . $currentTheme . '/assets/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/' . $currentTheme . '/assets/css/jquery-ui.css') }}">
    @if($currantLang == 'ar' || $currantLang == 'he')
    <link rel="stylesheet" href="{{ asset('themes/' . $currentTheme . '/assets/css/rtl-main-style.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/' . $currentTheme . '/assets/css/rtl-responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/rtl-custom.css') }}">
    @else
    <link rel="stylesheet" href="{{ asset('themes/' . $currentTheme . '/assets/css/main-style.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/' . $currentTheme . '/assets/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    @endif
    <link rel="stylesheet" href="{{ asset('public/assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/notifier.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/floating-wpp.min.css') }}">

    <link rel="stylesheet" href="{{ asset('public/assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/customizer.css') }}">
    <style>
    </style>
    <style>
        .notifier {
            padding: calc(25px - 5px) calc(25px - 5px);
            border-radius: 10px;
        }

        .notifier-title {
            margin: 0 0 4px;
            padding: 0;
            font-size: 18px;
            font-weight: 400;
            color: #000;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .notifier .notifier-body {
            font-size: 0.875rem;
        }
    </style>
    <style>
        nav ul li .active{
            background:  #d8135a !important;
            color: #ffffff
        }
    </style>
    {{-- pwa customer app --}}
    <meta name="mobile-wep-app-capable" content="yes">
    <meta name="apple-mobile-wep-app-capable" content="yes">
    <meta name="msapplication-starturl" content="/">
    <link rel="apple-touch-icon" href="{{ isset($theme_favicon) && !empty($theme_favicon) ? $theme_favicons : 'themes/' . $currentTheme . '/assets/images/Favicon.png' }}">

    @if ($store->enable_pwa_store == 'on')
        <link rel="manifest" href="{{ asset('storage/uploads/customer_app/store_' . $store->id . '/manifest.json') }}" />
    @endif
    @if (!empty($store->pwa_store($store->slug)->theme_color))
        <meta name="theme-color" content="{{ $store->pwa_store($store->slug)->theme_color }}" />
    @endif
    @if (!empty($store->pwa_store($store->slug)->background_color))
        <meta name="apple-mobile-web-app-status-bar"
            content="{{ $store->pwa_store($store->slug)->background_color }}" />
    @endif
</head>
<body>
@if(isset($pixelScript))
        @foreach ($pixelScript as $script)
            <?= $script ?>
        @endforeach
    @endif
    @yield('content')
    <svg style="display: none;">
        <symbol viewBox="0 0 6 5" id="slickarrow">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M5.89017 2.75254C6.03661 2.61307 6.03661 2.38693 5.89017 2.24746L3.64017 0.104605C3.49372 -0.0348681 3.25628 -0.0348681 3.10984 0.104605C2.96339 0.244078 2.96339 0.470208 3.10984 0.609681L5.09467 2.5L3.10984 4.39032C2.96339 4.52979 2.96339 4.75592 3.10984 4.8954C3.25628 5.03487 3.49372 5.03487 3.64016 4.8954L5.89017 2.75254ZM0.640165 4.8954L2.89017 2.75254C3.03661 2.61307 3.03661 2.38693 2.89017 2.24746L0.640165 0.104605C0.493719 -0.0348682 0.256282 -0.0348682 0.109835 0.104605C-0.0366115 0.244078 -0.0366115 0.470208 0.109835 0.609681L2.09467 2.5L0.109835 4.39032C-0.0366117 4.52979 -0.0366117 4.75592 0.109835 4.8954C0.256282 5.03487 0.493719 5.03487 0.640165 4.8954Z">
            </path>
        </symbol>
    </svg>
    <!-- [ Main Content ] start -->
    <div class="wrapper">

        <div class="pct-customizer">
            <div class="pct-c-btn">
                <button class="btn btn-primary" id="pct-toggler" data-toggle="tooltip"
                    data-bs-original-title="Order Track" aria-label="Order Track">
                    <i class='fas fa-shipping-fast' style='font-size:24px;'></i>
                </button>
            </div>
            <div class="pct-c-content">
                <div class="pct-header bg-primary">
                    <h5 class="mb-0 f-w-500">{{ 'Order Tracking' }}</h5>
                </div>
                <div class="pct-body">
                    {{ Form::open(['route' => ['order.track', $slug], 'method' => 'POST', 'id' => 'choice_form', 'enctype' => 'multipart/form-data']) }}
                    <div class="form-group col-md-12">
                        {!! Form::label('order_number', __('Order Number'), ['class' => 'form-label']) !!}
                        {!! Form::text('order_number', null, ['class' => 'form-control', 'placeholder' => 'Enter Your Order Id']) !!}
                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('email', __('Email Address'), ['class' => 'form-label']) !!}
                        {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Enter email']) !!}
                    </div>
                    <button type="submit" class="btn justify-contrnt-end">{{ __('Submit') }}</button>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
       
    </div>
    <!-- [ Main Content ] end -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.slim.min.js"></script>
    <script src="{{ asset('themes/' . $currentTheme . '/assets/js/jquery.js') }}"></script>
    <script src="{{ asset('themes/' . $currentTheme . '/assets/js/slick.js') }}" defer="defer"></script>
    <script src="{{ asset('themes/' . $currentTheme . '/assets/js/slick-lightbox.js') }}" defer="defer"></script>
    @if($currantLang == 'ar' || $currantLang == 'he')
    <script src="{{ asset('themes/' . $currentTheme . '/assets/js/rtl-custom.js') }}" defer="defer"></script>
    @else
    <script src="{{ asset('themes/' . $currentTheme . '/assets/js/custom.js') }}" defer="defer"></script>
    @endif
    <script src="{{ asset('themes/' . $currentTheme . '/assets/js/jquery-ui.js') }}" defer="defer"></script>
    <script src="{{ asset('public/assets/js/plugins/notifier.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}" defer="defer"></script>
    <script src="{{ asset('public/assets/js/plugins/bootstrap.min.js') }}"></script>

    <script src="{{ asset('public/assets/js/plugins/feather.min.js') }}"></script>

    <script src="{{ asset('assets/js/floating-wpp.min.js') }}"></script>
   <!--Theme Routes Script-->
   @include('front_end.jQueryRoute')
    <!--scripts end here-->

    <!--scripts start here-->

   
    @stack('page-script')
   
  

</body>
</html>
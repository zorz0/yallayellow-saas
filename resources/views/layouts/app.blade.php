@php
    if(auth()->user() && auth()->user()->type == 'admin') {
    $setting = getAdminAllSetting();
    } else {
        $setting = getSuperAdminAllSetting();
    }

    $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
    $cust_darklayout = \App\Models\Utility::GetValueByName('cust_darklayout', $theme_name);
    if ($cust_darklayout == '') {
        $setting['cust_darklayout'] = 'off';
    }

    $cust_theme_bg = \App\Models\Utility::GetValueByName('cust_theme_bg', $theme_name);
    if($cust_theme_bg == ''){
        $setting['cust_theme_bg'] = 'on';
    }

    $SITE_RTL = 'on';
    if($SITE_RTL == ''){
        $setting['SITE_RTL'] = 'off';
    }

   // $color = \App\Models\Utility::GetValueByName('color', $theme_name);
    //if(!empty($color)){
  //      $themeColor = $color;
   // }
    //else{
        if (!isset($setting['color'])) {
            $themeColor = 'theme-3';
        } elseif (isset($setting['color_flag']) && $setting['color_flag'] == 'true') {
            $themeColor = 'custom-color';
        } else {
            if (!in_array($setting['color'], ['theme-1','theme-2','theme-3','theme-4','theme-5','theme-6','theme-7','theme-8','theme-9'])) {
                $themeColor = 'custom-color' ?? 'theme-3';
            } else {
                $themeColor = $setting['color'] ?? 'theme-3';
            }
            
        }
   // }
    $currantLang = Cookie::get('LANGUAGE');
    if (!isset($currantLang)) {
        $setting['currantLang'] = 'en';
    }
    if (isset($setting['currantLang']) && ($setting['currantLang'] == 'ar' || $setting['currantLang'] == 'he')) {
        $setting['SITE_RTL'] = 'on';
    }
    $setting['SITE_RTL'] = 'on';
    app()->setLocale('ar');

@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ isset($setting['SITE_RTL']) && $setting['SITE_RTL'] == 'on'? 'rtl' : '' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Dashboard Template Description" />
    <meta name="keywords" content="Dashboard Template" />
    <meta name="author" content="WorkDo.io" />
    <meta name="base-url" content="{{ URL::to('/') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($setting['title_text']) ? $setting['title_text'] : ( env('APP_NAME') ?? 'Ecommercego saas') }} - @yield('page-title') </title>

    {{-- select2 --}}
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/select2.min.css') }}">
    <!-- Favicon icon -->
    <link rel="icon" href="{{ asset(isset($setting['favicon']) ? ($setting['favicon'].'?timestamp=' . time()) : ('uploads/logo/logo-sm.svg'.'?timestamp=' . time()))}}" type="image/x-icon" />

    <!-- notification css -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/notifier.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap-switch-button.min.css') }}">
    <!-- datatable css -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/style.css') }}">

    <!-- Fonts -->
    <!-- font css -->
    <link rel="stylesheet" href="{{ asset('/assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/fonts/material.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/plugins/dropzone.css') }}" type="text/css" />

    @if (isset($setting['cust_darklayout']) && isset($setting['SITE_RTL']) && $setting['cust_darklayout'] == 'on' && $setting['SITE_RTL'] == 'on')
        <link rel="stylesheet" href="{{ asset('public/assets/css/style-dark.css') }}" id="main-style-link">
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}" id="main-style-link">
    @elseif(isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}" id="main-style-link">
    @elseif(isset($setting['SITE_RTL']) && $setting['SITE_RTL'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}" id="main-style-link">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    @endif

    <link rel="stylesheet" href="{{ asset('assets/css/plugins/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}{{ '?v=' . time() }}">
    <link rel="stylesheet" href="{{ asset('css/custom-color.css') }}">
    <!-- Scripts -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    <style>
        {!! isset($setting['storecss']) ? $setting['storecss'] :  '' !!}

        :root {
            --color-customColor: <?= $setting['color'] ?? 'linear-gradient(141.55deg, rgba(240, 244, 243, 0) 3.46%, #ffffff 99.86%)' ?>;
        }
    </style>
    @stack('css')
</head>

{{-- <body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body> --}}

<body class="{{ $themeColor ?? 'theme-3'}}">
    @include('partision.sidebar')

    @include('partision.header')

    <!-- [ Main Content ] start -->
    <div class="dash-container">
        <div class="dash-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-xl-5">
                            <div class="page-header-title">
                                <h4 class="m-b-10">@yield('page-title')</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item">
                                    @if (\Request::route()->getName() != 'dashboard')
                                        <a href="{{ route('dashboard') }}">{{ __('Home') }}</a>
                                    @endif
                                </li>
                                @yield('breadcrumb')
                            </ul>
                        </div>
                        <div class="col-xl-7">
                            @yield('action-button')
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
            @yield('content')
        </div>
    </div>
    <!-- [ Main Content ] end -->

    {{-- @include('partision.footer') --}}
    @if (\Request::route()->getName() != 'pos.index')
        <div id="commanModel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modelCommanModelLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modelCommanModelLabel"></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body"></div>
                </div>
            </div>
        </div>

        <div id="commanModelOver" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modelCommanModelLabel"
        aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modelCommanModelLabel"></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body"></div>
                </div>
            </div>
        </div>
    @endif

    @include('partision.settingPopup')
    @include('partision.footerlink')
    @stack('scripts')
    @stack('custom-script')
    @stack('custom-script1')
    <script type="text/javascript">
        $(document).ready(function(){
            $('.select2').select2({
                tags: true,
                createTag: function (params) {
                  var term = $.trim(params.term);
                  if (term === '') {
                    return null;
                  }
                  return {
                    id: term,
                    text: term,
                    newTag: true
                  };
                }
              });
        })
        </script>
    <script>
        function add_more_choice_option(i, name) {

        $('#attribute_options').append(
            '<div class="card oprtion"><div class="card-body "><input type="hidden" class="abd" name="attribute_no[]" value="' +
            i + '"><input type="hidden" class="abd" name="option_no[]" value="' + i + '">' +

            '<div class="row">' +
            '<div class="form-group col-lg-6 col-12">' +
            '<label for="choice_attributes" class="col-6">' + name + ':</label></div>' +

            '<div class="form-group col-lg-6 col-12 d-flex justify-content-end all-button-box">' +
            '<a href="#" class="btn btn-sm btn-primary add_attribute" data-ajax-popup="true" data-title="{{ __('Add Attribute Option') }}" data-size="md" ' +
            'data-url="{{ route('product-attribute-option.create') }}/' + i + '" ' +
            'data-toggle="tooltip">' +
            '<i class="ti ti-plus">{{ __('Add Attribute option') }}</i></a></div></div>' +

            '<div class="row parent-clase">' +
            '<div class="form-group col-12">' +
            '<div class="form-chec1k form-switch p-0">' +
            '<input type="hidden" name="visible_attribute_' + i + '" value="0">' +
            '<input type="checkbox" class="form-check-input attribute-form-check" name="visible_attribute_' + i +
            '" id="visible_attribute" value="1">' +
            '<label class="form-check-label" for="visible_attribute"></label>' +
            '<label for="product_page_option" class=""> Visible on the product page</label></div>' +

            ' <div style="margin-top: 9px;"></div>' +

            '<div class="for-variation_data form-chec1k form-switch p-0 d-none use_for_variation" id="use_for_variation"  data-id="' +
            i + '">' +
            '<input type="hidden" name="for_variation_' + i + '" value="0">' +
            '<input type="checkbox" class="form-check-input input-options attribute-form-check enable_variation_' +
            i + '" name="for_variation_' + i + '" id="for_variation" value="1" data-id="' + i +
            '" data-enable-variation=" enable_variation_' + i + ' ">' +
            '<label class="form-check-label" for="for_variation"></label>' +
            '<label for="product_page_option" class=""> Used for variations</label></div>' +
            '</div>' +

            '<div class="form-group col-12">' +
            '<select class="col-6 form-control attribute attribute_option_data" name="attribute_options_' + i +
            '[]" __="{{ __('Enter choice values') }}"  data-role="" multiple id="attribute' + i +
            '" data-id="' + i + '" required ></select></div></div>' +

            '</div></div>');

        if ($('.enable_product_variant').prop('checked') == true) {
            $(".use_for_variation").removeClass("d-none");
        }

        comman_function();
    }
</script>
    @if ($message = Session::get('success'))
        <script>
            show_toastr('{{ __('Success') }}', '{!! $message !!}', 'success')
        </script>
    @endif

    @if ($message = Session::get('error'))
        <script>
            show_toastr('{{ __('Error') }}', '{!! $message !!}', 'error')
        </script>
    @endif




    @php
        $setting = getSuperAdminAllSetting();
    @endphp
    @if (isset($setting['enable_cookie']) && $setting['enable_cookie'] == 'on')
        @include('layouts.cookie_consent')
    @endif
</body>

</html>

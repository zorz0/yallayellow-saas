<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        {{ __('Theme Customize') }}</title>

    <link rel="icon"
        href="{{ asset(Storage::url('uploads/logo/')) . '/' . (isset($companySettings['company_favicon']) && !empty($companySettings['company_favicon']) ? $companySettings['company_favicon']->value : 'favicon.png') }}"
        type="image" sizes="16x16">
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- font css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">

    <!--bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap-switch-button.min.css') }}">

    <!-- vendor css -->
    @if ($SITE_RTL == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}">
    @endif
    @if (isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    @endif
    <!-- SweetAlert CSS -->
    @stack('css-page')
</head>

<body class="{{ $color }}">
  @yield('customize-section')

    <div id="commanModel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modelCommanModelLabel" aria-hidden="true">
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
    <script src="{{ asset('themes/' . $currentTheme . '/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('themes/' . $currentTheme . '/assets/js/custom.js') }}"></script>
    <!-- Required Js -->
    <script src="{{ asset('public/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/plugins/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap-switch-button.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/dash.js') }}"></script>
    <script src="{{ asset('public/assets/js/plugins/apexcharts.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/plugins/simple-datatables.js') }}"></script>
    <script src="{{ asset('public/assets/js/pages/ac-notification.js') }}"></script>
    <script src="{{ asset('public/assets/js/plugins/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/plugins/choices.min.js') }}"></script>
    <script src="{{ asset('assets/css/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('public/assets/js/plugins/flatpickr.min.js') }}"></script>
    <script src="{{ asset('public/js/socialSharing.js') }}"></script>
    <script src="{{ asset('public/js/custom.js') }}"></script>
    <script src="{{ asset('public/js/jquery.form.js') }}"></script>
    <script src="{{ asset('public/js/jquery-ui.min.js') }}"></script>
    <!-- SweetAlert JS -->
    @if ($message = Session::get('success'))
        <script>
            show_toastr('success', '{!! $message !!}');
        </script>
    @endif
    @if ($message = Session::get('error'))
        <script>
            show_toastr('error', '{!! $message !!}');
        </script>
    @endif
    @stack('script-page')

    <script>
        var saveThemeRoute = "{{ route('save-theme-layout') }}";
        var sidebarThemeRoute = "{{ route('sidebar-option') }}";
        
    </script>
    <!-- theme custome JS File-->
    <script src="{{ asset('public/js/theme-custom.js') }}"></script>


    <script>
        var site_currency_symbol_position = '{{ \App\Models\Utility::getValByName('currency_symbol_position') }}';
        var site_currency_symbol =
        '{{ \App\Models\Store::where('id', auth()->user()->current_store)->first()->currency }}';

    </script>
    @stack('script')
</body>

</html>

@php
    $logo = asset(Storage::url('uploads/logo/'));
    $company_logo = \App\Models\Utility::GetLogo();
    $company_logo = get_file($company_logo, APP_THEME());
@endphp
<!-- [ Pre-loader ] start -->
<div class="loader-bg">
    <div class="loader-track">
        <div class="loader-fill"></div>
    </div>
</div>

<!-- [ Pre-loader ] End -->
<!-- [ navigation menu ] start -->
@if (isset($setting['cust_theme_bg']) && $setting['cust_theme_bg'] == 'on')
<nav class="dash-sidebar light-sidebar transprent-bg">
    @else
        <nav class="dash-sidebar light-sidebar">
@endif 
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="{{ route('dashboard') }}" class="b-brand">
                <!-- ========   change your logo hear   ============ -->
               <img src="{{ isset($company_logo) && !empty($company_logo) ? $company_logo . '?timestamp=' . time() : $logo . '/logo-dark.svg' . '?timestamp=' . time() }}"
                alt="" class="logo logo-lg" />
            </a>
        </div>

        <div class="navbar-content">
            <ul class="dash-navbar">
                {!! getMenu() !!}
            </ul>
        </div>
</nav>
<!-- [ navigation menu ] end -->

@extends('layouts.app')

@section('page-title', __('Add-on Apps'))

@section('action-button')
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Add-on Apps') }}</li>
@endsection

@section('content')
    <div class="row justify-content-center px-0">
        <div class=" col-12">
            <div class="card">
                <div class="card-body package-card-inner  d-flex align-items-center">
                    <div class="package-itm ">
                        <a href="https://workdo.io/?utm_source=ecom-main-file&utm_medium=superadmin&utm_campaign=superadmin-btn-theme-all" target="new">
                            <img src="https://workdo.io/wp-content/uploads/2023/04/Logo.svg" alt="">
                        </a>
                    </div>
                    <div class="package-content flex-grow-1  px-3">
                        <h4>{{ __('Buy More Android & iOS Apps Addon') }}</h4>
                        <div class="text-muted">{{ __('+35 Premium Android & iOS Native Apps Addon') }}</div>
                    </div>
                    <div class="price text-end">
                        <a class="btn btn-primary" href="https://workdo.io/product-category/apps/?utm_source=ecom-main-file&utm_medium=superadmin&utm_campaign=superadmin-btn-mobile-all" target="new">
                            {{ __('Buy More Mobile Apps Addon') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <h4 class="mb-3"> {{ __('Buy More Themes') }}</h4>
    </div>
        @php
            $theme_array = [];
        @endphp
    <div class="event-cards row px-0">
            @php
                $theme_array = ['babycare','grocery'];
            @endphp   
        @foreach ($apps['apps'] as $key => $value)
                <div class="col-lg-3 col-md-4 col-sm-6 card-wrapper">
                    <div class="product-card ">
                        <div class="product-card-inner">
                            <div class="product-card-image img-wrapper">
                                <a href="{{ $value[1] }}" target="_new" class="pdp-img" tabindex="0">
                                    <img src="{{ $value[1] }}">
                                </a>
                            </div>
                            <div class="product-content">
                                <div class="product-content-top">
                                    <small class="text-muted">
                                            <span class="badges bg-success">{{ __('Buy Now') }}</span>
                                    </small>
                                        <h4 class="text-capitalize">{{ $value[0] }} ({{ __('Premium Addon') }})</h4>
                                </div>
                                @if (!in_array($value[0],$theme_array))
                                <div class="product-content-bottom d-flex gap-2">
                                    <a href="{{ $value[2] }}?utm_source=ecom-main-file&utm_medium=superadmin&utm_campaign=superadmin-btn-mobile-{{ $value[0] }}" target="_new"
                                        class="btn btn-outline-primary w-100 mt-2">{{ __('Mobile App') }}</a>
                                </div>
                                @else
                                <a href="{{ $value[2] }}" target="_new"
                                class="btn btn-outline-primary w-100 mt-2">{{ __('Buy Now') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
        @endforeach
    </div>

@endsection


@push('custom-script')
    <script>
        $(document).on('click','.module_change',function(){
            var id = $(this).attr('data-id');
            $('#form_'+id).submit();
        });
    </script>
@endpush




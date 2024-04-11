@extends('layouts.app')

@section('page-title', __('إضافة سمة الإضافة'))

@section('action-button')
<div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
    <a href="{{ route('addon.create') }}" class="btn btn-sm btn-primary">
        <i class="ti ti-plus"></i>
    </a>
</div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('سمة الإضافة') }}</li>
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
                        <h4>{{ __('احصل على المزيد من سمات الإضافات') }}</h4>
                        <div class="text-muted">{{ __('+35 سمة إضافية مميزة') }}</div>
                    </div>
                    <div class="price text-end">
                        <a class="btn btn-primary" href="https://workdo.io/product-category/theme-addon/?utm_source=ecom-main-file&utm_medium=superadmin&utm_campaign=superadmin-btn-theme-all" target="new">
                            {{ __('سمات الإضافات') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <h4 class="mb-3"> {{ __('السمات المثبتة') }}</h4>
    </div>
    <div class="event-cards row px-0">
            @php
                $theme_array = [];
            @endphp
        @foreach ($addon_themes as $key => $value)
            @php
                $theme_array[] = $value->theme_id;
            @endphp
            <div class="col-lg-3 col-md-4 col-sm-6 card-wrapper">
                <div class="product-card ">
                    <div class="product-card-inner">
                        <div class="product-card-image img-wrapper">
                            <a href="{{ asset('themes/'.$value->theme_id.'/theme_img/img_1.png') }}" class="pdp-img" target="_blank" tabindex="0">
                                <img src="{{ asset('themes/'.$value->theme_id.'/theme_img/img_1.png') }}">
                            </a>

                            <div class="checkbox-custom">
                                <div class="btn-group card-option">
                                    <button type="button" class="btn" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                        @if ($value->status == '1')
                                            <a href="#!" class="dropdown-item module_change" data-id="{{ $value->theme_id }}">
                                                <span>{{ __('تعطيل') }}</span>
                                            </a>
                                        @else
                                            <a href="#!" class="dropdown-item module_change" data-id="{{ $value->theme_id }}">
                                                <span>{{ __('تمكين') }}</span>
                                            </a>
                                        @endif

                                        <form action="{{ route('theme.enable') }}" method="POST" id="form_{{ $value->theme_id }}">
                                            @csrf
                                            <input type="hidden"
                                                name="name" value="{{ $value->theme_id }}">
                                        </form>

                                        {!! Form::open(['method' => 'DELETE', 'route' => ['addon.destroy', $value->theme_id], 'class' => 'd-inline']) !!}
                                            <button type="button" class="dropdown-item show_confirm">
                                                <span class="text-danger">{{ __('إزالة') }}</span>
                                            </button>
                                        {!! Form::close() !!}

                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="product-content">
                            <div class="product-content-top">
                                <small class="text-muted">
                                    @if($value->status == '1')
                                        <span class="badges bg-success">{{ __('ممكن') }}</span>
                                    @else
                                        <span class="badges bg-danger">{{ __('غير ممكن') }}</span>
                                    @endif
                                </small>
                                <h4 class="text-capitalize">{{ $value->theme_id }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="col-md-4">
        <h4 class="mb-3"> {{ __('شراء المزيد من السمات') }}</h4>
    </div>
    <div class="event-cards row px-0">
        @foreach ($theme['theme'] as $key => $value)
            @if (!in_array($value[0],$theme_array))
                <div class="col-lg-3 col-md-4 col-sm-6 card-wrapper">
                    <div class="product-card ">
                        <div class="product-card-inner">
                            <div class="product-card-image img-wrapper">
                                <a href="{{ $value[3] }}?utm_source=ecom-main-file&utm_medium=superadmin&utm_campaign=superadmin-btn-theme-{{ $value[0] }}" target="_new" class="pdp-img" tabindex="0">
                                    <img src="{{ $value[2] }}">
                                </a>
                            </div>
                            <div class="product-content">
                                <div class="product-content-top">
                                    <small class="text-muted">
                                        <span class="badges bg-success">{{ __('الإضافة مجانية') }}</span>
                                    </small>
                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                        <h4 class="text-capitalize">{{ $value[0] }} ({{ __('الإضافة مجانية') }}) </h4>
                                        @if(!in_array($value[0],['babycare','grocery','scent']))
                                               <a href="https://s3.ap-southeast-1.wasabisys.com/workdo-main-file.rajodiya/ecommercego/main_files/{{$value[0]}}/theme-addon/{{$value[0]}}.zip" target="_new" class="btn btn-outline-primary btn-sm" title="{{ __('تنزيل مجاني') }}">{{ __('تنزيل مجاني') }}
                                                <!-- <i class="fa fa-download"
                                                        aria-hidden="true"></i> -->
                                                    </a>
                                            @endif
                                    </div>
                                </div>
                                <div class="product-content-bottom d-flex gap-2">
                                    <a href="{{ $value[1] }}" target="_new"
                                        class="btn btn-outline-primary w-100 mt-2">{{ __('عرض الديمو') }}</a>
                                        <a href="{{ $value[3] }}?utm_source=ecom-main-file&utm_medium=superadmin&utm_campaign=superadmin-btn-theme-{{ $value[0] }}" target="_new"
                                        class="btn btn-outline-primary w-100 mt-2">{{ __('عرض التفاصيل') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

@endsection
@push('custom-script')
    <script>
        $(document).on('click','.module_change',function(){
            var id = $(this).data('id');
            $('#form_'+id).submit();
        });
    </script>
@endpush

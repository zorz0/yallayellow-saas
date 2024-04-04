@extends('layouts.app')
@section('page-title')
{{__('Add-on Manager')}}
@endsection
@section('page-breadcrumb')
{{ __('Add-on Manager') }}
@endsection
@push('css')
<style>
    .product-img {
        padding-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .system-version h5{
        position: absolute;
        bottom: -44px;
        right: 27px;
    }
    .center-text{
        display: flex;
        flex-direction: column;
    }
    .center-text .text-primary{
        font-size: 14px;
        margin-top: 5px;
    }
    .theme-main{
        display: flex;
        align-items: center;
    }
    .theme-main .theme-avtar{
        margin-right: 15px;
    }
    @media only screen and (max-width: 575px) {
        .system-version h5{
            position: unset;
            margin-bottom: 0px;
        }
        .system-version{
            text-align: center;
            margin-bottom: -22px;
        }
    }
</style>

@endpush
@section('page-action')

@endsection


@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card sticky-top " style="top:60px">
                <div class="list-group list-group-flush theme-set-tab" id="useradd-sidenav">
                    <ul class="nav nav-pills w-100 gap-3" id="pills-tab" role="tablist">
                        <li class="nav-item " role="presentation">
                            <a href="#premiuem"
                                class="nav-link active  list-group-item list-group-item-action border-0 rounded-1 text-center f-w-600"
                                id="pills-premium-tab" data-bs-toggle="pill" data-bs-target="#pills-premium" type="button"
                                role="tab" aria-controls="pills-premium" aria-selected="true">
                                {{ __('Add-On Premium') }}
                            </a>

                        </li>
                        <li class="nav-item " role="presentation">
                            <a href="#themes"
                                class="nav-link   list-group-item list-group-item-action border-0 rounded-1 text-center f-w-600"
                                id="pills-themes-tab" data-bs-toggle="pill" data-bs-target="#pills-themes" type="button"
                                role="tab" aria-controls="pills-themes" aria-selected="true">
                                {{ __('Add-On Themes') }}
                            </a>

                        </li>
                        <li class="nav-item " role="presentation">
                            <a href="#mobile-app"
                                class="nav-link   list-group-item list-group-item-action border-0 rounded-1 text-center f-w-600"
                                id="pills-mobile-app-tab" data-bs-toggle="pill" data-bs-target="#pills-mobile-app" type="button"
                                role="tab" aria-controls="pills-mobile-app" aria-selected="true">
                                {{ __('Add-On Mobile Apps') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-12">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-premium" role="tabpanel"
                    aria-labelledby="pills-premium-tab">
                    <div id="premium">
                        <div class="col-md-12">
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
                                                <h4>{{ __('Buy More Add-on') }}</h4>
                                                <div class="text-muted">{{ __('+'.(count($modules)-1).' Premium Add-on') }}</div>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="price">
                                                    <a class="btn btn-primary" href="https://workdo.io/product-category/theme-addon/ecommercego-addon/" target="new">
                                                    {{ __('Buy More Add-on')}}
                                                    </a>
                                                </div>
                                                <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
                                                    <a href="{{ route('module.add') }}" class="btn btn-primary">
                                                        <i class="ti ti-plus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <!-- [ sample-page ] start -->
                        <div class="event-cards row px-0">
                                <h4 class="mb-3">{{ __('Installed Add-on')}}</h4>
                                @php
                                    $module_array = [];
                                @endphp
                                @foreach ($modules as $module)
                                    @php
                                        $module_name = $module->getName();
                                        $id = strtolower(preg_replace('/\s+/', '_', $module_name));
                                        $path = $module->getPath().'/module.json';
                                        $json = json_decode(file_get_contents($path), true);
                                        $module_array[] = Module_Alias_Name($module->getName());
                                    @endphp
                                    @if ((!isset($json['display']) || $json['display'] == true || ($module_name == 'GoogleCaptcha')) && $module_name != 'LandingPage')
                                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 product-card ">
                                        <div class="card {{ ($module->isEnabled()) ? 'enable_module' : 'disable_module'}}">
                                            <div class="product-img">
                                                <div class="theme-main">
                                                    <div class="theme-avtar">
                                                        <img src="{{ get_module_img($module->getName()) }}"
                                                            alt="{{ $module->getName() }}" class="img-user"
                                                            style="max-width: 100%">
                                                    </div>
                                                    <div class="center-text">
                                                        <small class="text-muted">
                                                            @if ($module->isEnabled())
                                                            <span class="badge bg-success">{{ __('Enable') }}</span>
                                                            @else
                                                            <span class="badge bg-danger">{{ __('Disable') }}</span>
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="checkbox-custom">
                                                    <div class="btn-group card-option">
                                                        <button type="button" class="btn p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="ti ti-dots-vertical"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end" style="">
                                                            @if ($module->isEnabled())
                                                            <a href="#!" class="dropdown-item module_change" data-id="{{ $id }}">
                                                                <span>{{ __('Disable') }}</span>
                                                            </a>
                                                            @else
                                                            <a href="#!" class="dropdown-item module_change" data-id="{{ $id }}">
                                                                <span>{{ __('Enable') }}</span>
                                                            </a>
                                                            @endif
                                                            <form action="{{ route('module.enable') }}" method="POST" id="form_{{ $id }}">
                                                                @csrf
                                                                <input type="hidden" name="name" value="{{ $module->getName() }}">
                                                            </form>
                                                            <form action="{{ route('module.remove', $module->getName()) }}" method="POST" id="form_{{ $id }}">
                                                                @csrf
                                                                <button type="button" class="dropdown-item show_confirm" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$id}}">
                                                                    <span class="text-danger">{{ __("Remove") }}</span>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="product-content">
                                                <h4 class="text-capitalize"> {{ Module_Alias_Name($module->getName()) }}</h4>
                                                <p class="text-muted text-sm mb-0">
                                                    {{ isset($json['description']) ? $json['description'] : '' }}
                                                </p>
                                                <a href="{{ route('software.details',Module_Alias_Name($module->getName())) }}" class="btn  btn-outline-secondary w-100 mt-2">{{ __('View Details')}}</a>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                            <!-- [ sample-page ] end -->
                        </div>
                        <div class="col-md-4">
                                <h4 class="mb-3"> {{ __('Buy More Add-On') }}</h4>
                            </div>
                            <div class="event-cards row px-0">
                                @if(isset($theme['add_ons']))
                                @foreach ($theme['add_ons'] as $key => $value)
                                    @if (!in_array($value['name'],$module_array))
                                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 product-card ">
                                        <div class="card {{ ($module->isEnabled()) ? 'enable_module' : 'disable_module'}}">
                                            <div class="product-img">
                                                <div class="theme-main">
                                                    <div class="theme-avtar">
                                                        <img src="{{ $value['image'] }}"
                                                            alt="{{ $module->getName() }}" class="img-user"
                                                            style="max-width: 100%">
                                                    </div>
                                                    <div class="center-text">
                                                        <small class="text-muted">
                                                            @if ($module->isEnabled())
                                                            <span class="badge bg-success">{{ __('Enable') }}</span>
                                                            @else
                                                            <span class="badge bg-danger">{{ __('Disable') }}</span>
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="checkbox-custom">
                                                    <div class="btn-group card-option">
                                                        <button type="button" class="btn p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="ti ti-dots-vertical"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end" style="">
                                                            @if ($module->isEnabled())
                                                            <a href="#!" class="dropdown-item module_change" data-id="{{ $id }}">
                                                                <span>{{ __('Disable') }}</span>
                                                            </a>
                                                            @else
                                                            <a href="#!" class="dropdown-item module_change" data-id="{{ $id }}">
                                                                <span>{{ __('Enable') }}</span>
                                                            </a>
                                                            @endif
                                                            <form action="{{ route('module.enable') }}" method="POST" id="form_{{ $id }}">
                                                                @csrf
                                                                <input type="hidden" name="name" value="{{ $module->getName() }}">
                                                            </form>
                                                            <form action="{{ route('module.remove', $module->getName()) }}" method="POST" id="form_{{ $id }}">
                                                                @csrf
                                                                <button type="button" class="dropdown-item show_confirm" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$id}}">
                                                                    <span class="text-danger">{{ __("Remove") }}</span>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="product-content">
                                                <h4 class="text-capitalize"> {{ Module_Alias_Name($module->getName()) }}</h4>
                                                <p class="text-muted text-sm mb-0">
                                                    {{ isset($json['description']) ? $json['description'] : '' }}
                                                </p>
                                                <a href="{{ $value['url'] }}" class="btn  btn-outline-secondary w-100 mt-2">{{ __('View Details')}}</a>
                                            </div>
                                        </div>
                                    </div>

                                    @endif
                                @endforeach
                                @endif
                            </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-themes" role="tabpanel" aria-labelledby="pills-themes-tab">
                    <div id="themes">

                        <div class="col-md-12">
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
                                                <h4>{{ __('Get More Themes Addon') }}</h4>
                                                <div class="text-muted">{{ __('+35 Premium Themes Addon') }}</div>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="price">
                                                    <a class="btn btn-primary" href="https://workdo.io/product-category/theme-addon/?utm_source=ecom-main-file&utm_medium=superadmin&utm_campaign=superadmin-btn-theme-all" target="new">
                                                        {{ __('Themes Addon') }}
                                                    </a>
                                                </div>
                                                <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
                                                    <a href="{{ route('addon.create') }}" class="btn btn-primary">
                                                        <i class="ti ti-plus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <h4 class="mb-3"> {{ __('Installed Theme') }}</h4>
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
                                                                        <span>Disable</span>
                                                                    </a>
                                                                @else
                                                                    <a href="#!" class="dropdown-item module_change" data-id="{{ $value->theme_id }}">
                                                                        <span>Enable</span>
                                                                    </a>
                                                                @endif

                                                                <form action="{{ route('theme.enable') }}" method="POST" id="form_{{ $value->theme_id }}">
                                                                    @csrf
                                                                    <input type="hidden"
                                                                        name="name" value="{{ $value->theme_id }}">
                                                                </form>

                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['addon.destroy', $value->theme_id], 'class' => 'd-inline']) !!}
                                                                    <button type="button" class="dropdown-item show_confirm">
                                                                        <span class="text-danger">Remove</span>
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
                                                                <span class="badges bg-success">Enable</span>
                                                            @else
                                                                <span class="badges bg-danger">Disable</span>
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
                                <h4 class="mb-3"> {{ __('Buy More Themes') }}</h4>
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
                                                                <span class="badges bg-success">{{ __('Free Add-On') }}</span>
                                                            </small>
                                                            <div class="d-flex align-items-center justify-content-between gap-2">
                                                                <h4 class="text-capitalize">{{ $value[0] }} ({{ __('Free Add-On') }}) </h4>
                                                                @if(!in_array($value[0],['babycare','grocery','scent']))
                                                                    <a href="https://s3.ap-southeast-1.wasabisys.com/workdo-main-file.rajodiya/ecommercego/main_files/{{$value[0]}}/theme-addon/{{$value[0]}}.zip" target="_new" class="btn btn-outline-primary btn-sm" title="Free Download">{{ __('Free Download') }}
                                                                        <!-- <i class="fa fa-download"
                                                                                aria-hidden="true"></i> -->
                                                                            </a>
                                                                    @endif
                                                            </div>
                                                        </div>
                                                        <div class="product-content-bottom d-flex gap-2">
                                                            <a href="{{ $value[1] }}" target="_new"
                                                                class="btn btn-outline-primary w-100 mt-2">{{ __('View Demo') }}</a>
                                                                <a href="{{ $value[3] }}?utm_source=ecom-main-file&utm_medium=superadmin&utm_campaign=superadmin-btn-theme-{{ $value[0] }}" target="_new"
                                                                class="btn btn-outline-primary w-100 mt-2">{{ __('View Details') }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="pills-mobile-app" role="tabpanel" aria-labelledby="pills-mobile-app-tab">
                    <div id="mobile-app">
                        <div class="col-md-12">
                            <div class="row justify-content-center px-0">
                                <div class="col-12">
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
                                @foreach ($theme['apps'] as $key => $value)
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).on('click','.module_change',function(){
    var id = $(this).attr('data-id');
    $('#form_'+id).submit();
});
</script>
<script>
    if ($('#useradd-sidenav').length > 0) {
       var scrollSpy = new bootstrap.ScrollSpy(document.body, {
           target: '#useradd-sidenav',
           offset: 300
       })
   }
</script>
@endpush

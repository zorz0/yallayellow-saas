@extends('layouts.app')
@section('page-title')
    {{ __('Landing Page') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item">{{__('Landing Page')}}</li>
@endsection

@php
    $settings = \Modules\LandingPage\Entities\LandingPageSetting::settings();
    $logo= get_file('storage/uploads/landing_page_image');
@endphp



@push('custom-script')


<script src="{{ asset('Modules/LandingPage/Resources/assets/js/plugins/tinymce.min.js')}}" referrerpolicy="origin"></script>

@endpush

@section('breadcrumb')
    <li class="breadcrumb-item">{{__('Landing Page')}}</li>
@endsection


@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top" style="top:30px">
                        <div class="list-group list-group-flush" id="useradd-sidenav">

                            @include('landingpage::layouts.tab')


                        </div>
                    </div>
                </div>

                <div class="col-xl-9">
                    {{--  Start for all settings tab --}}


                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-10 col-md-10 col-sm-10">
                                        <h5>{{ __('Feature') }}</h5>
                                    </div>
                                </div>
                            </div>

                            {{ Form::open(array('route' => 'features.store', 'method'=>'post', 'enctype' => "multipart/form-data")) }}
                                @csrf
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('Title', __('Title'), ['class' => 'form-label']) }}
                                                {{ Form::text('feature_title',$settings['feature_title'], ['class' => 'form-control ', 'placeholder' => __('Enter Title')]) }}
                                                @error('mail_host')
                                                <span class="invalid-mail_driver" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('Heading', __('Heading'), ['class' => 'form-label']) }}
                                                {{ Form::text('feature_heading',$settings['feature_heading'], ['class' => 'form-control ', 'placeholder' => __('Enter Heading')]) }}
                                                @error('mail_host')
                                                <span class="invalid-mail_driver" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('Description', __('Description'), ['class' => 'form-label']) }}
                                                {{ Form::text('feature_description', $settings['feature_description'], ['class' => 'form-control', 'placeholder' => __('Enter Description')]) }}
                                                @error('mail_port')
                                                <span class="invalid-mail_port" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('Buy Now Link', __('Buy Now Link'), ['class' => 'form-label']) }}
                                                {{ Form::text('feature_buy_now_link', $settings['feature_buy_now_link'], ['class' => 'form-control', 'placeholder' => __('Enter Link')]) }}
                                                @error('mail_port')
                                                <span class="invalid-mail_port" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <button class="btn btn-print-invoice btn-primary m-r-10" type="submit" >{{ __('Save Changes') }}</button>
                                </div>
                            {{ Form::close() }}

                        </div>


                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col-lg-9 col-md-9 col-sm-9">
                                        {{-- <h5>{{ __('Menu Bar') }}</h5> --}}
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 justify-content-end d-flex">
                                        <a data-size="lg" data-url="{{ route('feature_create') }}" data-ajax-popup="true"   data-bs-toggle="tooltip" data-title="{{__('Create')}}"  class="btn btn-sm btn-primary">
                                            <i class="ti ti-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">

                                {{-- <div class="justify-content-end d-flex">

                                    <a data-size="lg" data-url="{{ route('users.create') }}" data-ajax-popup="true"  data-bs-toggle="tooltip" title="{{__('Create')}}"  class="btn btn-sm btn-primary">
                                        <i class="ti ti-plus text-light"></i>
                                    </a>
                                </div> --}}

                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>{{__('No')}}</th>
                                                <th>{{__('Name')}}</th>
                                                <th>{{__('Action')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           @if (is_array($feature_of_features) || is_object($feature_of_features))
                                           @php
                                               $ff_no = 1
                                           @endphp
                                                @foreach ($feature_of_features as $key => $value)
                                                    <tr>
                                                        <td>{{ $ff_no++ }}</td>
                                                        <td>{{ $value['feature_heading'] }}</td>
                                                        <td>
                                                            <span>
                                                                <div class="d-flex">
                                                                    <button class="btn btn-sm btn-primary me-2"
                                                                    data-url="{{ route('feature_edit',$key) }}" data-size="lg"
                                                                    data-ajax-popup="true" data-title="{{ __('Edit Page') }}">
                                                                    <i class="ti ti-pencil py-1" data-bs-toggle="tooltip" title="edit"></i>
                                                                    </button>
                                                                    {!! Form::open(['method' => 'GET', 'route' => ['feature_delete', $key], 'class' => 'd-inline']) !!}
                                                                    <button type="button" class="btn btn-sm btn-danger show_confirm">
                                                                    <i class="ti ti-trash text-white py-1"></i>
                                                                    </button>
                                                                    {!! Form::close() !!}
                                                                </div>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-10 col-md-10 col-sm-10">
                                        <h5>{{ __('Feature') }}</h5>
                                    </div>
                                </div>
                            </div>
                            {{ Form::open(array('route' => 'feature_highlight_create', 'method'=>'post', 'enctype' => "multipart/form-data")) }}
                                @csrf
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('highlight_feature_heading', __('Heading'), ['class' => 'form-label']) }}
                                                {{ Form::text('highlight_feature_heading', $settings['highlight_feature_heading'], ['class' => 'form-control', 'placeholder' => __('Enter Link')]) }}
                                                @error('highlight_feature_heading')
                                                    <span class="invalid-mail_port" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('highlight_feature_heading', __('Description'), ['class' => 'form-label']) }}
                                                {{ Form::text('highlight_feature_description', $settings['highlight_feature_description'], ['class' => 'form-control', 'placeholder' => __('Enter Link')]) }}
                                                @error('highlight_feature_description')
                                                <span class="invalid-mail_port" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('Logo', __('Logo'), ['class' => 'form-label']) }}
                                                <div class="logo-content mt-4">
                                                    <img id="image1" src="{{ $logo.'/'. $settings['highlight_feature_image'] }}"
                                                        class="big-logo">
                                                </div>
                                                <div class="choose-files mt-5">
                                                    <label for="highlight_feature_image">
                                                        <div class=" bg-primary dark_logo_update" style="cursor: pointer;"> <i class="ti ti-upload px-1">
                                                            </i>{{ __('Choose file here') }}
                                                        </div>
                                                        <input type="file" name="highlight_feature_image" id="highlight_feature_image" class="form-control file" data-filename="highlight_feature_image">
                                                    </label>
                                                </div>
                                                @error('highlight_feature_image')
                                                <div class="row">
                                                    <span class="invalid-logo" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <input class="btn btn-print-invoice btn-primary m-r-10" type="submit" value="{{ __('Save Changes') }}">
                                </div>
                            {{ Form::close() }}
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col-lg-9 col-md-9 col-sm-9">
                                        {{-- <h5>{{ __('Menu Bar') }}</h5> --}}
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 justify-content-end d-flex">
                                        <a data-size="lg" data-url="{{ route('features_create') }}" data-ajax-popup="true"  data-bs-toggle="tooltip" data-title="{{__('Create')}}"  class="btn btn-sm btn-primary">
                                            <i class="ti ti-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">

                                {{-- <div class="justify-content-end d-flex">

                                    <a data-size="lg" data-url="{{ route('users.create') }}" data-ajax-popup="true"  data-bs-toggle="tooltip" title="{{__('Create')}}"  class="btn btn-sm btn-primary">
                                        <i class="ti ti-plus text-light"></i>
                                    </a>
                                </div> --}}

                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>{{__('No')}}</th>
                                                <th>{{__('Name')}}</th>
                                                <th>{{__('Action')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (is_array($feature_of_features) || is_object($feature_of_features))
                                            @php
                                                $of_no = 1
                                            @endphp
                                                @foreach ($other_features as $key => $value)
                                                    <tr>
                                                        <td>{{ $of_no++ }}</td>
                                                        <td>{{ $value['other_features_heading'] }}</td>
                                                        <td>
                                                            <span>
                                                                <div class="d-flex">
                                                                    <button class="btn btn-sm btn-primary me-2"
                                                                    data-url="{{ route('features_edit',$key) }}" data-size="lg"
                                                                    data-ajax-popup="true" data-title="{{ __('Edit Page') }}">
                                                                    <i class="ti ti-pencil py-1" data-bs-toggle="tooltip" title="edit"></i>
                                                                    </button>
                                                                    {!! Form::open(['method' => 'GET', 'route' => ['features_delete', $key], 'class' => 'd-inline']) !!}
                                                                    <button type="button" class="btn btn-sm btn-danger show_confirm">
                                                                    <i class="ti ti-trash text-white py-1"></i>
                                                                    </button>
                                                                    {!! Form::close() !!}
                                                                </div>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    {{--  End for all settings tab --}}
                </div>
            </div>
        </div>
    </div>
@endsection




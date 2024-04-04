@extends('layouts.app')
@section('page-title')
    {{ __('Landing Page') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item">{{__('Landing Page')}}</li>
@endsection


@php

    $settings = \Modules\LandingPage\Entities\LandingPageSetting::settings();
    $logo=get_file('storage/uploads/landing_page_image');


@endphp

@push('custom-script')
    <script>
        document.getElementById('site_logo').onchange = function () {
                var src = URL.createObjectURL(this.files[0])
                document.getElementById('image').src = src
            }
    </script>

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
                                    <h5>{{ __('Home Section') }}</h5>
                                </div>
                            </div>
                        </div>

                        {{ Form::open(array('route' => 'custom_store', 'method'=>'post', 'enctype' => "multipart/form-data")) }}
                            <div class="card-body">
                                <div class="row">



                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('Site Logo', __('Site Logo'), ['class' => 'form-label']) }}
                                            <div class="logo-content mt-4">
                                                <img id="image" src="{{$logo.'/'. $settings['site_logo']}}"
                                                    class="big-logo"  style="filter: drop-shadow(2px 3px 7px #011C4B);">
                                            </div>
                                            <div class="choose-files mt-5">
                                                <label for="site_logo">
                                                    <div class=" bg-primary company_logo_update" style="cursor: pointer;">
                                                        <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                    </div>
                                                    <input type="file" name="site_logo" id="site_logo" class="form-control file" data-filename="site_logo">
                                                </label>
                                            </div>
                                            @error('site_logo')
                                                <div class="row">
                                                <span class="invalid-logo" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('Site Description', __('Site Description'), ['class' => 'form-label']) }}
                                            {{ Form::text('site_description', $settings['site_description'], ['class' => 'form-control', 'placeholder' => __('Enter Description')]) }}
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
                                <input class="btn btn-print-invoice btn-primary m-r-10" type="submit" value="{{ __('Save Changes') }}">
                            </div>
                        {{ Form::close() }}
                    </div>


                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col-lg-9 col-md-9 col-sm-9">
                                        <h5>{{ __('Menu Bar') }}</h5>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 justify-content-end d-flex">
                                        <a data-size="lg" data-url="{{ route('custom_page.create') }}" data-ajax-popup="true"  data-bs-toggle="tooltip" data-title="{{__('Create')}}"  class="btn btn-sm btn-primary">
                                            <i class="ti ti-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
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
                                            @if (is_array($pages) || is_object($pages))
                                                @php
                                                  $no = 1
                                                @endphp


                                                @foreach ($pages as $key => $value)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ $value['menubar_page_name'] }}</td>
                                                        <td>
                                                            <span>
                                                                <div class="d-flex">
                                                                    <button class="btn btn-sm btn-primary me-2"
                                                                        data-url="{{ route('custom_page.edit',$key) }}" data-size="lg"
                                                                            data-ajax-popup="true" data-title="{{ __('Edit Page') }}">
                                                                            <i class="ti ti-pencil py-1" data-bs-toggle="tooltip" title="edit"></i>
                                                                        </button>
                                                                    @if($value['page_slug'] != 'terms_and_conditions' && $value['page_slug'] != 'about_us' && $value['page_slug'] != 'privacy_policy')
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['custom_page.destroy', $key], 'class' => 'd-inline']) !!}
                                                                    <button type="button" class="btn btn-sm btn-danger show_confirm">
                                                                    <i class="ti ti-trash text-white py-1"></i>
                                                                    </button>
                                                                    {!! Form::close() !!}
                                                                    @endif
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




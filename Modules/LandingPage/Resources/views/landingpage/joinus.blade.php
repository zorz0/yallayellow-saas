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
        document.getElementById('home_banner').onchange = function () {
                var src = URL.createObjectURL(this.files[0])
                document.getElementById('image').src = src
            }
            document.getElementById('home_logo').onchange = function () {
                var src = URL.createObjectURL(this.files[0])
                document.getElementById('image1').src = src
            }
    </script>
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

                    {{Form::model(null, array('route' => array('join_us.store'), 'method' => 'POST')) }}
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <h5 class="mb-2">{{ __('Join User') }}</h5>
                                </div>
                                <div class="col switch-width text-end">
                                    <div class="form-group mb-0">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary" class="" name="joinus_status"
                                                id="joinus_status"  {{ $settings['joinus_status'] == 'on' ? 'checked="checked"' : '' }}>
                                            <label class="custom-control-label" for="joinus_status"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('Heading', __('Heading'), ['class' => 'form-label']) }}
                                        {{ Form::text('joinus_heading', $settings['joinus_heading'], ['class' => 'form-control', 'placeholder' => __('Enter Description')]) }}
                                        @error('mail_port')
                                        <span class="invalid-mail_port" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('Description', __('Description'), ['class' => 'form-label']) }}
                                        {{ Form::text('joinus_description', $settings['joinus_description'], ['class' => 'form-control', 'placeholder' => __('Enter Description')]) }}
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
                    </div>
                {{ Form::close() }}



                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-lg-9 col-md-9 col-sm-9">
                                    <h5>{{ __('Join Us User') }}</h5>
                                </div>
                                {{-- <div class="col-lg-3 col-md-3 col-sm-3 justify-content-end d-flex">
                                    <a data-size="lg" data-url="{{ route('testimonials_create') }}" data-ajax-popup="true"  data-bs-toggle="tooltip" title="{{__('Discover Feature Create')}}"  class="btn btn-sm btn-primary">
                                        <i class="ti ti-plus text-light"></i>
                                    </a>
                                </div> --}}
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
                                            <th>{{__('Email')}}</th>
                                            <th>{{__('Action')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>


                                        @if (is_array($join_us) || is_object($join_us))
                                            @foreach ($join_us as $key => $value)
                                                <tr>
                                                    <td>{{ $value->email }}</td>
                                                    <td>
                                                        <span>
                                                            <div class="action-btn ms-2">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['join_us.destroy', $value->id], 'class' => 'd-inline']) !!}
                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger show_confirm">
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




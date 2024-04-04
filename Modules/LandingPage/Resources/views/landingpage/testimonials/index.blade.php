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
                                        <h5>{{ __('Testimonials') }}</h5>
                                    </div>
                                </div>
                            </div>

                            {{ Form::open(array('route' => 'testimonials.store', 'method'=>'post', 'enctype' => "multipart/form-data")) }}
                                @csrf
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('Heading', __('Heading'), ['class' => 'form-label']) }}
                                                {{ Form::text('testimonials_heading',$settings['testimonials_heading'], ['class' => 'form-control ', 'placeholder' => __('Enter Heading')]) }}
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
                                                {{ Form::text('testimonials_description', $settings['testimonials_description'], ['class' => 'form-control', 'placeholder' => __('Enter Description')]) }}
                                                @error('testimonials_description')
                                                <span class="invalid-testimonials_description" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {{ Form::label('Long Description', __('Long Description'), ['class' => 'form-label']) }}
                                                {{ Form::textarea('testimonials_long_description', $settings['testimonials_long_description'], ['class' => 'form-control', 'placeholder' => __('Enter Long Description')]) }}
                                                @error('testimonials_long_description')
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
                                        <a data-size="lg" data-url="{{ route('testimonials_create') }}" data-ajax-popup="true"  data-bs-toggle="tooltip" data-title="{{__('Discover Feature Create')}}"  class="btn btn-sm btn-primary">
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
                                           @if (is_array($testimonials) || is_object($testimonials))
                                            @php
                                                $no = 1
                                            @endphp
                                                @foreach ($testimonials as $key => $value)
                                                    <tr>
                                                        <td>{{ $no }}</td>
                                                        <td>{{ $value['testimonials_title'] }}</td>
                                                        <td>
                                                            <span>
                                                                <div class="d-flex">
                                                                    <button class="btn btn-sm btn-primary me-2"
                                                                    data-url="{{ route('testimonials_edit',$key) }}"   data-size="lg"
                                                                        data-ajax-popup="true" data-title="{{ __('Edit Page') }}">
                                                                        <i class="ti ti-pencil py-1" data-bs-toggle="tooltip" title="edit"></i>
                                                                    </button>
                                                                    {!! Form::open(['method' => 'GET', 'route' => ['testimonials_delete', $key], 'class' => 'd-inline']) !!}
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




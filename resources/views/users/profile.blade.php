@extends('layouts.app')

@php
    $profile = asset(Storage::url('uploads/profile/'));
@endphp

@section('page-title')
    {{ __('Profile') }}
@endsection

@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block text-white font-weight-400 mb-0"> {{ __('Profile') }}</h5>
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Profile') }}</li>
@endsection

@section('action-btn')
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-xl-3">
                <div class="card sticky-top" style="top:30px">
                    <div class="list-group list-group-flush" id="useradd-sidenav">
                        <a href="#Personal_Info" id="Personal_Info_tab"
                            class="list-group-item list-group-item-action border-0">{{ __('Personal Info') }} <div
                                class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>

                        <a href="#Change_Password" id="Change_Password_tab"
                            class="list-group-item list-group-item-action border-0">{{__('Change Password')}}<div
                                class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-9">
                    <div class="active" id="Personal_Info">
                        {{Form::model($userDetail,array('route' => array('update.account'), 'method' => 'POST', 'enctype' => "multipart/form-data"))}}
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 col-sm-12 col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>{{ __('Personal Info') }}</h5>
                                    </div>
                                    <div class="card-body pb-0">
                                        <div class=" setting-card">
                                            <div class="row">
                                                <div class="col-lg-4 col-sm-6 col-md-6">
                                                    <div class="card-body pt-0 text-center">
                                                        <div class=" setting-card">
                                                            <h4>{{__('Picture')}}</h4>
                                                            <div class="logo-content mt-4 d-flex justify-content-center">
                                                                    <img src="{{(!empty($userDetail->profile_image))? get_file($userDetail->profile_image , APP_THEME()) : $profile.'/avatar.png'}}"
                                                                    class=" rounded-circle-avatar" width="100px">
                                                            </div>
                                                            <div class="choose-files mt-4">
                                                                <label for="file-1">
                                                                    <div class=" bg-primary profile_update" style="max-width: 100% !important;"> <i
                                                                            class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                                    </div>
                                                                    <input type="file" class="form-control file" name="profile_image" id="file-1"
                                                                        data-filename="profile_update">
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-8 col-sm-6 col-md-6">
                                                    <div class="card-body pt-0">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                {{Form::label('name',__('Name'),array('class'=>'col-form-label')) }}
                                                                {{Form::text('name',null,array('class'=>'form-control font-style'))}}
                                                                @error('name')
                                                                <span class="invalid-name" role="alert">
                                                                        <strong class="text-danger">{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            {{Form::label('email',__('Email'),array('class'=>'col-form-label')) }}
                                                            {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter User Email')))}}
                                                            @error('email')
                                                            <span class="invalid-email" role="alert">
                                                                    <strong class="text-danger">{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="col-sm-12 ">
                                            <div class="text-end">
                                                {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary']) }}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        {{Form::close()}}
                    </div>

                    <div class="" id="Change_Password">
                        {!! Form::model(Auth::guard()->user(), [
                            'route' => ['user.password.update'],
                            'method' => 'PUT',
                        ]) !!}
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 col-sm-12 col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>{{ __('Change Password') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    {{Form::label('old_password',__('Current Password'),array('class'=>'col-form-label')) }}
                                                    {{Form::password('old_password',array('class'=>'form-control','placeholder'=>__('Enter Current Password')))}}
                                                    @error('old_password')
                                                    <span class="invalid-old_password" role="alert">
                                                         <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    {{Form::label('new_password',__('New Password'),array('class'=>'col-form-label')) }}
                                                    {{Form::password('new_password',array('class'=>'form-control','placeholder'=>__('Enter New Password')))}}
                                                    @error('new_password')
                                                    <span class="invalid-new_password" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                {{Form::label('new_password_confirmation',__('Re-type New Password'),array('class'=>'col-form-label')) }}
                                                {{Form::password('new_password_confirmation',array('class'=>'form-control','placeholder'=>__('Enter Re-type New Password')))}}
                                                @error('new_password_confirmation')
                                                <span class="invalid-new_password_confirmation" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    {!! Form::hidden('type', Auth::guard()->user()->type) !!}
                                    <div class="card-footer">
                                        <div class="col-sm-12 ">
                                            <div class="text-end">
                                                {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
            </div>
        </div>
        <!-- [ sample-page ] end -->
    </div>
</div>

@endsection
@push('custom-script')
    <script>
        $(document).on('click', '.list-group-item', function() {
            $('.list-group-item').removeClass('active');
            $('.list-group-item').removeClass('text-primary');
            setTimeout(() => {
                $(this).addClass('active').removeClass('text-primary');
            }, 10);
        });

        var type = window.location.hash.substr(1);
        $('.list-group-item').removeClass('active');
        $('.list-group-item').removeClass('text-primary');
        if (type != '') {
            $('a[href="#' + type + '"]').addClass('active').removeClass('text-primary');
        } else {
            $('.list-group-item:eq(0)').addClass('active').removeClass('text-primary');
        }




        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        })
    </script>
@endpush

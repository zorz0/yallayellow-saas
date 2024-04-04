@extends('layouts.app')
@section('page-title')
    {{$emailTemplate->name }}
@endsection
<link rel="stylesheet" href="{{asset('assets/css/summernote/summernote-bs4.css')}}">
@push('custom-script')
    <script src="{{ asset('assets/css/summernote/summernote-bs4.js') }}"></script>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Email Template') }}</li>
@endsection
@section('action-button')
<div class="d-flex flex-wrap justify-content-lg-end drp-languages">
    <ul class="list-unstyled mb-0 m-2">
        <li class="dropdown dash-h-item drp-language ">
            <a class="dash-head-link dropdown-toggle arrow-none me-0 bg-info rounded-1" data-bs-toggle="dropdown"
                href="#" role="button" aria-haspopup="false" aria-expanded="false"
                id="dropdownLanguage">
                <span class="drp-text hide-mob">{{ Str::upper($currEmailTempLang->language) }}</span>
                <i class="ti ti-chevron-down drp-arrow nocolor email_arrow"></i>
            </a>
            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end"
                aria-labelledby="dropdownLanguage">
                @foreach ($languages as $key => $lang)
                    <a href="{{ route('manage.email.language', [$emailTemplate->id, $key]) }}"
                        class="dropdown-item {{ $currEmailTempLang->language == $key ? 'text-primary' : '' }}">{{ Str::upper($lang) }}</a>
                @endforeach
            </div>
        </li>
    </ul>
    <ul class="list-unstyled mb-0 m-2">
        <li class="dropdown dash-h-item drp-language ">
            <a class="dash-head-link dropdown-toggle arrow-none me-0 bg-warning rounded-1" data-bs-toggle="dropdown"
                href="#" role="button" aria-haspopup="false" aria-expanded="false"
                id="dropdownLanguage">
                <span
                    class="drp-text hide-mob">{{ __('Template: ') }}{{ $emailTemplate->name }}</span>
                <i class="ti ti-chevron-down drp-arrow nocolor"></i>
            </a>
            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end"
                aria-labelledby="dropdownLanguage">
                @foreach ($EmailTemplates as $EmailTemplate)
                    <a href="{{ route('manage.email.language', [$EmailTemplate->id,Request::segment(4) ? Request::segment(4) : auth()->user()->language]) }}"
                        class="dropdown-item {{$EmailTemplate->name == $emailTemplate->name ? 'text-primary' : '' }}">{{ $EmailTemplate->name }}</a>
                @endforeach
            </div>
        </li>

    </ul>
</div>
@endsection

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body ">
                    {{-- <div class="card"> --}}
                        {{ Form::model($currEmailTempLang, ['route' => ['updateEmail.settings', $currEmailTempLang->parent_id], 'method' => 'PUT']) }}
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <h6 class="font-weight-bold pb-1">{{__('Place Holder')}}</h6>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row text-xs">
                                        <p class="mb-1">{{ __('App Name') }} : <span
                                            class="pull-right text-primary">{app_name}</span></p>
                                    <p class="mb-1">{{ __('Order Id') }} : <span
                                            class="pull-right text-primary">{order_id}</span></p>
                                    <p class="mb-1">{{ __('Order Status') }} : <span
                                            class="pull-right text-primary">{order_status}</span></p>
                                    <p class="mb-1">{{ __('Order URL') }} : <span
                                            class="pull-right text-primary">{order_url}</span></p>
                                    <p class="mb-1">{{ __('Order Id') }} : <span
                                            class="pull-right text-primary">{order_id}</span></p>
                                    <p class="mb-1">{{ __('Order Date') }} : <span
                                            class="pull-right text-primary">{order_date}</span></p>
                                    <p class="mb-1">{{ __('Owner Name') }} : <span
                                            class="pull-right text-primary">{owner_name}</span></p>
                                    <p class="mb-1">{{ __('Cart Data') }} : <span
                                        class="pull-right text-primary">{cart_table}</span></p>
                                    <p class="mb-1">{{ __('Wishlist Data') }} : <span
                                        class="pull-right text-primary">{wishlist_table}</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-6">
                            {{ Form::label('subject', __('Subject'), ['class' => 'col-form-label text-dark']) }}
                            {{ Form::text('subject', null, ['class' => 'form-control font-style', 'required' => 'required']) }}
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('from', __('From'), ['class' => 'col-form-label text-dark']) }}
                            {{ Form::text('from', $emailTemplate->from, ['class' => 'form-control font-style', 'required' => 'required']) }}
                        </div>


                        <div class="form-group col-12">
                            {{ Form::label('content', __('Email Message'), ['class' => 'col-form-label text-dark']) }}
                            {{ Form::textarea('content', $currEmailTempLang->content, ['class' => 'summernote-simple', 'required' => 'required']) }}
                        </div>


                        <div class="modal-footer">
                            {{ Form::hidden('language', null) }}
                            {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary']) }}
                        </div>

                        {{ Form::close() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

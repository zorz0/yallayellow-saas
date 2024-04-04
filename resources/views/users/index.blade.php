@extends('layouts.app')

@section('page-title')
    {{ __('Users') }}
@endsection

@php
    $logo = asset(Storage::url('uploads/profile/'));
@endphp

@section('breadcrumb')
    <li class="breadcrumb-item" aria-current="page">{{ __('Users') }}</li>
@endsection

@section('action-button')
    {{-- @permission('Create User') --}}
        <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
            <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="Add User"
                data-url="{{ route('users.create') }}" data-toggle="tooltip" title="{{ __('Add User') }}">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    {{-- @endpermission --}}
@endsection

@section('content')
    <div class="row">
        @foreach ($users as $user)
            <div class="col-lg-3 col-sm-6 col-md-6">
                <div class="card text-center">
                    <div class="card-header border-0 pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <div class="badge p-2 px-3 rounded-1 bg-primary">{{ ucfirst($user->type) }}</div>
                            </h6>
                        </div>
                            <div class="card-header-right">
                                <div class="btn-group card-option">
                                    <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="feather icon-more-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        {{-- @permission('Edit User') --}}
                                            <a href="#" class="dropdown-item"
                                                data-url="{{ route('users.edit', $user->id) }}" data-size="md"
                                                data-ajax-popup="true" data-title="{{ __('Update User') }}">
                                                <i class="ti ti-edit"></i>
                                                <span class="ms-2">{{ __('Edit') }}</span>
                                            </a>
                                        {{-- @endpermission --}}
                                        {{-- @permission('Reset Password') --}}
                                            <a href="#" class="dropdown-item"
                                                data-url="{{ route('stores.reset.password', \Crypt::encrypt($user->id)) }}"
                                                data-ajax-popup="true" data-size="md" data-title="{{ __('Change Password') }}">
                                                <i class="ti ti-key"></i>
                                                <span class="ms-2">{{ __('Reset Password') }}</span>
                                            </a>
                                        {{-- @endpermission --}}
                                        {{-- @permission('Delete User') --}}
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user->id], 'class' => 'd-inline']) !!}
                                            <a href="#" class="bs-pass-para dropdown-item show_confirm"
                                                data-confirm-yes="delete-form-{{ $user->id }}"><i class="ti ti-trash"></i>
                                                <span class="ms-2">{{ __('Delete') }}</span>
                                            </a>
                                            {!! Form::close() !!}
                                        {{-- @endpermission --}}
                                        {{-- @permission('Mange User Login') --}}
                                        @if ($user->is_enable_login == 1)
                                            <a href="{{ route('users.enable.login', \Crypt::encrypt($user->id)) }}"
                                                class="dropdown-item">
                                                <i class="ti ti-road-sign"></i>
                                                <span class="text-danger"> {{ __('Login Disable') }}</span>
                                            </a>
                                        @elseif ($user->is_enable_login == 0 && $user->password == null)
                                            <a href="#" data-url="{{ route('stores.reset.password', \Crypt::encrypt($user->id)) }}"
                                                data-ajax-popup="true" data-size="md" class="dropdown-item login_enable"
                                                data-title="{{ __('New Password') }}" class="dropdown-item">
                                                <i class="ti ti-road-sign"></i>
                                                <span class="text-success"> {{ __('Login Enable') }}</span>
                                            </a>
                                        @else
                                            <a href="{{ route('users.enable.login', \Crypt::encrypt($user->id)) }}"
                                                class="dropdown-item">
                                                <i class="ti ti-road-sign"></i>
                                                <span class="text-success"> {{ __('Login Enable') }}</span>
                                            </a>
                                        @endif
                                        {{-- @endpermission --}}
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="card-body">
                        <div class="img-fluid rounded-circle card-avatar pb-4   ">
                            <a href="{{ !empty($user->profile_image) ? asset('uploads/profile/' . $user->profile_image) : asset('storage/uploads/profile/avatar.png') }}"
                                target="_blank">
                                <img src="{{ !empty($user->profile_image) ? asset('uploads/profile/' . $user->profile_image) : asset('storage/uploads/profile/avatar.png') }}"
                                    class="img-user wid-150 round-img rounded-circle">
                            </a>
                        </div>
                        <h4 class="mt-2 text-primary">{{ $user->name }}</h4>
                        <small class="">{{ $user->email }}</small>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col-md-3">
            {{-- @permission('Create User') --}}
                <a class="btn-addnew-project" data-url="{{ route('users.create') }}" data-title="{{ __('Add User') }}"
                    data-ajax-popup="true" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Create') }}">
                    <div class="bg-primary proj-add-icon">
                        <i class="ti ti-plus f-30"></i>
                    </div>
                    <h6 class="mt-4 mb-2">{{ __('New User') }}</h6>
                    <p class="text-muted text-center">{{ __('Click here to add New User') }}</p>
                </a>
            {{-- @endpermission --}}
        </div>
    </div>
@endsection

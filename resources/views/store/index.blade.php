@extends('layouts.app')

@section('page-title', __('Users'))

@section('action-button')
    <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
        <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="Create New User"
            data-url="{{ route('stores.create') }}" data-toggle="tooltip" title="{{ __('Create New User') }}">
            <i class="ti ti-plus"></i>
        </a>
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Users') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xxl-12">
            <div class="row">
                @foreach ($users as $user)
                    <div class="col-md-3 mb-4">
                        <div class="card text-center card-2">
                            <div class="card-header border-0 pb-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        @if (auth()->user()->type == 'super admin')
                                            @if (!empty($user->currentPlan))
                                            <div class="badge bg-primary p-2 px-3 rounded">
                                                {{ !empty($user->currentPlan) ? $user->currentPlan->name : '' }}
                                            </div>
                                            @endif
                                        @else
                                            <div class="badge bg-primary p-2 px-3 rounded">
                                                {{ ucfirst($user->type) }}
                                            </div>
                                        @endif
                                    </h6>
                                </div>
                                <div class="card-header-right">
                                    <div class="btn-group card-option">
                                        {{-- @if ($user->is_active == 1 && $user->is_disable == 1) --}}
                                            <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>

                                            <div class="dropdown-menu dropdown-menu-end">

                                                @if (auth()->user()->type == 'super admin')
                                                    <a href="#!" data-size="lg"
                                                        data-url="{{ route('users.edit', $user->id) }}"
                                                        data-ajax-popup="true" class="dropdown-item"
                                                        data-title="Edit User"
                                                        title="{{ \Auth::user()->type == 'super admin' ?  __('Edit User')  : __('Edit User') }}">
                                                        <i class="ti ti-pencil"></i>
                                                        <span>{{ __('Edit') }}</span>
                                                    </a>
                                                @endif

                                                @if (auth()->user()->type == 'super admin')
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user->id], 'class' => 'd-inline']) !!}
                                                    <a href="#" class="dropdown-item bs-pass-para show_confirm"
                                                        data-confirm-yes="delete-form-{{ $user->id }}"><i class="ti ti-trash"></i><span class="ms-2">{{ __('Delete') }}</span>
                                                    </a>
                                                    {!! Form::close() !!}
                                                @endif

                                                @if (auth()->user()->type == 'super admin')
                                                    <a href="{{ route('login.with.admin',$user->id) }}"
                                                        class="dropdown-item"
                                                        data-bs-original-title="{{ __('Login As Company') }}">
                                                        <i class="ti ti-replace"></i>
                                                        <span> {{ __('Login As Admin') }}</span>
                                                    </a>
                                                @endif

                                                <a href="#" class="dropdown-item"
                                                    data-url="{{ route('stores.link', $user->id) }}" data-size="md"
                                                    data-ajax-popup="true" data-title="{{ __('Store Links') }}">
                                                    <i class="ti ti-unlink py-1" data-bs-toggle="tooltip" title="Store Links"></i>
                                                    <span> {{ __('Store Link') }}</span>
                                                </a>


                                                <a href="#"
                                                    data-url="{{ route('stores.reset.password', \Crypt::encrypt($user->id)) }}"
                                                    data-ajax-popup="true" data-size="md" class="dropdown-item"
                                                    data-bs-original-title="{{ __('Reset Password') }}">
                                                    <i class="ti ti-adjustments"></i>
                                                    <span> {{ __('Reset Password') }}</span>
                                                </a>

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
                                               
                                            </div>
                                        {{-- @else
                                            <a href="#" class="action-item text-lg"><i class="ti ti-lock"></i></a>
                                        @endif --}}

                                    </div>
                                </div>
                            </div>

                            <div class="card-body full-card">
                                <div class="img-fluid rounded-circle card-avatar">
                                    <img src="{{ !empty($user->profile_image) ? asset($user->profile_image) : asset('storage/uploads/profile/avatar.png') }}"
                                        class="img-user wid-80 round-img rounded-circle">
                                </div>
                                <h4 class=" mt-3 text-primary">{{ $user->name }}</h4>
                                @if ($user->is_active == 0)
                                    <h5 class="office-time mb-0">{{ __('In Active') }}</h5>
                                @endif
                                <small class="text-primary">{{ $user->email }}</small>
                                <br>
                                <small class="text-primary">{{ $user->mobile }}</small>

                                <p></p>
                                <div class="text-center" data-bs-toggle="tooltip" title="{{ __('Last Login') }}">
                                   
                                </div>
                                @if (\Auth::user()->type == 'super admin')
                                    <div class="mt-4">
                                        <div class="row justify-content-between align-items-center">
                                            <div class="col-6 text-center Id ">
                                                <a href="#" data-url="{{ route('plan.upgrade', $user->id) }}"
                                                    data-size="lg" data-ajax-popup="true" class="btn btn-outline-primary"
                                                    data-title="{{ __('Upgrade Plan') }}">{{ __('Upgrade Plan') }}</a>
                                            </div>
                                            <div class="col-6 text-center Id ">
                                                <a href="#" data-url="{{ route('user.info', $user->id) }}"
                                                    data-size="lg" data-ajax-popup="true" class="btn btn-outline-primary"
                                                    data-title="{{ __('Company Info') }}">{{ __('AdminHub') }}</a>
                                            </div>
                                            <div class="col-12">
                                                <hr class="my-3">
                                            </div>
                                            <div class="col-12 text-center pb-2">
                                                <span class="text-dark text-xs">{{ __('Plan Expired : ') }}
                                                    {{ !empty($user->plan_expire_date) ? auth()->user()->dateFormat($user->plan_expire_date) : __('Lifetime') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12 col-sm-12">
                                            <div class="card mb-0">
                                                <div class="card-body p-3">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <p class="text-muted text-sm mb-0" data-bs-toggle="tooltip"
                                                                title="{{ __('Users') }}"><i
                                                                    class="ti ti-users card-icon-text-space"></i>{{ $user->totalStoreUser($user->id) }}
                                                            </p>
                                                        </div>
                                                        <div class="col-6">
                                                            <p class="text-muted text-sm mb-0" data-bs-toggle="tooltip"
                                                                title="{{ __('Customers') }}"><i
                                                                    class="ti ti-users card-icon-text-space"></i>{{ $user->totalStoreCustomer($user->current_store) }}
                                                            </p>
                                                        </div>
                                                        {{--<div class="col-4">
                                                            <p class="text-muted text-sm mb-0" data-bs-toggle="tooltip"
                                                                title="{{ __('Vendors') }}"><i
                                                                    class="ti ti-users card-icon-text-space"></i>{{ $user->totalStoreVender($user->id) }}
                                                            </p>
                                                        </div> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

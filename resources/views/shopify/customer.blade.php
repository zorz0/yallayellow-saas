@extends('layouts.app')

@section('page-title')
    {{ __(' Customers') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item" aria-current="page">{{ __(' Customers') }}</li>
@endsection

@section('action-button')
@endsection

@php
    $customer_avatar = asset(Storage::url('uploads/customerprofile/avatar.png'));
@endphp

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table mb-0 dataTable" id="pc-dt-export">
                            <thead>
                                <tr>
                                    <th class="ignore"> {{__('Customer Avatar')}}</th>
                                    <th> {{__('Name')}}</th>
                                    <th> {{__('Email')}}</th>
                                    <th> {{__('Phone No')}}</th>
                                    <th class="text-right ignore"> {{__('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers['customers'] as $data)

                                    <tr class="font-style">
                                        <td class="ignore">
                                            <div class="media align-items-center">
                                                <div>
                                                    <img src="{{ $customer_avatar }}" class="wid-40 rounded-circle">
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $data['first_name'] }}</td>
                                        <td>{{ $data['email']}}</td>
                                        <td>{{ !empty($data['addresses']) ? $data['default_address']['phone'] : '' }}</td>
                                        <td class="Action ignore">

                                        @if ( in_array($data['email'],$upddata))
                                        {{-- @permission('Edit Shopify Customer') --}}
                                            <a href="{{ route('shopify_customer.edit', $data['id']) }}"  class="btn btn-sm btn-primary"
                                                data-title="{{ __('Sync Again') }}" >
                                                <i class="ti ti-refresh " data-bs-toggle="tooltip" title="Sync Again"></i>
                                            </a>
                                            {{-- @endpermission --}}
                                        @else
                                        {{-- @permission('Create Shopify Customer') --}}
                                            <a href="{{ route('shopify_customer.show', $data['id']) }}" class="btn btn-sm btn-primary"
                                                data-title="Add Customer"
                                                data-toggle="tooltip" title="{{ __('Create Main Customer') }}">
                                                <i class="ti ti-plus" data-bs-toggle="tooltip" title="Add Customer"></i>
                                            </a>
                                            {{-- @endpermission --}}
                                        @endif
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



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
    $customer_avatar = asset(Storage::url('uploads/customerprofile/'));
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
                                @foreach ($jsonData as $customer)
                                    <tr class="font-style">
                                        <td class="ignore">
                                            <div class="media align-items-center">
                                                <div>
                                                    <img src="{{ !empty($customer->avatar_url) ?  $customer->avatar_url : get_file($customer->avatar_url, APP_THEME()) }}" class="wid-40 rounded-circle">
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $customer->first_name }}</td>
                                        <td>{{ $customer->email}}</td>
                                        <td>{{ !empty($customer->billing) ? $customer->billing->phone : $customer->shipping->phone }}</td>
                                        <td class="Action ignore">
                                        @if ( in_array($customer->id,$upddata))
                                        {{-- @permission('Edit Woocommerce Customer') --}}
                                            <a href="{{ route('woocom_customer.edit', $customer->id) }}"  class="btn btn-sm btn-primary"
                                                data-title="{{ __('Sync Again') }}" >
                                                <i class="ti ti-refresh " data-bs-toggle="tooltip" title="Sync Again"></i>
                                            </a>
                                            {{--@endpermission--}}
                                        @else
                                        {{-- @permission('Create Woocommerce Customer') --}}
                                            <a href="{{ route('woocom_customer.show', $customer->id) }}" class="btn btn-sm btn-primary"
                                                data-title="Add Customer"
                                                data-toggle="tooltip" title="{{ __('Create Main Customer') }}">
                                                <i class="ti ti-plus" data-bs-toggle="tooltip" title="Add Customer"></i>
                                            </a>
                                            {{--@endpermission--}}
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

@push('custom-script')
    <script src="https://rawgit.com/unconditional/jquery-table2excel/master/src/jquery.table2excel.js"></script>
    <script>
        const d = new Date();
        let seconds = d.getSeconds();
        $('.csv').on('click', function() {
            $('.ignore').remove();
            $("#pc-dt-export").table2excel({
                filename: "Customer_" + seconds
            });
            window.location.reload();
        })
    </script>
@endpush

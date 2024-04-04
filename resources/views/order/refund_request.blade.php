@extends('layouts.app')

@section('page-title', __('Order Refund Request'))

@section('action-button')
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Order Refund Request') }}</li>
@endsection

@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                <h5></h5>
                <div class="table-responsive">
                    <table class="table dataTable">
                        <thead>
                            <tr>
                                <th>{{ __('Order ID') }}</th>
                                <th>{{ __('Refund Request Date') }}</th>
                                <th>{{ __('Refund Request Status') }}</th>
                                <th class="text-end">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($refund_requests as $refund_request)
                                @php
                                    $order_id = $refund_request->order_id;
                                    $order_refund_details = \App\Models\Order::order_detail($order_id);
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ route('refund-request.show', \Illuminate\Support\Facades\Crypt::encrypt($order_id)) }}"
                                                @php
                                                    $btn_class = 'bg-info';
                                                    if($refund_request->refund_status == 'Cancel') {
                                                        $btn_class = 'bg-danger';
                                                    } elseif($refund_request->refund_status == 'Accept') {
                                                        $btn_class = 'bg-success';
                                                    } elseif($refund_request->refund_status == 'Refunded') {
                                                        $btn_class = 'bg-warning';
                                                    }
                                                @endphp

                                                class="btn {{ $btn_class }}  text-white btn-sm text-sm"
                                                data-toggle="tooltip" title="{{ __('Invoice ID') }}">
                                                <span class="btn-inner--icon"></span>
                                                <span class="btn-inner--text">{{ $order_refund_details['order_id'] ?? '' }}</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($refund_request['created_at'])->format('Y-m-d') }}</td>
                                    <td>
                                        @if ($refund_request->refund_status == 'Cancel')
                                            <span class="badge badge-80 rounded p-2 f-w-600  bg-light-danger">
                                                {{ $refund_request['refund_status'] }}</span>
                                        @elseif ($refund_request->refund_status == 'Processing')
                                            <span class="badge badge-80 rounded p-2 f-w-600  bg-light-info">
                                                {{ $refund_request['refund_status'] }}</span>
                                        @elseif ($refund_request->refund_status == 'Refunded')
                                            <span class="badge badge-80 rounded p-2 f-w-600  bg-light-warning">
                                                {{ $refund_request['refund_status'] }}</span>
                                        @else
                                            <span class="badge badge-80 rounded p-2 f-w-600  bg-light-success">
                                                {{ $refund_request['refund_status'] }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('refund-request.show', \Illuminate\Support\Facades\Crypt::encrypt($order_id)) }}"
                                            class="btn btn-sm btn-info me-2">
                                            <i class="ti ti-eye py-1" data-bs-toggle="tooltip" title="view"></i>
                                        </a>
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

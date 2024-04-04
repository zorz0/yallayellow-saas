<table class="table datatable">
    <tr>
        <th>{{__('Order Id')}}</th>
        <td>{{ $order->order_id }}</td>
    </tr>
    <tr>
        <th>{{__('Plan Name ')}}</th>
        <td>{{$order->plan_name }}</td>
    </tr>
    <tr>
        <th>{{__('Plan Price')}}</th>
        <td>{{$order->price }}</td>
    </tr>
    <tr>
        <th>{{__('Payment Type')}}</th>
        <td>{{ $order->payment_type }}</td>
    </tr>
    <tr>
        <th>{{__('Payment Status')}}</th>
        <td>{{ $order->payment_status }}</td>
    </tr>
    <tr>
        <th>{{__('Bank Details')}}</th>
        <td>{!! $admin_payment_setting['bank_transfer']!!}</td>
    </tr>
    <tr>
        <th>{{__('Payment Receipt')}}</th>
        <td>
            <a href="{{ asset($order->receipt) }}"
                class="btn btn-sm btn-primary me-2"
                download="" data-bs-toggle="tooltip" title="Download">
            <span class="text-white"><i class="ti ti-download"></i></span>
            </a>
        </td>
    </tr>
</table>

<div class="modal-footer pr-0">
    <a href="{{ route('order.approve', [$order->id]) }}" class="btn btn-success">{{ __('Approval') }}</a>
    <a href="{{ route('order.reject', [$order->id]) }}"  class="btn btn-danger" >{{ __('Reject') }}</a>
</div>
@extends('layouts.app')

@section('page-title', __('Coupon Detail'))

@section('action-button')
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('coupon.index') }}">{{ __('Coupon') }}</a></li>
    <li class="breadcrumb-item">{{ __('Coupon Detail') }}</li>
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
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Order ID') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($UserCoupons as $UserCoupon)
                            <tr>
                                <td>{{ $UserCoupon->CouponData->coupon_name }}</td>
                                <td>{{ '#'.$UserCoupon->OrderData->product_order_id }}</td>
                                <td>{{ $UserCoupon->amount }}</td>
                                <td>{{ $UserCoupon->date_used }}</td>
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
<script>
    var doc = new jsPDF();
    var elementHandler = {
    '#ignorePDF': function (element, renderer) {
        return true;
    }
    };
    var source = window.document.getElementsByTagName("body")[0];
    doc.fromHTML(
        source,
        15,
        15,
        {
        'width': 180,'elementHandlers': elementHandler
        });

    doc.output("dataurlnewwindow");
</script>
@endpush

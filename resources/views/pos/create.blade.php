
@php
    $logo= get_file('uploads/logo');
    $store_id = \App\Models\Store::where('id', getCurrentStore())->first();
    $Tax =  \App\Models\Tax::where('store_id', getCurrentStore())->where('theme_id', APP_THEME())->first();
@endphp
@if (!empty($sales) && count($sales['data']) > 0)
    <div class="card">
        <div class="card-body">
            <div class="row mt-2">
                <div class="col-6">
                    {{-- <img src="{{isset($company_logo) && !empty($company_logo) ? $company_logo : $logo.'/logo-dark.svg'}}" width="120px;"> --}}
                </div>

            </div>
            <div id="printableArea">
                <div class="row mt-3">
                    <div class="col-6">
                        <h1 class="invoice-id h6">#{{ $details['pos_id'] }}</h1>
                        <div class="date"><b>{{ __('Date') }}: </b>{{ $details['date'] }}</div>
                    </div>
                    <div class="col-6 text-end">
                        <div class="text-dark "><b>{{ __('Store Name') }}: </b>
                            {!! $details['store']['name'] !!}
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col contacts billed-details d-flex justify-content-between pb-4">
                        <div class="invoice-to billed-to">
                            <div class="text-dark  h6"><b>{{ __('Billed To :') }}</b>
                                {!! $details['customer']['details'] !!}
                            </div>
                        </div>
                        @if(!empty( $details['customer']['shippdetails']))
                            <div class="invoice-to billed-to ">
                                <div class="text-dark h6"><b>{{ __('Shipped To :') }}</b></div>
                                {{--  <span class="ms-2 billed-to">

                            </span>  --}}
                            {!! $details['customer']['shippdetails'] !!}
                            </div>
                        @endif
                        <div class="company-details  ">
                            <div class="text-dark h6 me-2"><b>{{ __('From:') }}</b> {!! $details['user']['details'] !!}</div>


                        </div>
                    </div>
                </div>
                <div class="row" style="overflow-x: auto ">
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="text-left">{{ __('Items') }}</th>
                            <th>{{ __('Quantity') }}</th>
                            <th class="text-right">{{ __('Price') }}</th>
                            <th class="text-right">{{ __('Tax Amount') }}</th>
                            <th class="text-right">{{ __('Total') }}</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach ($sales['data'] as $key => $value)
                            <tr>
                                <td class="cart-summary-table text-left">
                                    {{ $value['name'] }}
                                </td>
                                <td class="cart-summary-table">
                                    {{ $value['quantity'] }}
                                </td>
                                <td class="text-right cart-summary-table">
                                    {{ $value['orignal_price'] }}
                                </td>
                                <td class="text-right cart-summary-table">
                                    <span class='badge badge-primary'> {{ $value['tax_amount'] }}%</span><br>
                                </td>

                                <td class="text-right cart-summary-table">
                                    {{ $value['total_orignal_price'] }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td class="">{{ __('Sub Total') }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-right">{{ $sales['sub_total'] }}</td>
                        </tr>
                        <tr>
                            <td class="">{{ __('Discount') }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-right">{{ $sales['discount'] }}</td>
                        </tr>
                        <tr class="pos-header">
                            <td class="">{{ __('Total') }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-right">{{ $sales['total'] }}</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            @if ($details['pay'] == 'show')
                <button class="btn btn-success payment-done-btn rounded mt-2 float-right" data-url="{{ route('pos.printview') }}" data-ajax-popup="true" data-size="sm"
                    data-bs-toggle="tooltip" data-title="{{ __('POS Invoice') }}">
                    {{ __('Cash Payment') }}
                </button>
            @endif
        </div>
    </div>

@endif

<script type="text/javascript" src="{{ asset('public/js/html2pdf.bundle.min.js') }}"></script>
<script>

    var filename = $('#filename').val()

    function saveAsPDF() {
        var element = document.getElementById('printableArea');
        var opt = {
            margin: 0.3,
            filename: filename,
            image: {type: 'jpeg', quality: 1},
            html2canvas: {scale: 4, dpi: 72, letterRendering: true},
            jsPDF: {unit: 'in', format: 'A2'}
        };
        html2pdf().set(opt).from(element).save();
    }

    $(document).on('click', '.payment-done-btn', function (e) {
        $('.modal-dialog').removeClass('modal-xl');
        e.preventDefault();
        var ele = $(this);
        $.ajax({
            url: "{{ route('pos.data.store') }}",
            method: 'GET',
            data: {
                vc_name: $('#vc_name_hidden').val(),
                store_id: $('#store_id').val(),
                discount : $('#discount_hidden').val(),
                price:$('.totalamount').text(),
            },
            beforeSend: function () {
                ele.remove();
            },
            success: function (data) {
                if (data.code == 200) {
                    console.log(document.URL +  ' #carthtml');
                    $('#carthtml').load(document.URL +  ' #carthtml');
                    show_toastr('success', data.success, 'success')
                }
            },
            error: function (data) {
                data = data.responseJSON;
                show_toastr('{{ __("Error") }}', data.error, 'error');
            }

        });
    });
</script>

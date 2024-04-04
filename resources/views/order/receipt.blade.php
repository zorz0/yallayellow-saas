@php
    $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
    $invoice_logo = \App\Models\Utility::GetValueByName('invoice_logo',$theme_name);
    $invoice_logo = get_file($invoice_logo , APP_THEME());
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{env('APP_NAME')}}</title>
</head>

<body>
    <div id="invoice-POS" style="width:100%">
        <style>
            #invoice-POS {
                /*box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);*/
            }

            #invoice-POS ::selection {
                /*background: #f31544;*/
                color: #fff;
            }

            #invoice-POS ::moz-selection {
                /*background: #f31544;*/
                color: #fff;
            }

            #invoice-POS h1 {
                font-size: 1.5em;
                color: #222;
                font-family: 'Trebuchet MS', sans-serif;
            }

            #invoice-POS h2 {
                font-size: 1.2em;
                font-family: 'Trebuchet MS', sans-serif;
            }

            #invoice-POS h3 {
                font-size: 1.2em;
                font-weight: bold;
                line-height: 2em;
                font-family: 'Trebuchet MS', sans-serif;
            }

            #invoice-POS p {
                font-size: 0.9em;
                color: #000;
                font-family: 'Trebuchet MS', sans-serif;
                font-weight: bold;
            }

            #invoice-POS .footer {
                font-size: 0.9em;
                color: #000;
                font-family: 'Trebuchet MS', sans-serif;
            }

            #invoice-POS #top,
            #invoice-POS #mid,
            #invoice-POS #bot {
                /* Targets all id with 'col-' */
                /*border-bottom: 1px solid #000;*/
            }

            #invoice-POS #top {
                min-height: 65px;
            }

            #invoice-POS #mid {
                min-height: 80px;
            }

            #invoice-POS #bot {
                min-height: 50px;
            }

            #invoice-POS #top .logo img {
                height: auto;
                width: 25%;
                height: auto;
                background-size: 60px 60px;
                filter: gray saturate(0%) brightness(70%) contrast(100%);
            }

            #invoice-POS .clientlogo {
                float: left;
                height: 60px;
                width: 60px;
                background-size: 60px 60px;
                border-radius: 50px;
            }

            #invoice-POS .info {
                display: block;
                margin-left: 0;
            }

            #invoice-POS .title {
                float: right;
            }

            #invoice-POS .title p {
                text-align: right;
            }

            #invoice-POS table {
                width: 100%;
                border-collapse: collapse;
            }

            #invoice-POS .tabletitle {
                font-size: 0.7em;
                /*background: #eee;*/
            }

            #invoice-POS .service {
                border-bottom: 2px solid #000;
            }

            #invoice-POS .item {
                width: 24mm;
            }

            #invoice-POS .itemtext {
                font-size: 0.9em;
                color: black;
            }

            #invoice-POS #legalcopy {
                margin-top: 5mm;
            }

            @media print {
                /*#invoice-POS {transform: scale(2);}*/
                /*@page  {*/
                /*    size: 80mm 210mm; /* landscape */
                /* you can also specify margins here: */
                /*}*/
            }
        </style>

        <center id="top">
            <div class="logo">
                <center>
                    <img class="mt-2 mb-2 ml-2"
                    src="{{ !empty($invoice_logo) ? $invoice_logo : $profile . '/logo.png' }}"  width="120" >
                </center>
            </div>
            <div class="info">
                <h2>{{__('SALE RECEIPT')}}</h2>
            </div>
            <!--End Info-->
        </center>
        <!--End InvoiceTop-->
        <div id="bot">
            <div id="table">
                <table>
                    <tbody>
                        <tr class="tabletitle">
                            <td class="item">
                                <h2>{{__('Item')}}</h2>
                            </td>
                            <td class="Hours" style="text-align: center">
                                <h2>{{__('Qty')}}</h2>
                            </td>
                            <td class="Rate">
                                <h2>{{__('Price')}}</h2>
                            </td>
                        </tr>

                        @foreach ($order['product'] as $product)
                        <tr class="service">
                            <td class="tableitem">
                                {{$product['name']}}
                            </td>
                            <td class="tableitem" style="text-align: center">
                                @if ($order['paymnet_type'] == 'POS')
                                    {{ $product['quantity'] }}
                                @else
                                    {{ $product['qty'] }}
                                @endif
                           </td>
                            <td class="tableitem">
                                @if ($order['paymnet_type'] == 'POS')
                                {{ currency_format_with_sym( ($product['orignal_price'] ?? 0), getCurrentStore(), APP_THEME()) ?? SetNumberFormat($product['orignal_price']) }}
                                @else
                                {{ currency_format_with_sym( ($product['orignal_price'] ?? 0), getCurrentStore(), APP_THEME()) ?? SetNumberFormat($product['final_price']) }}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        <tr class="tabletitle">
                            <td></td>
                            <td class="Rate">
                                <h2>{{__('Grand Total')}}</h2>
                            </td>
                            <td class="payment">
                                <h2><b>{{ currency_format_with_sym( ($product['sub_total'] ?? 0), getCurrentStore(), APP_THEME()) ?? SetNumberFormat($order['sub_total']) }}</b></h2>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!--End Table-->
        </div>
        <!--End InvoiceBot-->
        <div class="footer pt-2">
            <center>
                <p class="text-dark p-2">{{__('Merchandise may not be returned for refund at any time. Power bases cannot be
                    exchanged and are non-refundable. For purchases made in a Milo Showroom, no refunds are available
                    and sales c')}}</p>
            </center>
        </div>
    </div>
    <script>
        window.print();
        window.onafterprint = back;

        function back() {
            window.close();
            window.history.back();
        }
    </script>
</body>

</html>

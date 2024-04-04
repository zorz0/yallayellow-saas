<!DOCTYPE html>
<html lang="en">
@php
    $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
    $invoice_logo = \App\Models\Utility::GetValueByName('invoice_logo', $theme_name);
    $invoice_logo = get_file($invoice_logo, APP_THEME());

@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style type="text/css">
        :root {
            --theme-color: #FFF;
            --white: #ffffff;
            --black: #000000;
        }

        body {

            font-family: 'Lato', sans-serif;
        }

        p,
        li,
        ul,
        ol {
            margin: 0;
            padding: 0;
            list-style: none;
            line-height: 1.5;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table tr th {
            padding: 0.75rem;
            text-align: left;
        }

        table tr td {
            padding: 0.75rem;
            text-align: left;
        }

        table th small {
            display: block;
            font-size: 12px;
        }

        .invoice-preview-main {
            border: 3px solid #000;
            outline: 10px solid #ffff;
            max-width: 700px;
            width: 100%;
            margin: 10px auto;
            background: #ffff;
            box-shadow: 0 0 10px #ddd;
            padding: 20px
        }

        .invoice-logo {
            max-width: 200px;
            width: 100%;
        }

        .invoice-header .page-logo {
            display: flex;
            justify-content: flex-end;
        }

        .invoice-header table td {
            padding: 15px 30px;
        }

        .text-right {
            text-align: right;
        }

        .no-space tr td {
            padding: 0;
        }

        .vertical-align-top td {
            vertical-align: top;
        }

        .view-qrcode {
            max-width: 114px;
            height: 114px;
            margin-left: auto;
            margin-top: 15px;
            background: var(--white);
        }

        .view-qrcode img {
            width: 100%;
            height: 100%;
        }

        .invoice-body {
            /* padding: 30px 25px 0; */
        }



        table.add-border tr {
            border-top: 1px solid var(--theme-color);
        }

        tfoot tr:first-of-type {
            border-bottom: 1px solid var(--theme-color);
        }

        .total-table tr:first-of-type td {
            padding-top: 0;
        }

        .total-table tr:first-of-type {
            border-top: 0;
        }

        .sub-total {
            padding-right: 0;
            padding-left: 0;
        }

        .border-0 {
            border: none !important;
        }

        .invoice-summary td,
        .invoice-summary th {
            font-size: 13px;
            font-weight: 600;
        }

        .total-table td:last-of-type {
            width: 146px;
        }

        .invoice-footer {
            padding: 15px 20px;
        }

        .itm-description td {
            padding-top: 0;
        }

        html[dir="rtl"] table tr td,
        html[dir="rtl"] table tr th {
            text-align: right;
        }

        html[dir="rtl"] .text-right {
            text-align: left;
        }

        html[dir="rtl"] .view-qrcode {
            margin-left: 0;
            margin-right: auto;
        }

        p:not(:last-of-type) {
            margin-bottom: 15px;
        }

        .invoice-summary p {
            margin-bottom: 0;
        }
    </style>


</head>

<body class="">
    <div class="invoice-preview-main" id="boxes">
        <div class="invoice-header">
            <div class="row">
                <div class="col-xl-12 d-flex">
                    <div class="col-xl-6">
                        <address>
                            <h3>{{ __('FROM') }}</h3>
                            <p>
                                @if ($settings['store_address'])
                                    {{ $settings['store_address'] }}
                                @endif
                                <br>
                                @if ($settings['store_city'])
                                    {{ $settings['store_city'] }}
                                @endif
                                <br>
                                @if ($settings['store_state'])
                                    {{ $settings['store_state'] }}
                                @endif
                                <br>
                                @if ($settings['store_zipcode'])
                                    {{ $settings['store_zipcode'] }}
                                @endif
                                <br>
                                @if ($settings['store_country'])
                                    {{ $settings['store_country'] }}
                                @endif
                            </p>
                        </address>

                    </div>
                    <div class="col-xl-6">
                        <div class="page-logo">
                            <img class="invoice-logo"
                                src="{{ !empty($invoice_logo) ? $invoice_logo : $profile . '/logo.png' }}"
                                alt="Invoice-Logo">
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <div class="row">
                <div class="col-xl-12 d-flex">
                    <div class="col-xl-6">
                        <address class="vertical-align-top">
                            <h3>{{ __('To') }}</h3>
                            <p>
                                @if ($order['delivery_informations']['name'])
                                    {{ $order['delivery_informations']['name'] }}
                                @endif
                                <br>
                                @if ($order['delivery_informations']['address'])
                                    {{ $order['delivery_informations']['address'] }}
                                @endif
                                <br>
                                @if ($order['delivery_informations']['state'])
                                    {{ $order['delivery_informations']['state'] }}
                                @endif
                                <br>
                                @if ($order['delivery_informations']['country'])
                                    {{ $order['delivery_informations']['country'] }}
                                @endif
                                <br>
                                @if ($order['delivery_informations']['city'])
                                    {{ $order['delivery_informations']['city'] }}
                                @endif
                                @if ($order['delivery_informations']['post_code'])
                                    {{ $order['delivery_informations']['post_code'] }}
                                @endif
                                <br>
                                @if ($order['delivery_informations']['email'])
                                    {{ $order['delivery_informations']['email'] }}
                                @endif
                                <br>
                                @if ($order['delivery_informations']['phone'])
                                    {{ $order['delivery_informations']['phone'] }}
                                @endif
                            </p>


                        </address>
                    </div>
                    <div class="col-xl-6 d-flex justify-content-end">
                        {!! DNS1D::getBarcodeHTML($order['order_id'], 'C128') !!}
                    </div>
                </div>

            </div>

        </div>
        <hr>
        <div class="invoice-body">
            <table>
                <thead>
                    <tr>
                        <th>{{ __('ITEM') }}</th>
                        <th>{{ __('QUANTITY:') }}</th>
                        <th>{{ __('TOTAL:') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order['product'] as $item)
                        <tr>
                            <td>
                                {{ $item['name'] }}
                            </td>
                            <td>
                                {{ $item['qty'] }}
                            </td>
                            <td>
                                {{ $item['final_price'] }}
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>

        </div>
        <hr>
        <div class="invoice-body">
            <table>
                <thead>
                    <tr>
                        <th>{{ __('Order No:') }}</th>
                        <th>{{ __('Weight') }}:</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            {{ $order['order_id'] }}
                        </td>
                        <td>{{ $product_sum }}</td>
                    </tr>
            </table>

        </div>

    </div>

</body>

</html>

@include('order.script')

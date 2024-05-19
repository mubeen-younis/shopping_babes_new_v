@if( empty($report) || $report == '' ) {{dd('404 Not Found')}} @endif
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Title -->
    <title>Seller Invoice - {{$report['id']}}</title>
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet">

    <style type="text/css">
        body {
            font-family: 'Open Sans', sans-serif;
        }

        body,
        table,
        td,
        a {
            -ms-text-size-adjust: 100%;
            /* 1 */
            -webkit-text-size-adjust: 100%;
            /* 2 */
        }

        table,
        td {
            mso-table-rspace: 0pt;
            mso-table-lspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        a[x-apple-data-detectors] {
            font-family: inherit !important;
            font-size: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
            color: inherit !important;
            text-decoration: none !important;
        }

        a {
            color: #1a82e2;
        }

        img {
            height: auto;
            line-height: 100%;
            text-decoration: none;
            border: 0;
            outline: none;
        }
    </style>
</head>

@php

$shopCommission = $report['seller']['sales_commission_percentage'];
$globalCommission = \App\Models\BusinessSetting::where('type','sales_commission')->first();
if( $shopCommission == null) $shopCommission = $globalCommission->value;

$resNetAmount = $report['net_amount'] * $shopCommission / 100;
$orders = json_decode($report['data'], true);

if( !empty($orders) ) :
$couponDiscount = 0;
$shopDiscount = 0;
$orderTotal = 0;
$orderRow = "";

foreach( $orders as $key => $order ) :

$couponDiscount += $order['discount_amount'];
$shopDiscount += $order['extra_discount'];

$orderSerial = ++$key;
$orderID = $order['id'];
$orderDate = date('d F, Y', strtotime($order['created_at']));

$orderAmount = $order['order_amount'] + $order['discount_amount'] + $order['extra_discount'];

$orderTotal += $orderAmount;
$orderAmount = number_format($orderAmount, 2, '.', '');

$orderRow .= "<tr>";
    $orderRow .= "<td style='border:1px solid #000;' width='10%'> $orderSerial </td>";
    $orderRow .= "<td style='border:1px solid #000;' width='10%'> $orderID </td>";
    $orderRow .= "<td style='border:1px solid #000;' width='10%'> $orderDate </td>";
    $orderRow .= "<td style='border:1px solid #000;' width='10%'> ".
        \App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency( $orderAmount )) ."</td>";
    $orderRow .= "</tr>";
endforeach;

endif;

$resSubAmount = $resNetAmount-$couponDiscount;
$subTotal = $report['net_amount']-$resNetAmount;
$netPayAmount = $subTotal-$shopDiscount;


@endphp

<body class="print-report">
    <main id="content" role="main"
        style="background: #ffffff none repeat scroll 0 0; border-top: 12px solid #9f181c; padding: 40px 30px !important; position: relative; box-shadow: 0 1px 21px #acacac; color: #333333; width:768px; margin: 70px auto;">

        <table style="background-color: rgb(255, 255, 255);width: 100%;margin:auto;height:72px;">
            <tbody>
                <tr>
                    <td>
                        <img class="img-logo" alt="Shopppping Babes Logo"
                            src="{{asset('storage/app/public/logo.jpg')}}"
                            style="width: 200px;">
                    </td>
                    <td>
                        <div style="text-align: end; margin-inline-end:15px;">
                            <h5 style="font-size: 18px; margin: 0 0 5px;">Shopping Babes</h5>
                            <p style="font-size: 15px; margin: 0 0 5px;">42 Grace Road Darlington</p>
                            <p style="font-size: 15px; margin: 0 0 5px;">South Australia, 5047</p>
                            <p style="font-size: 15px; margin: 0 0 5px;">info@shoppingbabes.com</p>
                            <p style="font-size: 15px; margin: 0 0 5px;">Tel.: 0290538313</p>
                            <p style="font-size: 15px; margin: 0 0 5px;">ABN: 77624469692</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <div style="margin-bottom: 10px; border-bottom: 1px solid #000; padding-bottom: 10px;"></div>
        <table style="background-color: rgb(255, 255, 255);width: 100%;margin:auto;height:72px;">
            <tbody>
                <tr>
                    <td>
                        <h5 style="font-size: 18px; margin: 0 0 5px;">{{ $report['seller']['f_name'] }} {{
                            $report['seller']['l_name'] }}</h5>
                        <p style="font-size: 15px; margin: 0 0 5px;"><b>Mobile :</b> {{ $report['seller']['phone'] }}
                        </p>
                        <p style="font-size: 15px; margin: 0 0 5px;"><b>Email :</b> {{ $report['seller']['email'] }}</p>
                        <p style="font-size: 15px; margin: 0 0 5px;"><b>Address :</b> {{
                            $report['seller']['shop']['address'] }} </p>
                    </td>
                </tr>
            </tbody>
        </table>

        <table style="background-color: rgb(255, 255, 255);width: 100%;margin:auto;height:72px;">
            <tbody>
                <tr>
                    <td>
                        <div class="receipt-right">
                            <p style="font-size: 15px; margin: 0 0 5px;"><b>Date of invoice :</b> {{date('d F, Y',
                                strtotime($report['updated_at']))}}</p>
                            <p style="font-size: 15px; margin: 0 0 5px;"><b>Billing period :</b> [{{date('d F, Y',
                                strtotime($report['date_from']))}}] - [{{date('d F, Y',
                                strtotime($report['date_to']))}}]</p>
                        </div>
                    </td>
                    <td>
                        <div style="text-align: end; margin-inline-end:15px;">
                            <h3 style="font-size: 18px; margin: 0 0 5px;">INVOICE #{{$report['id']}}</h3>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <table cellpadding="10"
            style="margin-top:20px; border: 1px solid #000; width: 100%; border-collapse: collapse; font-size: 15px;">
            <thead>
                <tr>
                    <th style="border:1px solid #000;" width="70%">Description</th>
                    <th style="border:1px solid #000;" width="15%">Sub Total</th>
                    <th style="border:1px solid #000;" width="15%">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border:1px solid #000;" width="70%">Total Sale Amount</td>
                    <td style="border:1px solid #000;" width="15%"></td>
                    <td style="border:1px solid #000;" width="15%">{{
                        \App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency(number_format($report['net_amount'],
                        2, '.', '') )) }}</td>
                </tr>
                <tr>
                    <td style="border:1px solid #000;" width="70%">
                        <p></p>
                    </td>
                    <td style="border:1px solid #000;" width="15%">
                        <p></p>
                    </td>
                    <td style="border:1px solid #000;" width="15%">
                        <p></p>
                    </td>
                </tr>

                <tr>
                    <td style="border:1px solid #000; text-align:right" width="70%"><strong>ShoppingBabes Commission
                            ({{$shopCommission}}%):</strong></td>
                    <td style="border:1px solid #000;" width="15%"><strong>{{
                            \App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency(
                            number_format($resNetAmount, 2, '.', '') )) }}</strong></td>
                    <td style="border:1px solid #000;" width="15%">
                        <p></p>
                    </td>
                </tr>
                <tr>
                    <td style="border:1px solid #000; text-align:right" width="70%">ShoppingBabes Discounts (vouchers,
                        promotions):</td>
                    <td style="border:1px solid #000;" width="15%">{{
                        \App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency(
                        number_format($couponDiscount, 2, '.', '') )) }}</td>
                    <td style="border:1px solid #000;" width="15%">
                        <p></p>
                    </td>
                </tr>
                <tr>
                    <td style="border:1px solid #000; text-align:right" width="70%">Total ShoppingBabes Commission:</td>
                    <td style="border:1px solid #000;" width="15%">{{
                        \App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency(
                        number_format($resSubAmount, 2, '.', '') )) }}</td>
                    <td style="border:1px solid #000;" width="15%">
                        <p></p>
                    </td>
                </tr>
                <tr>
                    <td style="border:1px solid #000;" width="70%">
                        <p></p>
                    </td>
                    <td style="border:1px solid #000;" width="15%">
                        <p></p>
                    </td>
                    <td style="border:1px solid #000;" width="15%">
                        <p></p>
                    </td>
                </tr>

                <tr>
                    <td style="border:1px solid #000; text-align:right" width="70%"><strong>Sub Total: </strong></td>
                    <td style="border:1px solid #000;" width="15%">
                        <p></p>
                    </td>
                    <td style="border:1px solid #000;" width="15%">{{
                        \App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency(
                        number_format($subTotal, 2, '.', '') )) }}</td>
                </tr>
                <tr>
                    <td style="border:1px solid #000;" width="70%">
                        <p></p>
                    </td>
                    <td style="border:1px solid #000;" width="15%">
                        <p></p>
                    </td>
                    <td style="border:1px solid #000;" width="15%">
                        <p></p>
                    </td>
                </tr>

                <tr>
                    <td style="border:1px solid #000; text-align:right" width="70%"><strong>Seller Discounts (vouchers,
                            promotions):</strong></td>
                    <td style="border:1px solid #000;" width="15%"><strong>{{
                            \App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency(
                            number_format($shopDiscount, 2, '.', '') )) }}</strong></td>
                    <td style="border:1px solid #000;" width="15%">
                        <p></p>
                    </td>
                </tr>

                <tr>
                    <td style="border:1px solid #000;" width="70%">
                        <p></p>
                    </td>
                    <td style="border:1px solid #000;" width="15%">
                        <p></p>
                    </td>
                    <td style="border:1px solid #000;" width="15%">
                        <p></p>
                    </td>
                </tr>
                <tr>
                    <td style="border:1px solid #000; text-align:right" width="70%">
                        <h3 style="margin:0px">The amount to be transferred:</h3>
                    </td>
                    <td style="border:1px solid #000;" width="15%"></td>
                    <td style="border:1px solid #000;" width="15%">
                        <h3 style="margin:0px">{{
                            \App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency(
                            number_format($netPayAmount, 2, '.', '') )) }}</h3>
                    </td>
                </tr>
            </tbody>
        </table>

        <p style="font-size:14px"><strong>This credit will be credited to your account in the next few days BAN: Michael
                transferred.</strong></p>
        <p style="font-size:14px">If you have any questions about your receipt, please contact our service center
            [info@shoppingbabes.com]. Details of this invoice can be found on the attached page.</p>

        @if( !empty($orders) )
        <h3 style="margin:40px 0 10px;">Overview of individual orders - online payments</h3>
        <table cellpadding="10"
            style="margin-top:20px; border: 1px solid #000; width: 100%; border-collapse: collapse; font-size: 15px;">
            <thead>
                <tr>
                    <th style="border:1px solid #000;">Serial No.</th>
                    <th style="border:1px solid #000;">Order No.</th>
                    <th style="border:1px solid #000;">Order Date</th>
                    <th style="border:1px solid #000;">Amount</th>
                </tr>
            </thead>
            <tbody>
                {!! $orderRow !!}
                <tr>
                    <td style="border:1px solid #000; text-align:right" colspan="3"><strong>Total: </strong></td>
                    <td style="border:1px solid #000;"><strong>{{
                            \App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency(
                            number_format($orderTotal, 2, '.', '') )) }}</strong></td>
                </tr>
            </tbody>
        </table>
        @endif

        <h2 style="font-size:20px; text-align:center; margin:50px 0 20px;">Thank you for your business and your trust.
            It is our pleasure to work with you as a valuate shop partner.</h2>
    </main>

    <script>
        // window.print();
    </script>
</body>

</html>

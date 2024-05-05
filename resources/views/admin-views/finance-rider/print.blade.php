@if( empty($report) || $report == '' ) {{dd('404 Not Found')}} @endif
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Title -->
    <title>Rider Invoice - {{$report['id']}}</title>
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

$netAmount = number_format($report['net_amount'], 2, '.', '');
$orders = json_decode($report['data'], true);

if( !empty($orders) ) :
$orderTotal = 0;
$orderRow = "";

foreach( $orders as $key => $order ) :

$orderSerial = ++$key;
$orderID = $order['id'];
$orderDate = date('d F, Y', strtotime($report['created_at']));

$deliveryAmount = $order['delivery_charge'];
// $riderDistance = number_format($order['distance'], 2, '.', '');
$riderDistance = number_format(2.22, 2, '.', '');

$orderTotal += $deliveryAmount;
$deliveryAmount = \App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency(
number_format($deliveryAmount, 2, '.', '') ) );

$orderRow .= "<tr>";
    $orderRow .= "<td style='border:1px solid #000;' width='5%'> $orderSerial </td>";
    $orderRow .= "<td style='border:1px solid #000;' width='10%'> $orderID </td>";
    $orderRow .= "<td style='border:1px solid #000;' width='10%'> $orderDate </td>";
    $orderRow .= "<td style='border:1px solid #000;' width='15%'> ".$riderDistance."km</td>";
    $orderRow .= "<td style='border:1px solid #000;' width='10%'> $deliveryAmount </td>";
    $orderRow .= "</tr>";
endforeach;

$amountPayable = $orderTotal;

endif;

@endphp

<body class="print-report">
    <main id="content" role="main"
        style="background: #ffffff none repeat scroll 0 0; border-top: 12px solid #9f181c; padding: 40px 30px !important; position: relative; box-shadow: 0 1px 21px #acacac; color: #333333; width:768px; margin: 70px auto;">
        <table style="background-color: rgb(255, 255, 255);width: 100%;margin:auto;height:72px;">
            <tbody>
                <tr>
                    <td>
                        <img class="img-logo" alt="Shopping Babes Logo"
                            src="{{asset('storage/app/public/company/2023-12-19-65817dd08e552.webp')}}"
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
                        <h5 style="font-size: 18px; margin: 0 0 5px;">@if ( $report['rider'] != '' ) {{
                            $report['rider']['f_name'].' '.$report['rider']['l_name'] }} @endif</h5>
                        <p style="font-size: 15px; margin: 0 0 5px;"><b>Mobile :</b> @if ( $report['rider'] != '' ){{
                            $report['rider']['phone'] }} @endif</p>
                        <p style="font-size: 15px; margin: 0 0 5px;"><b>Email :</b> @if ( $report['rider'] != '' ){{
                            $report['rider']['email'] }} @endif</p>
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
                            <h4 style="font-size: 18px; margin: 0 0 5px;">Total : {{
                                \App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency(
                                number_format($amountPayable, 2, '.', '') ) ) }}</h4>
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
                    <td style="border:1px solid #000;" width="70%">Total Delivery Charges</td>
                    <td style="border:1px solid #000;" width="15%"></td>
                    <td style="border:1px solid #000;" width="15%">{{
                        \App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency(
                        number_format($orderTotal, 2, '.', '') ))}}</td>
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
                            number_format($amountPayable, 2, '.', '') )) }}</h3>
                    </td>
                </tr>
            </tbody>
        </table>
        <p style="font-size:14px">If you have any questions about your receipt, please contact our service center
            [info@shoppingbabes.com].</p>
        @if( !empty($orders) )
        <h3 style="margin:25px 0 10px;">Overview of individual orders.</h3>
        <table cellpadding="10"
            style="margin-top:20px; border: 1px solid #000; width: 100%; border-collapse: collapse; font-size: 15px;">
            <thead>
                <tr>
                    <th style="border:1px solid #000;">Serial No.</th>
                    <th style="border:1px solid #000;">Order No.</th>
                    <th style="border:1px solid #000;">Order Date</th>
                    <th style="border:1px solid #000;">Distance Covered </th>
                    <th style="border:1px solid #000;">Amount</th>
                </tr>
            </thead>
            <tbody>
                {!! $orderRow !!}
                <tr>
                    <td style="border:1px solid #000; text-align:right" colspan="4"><strong>Total: </strong></td>
                    <td style="border:1px solid #000;" class="text-left"><strong>{{
                            \App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency(
                            number_format($orderTotal, 2, '.', '') ) ) }}</strong></td>
                </tr>
            </tbody>
        </table>
        </div>
        @endif
    </main>
</body>

</html>
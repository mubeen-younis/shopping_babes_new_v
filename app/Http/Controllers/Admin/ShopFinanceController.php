<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\RiderFinance;
use App\Models\ShopFinance;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ShopFinanceController extends Controller
{
    public $PAID        = 'paid';
    public $NOT_PAID    = 'not_paid';
    public $CANCEL      = 'cancelled';

    public function shop(Request $request)
    {
        $reportStatus = $this->PAID;
        if ($request->report_status == 'not-paid') {
            $reportStatus = $this->NOT_PAID;
        } else if ($request->report_status == 'cancelled') {
            $reportStatus = $this->CANCEL;
        }

        $finance = ShopFinance::where('status', $reportStatus)->orderByDesc("id")->get();
        return view('admin-views.finance-shop.index', compact('finance', 'reportStatus'));
    }

    public function updateReportStatus(Request $request)
    {
        $reportID = $request->report_id;
        $reportStatus = $request->report_status;
        $weeklyReport = ShopFinance::where('id', $reportID)->first();
        if ($reportStatus == "paid") {
            $weeklyReport->status = $this->PAID;
        } else if ($reportStatus == "not-paid") {
            $weeklyReport->status = $this->NOT_PAID;
        } else if ($reportStatus == "cancelled") {
            $weeklyReport->status = $this->CANCEL;
        }
        $weeklyReport->save();
        return back();
    }

    public function printWeeklyReport(Request $request)
    {
        $report = ShopFinance::where('id', $request->id)->first();
        return view('admin-views.finance-shop.print', compact('report'));
    }

    /*
    * Rider Finance Start
    */
    public function rider(Request $request)
    {
        $reportStatus = $this->PAID;
        if ($request->report_status == 'not-paid') {
            $reportStatus = $this->NOT_PAID;
        } else if ($request->report_status == 'cancelled') {
            $reportStatus = $this->CANCEL;
        }

        $finance = RiderFinance::where('status', $reportStatus)->orderByDesc("id")->get();
        return view('admin-views.finance-rider.index', compact('finance', 'reportStatus'));
    }

    public function updateRiderReportStatus(Request $request)
    {
        $reportID = $request->report_id;
        $reportStatus = $request->report_status;
        $weeklyReport = RiderFinance::where('id', $reportID)->first();
        if ($reportStatus == "paid") {
            $weeklyReport->status = $this->PAID;
        } else if ($reportStatus == "not-paid") {
            $weeklyReport->status = $this->NOT_PAID;
        } else if ($reportStatus == "cancelled") {
            $weeklyReport->status = $this->CANCEL;
        }
        $weeklyReport->save();
        return back();
    }

    public function printRiderWeeklyReport(Request $request)
    {
        $report = RiderFinance::where('id', $request->id)->first();
        return view('admin-views.finance-rider.print', compact('report'));
    }
    /*
    * Rider Finance End
    */

    public function generateWeeklyReport()
    {
        $cDate = Carbon::now();
        $cDate->subDays($cDate->dayOfWeek + 1); // Previous Week's Dates

        $fromDate = $cDate->startOfWeek()->toDateTimeString();
        $toDate   = $cDate->endOfWeek()->toDateTimeString();

        // $orders   = Order::whereBetween('created_at', [$fromDate,$toDate])->get();
        // $orders   = Order::where('payment_status','paid')->get();
        $orders   = Order::where('payment_status', 'paid')
            // ->whereBetween('created_at', [$fromDate, $toDate])
            ->take(10)
            ->get();

        $shopOrders = [];
        $riderOrders = [];

        if (!empty($orders)) {
            foreach ($orders  as $order) {
                // Seller Report
                $shopOrders[$order->seller_id]['data'][] = [
                    'id' => $order->id,
                    'created_at' => $order->created_at,
                    'tax_status' => "included",
                    'order_amount' => $order->order_amount,
                    'delivery_charge' => $order->shipping_cost,
                    'total_tax_amount' => '5.00',
                    'discount_amount' => $order->discount_amount,
                    'extra_discount' => $order->extra_discount,
                ];
                $shopOrders[$order->seller_id]['net_amount'] = (isset($shopOrders[$order->seller_id]['net_amount']) ? $shopOrders[$order->seller_id]['net_amount'] : 0) + ($order->order_amount - $order->delivery_charge - $order->total_tax_amount + $order->discount_amount + $order->extra_discount);


                // Rider Report
                if ($order->delivery_man_id != null && $order->order_status == 'delivered') {
                    $riderOrders[$order->delivery_man_id]['data'][] = [
                        'id' => $order->id,
                        'created_at' => $order->created_at,
                        'delivery_charge' => $order->deliveryman_charge
                    ];
                    $riderOrders[$order->delivery_man_id]['net_amount'] = (isset($riderOrders[$order->delivery_man_id]['net_amount']) ? $riderOrders[$order->delivery_man_id]['net_amount'] : 0) + ($order->deliveryman_charge);
                }
            }
        }

        $weeklyShopReport = [];
        if (!empty($shopOrders)) {

            foreach ($shopOrders as $shopId => $data) {
                $weeklyShopReport = ShopFinance::where(['seller_id' => $shopId, 'date_from' => $fromDate, 'date_to' => $toDate])->first();
                if (is_null($weeklyShopReport)) {
                    $weeklyShopReport = new ShopFinance;
                }
                $weeklyShopReport->seller_id = $shopId;
                $weeklyShopReport->data      = json_encode($data['data']);
                $weeklyShopReport->date_from = $fromDate;
                $weeklyShopReport->date_to   = $toDate;
                $weeklyShopReport->status    = $this->NOT_PAID;
                $weeklyShopReport->net_amount = $data['net_amount'];
                $weeklyShopReport->save();

                $weeklyShopReport->refresh();

                echo "<pre>";
                echo "Shop Email Sent : $weeklyShopReport->id - " . $weeklyShopReport['seller']['email'] . " <br>";
                print_r(json_decode($weeklyShopReport['data']));
                echo "</pre>";
                Mail::to($weeklyShopReport['seller']['email'])->send(new \App\Mail\FinanceShop($weeklyShopReport));
            }
        }

        $weeklyRiderReport = [];
        if (!empty($riderOrders)) {
            foreach ($riderOrders as $riderId => $data) {
                $weeklyRiderReport = RiderFinance::where(['rider_id' => $riderId, 'date_from' => $fromDate, 'date_to' => $toDate])->first();
                if (is_null($weeklyRiderReport)) {
                    $weeklyRiderReport = new RiderFinance;
                }
                $weeklyRiderReport->rider_id    = $riderId;
                $weeklyRiderReport->data        = json_encode($data['data']);
                $weeklyRiderReport->date_from   = $fromDate;
                $weeklyRiderReport->date_to     = $toDate;
                $weeklyRiderReport->status      = $this->NOT_PAID;
                $weeklyRiderReport->net_amount  = $data['net_amount'];
                $weeklyRiderReport->save();
                $weeklyRiderReport->refresh();
                echo "<pre>";
                echo "Rider Email Sent : $weeklyRiderReport->id - " . $weeklyRiderReport['rider']['email'] . " <br>";
                print_r(json_decode($weeklyRiderReport['data']));
                echo "</pre>";
                Mail::to($weeklyRiderReport['rider']['email'])->send(new \App\Mail\FinanceRider($weeklyRiderReport));
            }
        }
    }
}

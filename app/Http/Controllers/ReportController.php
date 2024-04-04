<?php

namespace App\Http\Controllers;

use App\Models\MainCategory;
use App\Models\Order;
use App\Models\OrderCouponDetail;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Models\Store;
use App\Models\User;
use App\Models\Customer;
use App\Models\Utility;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Session;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        return view('reports.index');
    }

    public function reports_chart(Request $request)
    {
        $store_id = Store::where('id', getCurrentStore())->first();
        if ($request->chart_data == 'last-month') {
            $data = 'last-month';
            $lastMonth = Carbon::now()->subMonth();
            $guest = Order::where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->where('is_guest', '=', 1)->whereYear('order_date', $lastMonth->format('Y'))->whereMonth('order_date', $lastMonth->format('m'))->get()->count();
            $customer = Order::where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->where('is_guest', '=', 0)->whereYear('order_date', $lastMonth->format('Y'))->whereMonth('order_date', $lastMonth->format('m'))->get()->count();

            $customer_total = Customer::where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->where('regiester_date', '!=', NULL)->whereYear('regiester_date', $lastMonth->format('Y'))->whereMonth('regiester_date', $lastMonth->format('m'))->get()->count();
            $totaluser = $guest + $customer;



            $user = Order::selectRaw('orders.*,DATE(order_date) as DATE,MONTH(order_date) as month');
            $prevMonth = strtotime("-1 month");
            $start = strtotime(date('Y-m-01', $prevMonth));
            $end   = strtotime(date('Y-m-t', $prevMonth));
            $date  = (int)date('j', strtotime($end));

            $user->where('order_date', '>=', date('Y-m-01', $start))->where('order_date', '<=', date('Y-m-t', $end))->where('is_guest', '=', 0)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore());
            $user = $user->get();

            $register_customer = Customer::selectRaw('customers.*,DATE(regiester_date) as DATE,MONTH(regiester_date) as month');
            $register_customer->where('regiester_date', '>=', date('Y-m-01', $start))->where('regiester_date', '<=', date('Y-m-t', $end))->where('regiester_date', '!=', NULL)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore());
            $register_customer = $register_customer->get();

            $new_guest = Customer::selectRaw('customers.*,DATE(last_active) as DATE,MONTH(last_active) as month');
            $new_guest->where('last_active', '>=', date('Y-m-01', $start))->where('last_active', '<=', date('Y-m-t', $end))->where('regiester_date', '=', NULL)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore());
            $new_guest = $new_guest->get();

            $guests = Order::selectRaw('orders.*,DATE(order_date) as DATE,MONTH(order_date) as month');
            $guests->where('order_date', '>=', date('Y-m-01', $start))->where('order_date', '<=', date('Y-m-t', $end))->where('is_guest', '=', 1)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore());
            $guests = $guests->get();

            $userTotalArray = [];
            $guestTotalArray = [];
            $registerTotalArray = [];
            $newguestTotalArray = [];
            foreach ($user as $users) {
                $day = (int)date('j', strtotime($users->DATE)); // Extract day of the month

                $userTotalArray[$day][] = $users->order_date;
            }

            foreach ($guests as $guestss) {
                $day = (int)date('j', strtotime($guestss->DATE)); // Extract day of the month
                $guestTotalArray[$day][] = $guestss->order_date;
            }

            foreach ($register_customer as $register_c) {
                $day = (int)date('j', strtotime($register_c->DATE)); // Extract day of the month
                $registerTotalArray[$day][] = $register_c->regiester_date;
            }
            foreach ($new_guest as $guest_new) {
                $day = (int)date('j', strtotime($guest_new->DATE)); // Extract day of the month
                $newguestTotalArray[$day][] = $guest_new->last_active;
            }
            $lastDayofMonth = Carbon::now()->subMonthNoOverflow()->endOfMonth();
            $lastday = date('j', strtotime($lastDayofMonth));

            for ($i = 1; $i <= $lastday; $i++) {
                $userTotal[] = array_key_exists($i, $userTotalArray) ? count($userTotalArray[$i]) : 0;
                $guestTotal[] = array_key_exists($i, $guestTotalArray) ? count($guestTotalArray[$i]) : 0;
                $registerTotal[] = array_key_exists($i, $registerTotalArray) ? count($registerTotalArray[$i]) : 0;
                $newguestTotal[] = array_key_exists($i, $newguestTotalArray) ? count($newguestTotalArray[$i]) : 0;
            }

            $monthList = $month = $this->getLastMonthDatesFormatted();
        } elseif ($request->chart_data == 'this-month') {

            $guest = Order::where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->where('is_guest', '=', 1)->whereYear('order_date', date('Y'))->whereMonth('order_date', date('m'))->get()->count();
            $customer = Order::where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->where('is_guest', '=', 0)->whereYear('order_date', date('Y'))->whereMonth('order_date', date('m'))->get()->count();
            $customer_total = Customer::where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->where('regiester_date', '!=', NULL)->whereYear('regiester_date', date('Y'))->whereMonth('regiester_date', date('m'))->get()->count();
            $totaluser = $guest + $customer;


            $user = Order::selectRaw('orders.*,DATE(order_date) as DATE,MONTH(order_date) as month');
            $start = strtotime(date('Y-m-01'));
            $end   = strtotime(date('Y-m-t'));
            $day = (int)date('j', strtotime($end));

            $user->where('order_date', '>=', date('Y-m-01', $start))->where('order_date', '<=', date('Y-m-t', $end))->where('is_guest', '=', 0)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore());
            $user = $user->get();


            $register_customer = Customer::selectRaw('customers.*,DATE(regiester_date) as DATE,MONTH(regiester_date) as month');
            $register_customer->where('regiester_date', '>=', date('Y-m-01', $start))->where('regiester_date', '<=', date('Y-m-t', $end))->where('regiester_date', '!=', NULL)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore());
            $register_customer = $register_customer->get();

            $new_guest = Customer::selectRaw('customers.*,DATE(last_active) as DATE,MONTH(last_active) as month');
            $new_guest->where('last_active', '>=', date('Y-m-01', $start))->where('last_active', '<=', date('Y-m-t', $end))->where('regiester_date', '=', NULL)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore());
            $new_guest = $new_guest->get();

            $guests = Order::selectRaw('orders.*,DATE(order_date) as DATE,MONTH(order_date) as month');
            $guests->where('order_date', '>=', date('Y-m-01', $start))->where('order_date', '<=', date('Y-m-t', $end))->where('is_guest', '=', 1)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore());
            $guests = $guests->get();

            $userTotalArray = [];
            $guestTotalArray = [];
            $customerTotalArray = [];
            $newguestTotalArray = [];
            foreach ($user as $users) {
                $day = (int)date('j', strtotime($users->DATE));
                $userTotalArray[$day][] = $users->order_date;
            }
            foreach ($guests as $guestss) {
                $day = (int)date('j', strtotime($guestss->DATE));
                $guestTotalArray[$day][] = $guestss->order_date;
            }
            foreach ($register_customer as $register_c) {
                $day = (int)date('j', strtotime($register_c->DATE));
                $customerTotalArray[$day][] = $register_c->regiester_date;
            }
            foreach ($new_guest as $guest_new) {
                $day = (int)date('j', strtotime($guest_new->DATE));
                $newguestTotalArray[$day][] = $guest_new->last_active;
            }
            $lastDayofMonth = \Carbon\Carbon::now()->endOfMonth()->toDateString();
            $lastday = date('j', strtotime($lastDayofMonth));

            for ($i = 1; $i <=  $lastday; $i++) {
                $userTotal[] = array_key_exists($i, $userTotalArray) ? count($userTotalArray[$i]) : 0;
                $guestTotal[] = array_key_exists($i, $guestTotalArray) ? count($guestTotalArray[$i]) : 0;
                $registerTotal[] = array_key_exists($i, $customerTotalArray) ? count($customerTotalArray[$i]) : 0;
                $newguestTotal[] = array_key_exists($i, $newguestTotalArray) ? count($newguestTotalArray[$i]) : 0;
            }
            $monthList = $month = $this->getCurrentMonthDates();
        } elseif ($request->chart_data == 'seven-day') {
            $startDate = now()->subDays(6);
            $guest = Order::where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->where('is_guest', 1)->where('order_date', '>', $startDate)->get()->count();
            $customer = Order::where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->where('is_guest', '=', 0)->where('order_date', '>', $startDate)->get()->count();
            $customer_total = Customer::where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->where('regiester_date', '!=', NULL)->where('regiester_date', '>', $startDate)->get()->count();
            $totaluser = $guest + $customer;


            $userTotal           = [];
            $guestTotal          = [];
            $monthList           = [];
            $newguestTotal       = [];
            $previous_week = strtotime("-1 week +1 day");

            for ($i = 0; $i <= 7 - 1; $i++) {
                $date = date('Y-m-d', $previous_week);
                $previous_week = strtotime(date('Y-m-d', $previous_week) . " +1 day");
                $monthList[]    = __(date('d-M', strtotime($date)));
                $userTotal[] = Order::whereDate('order_date', $date)->where('is_guest', '=', 0)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->count();
                $guestTotal[] = Order::whereDate('order_date', $date)->where('is_guest', '=', 1)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->count();
                $registerTotal[] = Customer::whereDate('regiester_date', $date)->where('regiester_date', '!=', NULL)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->count();
                $newguestTotal[] = Customer::whereDate('last_active', $date)->where('regiester_date', '=', NULL)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->count();
            }
        } elseif ($request->chart_data == 'year') {

            $guest = Order::where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->where('is_guest', '=', 1)->whereYear('order_date', date('Y'))->get()->count();
            $customer = Order::where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->where('is_guest', '=', 0)->whereYear('order_date', date('Y'))->get()->count();
            $customer_total = Customer::where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->where('regiester_date', '!=', NULL)->whereYear('regiester_date', date('Y'))->get()->count();
            $totaluser = $guest + $customer;

            $user = Order::selectRaw('orders.*,MONTH(order_date) as month,YEAR(order_date) as year');
            $start = strtotime(date('Y-01'));
            $end   = strtotime(date('Y-12'));
            $user->where('order_date', '>=', date('Y-m-01', $start))->where('order_date', '<=', date('Y-m-t', $end))->where('is_guest', '=', 0)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore());
            $user = $user->get();

            $guests = Order::selectRaw('orders.*,MONTH(order_date) as month,YEAR(order_date) as year');
            $guests->where('order_date', '>=', date('Y-m-01', $start))->where('order_date', '<=', date('Y-m-t', $end))->where('is_guest', '=', 1)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore());
            $guests = $guests->get();

            $register_customer = Customer::selectRaw('customers.*,MONTH(regiester_date) as month,YEAR(regiester_date) as year');
            $register_customer->where('regiester_date', '>=', date('Y-m-01', $start))->where('regiester_date', '<=', date('Y-m-t', $end))->where('regiester_date', '!=', NULL)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore());
            $register_customer = $register_customer->get();

            $new_guest = Customer::selectRaw('customers.*,MONTH(last_active) as month,YEAR(last_active) as year');
            $new_guest->where('last_active', '>=', date('Y-m-01', $start))->where('last_active', '<=', date('Y-m-t', $end))->where('regiester_date', '=', NULL)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore());
            $new_guest = $new_guest->get();


            $userTotalArray = [];
            $guestTotalArray = [];
            $registerTotalArray = [];
            $newguestTotalArray = [];
            foreach ($user as $user) {
                $userTotalArray[$user->month][] = $user->order_date;
            }

            foreach ($guests as $guests) {
                $guestTotalArray[$guests->month][] = $guests->order_date;
            }
            foreach ($register_customer as $register_c) {
                $registerTotalArray[$register_c->month][] = $register_c->regiester_date;
            }
            foreach ($new_guest as $guest_new) {
                $newguestTotalArray[$guest_new->month][] = $guest_new->last_active;
            }
            for ($i = 1; $i <= 12; $i++) {
                $userTotal[] = array_key_exists($i, $userTotalArray) ? count($userTotalArray[$i]) : 0;
                $guestTotal[] = array_key_exists($i, $guestTotalArray) ? count($guestTotalArray[$i]) : 0;
                $registerTotal[] = array_key_exists($i, $registerTotalArray) ? count($registerTotalArray[$i]) : 0;
                $newguestTotal[] = array_key_exists($i, $newguestTotalArray) ? count($newguestTotalArray[$i]) : 0;
            }
            $monthList = $month = $this->yearMonth();
        } else {
            if (str_contains($request->Date, ' to ')) {
                $date_range = explode(' to ', $request->Date);
                if (count($date_range) === 2) {
                    $form_date = date('Y-m-d', strtotime($date_range[0]));
                    $to_date = date('Y-m-d', strtotime($date_range[1]));
                } else {
                    $start_date = date('Y-m-d', strtotime($date_range[0]));
                    $end_date = date('Y-m-d', strtotime($date_range[0]));
                }
            } else {

                $form_date = date('Y-m-d', strtotime($request->Date));
                $to_date = date('Y-m-d', strtotime($request->Date));
            }
            $guest = Order::whereDate('order_date', '>=', $form_date)->whereDate('order_date', '<=', $to_date)->where('is_guest', '=', 1)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->count();
            $customer = Order::whereDate('order_date', '>=', $form_date)->whereDate('order_date', '<=', $to_date)->where('is_guest', '=', 0)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->count();

            $customer_total = Customer::whereDate('regiester_date', '>=', $form_date)->whereDate('regiester_date', '<=', $to_date)->where('regiester_date', '!=', NULL)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->count();

            $totaluser = $guest + $customer;

            $user = Order::selectRaw('orders.*,DATE(order_date) as DATE,MONTH(order_date) as month');
            $user->whereDate('order_date', '>=', $form_date)
                ->whereDate('order_date', '<=', $to_date)->where('theme_id', $store_id->theme_id)->where('is_guest', '=', 0)->where('store_id', getCurrentStore());
            $user = $user->get();

            $guests = Order::selectRaw('orders.*,DATE(order_date) as DATE,MONTH(order_date) as month');
            $guests->whereDate('order_date', '>=', $form_date)
                ->whereDate('order_date', '<=', $to_date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->where('is_guest', '=', 1);
            $guests = $guests->get();

            $register_customer = Customer::selectRaw('customers.*,DATE(regiester_date) as DATE,MONTH(regiester_date) as month');
            $register_customer->whereDate('regiester_date', '>=', $form_date)
                ->whereDate('regiester_date', '<=', $to_date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->where('regiester_date', '!=', NULL);
            $register_customer = $register_customer->get();

            $new_guest = Customer::selectRaw('customers.*,DATE(last_active) as DATE,MONTH(last_active) as month');
            $new_guest->whereDate('last_active', '>=', $form_date)
                ->whereDate('last_active', '<=', $to_date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->where('regiester_date', '=', NULL);
            $new_guest = $new_guest->get();

            $userTotalArray     = [];
            $guestTotalArray    = [];
            $registerTotalArray = [];
            $newguestTotalArray = [];
            $data           = [];
            $data_month     = [];
            $guestTotal  = [];
            $userTotal   = [];
            $monthLists = Order::selectRaw('orders.*,DATE(order_date) as DATE,MONTH(order_date) as month');
            $monthLists = Order::whereDate('order_date', '>=', $form_date)
                ->whereDate('order_date', '<=', $to_date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore());
            $monthLists = $monthLists->get();

            foreach ($monthLists as $monthLists_date) {
                $data[] = date('y-n-j', strtotime($monthLists_date->order_date));
                $data_month[] = date('Y-m-d', strtotime($monthLists_date->order_date));
            }

            $List = array_values(array_unique($data));
            $monthList_data = $List;
            $List_month = array_values(array_unique($data_month));
            $monthList = $List_month;
            //  dd($monthList);
            foreach ($user as $users) {
                $day = date('y-n-j', strtotime($users->DATE));
                $userTotalArray[$day][] = date('y-n-j', strtotime($users->order_date));
            }
            foreach ($register_customer as $register_c) {
                $day = date('y-n-j', strtotime($register_c->DATE));
                $registerTotalArray[$day][] = date('y-n-j', strtotime($register_c->regiester_date));
            }

            foreach ($new_guest as $guest_new) {
                $day = date('y-n-j', strtotime($guest_new->DATE));
                $newguestTotalArray[$day][] = date('y-n-j', strtotime($guest_new->last_active));
            }
            foreach ($guests as $g) {
                $day = date('y-n-j', strtotime($g->DATE));
                $guestTotalArray[$day][] = date('y-n-j', strtotime($g->order_date));
            }
            foreach ($monthList_data as $month) {
                $userTotal[] = array_key_exists($month, $userTotalArray) ? count($userTotalArray[$month]) : 0;
                $guestTotal[] = array_key_exists($month, $guestTotalArray) ? count($guestTotalArray[$month]) : 0;
                $registerTotal[] = array_key_exists($month, $registerTotalArray) ? count($registerTotalArray[$month]) : 0;
                $newguestTotal[] = array_key_exists($month, $newguestTotalArray) ? count($newguestTotalArray[$month]) : 0;
            }
        }

        $html = '';
        $html = view('reports.chart_data', compact('customer', 'guest', 'totaluser', 'customer_total'))->render();

        $return['html'] = $html;
        $return['guestTotal'] = $guestTotal ?? [];
        $return['userTotal'] = $userTotal ?? [];
        $return['registerTotal'] = $registerTotal ?? [];
        $return['monthList'] = $monthList;
        $return['customer'] = $customer;
        $return['guest'] = $guest;
        $return['newguestTotal'] = $newguestTotal ?? [];

        Session::put('return', $return);


        return response()->json($return);
    }

    public function getCurrentMonthDates()
    {
        $currentYear = date('Y');
        $currentMonth = date('m');
        $daysInMonth = date('t', mktime(0, 0, 0, $currentMonth, 1, $currentYear));

        $dates = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $formattedDate = date('d-M', mktime(0, 0, 0, $currentMonth, $day, $currentYear));
            $dates[] = $formattedDate;
        }

        return $dates;
    }

    public function getLastMonthDatesFormatted()
    {
        $lastMonth = Carbon::now()->subMonth();
        $lastMonthYear = $lastMonth->format('Y');
        $lastMonthMonth = $lastMonth->format('m');
        $daysInLastMonth = $lastMonth->daysInMonth;

        $dates = [];

        for ($day = 1; $day <= $daysInLastMonth; $day++) {
            $formattedDate = $lastMonth->setDay($day)->format('d-M');
            $dates[] = $formattedDate;
        }

        return $dates;
    }
    public function yearMonth()
    {
        $month[] = __('January');
        $month[] = __('February');
        $month[] = __('March');
        $month[] = __('April');
        $month[] = __('May');
        $month[] = __('June');
        $month[] = __('July');
        $month[] = __('August');
        $month[] = __('September');
        $month[] = __('October');
        $month[] = __('November');
        $month[] = __('December');
        return $month;
    }

    public function export(Request $request)
    {
        $requests_data = Session::get('return');
        $return['monthList'] = $requests_data['monthList'];
        $return['userTotal'] = $requests_data['userTotal'];
        $return['registerTotal'] = $requests_data['registerTotal'];
        $return['guest'] = $requests_data['guestTotal'];
        $return['newguestTotal'] = $requests_data['newguestTotal'];
        return response()->json($return);
    }

    public function OrderReport(Request $request)
    {
        return view('reports.order_report');
    }

    public function order_reports_chart(Request $request)
    {
        $store_id = Store::where('id', getCurrentStore())->first();
        $currency = Utility::GetValueByName('CURRENCY');
        if ($request->chart_data == 'last-month') {
            $data = 'last-month';
            $lastMonth = Carbon::now()->subMonth();
            $prevMonth = strtotime("-1 month");
            $start = strtotime(date('Y-m-01', $prevMonth));
            $end   = strtotime(date('Y-m-t', $prevMonth));

            $customer = Order::where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->whereYear('order_date', date('Y'))->get()->count();
            $customer_total = Customer::where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->where('regiester_date', '!=', NULL)->whereYear('regiester_date', date('Y'))->get()->count();
            $totaluser = 0;
            $guest = '';

            $lastDayofMonth = Carbon::now()->subMonthNoOverflow()->endOfMonth();
            $lastday = date('j', strtotime($lastDayofMonth));

            $orders = Order::selectRaw('orders.*,DATE(order_date) as DATE,MONTH(order_date) as month');
            $orders->where('order_date', '>=', date('Y-m-01', $start))->where('order_date', '<=', date('Y-m-t', $end))->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore());
            $orders = $orders->get();
            $TotalOrder = $orders->count();

            $totalDuePurchaseorder   = 0;
            $grossSaleTotalArray = [];
            $netSaleTotalArray = [];
            $CouponTotalArray = [];
            $ShippingTotalArray = [];
            $OrderTotalArray = [];
            $totalduepurchaseorderArray = [];
            $averageGrossSales = [];
            $PurchasedItemArray = [];
            $totalProductQuantity = 0;
            $PurchasedProductItemTotal = 0;
            $TotalgrossSale = 0;
            $TotalNetSale = 0;
            $TotalShippingCharge = 0;
            $TotalCouponAmount = 0;
            foreach ($orders as $order) {
                $day = (int)date('j', strtotime($order->DATE)); // Extract day of the month

                $netSaleTotalArray[$day][] = (float)($order->final_price - $order->delivery_price - $order->tax_price);
                $grossSaleTotalArray[$day][] = (float)$order->final_price;
                $CouponTotalArray[$day][] = (float)$order['coupon_price'];
                $ShippingTotalArray[$day][] = (float)$order->delivery_price;
                $OrderTotalArray[$day][] = 1;
                $products = json_decode($order['product_json'], true);
                foreach ($products as $product) {
                    $totalProductQuantity = intval($product['qty']);
                    $PurchasedItemArray[$day][] = $totalProductQuantity;
                    $PurchasedProductItemTotal += $totalProductQuantity;
                }
                $TotalgrossSale         += (float)$order->final_price;
                $TotalNetSale           += (float)($order->final_price - $order->delivery_price - $order->tax_price);
                $TotalCouponAmount      += (float)$order['coupon_price'];
                $TotalShippingCharge    += (float)$order->delivery_price;
            }


            for ($i = 1; $i <= $lastday; $i++) {
                $GrossSaleTotal[] = array_key_exists($i, $grossSaleTotalArray) ? array_sum($grossSaleTotalArray[$i]) : 0;
                $NetSaleTotal[] = array_key_exists($i, $netSaleTotalArray) ? array_sum($netSaleTotalArray[$i]) : 0;
                $ShippingTotal[] = array_key_exists($i, $ShippingTotalArray) ? array_sum($ShippingTotalArray[$i]) : 0;
                $CouponTotal[] = array_key_exists($i, $CouponTotalArray) ? array_sum($CouponTotalArray[$i]) : 0;
                $TotalOrderCount[] = array_key_exists($i, $OrderTotalArray) ? count($OrderTotalArray[$i]) : 0;

                $PurchasedItemTotal[] = array_key_exists($i, $PurchasedItemArray) ? array_sum($PurchasedItemArray[$i]) : 0;

                $dailySales = array_key_exists($i, $grossSaleTotalArray) ? $grossSaleTotalArray[$i] : [];
                $averageGrossSales[] = count($dailySales) > 0 ? (array_sum($dailySales) / count($dailySales)) : 0;

                $dailyNetSales = array_key_exists($i, $netSaleTotalArray) ? $netSaleTotalArray[$i] : [];
                $averageNetSales[] = count($dailyNetSales) > 0 ? (array_sum($dailyNetSales) / count($dailyNetSales)) : 0;
            }

            $monthList = $month = $this->getLastMonthDatesFormatted();
        } elseif ($request->chart_data == 'this-month') {
            $start = strtotime(date('Y-m-01'));
            $end   = strtotime(date('Y-m-t'));
            $day = (int)date('j', strtotime($end));

            $orders = Order::selectRaw('orders.*,DATE(order_date) as DATE,MONTH(order_date) as month');
            $orders->where('order_date', '>=', date('Y-m-01', $start))->where('order_date', '<=', date('Y-m-t', $end))->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore());
            $orders = $orders->get();
            $TotalOrder = $orders->count();

            $totalDuePurchaseorder   = 0;
            $grossSaleTotalArray = [];
            $netSaleTotalArray = [];
            $CouponTotalArray = [];
            $ShippingTotalArray = [];
            $OrderTotalArray = [];
            $totalduepurchaseorderArray = [];
            $averageGrossSales = [];
            $PurchasedItemArray = [];
            $totalProductQuantity = 0;
            $PurchasedProductItemTotal = 0;
            $TotalgrossSale = 0;
            $TotalNetSale = 0;
            $TotalShippingCharge = 0;
            $TotalCouponAmount = 0;

            foreach ($orders as $order) {
                $day = (int)date('j', strtotime($order->DATE));
                $userTotalArray[$day][] = $order->order_date;

                $netSaleTotalArray[$day][] = (float)($order->final_price - $order->delivery_price - $order->tax_price);
                $grossSaleTotalArray[$day][] = (float)$order->final_price;
                $CouponTotalArray[$day][] = (float)$order['coupon_price'];
                $ShippingTotalArray[$day][] = (float)$order->delivery_price;
                $OrderTotalArray[$day][] = 1;
                $products = json_decode($order['product_json'], true);
                foreach ($products as $product) {
                    $totalProductQuantity = intval($product['qty']);
                    $PurchasedItemArray[$day][] = $totalProductQuantity;
                    $PurchasedProductItemTotal += $totalProductQuantity;
                }
                $TotalgrossSale         += (float)$order->final_price;
                $TotalNetSale           += (float)($order->final_price - $order->delivery_price - $order->tax_price);
                $TotalCouponAmount      += (float)$order['coupon_price'];
                $TotalShippingCharge    += (float)$order->delivery_price;
            }
            $lastDayofMonth = \Carbon\Carbon::now()->endOfMonth()->toDateString();
            $lastday = date('j', strtotime($lastDayofMonth));

            for ($i = 1; $i <=  $lastday; $i++) {
                $GrossSaleTotal[] = array_key_exists($i, $grossSaleTotalArray) ? array_sum($grossSaleTotalArray[$i]) : 0;
                $NetSaleTotal[] = array_key_exists($i, $netSaleTotalArray) ? array_sum($netSaleTotalArray[$i]) : 0;
                $ShippingTotal[] = array_key_exists($i, $ShippingTotalArray) ? array_sum($ShippingTotalArray[$i]) : 0;
                $CouponTotal[] = array_key_exists($i, $CouponTotalArray) ? array_sum($CouponTotalArray[$i]) : 0;
                $TotalOrderCount[] = array_key_exists($i, $OrderTotalArray) ? count($OrderTotalArray[$i]) : 0;

                $PurchasedItemTotal[] = array_key_exists($i, $PurchasedItemArray) ? array_sum($PurchasedItemArray[$i]) : 0;

                $dailySales = array_key_exists($i, $grossSaleTotalArray) ? $grossSaleTotalArray[$i] : [];
                $averageGrossSales[] = count($dailySales) > 0 ? (array_sum($dailySales) / count($dailySales)) : 0;

                $dailyNetSales = array_key_exists($i, $netSaleTotalArray) ? $netSaleTotalArray[$i] : [];
                $averageNetSales[] = count($dailyNetSales) > 0 ? (array_sum($dailyNetSales) / count($dailyNetSales)) : 0;
            }
            $monthList = $month = $this->getCurrentMonthDates();
        } elseif ($request->chart_data == 'seven-day') {
            $startDate = now()->subDays(6);

            $TotalOrder      = 0;
            $totalDuePurchaseorder   = 0;
            $grossSaleTotalArray = [];
            $netSaleTotalArray = [];
            $CouponTotalArray = [];
            $ShippingTotalArray = [];
            $OrderTotalArray = [];
            $totalduepurchaseorderArray = [];
            $averageGrossSales = [];
            $PurchasedItemArray = [];
            $totalProductQuantity = 0;
            $PurchasedProductItemTotal = 0;
            $TotalgrossSale = 0;
            $TotalNetSale = 0;
            $TotalShippingCharge = 0;
            $TotalCouponAmount = 0;
            $monthList           = [];
            $previous_week = strtotime("-1 week +1 day");

            for ($i = 0; $i <= 7 - 1; $i++) {
                $date = date('Y-m-d', $previous_week);
                $previous_week = strtotime(date('Y-m-d', $previous_week) . " +1 day");
                $monthList[]    = __(date('d-M', strtotime($date)));

                $ordersForDate = Order::whereDate('order_date', $date)
                    ->where('theme_id', $store_id->theme_id)
                    ->where('store_id', getCurrentStore())
                    ->get();
                $TotalOrder += $ordersForDate->count();
                $totalPurchasedItemsForDate = 0;

                foreach ($ordersForDate as $order) {
                    $products = json_decode($order->product_json, true);

                    $totalProductQuantity = array_reduce($products, function ($carry, $product) {
                        return $carry + intval($product['qty']);
                    }, 0);
                    $totalPurchasedItemsForDate += $totalProductQuantity;
                    $PurchasedProductItemTotal += $totalProductQuantity;
                }
                $PurchasedItemTotal[] = $totalPurchasedItemsForDate;

                $totalOrdersForDate = Order::whereDate('order_date', $date)
                    ->where('theme_id', $store_id->theme_id)
                    ->where('store_id', getCurrentStore())
                    ->count();

                $GrossSaleTotal[] =  Order::whereDate('order_date', $date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->get()->sum('final_price');

                $NetSaleTotal[] = Order::whereDate('order_date', $date)
                    ->where('theme_id', $store_id->theme_id)
                    ->where('store_id', getCurrentStore())
                    ->get()
                    ->sum(function ($order) {
                        return $order->final_price - $order->delivery_price - $order->tax_price;
                    });
                $CouponTotal[] = Order::whereDate('order_date', $date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->get()->sum('coupon_price');
                $ShippingTotal[] = Order::whereDate('order_date', $date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->get()->sum('delivery_price');
                $TotalOrderCount[] = $totalOrdersForDate;

                $averageGrossSales[] = $totalOrdersForDate > 0 ? ($GrossSaleTotal[count($GrossSaleTotal) - 1] / $totalOrdersForDate) : 0;
                $averageNetSales[] = $totalOrdersForDate > 0 ? ($NetSaleTotal[count($NetSaleTotal) - 1] / $totalOrdersForDate) : 0;

                $TotalgrossSale         += Order::whereDate('order_date', $date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->get()->sum('final_price');
                $TotalNetSale           += Order::whereDate('order_date', $date)
                    ->where('theme_id', $store_id->theme_id)
                    ->where('store_id', getCurrentStore())
                    ->get()
                    ->sum(function ($order) {
                        return $order->final_price - $order->delivery_price - $order->tax_price;
                    });
                $TotalCouponAmount      += Order::whereDate('order_date', $date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->get()->sum('coupon_price');
                $TotalShippingCharge    += Order::whereDate('order_date', $date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->get()->sum('delivery_price');
                $TotalOrderCount[] = $totalOrdersForDate;
            }
        } elseif ($request->chart_data == 'year') {

            $TotalOrder = Order::where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->whereYear('order_date', date('Y'))->get()->count();

            $orders = Order::selectRaw('orders.*,MONTH(order_date) as month,YEAR(order_date) as year');
            $start = strtotime(date('Y-01'));
            $end   = strtotime(date('Y-12'));
            $orders->where('order_date', '>=', date('Y-m-01', $start))->where('order_date', '<=', date('Y-m-t', $end))->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore());
            $orders = $orders->get();
            $order = Order::where('theme_id', $store_id->theme_id)
                ->where('store_id', getCurrentStore())
                ->whereYear('order_date', date('Y'))
                ->get()->count();

            $totalDuePurchaseorder   = 0;
            $grossSaleTotalArray = [];
            $netSaleTotalArray = [];
            $CouponTotalArray = [];
            $ShippingTotalArray = [];
            $OrderTotalArray = [];
            $totalduepurchaseorderArray = [];
            $averageGrossSales = [];
            $PurchasedItemArray = [];
            $totalProductQuantity = 0;
            $PurchasedProductItemTotal = 0;
            $TotalgrossSale = 0;
            $TotalNetSale = 0;
            $TotalShippingCharge = 0;
            $TotalCouponAmount = 0;
            foreach ($orders as $order) {
                $netSaleTotalArray[$order->month][] = (float)($order->final_price - $order->delivery_price - $order->tax_price);
                $grossSaleTotalArray[$order->month][] = (float)$order->final_price;
                $CouponTotalArray[$order->month][] = (float)$order['coupon_price'];
                $ShippingTotalArray[$order->month][] = (float)$order->delivery_price;
                $OrderTotalArray[$order->month][] = 1;
                $products = json_decode($order['product_json'], true);
                foreach ($products as $product) {
                    $totalProductQuantity = intval($product['qty']);
                    $PurchasedItemArray[$order->month][] = $totalProductQuantity;
                    $PurchasedProductItemTotal += $totalProductQuantity;
                }
                $TotalgrossSale         += (float)$order->final_price;
                $TotalNetSale           += (float)($order->final_price - $order->delivery_price - $order->tax_price);
                $TotalCouponAmount      += (float)$order['coupon_price'];
                $TotalShippingCharge    += (float)$order->delivery_price;
            }
            for ($i = 1; $i <= 12; $i++) {

                $GrossSaleTotal[] = array_key_exists($i, $grossSaleTotalArray) ? array_sum($grossSaleTotalArray[$i]) : 0;
                $NetSaleTotal[] = array_key_exists($i, $netSaleTotalArray) ? array_sum($netSaleTotalArray[$i]) : 0;
                $ShippingTotal[] = array_key_exists($i, $ShippingTotalArray) ? array_sum($ShippingTotalArray[$i]) : 0;
                $CouponTotal[] = array_key_exists($i, $CouponTotalArray) ? array_sum($CouponTotalArray[$i]) : 0;
                $TotalOrderCount[] = array_key_exists($i, $OrderTotalArray) ? count($OrderTotalArray[$i]) : 0;

                $PurchasedItemTotal[] = array_key_exists($i, $PurchasedItemArray) ? array_sum($PurchasedItemArray[$i]) : 0;

                $monthlySales = array_key_exists($i, $grossSaleTotalArray) ? $grossSaleTotalArray[$i] : [];
                $average = count($monthlySales) > 0 ? (array_sum($monthlySales) / count($monthlySales)) : 0;
                $averageGrossSales[] = $average;

                $monthlySales = array_key_exists($i, $netSaleTotalArray) ? $netSaleTotalArray[$i] : [];
                $netsaleaverage = count($monthlySales) > 0 ? (array_sum($monthlySales) / count($monthlySales)) : 0;
                $averageNetSales[] = $netsaleaverage;
            }
            $monthList = $month = $this->yearMonth();
        } else {
            if (str_contains($request->Date, ' to ')) {
                $date_range = explode(' to ', $request->Date);
                if (count($date_range) === 2) {
                    $form_date = date('Y-m-d', strtotime($date_range[0]));
                    $to_date = date('Y-m-d', strtotime($date_range[1]));
                } else {
                    $start_date = date('Y-m-d', strtotime($date_range[0]));
                    $end_date = date('Y-m-d', strtotime($date_range[0]));
                }
            } else {

                $form_date = date('Y-m-d', strtotime($request->Date));
                $to_date = date('Y-m-d', strtotime($request->Date));
            }
            $orders = Order::selectRaw('orders.*,DATE(order_date) as DATE,MONTH(order_date) as month');
            $orders->whereDate('order_date', '>=', $form_date)->whereDate('order_date', '<=', $to_date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore());
            $orders = $orders->get();
            $TotalOrder = $orders->count();

            $totalDuePurchaseorder   = 0;
            $grossSaleTotalArray = [];
            $netSaleTotalArray = [];
            $CouponTotalArray = [];
            $ShippingTotalArray = [];
            $OrderTotalArray = [];
            $totalduepurchaseorderArray = [];
            $averageGrossSales = [];
            $PurchasedItemArray = [];
            $totalProductQuantity = 0;
            $PurchasedProductItemTotal = 0;
            $TotalgrossSale = 0;
            $TotalNetSale = 0;
            $TotalShippingCharge = 0;
            $TotalCouponAmount = 0;

            $monthLists = Order::selectRaw('orders.*,DATE(order_date) as DATE,MONTH(order_date) as month');
            $monthLists = Order::whereDate('order_date', '>=', $form_date)
                ->whereDate('order_date', '<=', $to_date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore());
            $monthLists = $monthLists->get();

            foreach ($monthLists as $monthLists_date) {
                $data[] = date('y-n-j', strtotime($monthLists_date->order_date));
                $data_month[] = date('Y-m-d', strtotime($monthLists_date->order_date));
            }
            if (!empty($data) && is_array($data)) {
                $List = array_values(array_unique($data));
                $monthList_data = $List;
                $List_month = array_values(array_unique($data_month));
                $monthList = $List_month;
            } else {
                $List = [];
                $monthList_data = [];
                $data_month[] = date('y-n-j');
                $List_month = array_values(array_unique($data_month));
                $monthList = $List_month;
            }

            foreach ($orders as $order) {
                $day = date('y-n-j', strtotime($order->DATE));
                $userTotalArray[$day][] = date('y-n-j', strtotime($order->order_date));

                $netSaleTotalArray[$day][] = (float)($order->final_price - $order->delivery_price - $order->tax_price);
                $grossSaleTotalArray[$day][] = (float)$order->final_price;
                $CouponTotalArray[$day][] = (float)$order['coupon_price'];
                $ShippingTotalArray[$day][] = (float)$order->delivery_price;
                $OrderTotalArray[$day][] = 1;
                $products = json_decode($order['product_json'], true);
                foreach ($products as $product) {
                    $totalProductQuantity = intval($product['qty']);
                    $PurchasedItemArray[$day][] = $totalProductQuantity;
                    $PurchasedProductItemTotal += $totalProductQuantity;
                }
                $TotalgrossSale         += (float)$order->final_price;
                $TotalNetSale           += (float)($order->final_price - $order->delivery_price - $order->tax_price);
                $TotalCouponAmount      += (float)$order['coupon_price'];
                $TotalShippingCharge    += (float)$order->delivery_price;
            }

            if (!empty($data) && is_array($data)) {
                foreach ($monthList_data as $month) {
                    $GrossSaleTotal[] = array_key_exists($month, $grossSaleTotalArray) ? array_sum($grossSaleTotalArray[$month]) : 0;
                    $NetSaleTotal[] = array_key_exists($month, $netSaleTotalArray) ? array_sum($netSaleTotalArray[$month]) : 0;
                    $ShippingTotal[] = array_key_exists($month, $ShippingTotalArray) ? array_sum($ShippingTotalArray[$month]) : 0;
                    $CouponTotal[] = array_key_exists($month, $CouponTotalArray) ? array_sum($CouponTotalArray[$month]) : 0;
                    $TotalOrderCount[] = array_key_exists($month, $OrderTotalArray) ? count($OrderTotalArray[$month]) : 0;

                    $PurchasedItemTotal[] = array_key_exists($month, $PurchasedItemArray) ? array_sum($PurchasedItemArray[$month]) : 0;

                    $dailySales = array_key_exists($month, $grossSaleTotalArray) ? $grossSaleTotalArray[$month] : [];
                    $averageGrossSales[] = count($dailySales) > 0 ? (array_sum($dailySales) / count($dailySales)) : 0;

                    $dailyNetSales = array_key_exists($month, $netSaleTotalArray) ? $netSaleTotalArray[$month] : [];
                    $averageNetSales[] = count($dailyNetSales) > 0 ? (array_sum($dailyNetSales) / count($dailyNetSales)) : 0;
                }
            } else {
                $month = date('y-n-j');
                $GrossSaleTotal[] = array_key_exists($month, $grossSaleTotalArray) ? array_sum($grossSaleTotalArray[$month]) : 0;
                $NetSaleTotal[] = array_key_exists($month, $netSaleTotalArray) ? array_sum($netSaleTotalArray[$month]) : 0;
                $ShippingTotal[] = array_key_exists($month, $ShippingTotalArray) ? array_sum($ShippingTotalArray[$month]) : 0;
                $CouponTotal[] = array_key_exists($month, $CouponTotalArray) ? array_sum($CouponTotalArray[$month]) : 0;
                $TotalOrderCount[] = array_key_exists($month, $OrderTotalArray) ? count($OrderTotalArray[$month]) : 0;

                $PurchasedItemTotal[] = array_key_exists($month, $PurchasedItemArray) ? array_sum($PurchasedItemArray[$month]) : 0;

                $dailySales = array_key_exists($month, $grossSaleTotalArray) ? $grossSaleTotalArray[$month] : [];
                $averageGrossSales[] = count($dailySales) > 0 ? (array_sum($dailySales) / count($dailySales)) : 0;

                $dailyNetSales = array_key_exists($month, $netSaleTotalArray) ? $netSaleTotalArray[$month] : [];
                $averageNetSales[] = count($dailyNetSales) > 0 ? (array_sum($dailyNetSales) / count($dailyNetSales)) : 0;
            }
        }

        $html = '';
        $html = view('reports.order_chart_data', compact('TotalOrder', 'PurchasedProductItemTotal', 'TotalgrossSale', 'currency', 'TotalNetSale', 'TotalCouponAmount', 'TotalShippingCharge'))->render();

        $return['html'] = $html;

        $return['TotalOrderCount'] = $TotalOrderCount;
        $return['NetSaleTotal'] = $NetSaleTotal;
        $return['AverageNetSales'] = $averageNetSales;
        $return['GrossSaleTotal'] = $GrossSaleTotal;
        $return['AverageGrossSales'] = $averageGrossSales;
        $return['PurchasedItemTotal'] = $PurchasedItemTotal;
        $return['ShippingTotal'] = $ShippingTotal;
        $return['CouponTotal'] = $CouponTotal;
        $return['monthList'] = $monthList;
        Session::put('order_line_chart_report', $return);

        return response()->json($return);
    }

    public function order_report_export(Request $request)
    {

        $requests_data = Session::get('order_line_chart_report');
        $return['monthList'] = $requests_data['monthList'];
        $return['TotalOrderCount'] = $requests_data['TotalOrderCount'];
        $return['NetSaleTotal'] = $requests_data['NetSaleTotal'];
        $return['AverageNetSales'] = $requests_data['AverageNetSales'];
        $return['GrossSaleTotal'] = $requests_data['GrossSaleTotal'];
        $return['AverageGrossSales'] = $requests_data['AverageGrossSales'];
        $return['PurchasedItemTotal'] = $requests_data['PurchasedItemTotal'];
        $return['ShippingTotal'] = $requests_data['ShippingTotal'];
        $return['CouponTotal'] = $requests_data['CouponTotal'];
        return response()->json($return);
    }

    public function order_bar_report_export(Request $request)
    {

        $requests_data = Session::get('order_bar_chart_report');
        $return['monthList'] = $requests_data['monthList'];
        $return['NetSaleTotal'] = $requests_data['NetSaleTotal'];
        $return['AverageNetSales'] = $requests_data['AverageNetSales'];
        $return['GrossSaleTotal'] = $requests_data['GrossSaleTotal'];
        $return['AverageGrossSales'] = $requests_data['AverageGrossSales'];
        $return['ShippingTotal'] = $requests_data['ShippingTotal'];
        $return['CouponTotal'] = $requests_data['CouponTotal'];
        return response()->json($return);
    }

    public function BarChartOrderReport(Request $request)
    {
        return view('reports.bar_chart_order_report');
    }

    public function order_reports_chart_data(Request $request)
    {
        $store_id = Store::where('id', getCurrentStore())->first();
        $currency = Utility::GetValueByName('CURRENCY');
        if ($request->chart_data == 'last-month') {
            $data = 'last-month';
            $lastMonth = Carbon::now()->subMonth();
            $prevMonth = strtotime("-1 month");
            $start = strtotime(date('Y-m-01', $prevMonth));
            $end   = strtotime(date('Y-m-t', $prevMonth));

            $TotalOrder = Order::where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->whereYear('order_date', date('Y'))->get()->count();
            $customer = Order::where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->whereYear('order_date', date('Y'))->get()->count();
            $customer_total = Customer::where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->where('regiester_date', '!=', NULL)->whereYear('regiester_date', date('Y'))->get()->count();
            $totaluser = 0;
            $guest = '';

            $lastDayofMonth = Carbon::now()->subMonthNoOverflow()->endOfMonth();
            $lastday = date('j', strtotime($lastDayofMonth));

            $orders = Order::selectRaw('orders.*,DATE(order_date) as DATE,MONTH(order_date) as month');
            $orders->where('order_date', '>=', date('Y-m-01', $start))->where('order_date', '<=', date('Y-m-t', $end))->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore());
            $orders = $orders->get();
            $TotalOrder = $orders->count();
            $TotalOrderCount = Order::selectRaw('orders.*, MONTH(order_date) as month, YEAR(order_date) as year')
                ->whereBetween('order_date', [$lastMonth, $lastDayofMonth])
                ->where('theme_id', $store_id->theme_id)
                ->where('store_id', getCurrentStore())
                ->count();

            $totalDuePurchaseorder   = 0;
            $grossSaleTotalArray = [];
            $netSaleTotalArray = [];
            $CouponTotalArray = [];
            $ShippingTotalArray = [];
            $OrderTotalArray = [];
            $totalduepurchaseorderArray = [];
            $averageGrossSales = [];
            $PurchasedItemArray = [];
            $totalProductQuantity = 0;
            $PurchasedItemTotal = 0;
            $PurchasedProductItemTotal = 0;
            $TotalgrossSale = 0;
            $TotalNetSale = 0;
            $TotalShippingCharge = 0;
            $TotalCouponAmount = 0;
            foreach ($orders as $order) {
                $day = (int)date('j', strtotime($order->DATE)); // Extract day of the month

                $netSaleTotalArray[$day][] = (float)($order->final_price - $order->delivery_price - $order->tax_price);
                $grossSaleTotalArray[$day][] = (float)$order->final_price;
                $CouponTotalArray[$day][] = (float)$order['coupon_price'];
                $ShippingTotalArray[$day][] = (float)$order->delivery_price;
                $OrderTotalArray[$day][] = 1;
                $products = json_decode($order['product_json'], true);
                foreach ($products as $product) {
                    $totalProductQuantity = intval($product['qty']);
                    $PurchasedItemArray[$day][] = $totalProductQuantity;
                    $PurchasedItemTotal += $totalProductQuantity;
                    $PurchasedProductItemTotal += $totalProductQuantity;
                }
                $TotalgrossSale         += (float)$order->final_price;
                $TotalNetSale           += (float)($order->final_price - $order->delivery_price - $order->tax_price);
                $TotalCouponAmount      += (float)$order['coupon_price'];
                $TotalShippingCharge    += (float)$order->delivery_price;
            }

            for ($i = 1; $i <= $lastday; $i++) {
                $GrossSaleTotal[] = array_key_exists($i, $grossSaleTotalArray) ? array_sum($grossSaleTotalArray[$i]) : 0;
                $NetSaleTotal[] = array_key_exists($i, $netSaleTotalArray) ? array_sum($netSaleTotalArray[$i]) : 0;
                $ShippingTotal[] = array_key_exists($i, $ShippingTotalArray) ? array_sum($ShippingTotalArray[$i]) : 0;
                $CouponTotal[] = array_key_exists($i, $CouponTotalArray) ? array_sum($CouponTotalArray[$i]) : 0;

                $dailySales = array_key_exists($i, $grossSaleTotalArray) ? $grossSaleTotalArray[$i] : [];
                $averageGrossSales[] = count($dailySales) > 0 ? (array_sum($dailySales) / count($dailySales)) : 0;

                $dailyNetSales = array_key_exists($i, $netSaleTotalArray) ? $netSaleTotalArray[$i] : [];
                $averageNetSales[] = count($dailyNetSales) > 0 ? (array_sum($dailyNetSales) / count($dailyNetSales)) : 0;
            }

            $monthList = $month = $this->getLastMonthDatesFormatted();
        } elseif ($request->chart_data == 'this-month') {

            $guest = Order::where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->where('is_guest', '=', 1)->whereYear('order_date', date('Y'))->whereMonth('order_date', date('m'))->get()->count();
            $customer = Order::where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->where('is_guest', '=', 0)->whereYear('order_date', date('Y'))->whereMonth('order_date', date('m'))->get()->count();
            $customer_total = Customer::where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->where('regiester_date', '!=', NULL)->whereYear('regiester_date', date('Y'))->get()->count();
            $totaluser = 0;
            $guest = '';
            $start = strtotime(date('Y-m-01'));
            $end   = strtotime(date('Y-m-t'));
            $day = (int)date('j', strtotime($end));

            $orders = Order::selectRaw('orders.*,DATE(order_date) as DATE,MONTH(order_date) as month');
            $orders->where('order_date', '>=', date('Y-m-01', $start))->where('order_date', '<=', date('Y-m-t', $end))->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore());
            $orders = $orders->get();
            $TotalOrder = $orders->count();

            $TotalOrderCount = Order::selectRaw('orders.*, MONTH(order_date) as month, YEAR(order_date) as year')
                ->whereMonth('order_date', date('m'))
                ->where('theme_id', $store_id->theme_id)
                ->where('store_id', getCurrentStore())
                ->count();

            $totalDuePurchaseorder   = 0;
            $grossSaleTotalArray = [];
            $netSaleTotalArray = [];
            $CouponTotalArray = [];
            $ShippingTotalArray = [];
            $OrderTotalArray = [];
            $totalduepurchaseorderArray = [];
            $averageGrossSales = [];
            $PurchasedItemArray = [];
            $totalProductQuantity = 0;
            $PurchasedItemTotal = 0;
            $PurchasedProductItemTotal = 0;
            $TotalgrossSale = 0;
            $TotalNetSale = 0;
            $TotalShippingCharge = 0;
            $TotalCouponAmount = 0;

            foreach ($orders as $order) {
                $day = (int)date('j', strtotime($order->DATE));
                $netSaleTotalArray[$day][] = (float)($order->final_price - $order->delivery_price - $order->tax_price);
                $grossSaleTotalArray[$day][] = (float)$order->final_price;
                $CouponTotalArray[$day][] = (float)$order['coupon_price'];
                $ShippingTotalArray[$day][] = (float)$order->delivery_price;
                $OrderTotalArray[$day][] = 1;
                $products = json_decode($order['product_json'], true);
                foreach ($products as $product) {
                    $totalProductQuantity = intval($product['qty']);
                    $PurchasedItemArray[$day][] = $totalProductQuantity;
                    $PurchasedItemTotal += $totalProductQuantity;
                    $PurchasedProductItemTotal += $totalProductQuantity;
                }
                $TotalgrossSale         += (float)$order->final_price;
                $TotalNetSale           += (float)($order->final_price - $order->delivery_price - $order->tax_price);
                $TotalCouponAmount      += (float)$order['coupon_price'];
                $TotalShippingCharge    += (float)$order->delivery_price;
            }
            $lastDayofMonth = \Carbon\Carbon::now()->endOfMonth()->toDateString();
            $lastday = date('j', strtotime($lastDayofMonth));

            for ($i = 1; $i <=  $lastday; $i++) {
                $GrossSaleTotal[] = array_key_exists($i, $grossSaleTotalArray) ? array_sum($grossSaleTotalArray[$i]) : 0;
                $NetSaleTotal[] = array_key_exists($i, $netSaleTotalArray) ? array_sum($netSaleTotalArray[$i]) : 0;
                $ShippingTotal[] = array_key_exists($i, $ShippingTotalArray) ? array_sum($ShippingTotalArray[$i]) : 0;
                $CouponTotal[] = array_key_exists($i, $CouponTotalArray) ? array_sum($CouponTotalArray[$i]) : 0;

                $dailySales = array_key_exists($i, $grossSaleTotalArray) ? $grossSaleTotalArray[$i] : [];
                $averageGrossSales[] = count($dailySales) > 0 ? (array_sum($dailySales) / count($dailySales)) : 0;

                $dailyNetSales = array_key_exists($i, $netSaleTotalArray) ? $netSaleTotalArray[$i] : [];
                $averageNetSales[] = count($dailyNetSales) > 0 ? (array_sum($dailyNetSales) / count($dailyNetSales)) : 0;
            }
            $monthList = $month = $this->getCurrentMonthDates();
        } elseif ($request->chart_data == 'seven-day') {
            $startDate = now()->subDays(6);

            $TotalOrderCount = Order::where('order_date', '>=', $startDate)
                ->where('order_date', '<=', now())
                ->where('theme_id', $store_id->theme_id)
                ->where('store_id', getCurrentStore())
                ->count();
            $TotalOrder      = 0;
            $totalDuePurchaseorder   = 0;
            $grossSaleTotalArray = [];
            $netSaleTotalArray = [];
            $CouponTotalArray = [];
            $ShippingTotalArray = [];
            $OrderTotalArray = [];
            $totalduepurchaseorderArray = [];
            $averageGrossSales = [];
            $PurchasedItemArray = [];
            $totalProductQuantity = 0;
            $PurchasedItemTotal = 0;
            $PurchasedProductItemTotal = 0;
            $TotalgrossSale = 0;
            $TotalNetSale = 0;
            $TotalShippingCharge = 0;
            $TotalCouponAmount = 0;

            $monthList           = [];
            $previous_week = strtotime("-1 week +1 day");

            for ($i = 0; $i <= 7 - 1; $i++) {
                $date = date('Y-m-d', $previous_week);
                $previous_week = strtotime(date('Y-m-d', $previous_week) . " +1 day");
                $monthList[]    = __(date('d-M', strtotime($date)));

                $ordersForDate = Order::whereDate('order_date', $date)
                    ->where('theme_id', $store_id->theme_id)
                    ->where('store_id', getCurrentStore())
                    ->get();
                $TotalOrder += $ordersForDate->count();

                $totalPurchasedItemsForDate = 0;

                foreach ($ordersForDate as $order) {
                    $products = json_decode($order->product_json, true);

                    $totalProductQuantity = array_reduce($products, function ($carry, $product) {
                        return $carry + intval($product['qty']);
                    }, 0);
                    $PurchasedItemTotal += $totalProductQuantity;
                    $PurchasedProductItemTotal += $totalProductQuantity;
                }

                $totalOrdersForDate = Order::whereDate('order_date', $date)
                    ->where('theme_id', $store_id->theme_id)
                    ->where('store_id', getCurrentStore())
                    ->count();

                $GrossSaleTotal[] =  Order::whereDate('order_date', $date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->get()->sum('final_price');
                $NetSaleTotal[] = Order::whereDate('order_date', $date)
                    ->where('theme_id', $store_id->theme_id)
                    ->where('store_id', getCurrentStore())
                    ->get()
                    ->sum(function ($order) {
                        return $order->final_price - $order->delivery_price - $order->tax_price;
                    });
                $CouponTotal[] = Order::whereDate('order_date', $date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->get()->sum('coupon_price');
                $ShippingTotal[] = Order::whereDate('order_date', $date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->get()->sum('delivery_price');

                $averageGrossSales[] = $totalOrdersForDate > 0 ? ($GrossSaleTotal[count($GrossSaleTotal) - 1] / $totalOrdersForDate) : 0;
                $averageNetSales[] = $totalOrdersForDate > 0 ? ($NetSaleTotal[count($NetSaleTotal) - 1] / $totalOrdersForDate) : 0;

                $TotalgrossSale         += Order::whereDate('order_date', $date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->get()->sum('final_price');
                $TotalNetSale           += Order::whereDate('order_date', $date)
                    ->where('theme_id', $store_id->theme_id)
                    ->where('store_id', getCurrentStore())
                    ->get()
                    ->sum(function ($order) {
                        return $order->final_price - $order->delivery_price - $order->tax_price;
                    });
                $TotalCouponAmount      += Order::whereDate('order_date', $date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->get()->sum('coupon_price');
                $TotalShippingCharge    += Order::whereDate('order_date', $date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->get()->sum('delivery_price');
            }
        } elseif ($request->chart_data == 'year') {
            $TotalOrder = Order::where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->whereYear('order_date', date('Y'))->get()->count();

            $orders = Order::selectRaw('orders.*,MONTH(order_date) as month,YEAR(order_date) as year');
            $start = strtotime(date('Y-01'));
            $end   = strtotime(date('Y-12'));
            $orders->where('order_date', '>=', date('Y-m-01', $start))->where('order_date', '<=', date('Y-m-t', $end))->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore());
            $orders = $orders->get();
            $TotalOrderCount = Order::selectRaw('orders.*, MONTH(order_date) as month, YEAR(order_date) as year')
                ->whereYear('order_date', date('Y')) // Filter by the current year
                ->where('theme_id', $store_id->theme_id)
                ->where('store_id', getCurrentStore())
                ->get()->count();

            $totalgrossSale      = 0;
            $totalDuePurchaseorder   = 0;
            $grossSaleTotalArray = [];
            $netSaleTotalArray = [];
            $CouponTotalArray = [];
            $ShippingTotalArray = [];
            $OrderTotalArray = [];
            $totalduepurchaseorderArray = [];
            $averageGrossSales = [];
            $PurchasedItemArray = [];
            $PurchasedItemTotal = 0;
            $PurchasedProductItemTotal = 0;
            $TotalgrossSale = 0;
            $TotalNetSale = 0;
            $TotalShippingCharge = 0;
            $TotalCouponAmount = 0;

            foreach ($orders as $order) {
                $netSaleTotalArray[$order->month][] = (float)($order->final_price - $order->delivery_price - $order->tax_price);
                $grossSaleTotalArray[$order->month][] = (float)$order->final_price;
                $CouponTotalArray[$order->month][] = (float)$order['coupon_price'];
                $ShippingTotalArray[$order->month][] = (float)$order->delivery_price;
                $OrderTotalArray[$order->month][] = 1;
                $products = json_decode($order['product_json'], true);
                foreach ($products as $product) {
                    $totalProductQuantity = intval($product['qty']);
                    $PurchasedItemArray[$order->month][] = $totalProductQuantity;
                    $PurchasedItemTotal += $totalProductQuantity;
                    $PurchasedProductItemTotal += $totalProductQuantity;
                }
                $TotalgrossSale         += (float)$order->final_price;
                $TotalNetSale           += (float)($order->final_price - $order->delivery_price - $order->tax_price);
                $TotalCouponAmount      += (float)$order['coupon_price'];
                $TotalShippingCharge    += (float)$order->delivery_price;
            }
            for ($i = 1; $i <= 12; $i++) {

                $GrossSaleTotal[] = array_key_exists($i, $grossSaleTotalArray) ? array_sum($grossSaleTotalArray[$i]) : 0;
                $NetSaleTotal[] = array_key_exists($i, $netSaleTotalArray) ? array_sum($netSaleTotalArray[$i]) : 0;
                $ShippingTotal[] = array_key_exists($i, $ShippingTotalArray) ? array_sum($ShippingTotalArray[$i]) : 0;
                $CouponTotal[] = array_key_exists($i, $CouponTotalArray) ? array_sum($CouponTotalArray[$i]) : 0;

                $monthlySales = array_key_exists($i, $grossSaleTotalArray) ? $grossSaleTotalArray[$i] : [];
                $average = count($monthlySales) > 0 ? (array_sum($monthlySales) / count($monthlySales)) : 0;
                $averageGrossSales[] = $average;

                $monthlySales = array_key_exists($i, $netSaleTotalArray) ? $netSaleTotalArray[$i] : [];
                $netsaleaverage = count($monthlySales) > 0 ? (array_sum($monthlySales) / count($monthlySales)) : 0;
                $averageNetSales[] = $netsaleaverage;
            }
            $monthList = $month = $this->yearMonth();
        } else {
            if (str_contains($request->Date, ' to ')) {
                $date_range = explode(' to ', $request->Date);
                if (count($date_range) === 2) {
                    $form_date = date('Y-m-d', strtotime($date_range[0]));
                    $to_date = date('Y-m-d', strtotime($date_range[1]));
                } else {
                    $start_date = date('Y-m-d', strtotime($date_range[0]));
                    $end_date = date('Y-m-d', strtotime($date_range[0]));
                }
            } else {

                $form_date = date('Y-m-d', strtotime($request->Date));
                $to_date = date('Y-m-d', strtotime($request->Date));
            }

            $orders = Order::selectRaw('orders.*,DATE(order_date) as DATE,MONTH(order_date) as month');
            $orders->whereDate('order_date', '>=', $form_date)->whereDate('order_date', '<=', $to_date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore());
            $orders = $orders->get();
            $TotalOrder = $orders->count();

            $guests = Order::selectRaw('orders.*,DATE(order_date) as DATE,MONTH(order_date) as month');
            $guests->whereDate('order_date', '>=', $form_date)
                ->whereDate('order_date', '<=', $to_date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->where('is_guest', '=', 1);
            $guests = $guests->get();

            $register_customer = Customer::selectRaw('customers.*,DATE(regiester_date) as DATE,MONTH(regiester_date) as month');
            $register_customer->whereDate('regiester_date', '>=', $form_date)
                ->whereDate('regiester_date', '<=', $to_date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->where('regiester_date', '!=', NULL);
            $register_customer = $register_customer->get();

            $new_guest = Customer::selectRaw('customers.*,DATE(last_active) as DATE,MONTH(last_active) as month');
            $new_guest->whereDate('last_active', '>=', $form_date)
                ->whereDate('last_active', '<=', $to_date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->where('regiester_date', '=', NULL);
            $new_guest = $new_guest->get();


            $TotalOrderCount = Order::selectRaw('orders.*,DATE(order_date) as DATE,MONTH(order_date) as month')
                ->whereDate('order_date', '>=', $form_date)
                ->whereDate('order_date', '<=', $to_date)
                ->where('theme_id', $store_id->theme_id)
                ->where('store_id', getCurrentStore())
                ->count();

            $totalDuePurchaseorder   = 0;
            $grossSaleTotalArray = [];
            $netSaleTotalArray = [];
            $CouponTotalArray = [];
            $ShippingTotalArray = [];
            $OrderTotalArray = [];
            $totalduepurchaseorderArray = [];
            $averageGrossSales = [];
            $PurchasedItemArray = [];
            $totalProductQuantity = 0;
            $PurchasedItemTotal = 0;
            $PurchasedProductItemTotal = 0;
            $TotalgrossSale = 0;
            $TotalNetSale = 0;
            $TotalShippingCharge = 0;
            $TotalCouponAmount = 0;

            $monthLists = Order::selectRaw('orders.*,DATE(order_date) as DATE,MONTH(order_date) as month');
            $monthLists = Order::whereDate('order_date', '>=', $form_date)
                ->whereDate('order_date', '<=', $to_date)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore());
            $monthLists = $monthLists->get();

            foreach ($monthLists as $monthLists_date) {
                $data[] = date('y-n-j', strtotime($monthLists_date->order_date));
                $data_month[] = date('Y-m-d', strtotime($monthLists_date->order_date));
            }
            if (!empty($data) && is_array($data)) {
                $List = array_values(array_unique($data));
                $monthList_data = $List;
                $List_month = array_values(array_unique($data_month));
                $monthList = $List_month;
            } else {
                $List = [];
                $monthList_data = [];
                $data_month[] = date('y-n-j');
                $List_month = array_values(array_unique($data_month));
                $monthList = $List_month;
            }

            foreach ($orders as $order) {
                $day = date('y-n-j', strtotime($order->DATE));
                $userTotalArray[$day][] = date('y-n-j', strtotime($order->order_date));

                $netSaleTotalArray[$day][] = (float)($order->final_price - $order->delivery_price - $order->tax_price);
                $grossSaleTotalArray[$day][] = (float)$order->final_price;
                $CouponTotalArray[$day][] = (float)$order['coupon_price'];
                $ShippingTotalArray[$day][] = (float)$order->delivery_price;
                $OrderTotalArray[$day][] = 1;
                $products = json_decode($order['product_json'], true);
                foreach ($products as $product) {
                    $totalProductQuantity = intval($product['qty']);
                    $PurchasedItemTotal += $totalProductQuantity;
                    $PurchasedProductItemTotal += $totalProductQuantity;
                }
                $TotalgrossSale         += (float)$order->final_price;
                $TotalNetSale           += (float)($order->final_price - $order->delivery_price - $order->tax_price);
                $TotalCouponAmount      += (float)$order['coupon_price'];
                $TotalShippingCharge    += (float)$order->delivery_price;
            }

            if (!empty($data) && is_array($data)) {
                foreach ($monthList_data as $month) {
                    $GrossSaleTotal[] = array_key_exists($month, $grossSaleTotalArray) ? array_sum($grossSaleTotalArray[$month]) : 0;
                    $NetSaleTotal[] = array_key_exists($month, $netSaleTotalArray) ? array_sum($netSaleTotalArray[$month]) : 0;
                    $ShippingTotal[] = array_key_exists($month, $ShippingTotalArray) ? array_sum($ShippingTotalArray[$month]) : 0;
                    $CouponTotal[] = array_key_exists($month, $CouponTotalArray) ? array_sum($CouponTotalArray[$month]) : 0;

                    $dailySales = array_key_exists($month, $grossSaleTotalArray) ? $grossSaleTotalArray[$month] : [];
                    $averageGrossSales[] = count($dailySales) > 0 ? (array_sum($dailySales) / count($dailySales)) : 0;

                    $dailyNetSales = array_key_exists($month, $netSaleTotalArray) ? $netSaleTotalArray[$month] : [];
                    $averageNetSales[] = count($dailyNetSales) > 0 ? (array_sum($dailyNetSales) / count($dailyNetSales)) : 0;
                }
            } else {
                $month = date('y-n-j');
                $GrossSaleTotal[] = array_key_exists($month, $grossSaleTotalArray) ? array_sum($grossSaleTotalArray[$month]) : 0;
                $NetSaleTotal[] = array_key_exists($month, $netSaleTotalArray) ? array_sum($netSaleTotalArray[$month]) : 0;
                $ShippingTotal[] = array_key_exists($month, $ShippingTotalArray) ? array_sum($ShippingTotalArray[$month]) : 0;
                $CouponTotal[] = array_key_exists($month, $CouponTotalArray) ? array_sum($CouponTotalArray[$month]) : 0;

                $dailySales = array_key_exists($month, $grossSaleTotalArray) ? $grossSaleTotalArray[$month] : [];
                $averageGrossSales[] = count($dailySales) > 0 ? (array_sum($dailySales) / count($dailySales)) : 0;

                $dailyNetSales = array_key_exists($month, $netSaleTotalArray) ? $netSaleTotalArray[$month] : [];
                $averageNetSales[] = count($dailyNetSales) > 0 ? (array_sum($dailyNetSales) / count($dailyNetSales)) : 0;
            }
        }

        $html = '';
        $html = view('reports.order_bar_chart_data', compact('TotalOrder', 'PurchasedProductItemTotal', 'TotalgrossSale', 'currency', 'TotalNetSale', 'TotalCouponAmount', 'TotalShippingCharge'))->render();

        $return['html'] = $html;

        $return['TotalOrderCount'] = $TotalOrderCount;
        $return['NetSaleTotal'] = $NetSaleTotal;
        $return['AverageNetSales'] = $averageNetSales;
        $return['GrossSaleTotal'] = $GrossSaleTotal;
        $return['AverageGrossSales'] = $averageGrossSales;
        $return['PurchasedItemTotal'] = $PurchasedItemTotal;
        $return['ShippingTotal'] = $ShippingTotal;
        $return['CouponTotal'] = $CouponTotal;
        $return['monthList'] = $monthList;
        Session::put('order_bar_chart_report', $return);

        return response()->json($return);
    }

    public function StockReport(Request $request)
    {
        $store_id = Store::where('id', getCurrentStore())->first();
        $theme_id =  $store_id->theme_id;
        $settings = Setting::where('theme_id', $theme_id)->where('store_id', $store_id->id)->pluck('value', 'name')->toArray();

        $products = Product::where('theme_id', $store_id->theme_id)
            ->where('store_id', getCurrentStore())
            ->get();
        $low_stock_products = [];
        $out_of_stock_products = [];
        $most_stocked_products = [];
        $product_data = [];
       
        foreach ($products as $product) {
            if ($product->variant_product == 0) {
                if ($product->track_stock == 1 && $product->product_stock != 0 && $product->product_stock > 0 && $product->product_stock <= $product->low_stock_threshold) {
                    $low_stock_product = [
                        'product_name' => $product->name,
                        'stock_status' => $product->stock_order_status,
                        'stock' => $product->product_stock,
                        'category' => $product->ProductData->name,
                        'product_id' => $product->id,
                    ];
                    $low_stock_products[] = $low_stock_product;
                } elseif ($product->track_stock == 1 && isset($settings['out_of_stock_threshold']) && $product->product_stock <= $settings['out_of_stock_threshold']) {
                    $out_of_stock_product = [
                        'product_name' => $product->name,
                        'stock_status' => $product->stock_order_status,
                        'stock' => $product->product_stock,
                        'category' => $product->ProductData->name,
                        'product_id' => $product->id,
                    ];
                    $out_of_stock_products[] = $out_of_stock_product;
                }
                if ($product->track_stock == 1 && $product->product_stock != 0 && $product->product_stock > 0) {
                    $product_data[] = [
                        'product_name' => $product->name,
                        'stock_status' => $product->stock_order_status,
                        'stock' => $product->product_stock, // Use product_stock here
                        'category' => $product->ProductData->name,
                        'product_id' => $product->id,
                    ];
                }
            } else {
                $product_stocks = ProductVariant::where('product_id', $product->id)->get();

                foreach ($product_stocks as $stock) {
                    $variationOptions = explode(',', $stock->variation_option);

                    if (in_array('manage_stock', $variationOptions) && $stock->stock != 0 && $stock->stock > 0 && $stock->stock <= $stock->low_stock_threshold) {
                        $low_stock_product = [
                            'product_name' => $product->name . '(' . $stock->variant . ')',
                            'category' => $product->ProductData->name,
                            'product_id' => $product->id,
                        ];
                        if ($stock->stock_order_status == 'allow') {
                            $low_stock_product['stock_status'] = 'in_stock';
                        } elseif ($stock->stock_order_status == 'on_backorder') {
                            $low_stock_product['stock_status'] = 'out of stock';
                        }


                        $low_stock_product['stock'] = $stock->stock;

                        $low_stock_products[] = $low_stock_product;
                    } elseif (in_array('manage_stock', $variationOptions) && isset($settings['out_of_stock_threshold']) && $stock->stock <= $settings['out_of_stock_threshold']) {
                        $out_of_stock_product = [
                            'product_name' => $product->name . '(' . $stock->variant . ')',
                            'stock_status' => $stock->stock_order_status,
                            'stock' => $stock->stock,
                            'category' => $product->ProductData->name,
                            'product_id' => $product->id,
                        ];
                        $out_of_stock_products[] = $out_of_stock_product;
                    }
                    if (in_array('manage_stock', $variationOptions) && $stock->stock != 0 && $stock->stock > 0) {
                        $product_data[] = [
                            'product_name' => $product->name . '(' . $stock->variant . ')',
                            'category' => $product->ProductData->name,
                            'product_id' => $product->id,
                        ];
                        if ($stock->stock_order_status == 'allow') {
                            $product_data[count($product_data) - 1]['stock_status'] = 'in_stock';
                        } elseif ($stock->stock_order_status == 'on_backorder' || $stock->stock_order_status == 'not_allow') {
                            $product_data[count($product_data) - 1]['stock_status'] = 'out_of_stock';
                        }

                        $product_data[count($product_data) - 1]['stock'] = $stock->stock; // Use stock here
                    }
                }
            }
        }

        usort($product_data, function ($stocks, $stock_data) {
            return $stock_data['stock'] - $stocks['stock'];
        });

        
        $most_stocked_products = array_slice($product_data, 0, 10);
        // dd($most_stocked_products);
        return view('reports.stock_report', compact('low_stock_products', 'out_of_stock_products', 'most_stocked_products'));
    }


    public function OrderSaleProductReport(Request $request)
    {
        $store_id = Store::where('id', getCurrentStore())->first();
        $products = Product::where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->pluck('name', 'id');
        return view('reports.sale_by_product', compact('products'));
    }


    public function order_product_reports(Request $request)
    {
        $store_id = Store::where('id', getCurrentStore())->first();

        $currency = Utility::GetValueByName('CURRENCY');
        if ($request->chart_data == 'last-month') {
            $selectedProducts = $request->selectedProducts;

            $NetSaleTotal = array_fill(0, 31, 0);
            $PurchasedItemTotal = array_fill(0, 31, 0);
            if (empty($selectedProducts)) {
                $selectedProducts = Product::all()->where('store_id', getCurrentStore())->where('theme_id', $store_id->theme_id)->pluck('id');
            }
            $PurchasedItem = 0;
            $Totalsale = 0;

            if ($selectedProducts) {
                foreach ($selectedProducts as $productId) {
                    $totalNetSalesArray = [];
                    $totalPurchasedItemsArray = [];

                    $currentYear = date('Y');
                    $previousMonth = date('n') - 1;
                    if ($previousMonth == 0) {
                        $currentYear -= 1;
                        $previousMonth = 12;
                    }

                    $daysInPreviousMonth = cal_days_in_month(CAL_GREGORIAN, $previousMonth, $currentYear);

                    for ($day = 1; $day <= $daysInPreviousMonth; $day++) {

                        $date = "$currentYear-$previousMonth-$day";

                        $startOfDay = date('Y-m-d 00:00:00', strtotime($date));
                        $endOfDay = date('Y-m-d 23:59:59', strtotime($date));

                        $orders = Order::selectRaw('orders.*, DATE(order_date) as DATE, MONTH(order_date) as month')
                            ->where('theme_id', $store_id->theme_id)
                            ->where('store_id', getCurrentStore())
                            ->where('order_date', '>=', $startOfDay)
                            ->where('order_date', '<=', $endOfDay)
                            ->get();

                        $totalPurchasedItems = 0;
                        $totalNetSales = 0;

                        foreach ($orders as $order) {
                            $products = json_decode($order->product_json, true);
                            foreach ($products as $product) {
                                if ($product['product_id'] == $productId) {
                                    $totalPurchasedItems += intval($product['qty']);
                                    $PurchasedItem += intval($product['qty']);
                                    $totalNetSales += (float)($product['final_price']);
                                    $Totalsale += (float)($product['final_price']);
                                }
                            }
                        }

                        $totalPurchasedItemsArray[] = $totalPurchasedItems;
                        $totalNetSalesArray[] = $totalNetSales;
                    }

                    $PurchasedItemTotal = array_map(
                        function ($a, $b) {
                            return $a + $b;
                        },
                        $PurchasedItemTotal,
                        $totalPurchasedItemsArray
                    );

                    $NetSaleTotal = array_map(
                        function ($a, $b) {
                            return $a + $b;
                        },
                        $NetSaleTotal,
                        $totalNetSalesArray
                    );
                }
            }
            $monthList = $month = $this->getLastMonthDatesFormatted();
        } elseif ($request->chart_data == 'this-month') {
            $selectedProducts = $request->input('selectedProducts', []);

            $NetSaleTotal = array_fill(0, 31, 0);
            $PurchasedItemTotal = array_fill(0, 31, 0);
            $Totalsale = 0;
            $PurchasedItem = 0;
            if (empty($selectedProducts)) {
                $selectedProducts = Product::all()->where('store_id', getCurrentStore())->where('theme_id', $store_id->theme_id)->pluck('id');
            }
            if (!empty($selectedProducts)) {
                foreach ($selectedProducts as $productId) {
                    $currentYear = date('Y');
                    $currentMonth = date('n');
                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);

                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $startOfDay = "$currentYear-$currentMonth-" . sprintf('%02d', $day);
                        $endOfDay = "$currentYear-$currentMonth-" . sprintf('%02d', $day) . ' 23:59:59';

                        $orders = Order::where('theme_id', $store_id->theme_id)
                            ->where('store_id', getCurrentStore())
                            ->whereBetween('order_date', [$startOfDay, $endOfDay])
                            ->get();

                        $totalPurchasedItems = 0;
                        $totalNetSales = 0;

                        foreach ($orders as $order) {
                            $products = json_decode($order->product_json, true);
                            foreach ($products as $product) {
                                if ($product['product_id'] == $productId) {
                                    $totalPurchasedItems += intval($product['qty']);
                                    $PurchasedItem += intval($product['qty']);
                                    $totalNetSales += (float)($product['final_price']);
                                    $Totalsale += (float)($product['final_price']);
                                }
                            }
                        }

                        $PurchasedItemTotal[$day - 1] += $totalPurchasedItems;
                        $NetSaleTotal[$day - 1] += $totalNetSales;
                    }
                }
            }

            $monthList = $month = $this->getCurrentMonthDates();
        } elseif ($request->chart_data == 'seven-day') {
            $selectedProducts = $request->selectedProducts;

            $NetSaleTotal = [];
            $PurchasedItemTotal = [];
            $monthList = [];
            $Totalsale = 0;
            $PurchasedItem = 0;
            if ($selectedProducts) {
                for ($i = 6; $i >= 0; $i--) {
                    $totalPurchasedItems = 0;
                    $totalNetSales = 0;

                    $currentDate = date('Y-m-d', strtotime("-$i days"));
                    $startOfDay = $currentDate . ' 00:00:00';
                    $endOfDay = $currentDate . ' 23:59:59';

                    $monthList[] = date('d-M', strtotime($currentDate));

                    $orders = Order::whereBetween('order_date', [$startOfDay, $endOfDay])
                        ->where('theme_id', $store_id->theme_id)
                        ->where('store_id', getCurrentStore())
                        ->get();

                    foreach ($orders as $order) {
                        $products = json_decode($order->product_json, true);
                        foreach ($products as $product) {

                            if (in_array($product['product_id'], $selectedProducts)) {
                                $totalPurchasedItems += intval($product['qty']);
                                $PurchasedItem += intval($product['qty']);
                                $totalNetSales += (float)($product['final_price'] );
                                $Totalsale += (float)($product['final_price']);
                            }
                        }
                    }
                    $PurchasedItemTotal[] = $totalPurchasedItems;
                    $NetSaleTotal[] = $totalNetSales;
                }
            }else{
                $Totalsale = 0;
                $PurchasedItem = 0;
                for ($i = 6; $i >= 0; $i--) {
                    $totalPurchasedItems = 0;
                    $totalNetSales = 0;

                    $currentDate = date('Y-m-d', strtotime("-$i days"));
                    $startOfDay = $currentDate . ' 00:00:00';
                    $endOfDay = $currentDate . ' 23:59:59';

                    $monthList[] = date('d-M', strtotime($currentDate));

                    $orders = Order::whereBetween('order_date', [$startOfDay, $endOfDay])
                        ->where('theme_id', $store_id->theme_id)
                        ->where('store_id', getCurrentStore())
                        ->get();

                    foreach ($orders as $order) {
                        $products = json_decode($order->product_json, true);
                        foreach ($products as $product) {
                            $totalPurchasedItems += intval($product['qty']);
                            $PurchasedItem += intval($product['qty']);
                            $totalNetSales += (float)($product['final_price']);
                            $Totalsale += (float)($product['final_price']);
                        }
                    }
                    $PurchasedItemTotal[] = $totalPurchasedItems;
                    $NetSaleTotal[] = $totalNetSales;
                }
            }
        } elseif ($request->chart_data == 'year') {
            $selectedProducts = $request->selectedProducts;

            $NetSaleTotal = [];
            $PurchasedItemTotal = [];
            if (empty($selectedProducts)) {
                $selectedProducts = Product::all()->where('store_id', getCurrentStore())->where('theme_id', $store_id->theme_id)->pluck('id');
            }
            $Totalsale = 0;
            $PurchasedItem = 0;
            if ($selectedProducts) {
                foreach ($selectedProducts as $productId) {
                    $totalNetSalesArray = [];
                    $totalPurchasedItemsArray = [];

                    for ($i = 1; $i <= 12; $i++) {
                        $totalPurchasedItems = 0;
                        $totalNetSales = 0;

                        $startOfMonth = date('Y-m-01', strtotime(date('Y') . '-' . $i . '-01'));
                        $endOfMonth = date('Y-m-t', strtotime(date('Y') . '-' . $i . '-01'));

                        $orders = Order::where('theme_id', $store_id->theme_id)
                            ->where('store_id', getCurrentStore())
                            ->where('order_date', '>=', $startOfMonth)
                            ->where('order_date', '<=', $endOfMonth)
                            ->get();

                        foreach ($orders as $order) {
                            $products = json_decode($order->product_json, true);
                            foreach ($products as $product) {
                                if ($product['product_id'] == $productId) {
                                    $totalPurchasedItems += intval($product['qty']);
                                    $PurchasedItem += intval($product['qty']);
                                    $totalNetSales += (float)($product['final_price']);
                                    $Totalsale += (float)($product['final_price']);
                                }
                            }
                        }

                        $totalPurchasedItemsArray[] = $totalPurchasedItems;
                        $totalNetSalesArray[] = $totalNetSales;
                    }

                    $PurchasedItemTotal = array_map(
                        function ($a, $b) {
                            return $a + $b;
                        },
                        $PurchasedItemTotal,
                        $totalPurchasedItemsArray
                    );

                    $NetSaleTotal = array_map(
                        function ($a, $b) {
                            return $a + $b;
                        },
                        $NetSaleTotal,
                        $totalNetSalesArray
                    );
                }
            }
            $monthList = $month = $this->yearMonth();
        } else {
            $selectedProducts = $request->selectedProducts;

            if (str_contains($request->Date, ' to ')) {
                $date_range = explode(' to ', $request->Date);
                if (count($date_range) === 2) {
                    $from_date = date('Y-m-d', strtotime($date_range[0]));
                    $to_date = date('Y-m-d', strtotime($date_range[1]));
                } else {
                    $from_date = date('Y-m-d', strtotime($date_range[0]));
                    $to_date = date('Y-m-d', strtotime($date_range[0]));
                }
            } else {
                $from_date = date('Y-m-d', strtotime($request->Date));
                $to_date = date('Y-m-d', strtotime($request->Date));
            }
            $Totalsale = 0;
            $PurchasedItem = 0;
            $orders = Order::selectRaw('orders.*, DATE(order_date) as DATE, MONTH(order_date) as month')
                ->whereDate('order_date', '>=', $from_date)
                ->whereDate('order_date', '<=', $to_date)
                ->where('theme_id', $store_id->theme_id)
                ->where('store_id', getCurrentStore())
                ->get();

            $netSaleTotalArray = [];
            $PurchasedItemArray = [];
            $PurchasedProductItemTotal = 0;
            $TotalNetSale = 0;
            $monthList = [];

            if($selectedProducts)
            {
                foreach ($orders as $order) {
                    $day = date('Y-m-d', strtotime($order->DATE));

                    if (!isset($netSaleTotalArray[$day])) {
                        $netSaleTotalArray[$day] = 0;
                        $PurchasedItemArray[$day] = 0;
                    }

                    $OrderTotalArray[$day][] = 1;
                    $products = json_decode($order->product_json, true);

                    foreach ($products as $product) {
                        if (in_array($product['product_id'], $selectedProducts)) {
                            $totalProductQuantity = intval($product['qty']);
                            $PurchasedItem = intval($product['qty']);
                            $PurchasedItemArray[$day] += $totalProductQuantity;
                            $PurchasedProductItemTotal += $totalProductQuantity;
                            $TotalNetSale += (float)($product['final_price']);
                            $netSaleTotalArray[$day] += (float)($product['final_price']);
                            $Totalsale += (float)($product['final_price']);
                        }
                    }

                    if ($PurchasedItemArray[$day] > 0 && !in_array($day, $monthList)) {
                        $monthList[] = $day;
                    }
                }

                $NetSaleTotal = [];
                $PurchasedItemTotal = [];

                foreach ($monthList as $day) {
                    $NetSaleTotal[] = $netSaleTotalArray[$day];
                    $PurchasedItemTotal[] = $PurchasedItemArray[$day];
                }
            }else{
                $Totalsale = 0;
                $PurchasedItem = 0;
                foreach ($orders as $order) {
                    $day = date('Y-m-d', strtotime($order->DATE));

                    if (!isset($netSaleTotalArray[$day])) {
                        $netSaleTotalArray[$day] = 0;
                        $PurchasedItemArray[$day] = 0;
                    }

                    $OrderTotalArray[$day][] = 1;
                    $products = json_decode($order->product_json, true);

                    foreach ($products as $product) {
                            $totalProductQuantity = intval($product['qty']);
                            $PurchasedItem = intval($product['qty']);
                            $PurchasedItemArray[$day] += $totalProductQuantity;
                            $PurchasedProductItemTotal += $totalProductQuantity;
                            $TotalNetSale += (float)($product['final_price']);
                            $netSaleTotalArray[$day] += (float)($product['final_price']);
                            $Totalsale += (float)($product['final_price']);
                    }

                    if ($PurchasedItemArray[$day] > 0 && !in_array($day, $monthList)) {
                        $monthList[] = $day;
                    }
                }

                $NetSaleTotal = [];
                $PurchasedItemTotal = [];

                foreach ($monthList as $day) {
                    $NetSaleTotal[] = $netSaleTotalArray[$day];
                    $PurchasedItemTotal[] = $PurchasedItemArray[$day];
                }
            }
        }
        $html = '';
        $html = view('reports.order_product_chart',compact('currency','Totalsale','PurchasedItem'))->render();

        $return['html'] = $html;

        $return['NetSaleTotal'] = $NetSaleTotal;
        $return['PurchasedItemTotal'] = $PurchasedItemTotal;
        $return['monthList'] = $monthList;
        Session::put('product_order_report', $return);

        return response()->json($return);
    }

    public function product_order_export(Request $request)
    {

        $requests_data = Session::get('product_order_report');
        $return['monthList'] = $requests_data['monthList'];
        $return['NetSaleTotal'] = $requests_data['NetSaleTotal'];
        $return['PurchasedItemTotal'] = $requests_data['PurchasedItemTotal'];
        return response()->json($return);
    }

    public function OrderSaleCategoryReport(Request $request)
    {
        $store_id = Store::where('id', getCurrentStore())->first();
        $MainCategoryList = MainCategory::where('status', 1)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->pluck('name', 'id');
        return view('reports.sale_by_category', compact('MainCategoryList'));
    }

    public function order_category_reports(Request $request)
    {
        $store_id = Store::where('id', getCurrentStore())->first();
        $currency = Utility::GetValueByName('CURRENCY');
        if ($request->chart_data == 'last-month') {
            $selectedCategory = $request->selectedCategory;
            $year = date('Y');
            $monthList = $this->yearMonth();
            $NetSaleTotal = [];

            $firstDayOfPreviousMonth = date('Y-m-d', strtotime('first day of last month'));
            $lastDayOfPreviousMonth = date('Y-m-d', strtotime('last day of last month'));
            $daysInPreviousMonth = date('j', strtotime('last day of last month'));

            if (empty($selectedCategory)) {
                $selectedCategory = MainCategory::all()->where('store_id', getCurrentStore())->where('theme_id', $store_id->theme_id)->pluck('id');
            }

            $categoryNetSales = [];

            foreach ($selectedCategory as $categoryId) {
                $category = MainCategory::find($categoryId);
                if ($category) {
                    $categoryNetSales[$category->name] = array_fill(0, $daysInPreviousMonth, 0);
                }
            }

            $orders = Order::where('theme_id', $store_id->theme_id)
                ->where('store_id', getCurrentStore())
                ->whereYear('order_date', $year)
                ->whereBetween('order_date', [$firstDayOfPreviousMonth, $lastDayOfPreviousMonth])
                ->get();
                $NetSalesofcategory = [];
            foreach ($selectedCategory as $categoryId) {
                $category = MainCategory::find($categoryId);
                if (!$category) {
                    continue;
                }

                foreach ($orders as $order) {
                    $orderDate = date('j', strtotime($order->order_date));

                    $products = json_decode($order->product_json, true);

                    foreach ($products as $product) {
                        $product_data = Product::find($product['product_id']);

                        if ($product_data && $product_data->category_id == $categoryId) {
                            $totalProductQuantity = intval($product['qty']);
                            $categoryNetSales[$category->name][$orderDate - 1] += (float)($product['final_price']);
                        }
                    }
                }

                $NetSalesofcategory[$category->name] = array_sum($categoryNetSales[$category->name]);
            }

            $allCategoriesTotal = array_fill(0, $daysInPreviousMonth, 0);

            foreach ($categoryNetSales as $categoryName => $categoryArray) {
                $NetSaleTotal[] = [
                    'name' => $categoryName,
                    'data' => $categoryArray,
                ];

                $allCategoriesTotal = array_map(
                    function ($a, $b) {
                        return $a + $b;
                    },
                    $allCategoriesTotal,
                    $categoryArray
                );
            }
            if (!$request->selectedCategory) {
                $NetSaleTotal[] = [
                    'name' => 'All Categories',
                    'data' => $allCategoriesTotal,
                ];
            }
            $monthList = $month = $this->getLastMonthDatesFormatted();
        } elseif ($request->chart_data == 'this-month') {
            $selectedCategory = $request->selectedCategory;
            $year = date('Y');
            $monthList = $this->yearMonth();
            $NetSaleTotal = [];

            $daysInCurrentMonth = date('j', strtotime('last day of this month'));

            if (empty($selectedCategory)) {
                $selectedCategory = MainCategory::all()->where('store_id', getCurrentStore())->where('theme_id', $store_id->theme_id)->pluck('id');
            }

            $categoryNetSales = [];

            foreach ($selectedCategory as $categoryId) {
                $category = MainCategory::find($categoryId);
                if ($category) {
                    $categoryNetSales[$category->name] = array_fill(0, $daysInCurrentMonth, 0);
                }
            }

            $orders = Order::where('theme_id', $store_id->theme_id)
                ->where('store_id', getCurrentStore())
                ->whereYear('order_date', $year)
                ->whereMonth('order_date', date('m'))
                ->get();

            foreach ($selectedCategory as $categoryId) {
                $category = MainCategory::find($categoryId);
                if (!$category) {
                    continue;
                }

                foreach ($orders as $order) {
                    $orderDay = date('j', strtotime($order->order_date));

                    $products = json_decode($order->product_json, true);

                    foreach ($products as $product) {
                        $product_data = Product::find($product['product_id']);

                        if ($product_data && $product_data->category_id == $categoryId) {
                            $totalProductQuantity = intval($product['qty']);
                            $categoryNetSales[$category->name][$orderDay - 1] += (float)($product['final_price']);
                        }
                    }
                }
            }

            $allCategoriesTotal = array_fill(0, $daysInCurrentMonth, 0);
            $NetSalesofcategory = [];
            foreach ($categoryNetSales as $categoryName => $categoryArray) {
                $NetSaleTotal[] = [
                    'name' => $categoryName,
                    'data' => $categoryArray,
                ];
                $totalNetSale = array_sum($categoryArray);
                $NetSalesofcategory[$categoryName] = $totalNetSale;
                $allCategoriesTotal = array_map(
                    function ($a, $b) {
                        return $a + $b;
                    },
                    $allCategoriesTotal,
                    $categoryArray
                );
            }

            if (!$request->selectedCategory) {
                $NetSaleTotal[] = [
                    'name' => 'All Categories',
                    'data' => $allCategoriesTotal,
                ];
            }

            $monthList = $month = $this->getCurrentMonthDates();
        } elseif ($request->chart_data == 'seven-day') {
            $selectedCategory = $request->selectedCategory;
            $year = date('Y');
            $NetSaleTotal = [];
            $currentDate = date('Y-m-d');
            $sevenDaysAgo = date('Y-m-d', strtotime('-7 days'));

            $monthList = [];
            for ($i = 0; $i < 7; $i++) {
                $monthList[] = date('d-M', strtotime("-$i days", strtotime($currentDate)));
            }

            if (empty($selectedCategory)) {
                $selectedCategory = MainCategory::where('store_id', getCurrentStore())
                    ->where('theme_id', $store_id->theme_id)
                    ->pluck('id');
            }

            $categoryNetSales = [];

            foreach ($selectedCategory as $categoryId) {
                $category = MainCategory::find($categoryId);
                if ($category) {
                    $categoryNetSales[$category->name] = array_fill(0, 7, 0);
                }
            }

            $orders = Order::where('theme_id', $store_id->theme_id)
                ->where('store_id', getCurrentStore())
                ->whereDate('order_date', '>=', $sevenDaysAgo)
                ->get();

            foreach ($selectedCategory as $categoryId) {
                $category = MainCategory::find($categoryId);
                if (!$category) {
                    continue;
                }

                foreach ($orders as $order) {
                    $orderDate = date('Y-m-d', strtotime($order->order_date));

                    $products = json_decode($order->product_json, true);

                    foreach ($products as $product) {
                        $product_data = Product::find($product['product_id']);

                        if ($product_data && $product_data->category_id == $categoryId) {
                            $totalProductQuantity = intval($product['qty']);
                            $daysAgo = date_diff(date_create($currentDate), date_create($orderDate))->days;

                            if ($daysAgo >= 0 && $daysAgo < 7) {
                                $categoryNetSales[$category->name][$daysAgo] += (float)($product['final_price']);
                            }
                        }
                    }
                }
            }

            $allCategoriesTotal = array_fill(0, 7, 0);
            $NetSalesofcategory = [];
            foreach ($categoryNetSales as $categoryName => $categoryArray) {
                $NetSaleTotal[] = [
                    'name' => $categoryName,
                    'data' => $categoryArray,
                ];
                $totalNetSale = array_sum($categoryArray);

                $NetSalesofcategory[$categoryName] = $totalNetSale;
                $allCategoriesTotal = array_map(
                    function ($a, $b) {
                        return $a + $b;
                    },
                    $allCategoriesTotal,
                    $categoryArray
                );
            }

            if (!$request->selectedCategory) {
                $NetSaleTotal[] = [
                    'name' => 'All Categories',
                    'data' => $allCategoriesTotal,
                ];
            }
        } elseif ($request->chart_data == 'year') {

            $selectedCategory = $request->selectedCategory;
            $year = date('Y');
            $monthList = $this->yearMonth();
            $NetSaleTotal = [];
            $NetSalesofcategory = [];

            $totalNetSalesAllCategories = array_fill(0, count($monthList), 0);

            if (empty($selectedCategory)) {
                $selectedCategory = MainCategory::all()->where('store_id', getCurrentStore())->where('theme_id', $store_id->theme_id)->pluck('id');
            }

            $categoryNetSales = [];

            foreach ($selectedCategory as $categoryId) {
                $category = MainCategory::find($categoryId);
                if ($category) {
                    $categoryNetSales[$category->name] = array_fill(0, count($monthList), 0);
                }
            }

            $orders = Order::where('theme_id', $store_id->theme_id)
                ->where('store_id', getCurrentStore())
                ->whereYear('order_date', $year)
                ->get();

            foreach ($monthList as $index => $month) {
                foreach ($selectedCategory as $categoryId) {
                    $category = MainCategory::find($categoryId);
                    if (!$category) {
                        continue;
                    }

                    $totalNetSales = 0;

                    foreach ($orders as $order) {
                        $products = json_decode($order->product_json, true);

                        foreach ($products as $product) {
                            $product_data = Product::find($product['product_id']);

                            if ($product_data && $product_data->category_id == $categoryId) {
                                $orderDate = date('Y-m', strtotime($order->order_date));
                                if ($orderDate == date('Y-m', strtotime($year . '-' . ($index + 1)))) {
                                    // $totalProductQuantity = intval($product['qty']);
                                    $totalNetSales += (float)($product['final_price']);
                                }
                            }
                        }
                    }

                    $categoryNetSales[$category->name][$index] = $totalNetSales;

                    $totalNetSalesAllCategories[$index] += $totalNetSales;

                    if (!isset($NetSalesofcategory[$category->name])) {
                        $NetSalesofcategory[$category->name] = 0;
                    }
                    $NetSalesofcategory[$category->name] += $totalNetSales;
                }
            }

            foreach ($categoryNetSales as $categoryName => $categoryArray) {
                $NetSaleTotal[] = [
                    'name' => $categoryName,
                    'data' => $categoryArray,
                ];
            }

            if (!$request->selectedCategory) {
                $NetSaleTotal[] = [
                    'name' => 'All Categories',
                    'data' => $totalNetSalesAllCategories,
                ];
            }
        } else {
            if (str_contains($request->Date, ' to ')) {
                $date_range = explode(' to ', $request->Date);
                if (count($date_range) === 2) {
                    $from_date = date('Y-m-d', strtotime($date_range[0]));
                    $to_date = date('Y-m-d', strtotime($date_range[1]));
                } else {
                    $from_date = date('Y-m-d', strtotime($date_range[0]));
                    $to_date = date('Y-m-d', strtotime($date_range[0]));
                }
            } else {
                $from_date = date('Y-m-d', strtotime($request->Date));
                $to_date = date('Y-m-d', strtotime($request->Date));
            }

            $selectedCategory = $request->selectedCategory;
            $year = date('Y');
            $NetSaleTotal = [];

            $startDate = new \DateTime($from_date);
            $endDate = new \DateTime($to_date);

            $interval = new \DateInterval('P1D');
            $period = new \DatePeriod($startDate, $interval, $endDate);

            $monthList = [];

            foreach ($period as $date) {
                $monthList[] = $date->format('d-M');
            }

            if (empty($selectedCategory)) {
                $selectedCategory = MainCategory::where('store_id', getCurrentStore())
                    ->where('theme_id', $store_id->theme_id)
                    ->pluck('id');
            }

            $categoryNetSales = [];

            foreach ($selectedCategory as $categoryId) {
                $category = MainCategory::find($categoryId);
                if ($category) {
                    $categoryNetSales[$category->name] = array_fill(0, count($monthList), 0);
                }
            }

            $orders = Order::where('theme_id', $store_id->theme_id)
                ->where('store_id', getCurrentStore())
                ->whereDate('order_date', '>=', $from_date)
                ->whereDate('order_date', '<=', $to_date)
                ->get();

            foreach ($selectedCategory as $categoryId) {
                $category = MainCategory::find($categoryId);
                if (!$category) {
                    continue;
                }

                foreach ($orders as $order) {
                    $orderDate = date('d-M', strtotime($order->order_date));

                    $products = json_decode($order->product_json, true);

                    foreach ($products as $product) {
                        $product_data = Product::find($product['product_id']);

                        if ($product_data && $product_data->category_id == $categoryId) {
                            $totalProductQuantity = intval($product['qty']);
                            $monthIndex = array_search($orderDate, $monthList);

                            if ($monthIndex !== false) {
                                $categoryNetSales[$category->name][$monthIndex] += (float)($product['final_price']);
                            }
                        }
                    }
                }
            }

            $allCategoriesTotal = array_fill(0, count($monthList), 0);
            $NetSalesofcategory = [];
            foreach ($categoryNetSales as $categoryName => $categoryArray) {
                $NetSaleTotal[] = [
                    'name' => $categoryName,
                    'data' => $categoryArray,
                ];

                $totalNetSale = array_sum($categoryArray);

                $NetSalesofcategory[$categoryName] = $totalNetSale;
                $allCategoriesTotal = array_map(
                    function ($a, $b) {
                        return $a + $b;
                    },
                    $allCategoriesTotal,
                    $categoryArray
                );
            }

            if (!$request->selectedCategory) {
                $NetSaleTotal[] = [
                    'name' => 'All Categories',
                    'data' => $allCategoriesTotal,
                ];
            }
        }
        $html = '';
        $html = view('reports.order_category_chart',compact('currency','NetSalesofcategory'))->render();

        $return['html'] = $html;

        $return['NetSaleTotal'] = $NetSaleTotal;
        $return['monthList'] = $monthList;
        Session::put('category_order_report', $return);

        return response()->json($return);
    }

    public function category_order_export(Request $request)
    {
        $requests_data = Session::get('category_order_report');
        $return['monthList'] = $requests_data['monthList'];
        $return['NetSaleTotal'] = $requests_data['NetSaleTotal'];
        return response()->json($return);
    }

    public function OrderDownlodableReport(Request $request)
    {
        $store_id = Store::where('id', getCurrentStore())->first();
        $orders = Order::where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->orderBy('id','desc')->get();
        return view('reports.downloadable_product_report', compact('orders'));
    }
}

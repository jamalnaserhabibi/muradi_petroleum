<?php

namespace App\Http\Controllers;

use App\Helpers\AfghanCalendarHelper;
use App\Models\User;
use App\Models\Product;
use App\Models\Contract;
use App\Models\type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\CalendarUtils;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }

    public function dashboard(Request $request)
    {
        if ($request->has('start_date') && $request->has('end_date')) {
            $startOfMonth = CalendarUtils::createCarbonFromFormat('Y/m/d', $request->start_date)->toDateString();
            $endOfMonth = CalendarUtils::createCarbonFromFormat('Y/m/d', $request->end_date)->toDateString();
            $afghaniStartDate = $request->start_date;
            $afghaniEndDate = $request->end_date;
        } else {
            $monthRange = AfghanCalendarHelper::getCurrentShamsiMonthRange();
            $startOfMonth = $monthRange['start'];
            $endOfMonth = $monthRange['end'];
            $afghaniStartDate = AfghanCalendarHelper::toAfghanDateFormat($startOfMonth);
            $afghaniEndDate = AfghanCalendarHelper::toAfghanDateFormat($endOfMonth);
        }

        $sarafiPayments = DB::table('sarafi_payments')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->select(
                DB::raw('COALESCE(SUM(equivalent_dollar), 0) as total_equivalent_dollar'),
                DB::raw('COALESCE(SUM(amount_dollar), 0) as total_amount_dollar')
            )
            ->first();

        $sarafiPickups = DB::table('sarafi_pickup')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->select(DB::raw('COALESCE(SUM(amount), 0) as total_amount'))
            ->first();

        $balance = ($sarafiPayments->total_equivalent_dollar + $sarafiPayments->total_amount_dollar) - $sarafiPickups->total_amount;

        $purchases = DB::table('purchase')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->select(
                'product_id',
                'products.product_name',
                DB::raw('COALESCE(SUM(rate * amount), 0) as total_purchase_value'),
                DB::raw('COALESCE(SUM(amount), 0) as total_purchase_amount'),
                DB::raw('CASE 
                    WHEN products.product_name = "Gas" THEN COALESCE(SUM(amount * heaviness), 0)
                    ELSE COALESCE(SUM((1000000 / heaviness) * amount), 0)
                END as total_liters')
            )
            ->leftJoin('products', 'products.id', '=', 'purchase.product_id')
            ->groupBy('product_id', 'products.product_name')
            ->get()
            // ->keyBy('product_id');
            ->map(function ($purchases) use ($startOfMonth, $endOfMonth) {
                return [
                    'product_id' => $purchases->product_id,
                    'name' => $purchases->product_name,
                    'total_purchase_value' => $purchases->total_purchase_value,
                    'total_purchase_amount' => $purchases->total_purchase_amount,
                    'total_purchase_liters' => $purchases->total_liters,
                    'icon' => $this->getProductIcon($purchases->product_name),
                    'bg_color' => $this->getProductColor($purchases->product_name),
                    'is_money' => in_array($purchases->product_id, [13, 14, 15]),
                    'month_start' => $startOfMonth,
                    'month_end' => $endOfMonth,
                ];
            });

        $products = Product::leftJoin('towers', 'towers.product_id', '=', 'products.id')
            ->leftJoin('distribution', function ($join) use ($startOfMonth, $endOfMonth) {
                $join->on('distribution.tower_id', '=', 'towers.id')
                    ->whereBetween('distribution.date', [$startOfMonth, $endOfMonth]);
            })
            ->selectRaw('
                products.id,
                products.product_name,
                COALESCE(SUM(distribution.rate * distribution.amount), 0) as total_value,
                COALESCE(SUM(distribution.amount), 0) as total_amount
            ')
            ->groupBy('products.id', 'products.product_name')
            ->orderBy('products.id')
            ->get()
            ->map(function ($product) use ($startOfMonth, $endOfMonth) {
                return [
                    'id' => $product->id,
                    'name' => $product->product_name,
                    'total_value' => $product->total_value,
                    'total_amount' => $product->total_amount,
                    'icon' => $this->getProductIcon($product->product_name),
                    'bg_color' => $this->getProductColor($product->product_name),
                    'is_money' => in_array($product->id, [13, 14, 15]),
                    'month_start' => $startOfMonth,
                    'month_end' => $endOfMonth,
                ];
            });



        // Chart Data

        $nonMoneyProducts = $products->filter(fn($product) => !$product['is_money']);
        $chartLabels = $nonMoneyProducts->pluck('name');
        $chartValues = $nonMoneyProducts->pluck('total_amount');

        $PaymentTotalbalance = Contract::join('customers', 'contracts.customer_id', '=', 'customers.id')
            ->leftJoinSub(
                DB::table('distribution')
                    ->join('towers', 'distribution.tower_id', '=', 'towers.id')
                    ->where('towers.product_id', '!=', 14) // Sales (not payments)
                    ->whereBetween('distribution.date', [$startOfMonth, $endOfMonth])
                    ->select('distribution.contract_id', DB::raw('SUM(distribution.amount * distribution.rate) as total_sales'))
                    ->groupBy('distribution.contract_id'),
                'sales_summary',
                'contracts.id',
                '=',
                'sales_summary.contract_id'
            )
            ->leftJoinSub(
                DB::table('distribution')
                    ->join('towers', 'distribution.tower_id', '=', 'towers.id')
                    ->where('towers.product_id', 14) // Payments only
                    ->whereBetween('distribution.date', [$startOfMonth, $endOfMonth])
                    ->select('distribution.contract_id', DB::raw('SUM(distribution.amount * distribution.rate) as total_payments'))
                    ->groupBy('distribution.contract_id'),
                'payments_summary',
                'contracts.id',
                '=',
                'payments_summary.contract_id'
            )
            ->select(
                DB::raw('SUM(COALESCE(payments_summary.total_payments, 0) - COALESCE(sales_summary.total_sales, 0)) as total_balance')
            )
            ->where('contracts.isActive', 1)
            ->first();

        return view('admin.dashboard', compact(
            'sarafiPayments',
            'sarafiPickups',
            'balance',
            'products',
            'afghaniStartDate',
            'afghaniEndDate',
            'chartLabels',
            'chartValues',
            'purchases',
            'PaymentTotalbalance'
        ));
    }



    private function getProductIcon($name)
    {
        $name = strtolower($name);

        if (str_contains($name, 'پول') || in_array($name, ['رفت پول', 'آمد پول', 'مصرف'])) {
            return 'fa-money-bill-wave';
        } elseif (str_contains($name, 'gas')) {
            return 'fa-fire';
        } elseif (str_contains($name, 'p-') || str_contains($name, 'petrol')) {
            return 'fa-gas-pump';
        } elseif (str_contains($name, 'd-') || str_contains($name, 'diesel')) {
            return 'fa-truck-moving';
        }

        return 'fa-box';
    }

    private function getProductColor($name)
    {
        $name = strtolower($name);

        if (str_contains($name, 'پول') || in_array($name, ['رفت پول', 'آمد پول', 'مصرف'])) {
            return 'bg-purple';
        } elseif (str_contains($name, 'gas')) {
            return 'bg-orange';
        } elseif (str_contains($name, 'p-95')) {
            return 'bg-danger';
        } elseif (str_contains($name, 'p-92')) {
            return 'bg-warning';
        } elseif (str_contains($name, 'p-80')) {
            return 'bg-info';
        } elseif (str_contains($name, 'd-')) {
            return 'bg-primary';
        }

        return 'bg-secondary';
    }
    public function useraccounts()
    {
        if (Auth::user()->usertype !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        $users = User::all();
        return view('admin.useraccounts', compact('users'));
    }

    public function table()
    {

        $type = type::all();
        return view('admin.table', compact('type'));
    }
}

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
        // Date handling (unchanged)
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

        // 1. Get all financial data in one query
        $financialData = DB::table('purchase')
            ->select([
                DB::raw('COALESCE(SUM(CASE WHEN product_id NOT IN (13,14,15) THEN rate * amount ELSE 0 END), 0) as fuel_purchase_value'),
                DB::raw('COALESCE(SUM(CASE WHEN product_id IN (13,14,15) THEN rate * amount ELSE 0 END), 0) as nonfuel_purchase_value'),
                DB::raw('COALESCE(SUM(
                    CASE 
                        WHEN products.product_name = "Gas" AND product_id NOT IN (13,14,15) THEN amount * heaviness
                        WHEN product_id NOT IN (13,14,15) THEN (1000000 / heaviness) * amount
                        ELSE 0
                    END
                ), 0) as total_purchased_liters')
            ])
            ->leftJoin('products', 'products.id', '=', 'purchase.product_id')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->first();

        // 2. Get all sales data in one query
        $salesData = DB::table('distribution')
            ->join('towers', 'distribution.tower_id', '=', 'towers.id')
            ->select([
                DB::raw('COALESCE(SUM(CASE WHEN towers.product_id NOT IN (13,14,15) THEN distribution.rate * distribution.amount ELSE 0 END), 0) as fuel_sales_value'),
                DB::raw('COALESCE(SUM(CASE WHEN towers.product_id IN (13,14,15) THEN distribution.rate * distribution.amount ELSE 0 END), 0) as nonfuel_sales_value'),
                DB::raw('COALESCE(SUM(CASE WHEN towers.product_id NOT IN (13,14,15) THEN distribution.amount ELSE 0 END), 0) as total_sold_liters')
            ])
            ->whereBetween('distribution.date', [$startOfMonth, $endOfMonth])
            ->first();

        // 3. Get sarafi data (unchanged)
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


        $productsQuery = Product::leftJoin('towers', 'towers.product_id', '=', 'products.id')
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
            ->orderBy('products.id');

        $products = $productsQuery->get()->map(function ($product) use ($startOfMonth, $endOfMonth) {
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
            ->map(function ($purchase) use ($startOfMonth, $endOfMonth) {
                return [
                    'product_id' => $purchase->product_id,
                    'name' => $purchase->product_name,
                    'total_purchase_value' => $purchase->total_purchase_value,
                    'total_purchase_amount' => $purchase->total_purchase_amount,
                    'total_purchase_liters' => $purchase->total_liters,
                    'icon' => $this->getProductIcon($purchase->product_name),
                    'bg_color' => $this->getProductColor($purchase->product_name),
                    'is_money' => in_array($purchase->product_id, [13, 14, 15]),
                    'month_start' => $startOfMonth,
                    'month_end' => $endOfMonth,
                ];
            });

        // 6. Calculate metrics
        $balance = ($sarafiPayments->total_equivalent_dollar + $sarafiPayments->total_amount_dollar) - $sarafiPickups->total_amount;

        $nonMoneyProducts = $products->filter(fn($product) => !$product['is_money']);
        $chartLabels = $nonMoneyProducts->pluck('name');
        $chartValues = $nonMoneyProducts->pluck('total_amount');

        // Tankers Level Calculation - Grouped by product categories
        $tankersLevel = collect([
            ['name' => 'Diesel Products', 'product_ids' => [4, 5], 'icon' => 'fa-gas-pump', 'bg_color' => 'bg-secondary'],
            ['name' => 'Petrol Products', 'product_ids' => [1, 2, 6], 'icon' => 'fa-gas-pump', 'bg_color' => 'bg-danger'],
            ['name' => 'Gas', 'product_ids' => [3], 'icon' => 'fa-fire', 'bg_color' => 'bg-warning']
        ])->map(function ($category) use ($purchases, $products) {
            $purchased = $purchases->whereIn('product_id', $category['product_ids'])->sum('total_purchase_liters');
            $sold = $products->whereIn('id', $category['product_ids'])->sum('total_amount');

            return [
                'name' => $category['name'],
                'product_ids' => $category['product_ids'],
                'total_purchased' => $purchased,
                'total_sold' => $sold,
                'remaining' => $purchased - $sold,
                'icon' => $category['icon'],
                'bg_color' => $category['bg_color']
            ];
        });

        // Calculate all metrics from the consolidated data
        $metrics = [
            'benefitsValue' => $salesData->fuel_sales_value - $financialData->fuel_purchase_value,
            'fuelBalanceValue' => $financialData->total_purchased_liters - $salesData->total_sold_liters,
            'valueBalance' => $financialData->fuel_purchase_value - $salesData->fuel_sales_value,
            'totalSalesValue' => $salesData->fuel_sales_value,
            'totalPurchaseValue' => $financialData->fuel_purchase_value,
            'totalPurchasedLiters' => $financialData->total_purchased_liters,
            'totalSoldLiters' => $salesData->total_sold_liters
        ];

        // Customer balance query (unchanged)
        $PaymentTotalbalance = Contract::join('customers', 'contracts.customer_id', '=', 'customers.id')
            ->leftJoinSub(
                DB::table('distribution')
                    ->join('towers', 'distribution.tower_id', '=', 'towers.id')
                    ->where('towers.product_id', '!=', 14)
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
                    ->where('towers.product_id', 14)
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

        $hesabSherkatPurchaseTotal = DB::table('hesabSherkat_purchase')
            ->select(DB::raw('COALESCE(SUM(amount * rate), 0) as total_purchase_value'))
            ->first();

        $hesabSherkatPaymentTotal = DB::table('hesabSherkat_payment')
            ->select(DB::raw('COALESCE(SUM(amount), 0) as total_payment_value'))
            ->first();

        $dolatiDistribution = DB::table('distribution')
            ->join('towers', 'distribution.tower_id', '=', 'towers.id')
            ->join('products', 'towers.product_id', '=', 'products.id')
            ->join('contracts', 'distribution.contract_id', '=', 'contracts.id')
            ->join('customers', 'contracts.customer_id', '=', 'customers.id')
            ->whereBetween('distribution.date', [$startOfMonth, $endOfMonth])
            ->where('customers.name', 'دولتی')
            ->select(
                'products.id',
                'products.product_name',
                DB::raw('COALESCE(SUM(distribution.amount), 0) as total_amount'),
                // DB::raw('COALESCE(SUM(distribution.rate * distribution.amount), 0) as total_value')
            )
            ->groupBy('products.id', 'products.product_name')
            ->orderBy('products.product_name')
            ->get();

        // Get all products purchased from "دولتی" supplier with details
        $dolatiPurchasesByProduct = DB::table('purchase')
            ->leftJoin('products', 'products.id', '=', 'purchase.product_id')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('supplier', 'دولتی')
            ->select(
                'products.id',
                'products.product_name',
                DB::raw('COALESCE(SUM(purchase.amount), 0) as total_amount'),
                // DB::raw('COALESCE(SUM(purchase.rate * purchase.amount), 0) as total_value'),
                DB::raw('COALESCE(SUM(
            CASE 
                WHEN products.product_name = "Gas" THEN purchase.amount * purchase.heaviness
                ELSE (1000000 / purchase.heaviness) * purchase.amount
            END
        ), 0) as total_liters')
            )
            ->groupBy('products.id', 'products.product_name')
            ->orderBy('products.product_name')
            ->get();

// Calculate total remaining for دولتی
$totalDolatiPurchaseLiters = $dolatiPurchasesByProduct->sum('total_liters');
$totalDolatiDistributionLiters = $dolatiDistribution->sum('total_amount'); // Assuming distribution amount is in liters
$dolatiRemainingLiters = $totalDolatiPurchaseLiters - $totalDolatiDistributionLiters;


// Calculate product-wise remaining for دولتی
$dolatiRemainingByProduct = [];

// Loop through all دولتی purchases
foreach ($dolatiPurchasesByProduct as $purchaseProduct) {
    // Find corresponding distribution for this product
    $distributionProduct = $dolatiDistribution->firstWhere('id', $purchaseProduct->id);
    
    $distributionLiters = $distributionProduct->total_amount ?? 0; // Assuming total_amount is in liters
    $remainingLiters = $purchaseProduct->total_liters - $distributionLiters;
    
    $dolatiRemainingByProduct[] = [
        'id' => $purchaseProduct->id,
        'product_name' => $purchaseProduct->product_name,
        'purchase_liters' => $purchaseProduct->total_liters,
        'distribution_liters' => $distributionLiters,
        'remaining_liters' => $remainingLiters
    ];
}

// Also check for products that exist only in distribution (no purchase)
foreach ($dolatiDistribution as $distributionProduct) {
    $existsInPurchases = $dolatiPurchasesByProduct->contains('id', $distributionProduct->id);
    
    if (!$existsInPurchases) {
        $dolatiRemainingByProduct[] = [
            'id' => $distributionProduct->id,
            'product_name' => $distributionProduct->product_name,
            'purchase_liters' => 0,
            'distribution_liters' => $distributionProduct->total_amount,
            'remaining_liters' => -$distributionProduct->total_amount // Negative since no purchase
        ];
    }
}

// Calculate totals
$totalRemainingLiters = collect($dolatiRemainingByProduct)->sum('remaining_liters');


        return view('admin.dashboard', compact(
            'sarafiPayments',
            'sarafiPickups',
            'balance',
            'products',
            'afghaniStartDate',
            'afghaniEndDate',
            'chartLabels',
            'chartValues',
            'dolatiPurchasesByProduct',
          'totalDolatiPurchaseLiters',
    'totalDolatiDistributionLiters',
    'dolatiRemainingLiters',

    'dolatiRemainingByProduct',
    'totalRemainingLiters',

            'dolatiDistribution',
            'purchases',
            'PaymentTotalbalance',
            'tankersLevel',
            'metrics',
            'hesabSherkatPurchaseTotal',
            'hesabSherkatPaymentTotal'
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

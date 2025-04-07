<?php

namespace App\Http\Controllers;

use App\Helpers\AfghanCalendarHelper;
use App\Models\User;
use App\Models\Product;
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
            $afghaniEndDate =  AfghanCalendarHelper::toAfghanDateFormat($endOfMonth);
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
            ->select(
                DB::raw('COALESCE(SUM(amount), 0) as total_amount'),
            )
            ->first();

        // Calculate the balance
        $balance = ($sarafiPayments->total_equivalent_dollar + $sarafiPayments->total_amount_dollar) - $sarafiPickups->total_amount;



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


        return view('admin.dashboard', compact('sarafiPayments', 'sarafiPickups', 'balance', 'products', 'afghaniStartDate', 'afghaniEndDate'));
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

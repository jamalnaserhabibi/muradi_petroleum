@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <style>
            .progress {
                height: 20px;
                background-color: #e9ecef;
                border-radius: 5px;
                overflow: hidden;
            }

            .progress-bar {
                height: 100%;
                background-color: #28a745;
                transition: width 0.6s ease;
            }

            .amount-display small {
                font-size: 0.8rem;
                opacity: 0.8;
            }
        </style>
        <div class="content-header">
            <div class="container-fluid text-center d-flex justify-content-between align-items-center flex-row">

                <h1 class="dashboardtitle">
                    {{ \Morilog\Jalali\Jalalian::now()->format('%A  %d') }}
                    {{ ['حمل', 'ثور', 'جوزا', 'سرطان', 'اسد', 'سنبله', 'میزان', 'عقرب', 'قوس', 'جدی', 'دلو', 'حوت'][\Morilog\Jalali\Jalalian::now()->getMonth() - 1] }}
                    {{ \Morilog\Jalali\Jalalian::now()->format('%Y | %I:%M') }}
                    {{ \Morilog\Jalali\Jalalian::now()->format('a') == 'am' ? 'قبل از ظهر' : 'بعد از ظهر' }}
                </h1>
                <form id="filter-form" action="{{ route('admin.dashboard') }}" method="GET">
                    <div id="reservationdate" class="d-flex align-items-center justify-content-between">
                        <input value="{{ isset($afghaniStartDate) ? $afghaniStartDate : '' }}" type="text"
                            name="start_date" id="start_date" class="form-control" placeholder="Start Date" required />

                        <span style="margin: 0 10px; font-weight: bold;">to</span>

                        <input value="{{ isset($afghaniEndDate) ? $afghaniEndDate : '' }}" type="text" name="end_date"
                            id="end_date" class="form-control" placeholder="End Date" required />
                    </div>
                </form>
            </div>
        </div>



        <section class="content">
            <div class="container-fluid">


                {{-- sarafi balance --}}
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info ">
                            <div class="inner">
                                <h3>{{ number_format($hesabSherkatPaymentTotal->total_payment_value - $hesabSherkatPurchaseTotal->total_purchase_value, 1) }}
                                </h3>
                                <p>بیلانس شرکت</p>
                            </div>
                            <div class="icon">
                                @if ($hesabSherkatPaymentTotal->total_payment_value - $hesabSherkatPurchaseTotal->total_purchase_value <= 0)
                                    <i class="fas fa-industry text-danger"></i>
                                @else
                                    <i class="fas fa-industry text-success"></i>
                                @endif
                            </div>
                            <div class="small-box-footer d-flex justify-content-between pl-3 pr-3">
                                <span>
                                    <i class="fas fa-arrow-up text-success"></i>
                                </span>
                                <span>
                                    {{ number_format($hesabSherkatPaymentTotal->total_payment_value, 0) }}
                                    {{-- {{ $sarafiPayments->latest_payment_date ? \App\Helpers\AfghanCalendarHelper::toAfghanDateFormat($sarafiPayments->latest_payment_date) : 'N/A' }} --}}
                                </span>
                                <span>
                                    {{ number_format($hesabSherkatPurchaseTotal->total_purchase_value, 0) }}
                                    {{-- {{ $sarafiPickups->latest_pickup_date ? \App\Helpers\AfghanCalendarHelper::toAfghanDateFormat($sarafiPickups->latest_pickup_date) : 'N/A' }} --}}
                                </span>
                                <span>
                                    <i class="fas fa-arrow-down text-danger"></i>
                                </span>
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-primary ">
                            <div class="inner">
                                <h3>{{ number_format($balance, 1) }}</h3>
                                <p>بیلانس صرافی</p>
                            </div>
                            <div class="icon">
                                @if ($balance <= 0)
                                    <i class="fas fa-hand-holding-usd text-danger"></i>
                                @else
                                    <i class="fas fa-hand-holding-usd text-success"></i>
                                @endif
                            </div>
                            <div class="small-box-footer d-flex justify-content-between pl-3 pr-3">
                                <span>
                                    <i class="fas fa-arrow-up text-success"></i>
                                </span>
                                <span>
                                    {{ number_format($sarafiPickups->total_amount, 0) }}
                                    {{-- {{ $sarafiPayments->latest_payment_date ? \App\Helpers\AfghanCalendarHelper::toAfghanDateFormat($sarafiPayments->latest_payment_date) : 'N/A' }} --}}
                                </span>
                                <span>
                                    {{ number_format($sarafiPayments->total_equivalent_dollar + $sarafiPayments->total_amount_dollar, 0) }}
                                    {{-- {{ $sarafiPickups->latest_pickup_date ? \App\Helpers\AfghanCalendarHelper::toAfghanDateFormat($sarafiPickups->latest_pickup_date) : 'N/A' }} --}}
                                </span>
                                <span>
                                    <i class="fas fa-arrow-down text-danger"></i>
                                </span>
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info ">
                            <div class="inner">
                                <h3>{{ number_format($PaymentTotalbalance->total_balance, 1) }}</h3>
                                <p>بیلانس مشتریان</p>
                            </div>
                            <div class="icon">
                                @if ($PaymentTotalbalance->total_balance <= 0)
                                    <i class="fas fa-coins text-danger"></i>
                                @else
                                    <i class="fas fa-coins text-success"></i>
                                @endif
                            </div>

                            <a href="{{ route('admin.useraccounts') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>

                        </div>
                    </div>
                    <div class="col-lg-3 col-6">

                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ \App\Models\User::count() - 1 }}</h3>
                                <p>Registared User</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="{{ route('admin.useraccounts') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>




                <h1>Sales</h1>
                {{-- products sales statistics --}}
                <div class="row">
                    @foreach ($products as $product)
                        <div class="col-lg-3 col-4 mb-4">
                            <div class="small-box {{ $product['bg_color'] }}">
                                <div class="inner">
                                    <h3>{{ number_format($product['total_value']) }}</h3>
                                    <h4>{{ $product['name'] }}</h4>
                                    <div class="amount-display">
                                        @if (!$product['is_money'])
                                            {{ number_format($product['total_amount']) }}
                                            <small>L</small>
                                        @else
                                            {{-- {{ number_format($product['total_amount']) }} --}}
                                        @endif
                                    </div>
                                </div>
                                <div class="icon">
                                    <i class="fas {{ $product['icon'] }}"></i>
                                </div>
                                <a href="" class="small-box-footer">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{-- chart of products --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Sales Chart (Liters)</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="productChart"
                                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- products purchase statistics --}}
                <h1>Purchase</h1>

                <div class="row">
                    @foreach ($purchases as $product)
                        <div class="col-lg-3 col-4 mb-4">
                            <div class="small-box {{ $product['bg_color'] }}">
                                <div class="inner">
                                    <h3>{{ number_format($product['total_purchase_amount']) }} T</h3>

                                    {{ number_format($product['total_purchase_value']) }}


                                    <h4>{{ $product['name'] }}</h4>
                                    <div class="amount-display">
                                        @if (!$product['is_money'])
                                            {{ number_format($product['total_purchase_liters']) }}
                                            <small>L</small>
                                        @else
                                            {{-- {{ number_format($product['total_amount']) }} --}}
                                        @endif
                                    </div>
                                </div>
                                <div class="icon">
                                    <i class="fas {{ $product['icon'] }}"></i>
                                </div>
                                <a href="" class="small-box-footer">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <h1>Tankers Status</h1>
                {{--
                each tower one cart
                <div class="row">
                    @foreach ($tankersLevel as $tanker)
                        <div class="col-lg-3 col-6 mb-4">
                            <div class="small-box {{ $tanker['bg_color'] }}">
                                <div class="inner">
                                    <h3>{{ number_format($tanker['remaining'], 0) }} <small>L</small></h3>
                                    <h4>{{ $tanker['name'] }}</h4>
                                    <div class="progress">
                                        @php
                                            $percentage = $tanker['total_purchased'] > 0 
                                                ? ($tanker['remaining'] / $tanker['total_purchased']) * 100 
                                                : 0;
                                        @endphp
                                        <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%" 
                                             aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">{{number_format($percentage)}}%</div>
                                    </div>
                                </div>
                                <div class="icon">
                                    <i class="fas {{ $tanker['icon'] }}"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div> --}}
                {{-- petrol gas diesl carts --}}
                <div class="row">
                    @foreach ($tankersLevel as $tanker)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="small-box {{ $tanker['bg_color'] }}">
                                <div class="inner">
                                    <h3>{{ number_format($tanker['remaining'], 0) }} <small>L</small></h3>
                                    <h4>{{ $tanker['name'] }}</h4>
                                    <div class="progress">
                                        @php
                                            $percentage =
                                                $tanker['total_purchased'] > 0
                                                    ? ($tanker['remaining'] / $tanker['total_purchased']) * 100
                                                    : 0;
                                        @endphp
                                        <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%"
                                            aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                            {{ number_format($percentage) }}%</div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-2">
                                        <small>Purchased: {{ number_format($tanker['total_purchased'], 0) }}</small>
                                        <small>Sold: {{ number_format($tanker['total_sold'], 0) }} </small>
                                    </div>
                                </div>
                                <div class="icon">
                                    <i class="fas {{ $tanker['icon'] }}"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>


                {{-- Benefits Card --}}
                <h1>Financial Summary</h1>
                <div class="row">
                    <div class="col-md-4">
                        <div class="small-box {{ $metrics['benefitsValue'] >= 0 ? 'bg-success' : 'bg-danger' }}">
                            <div class="inner">
                                <h3>{{ number_format($metrics['benefitsValue'], 0) }} <small></small></h3>
                                <h4>Net Benefits</h4>
                                <p>Total Sales: {{ number_format($metrics['totalSalesValue'], 0) }}</p>
                                <p>Total Purchases: {{ number_format($metrics['totalPurchaseValue'], 0) }}</p>
                            </div>
                            <div class="icon">
                                <i
                                    class="fas {{ $metrics['benefitsValue'] >= 0 ? 'fa-chart-line' : 'fa-chart-bar' }}"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ number_format($metrics['fuelBalanceValue'], 0) }} <small> L</small></h3>
                                <h4>Fuel Balance</h4>
                                <p>Total Sold: {{ number_format($metrics['totalSoldLiters'], 0) }} L</p>
                                <p>Total Purchased: {{ number_format($metrics['totalPurchasedLiters'], 0) }} L</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-gas-pump"></i>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="col-md-4">
                        <div class="small-box {{ $metrics['valueBalance'] >= 0 ? 'bg-warning' : 'bg-danger' }}">
                            <div class="inner">
                                <h3>{{ number_format(abs($metrics['valueBalance']), 0) }} <small></small></h3>
                                <h4>Value Balance</h4>
                                <p>Total Purchase Value: {{ number_format($metrics['totalPurchaseValue'], 0) }}</p>
                                <p>Total Sales Value: {{ number_format($metrics['totalSalesValue'], 0) }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                        </div> --}}
                </div>

             
                {{-- <h1>خرید دولتی</h1>
                @if ($dolatiPurchasesByProduct->count() > 0)
                    <div class="row">
                        @foreach ($dolatiPurchasesByProduct as $product)
                            <div class="col-lg-3 col-6 mb-4">
                                <div class="small-box bg-secondary">
                                    <div class="inner">
                                        <h4>{{ $product->product_name }}</h4>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Liters:</span>
                                            <strong>{{ number_format($product->total_liters, 0) }} L</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Tons:</span>
                                            <strong>{{ number_format($product->total_amount, 0) }} T</strong>
                                        </div>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-cube"></i>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info"></i> No دولتی Purchases Found</h5>
                        No products were purchased from دولتی supplier in the selected date range.
                    </div>
                @endif
          

                <h1>توضیع دولتی</h1>
                @if ($dolatiDistribution->count() > 0)
                    <div class="row">


                        @foreach ($dolatiDistribution as $product)
                            <div class="col-lg-3 col-6 mb-4">
                                <div class="small-box bg-primary">
                                    <div class="inner">
                                        <h4>{{ $product->product_name }}</h4>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Amount:</span>
                                            <strong>{{ number_format($product->total_amount, 0) }} L</strong>
                                        </div>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                </div>
                            </div>
                        @endforeach


                    </div>
                @else
                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info"></i> No دولتی Distribution Found</h5>
                        No products were distributed to دولتی company in the selected date range.
                    </div>
                @endif --}}


                <h1>بیلانس دولتی</h1>

@if(count($dolatiRemainingByProduct) > 0)
    <div class="row">
        @foreach($dolatiRemainingByProduct as $product)
            @php
                $remainingClass = $product['remaining_liters'] >= 0 ? 'bg-success' : 'bg-danger';
                $remainingIcon = $product['remaining_liters'] >= 0 ? 'fa-check-circle' : 'fa-exclamation-triangle';
            @endphp
            
            <div class="col-lg-3 col-6 mb-4">
                <div class="small-box {{ $remainingClass }}">
                    <div class="inner">
                        <h4>{{ $product['product_name'] }}</h4>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <small>خرید:</small>
                            <small>{{ number_format($product['purchase_liters'], 0) }} L</small>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <small>توضیع:</small>
                            <small>{{ number_format($product['distribution_liters'], 0) }} L</small>
                        </div>
                        
                        <hr class="my-1">
                        
                        <div class="d-flex justify-content-between">
                            <strong>مانده:</strong>
                            <strong>{{ number_format($product['remaining_liters'], 0) }} L</strong>
                        </div>
                        
                        @if($product['remaining_liters'] < 0)
                            <small class="text-white">(کمبود)</small>
                        @endif
                    </div>
                    <div class="icon">
                        <i class="fas {{ $remainingIcon }}"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    {{-- Total Summary
    <div class="row">
        <div class="col-lg-8">
            <div class="small-box {{ $totalRemainingLiters >= 0 ? 'bg-primary' : 'bg-warning' }}">
                <div class="inner">
                    <div class="row">
                        <div class="col-md-">
                            <h3>مجموع مانده لیتر: {{ number_format($totalRemainingLiters, 0) }} L</h3>
                            <p>{{ $totalRemainingLiters >= 0 ? 'باقی' : 'کمبود' }}</p>
                        </div>
                      
                    </div>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>
    </div> --}}
@else
    <div class="alert alert-info">
        <h5><i class="icon fas fa-info"></i> No دولتی Data Found</h5>
        No دولتی purchase or distribution data found.
    </div>
@endif
            </div>


            <script src="{{ asset('admin-lte/plugins/chart.js/Chart.min.js') }}"></script>
            <script src="{{ asset('admin-lte/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

        </section>
    </div>
@endsection

@section('CustomScripts')
@endsection

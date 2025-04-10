@extends('admin.layout')
@section('content')
    <div class="content-wrapper">

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
                        <div class="small-box bg-primary ">
                            <div class="inner">
                                <h3>{{ number_format($balance, 1) }}</h3>
                                <p>Sarafi Balance</p>
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
                                <p>Customers Balance</p>
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

                                    {{ number_format($product['total_purchase_value']) }} L


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
            </div>
            <script src="{{ asset('admin-lte/plugins/chart.js/Chart.min.js') }}"></script>
            <script src="{{ asset('admin-lte/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

        </section>
    </div>
@endsection

@section('CustomScripts')
@endsection

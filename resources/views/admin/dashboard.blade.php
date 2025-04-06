@extends('admin.layout')
@section('content')
    <div class="content-wrapper">

        <div class="content-header" >
            <div class="container-fluid text-center">
                <h1 class="dashboardtitle">
                    {{ \Morilog\Jalali\Jalalian::now()->format('%A  %d') }} 
                    {{ ['حمل', 'ثور', 'جوزا', 'سرطان', 'اسد', 'سنبله', 'میزان', 'عقرب', 'قوس', 'جدی', 'دلو', 'حوت'][\Morilog\Jalali\Jalalian::now()->getMonth() - 1] }}
                    {{ \Morilog\Jalali\Jalalian::now()->format('%Y | %I:%M') }} 
                    {{ \Morilog\Jalali\Jalalian::now()->format('a') == 'am' ? 'قبل از ظهر' : 'بعد از ظهر' }}
                </h1>
            </div>
        </div>


        <section class="content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-primary ">
                            <div class="inner">
                                <h3>{{ number_format($balance, 1) }}</h3>
                                <p>Sarafi Balance</p>
                            </div>
                            <div class="icon">
                                @if($balance <= 0)
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
                                    {{number_format($sarafiPickups->total_amount,0)}}
                                   {{-- {{ $sarafiPayments->latest_payment_date ? \App\Helpers\AfghanCalendarHelper::toAfghanDateFormat($sarafiPayments->latest_payment_date) : 'N/A' }} --}}
                                </span>
                                <span>
                                    {{number_format($sarafiPayments->total_equivalent_dollar + $sarafiPayments->total_amount_dollar,0)}}
                                    {{-- {{ $sarafiPickups->latest_pickup_date ? \App\Helpers\AfghanCalendarHelper::toAfghanDateFormat($sarafiPickups->latest_pickup_date) : 'N/A' }} --}}
                                </span>
                                <span>
                                    <i class="fas fa-arrow-down text-danger"></i>
                                </span>
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">                           

                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>150</h3>
                                <p>Total Purchase</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">

                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>53<sup style="font-size: 20px">%</sup></h3>
                                <p>Total Expenses</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i
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

                    <div class="col-lg-3 col-6">

                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>65</h3>
                                <p>Total Sales</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box petrol-bg">
                            <div class="inner">
                                <h3>150</h3>
                                <p>Petrol</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-gas-pump"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box super-petrol-bg">
                            <div class="inner">
                                <h3>53<sup style="font-size: 20px">%</sup></h3>
                                <p>Super Petrol</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-oil-can"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box diesel-bg">
                            <div class="inner">
                                <h3>23</h3>
                                <p>Diesel</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-truck-pickup"></i>
                            </div>
                            <a href="" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box gas-bg">
                            <div class="inner">
                                <h3>65</h3>
                                <p>Gas</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-burn"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>

            </div>
        </section>
         

    </div>
@endsection

@section('CustomScripts')
    <script src="plugins/chart.js/Chart.min.js"></script>

    <script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <script>
        $(function() {
            bsCustomFileInput.init();
        });
    </script>
@endsection

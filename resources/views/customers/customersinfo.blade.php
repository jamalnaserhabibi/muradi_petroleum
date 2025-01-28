@extends('admin.layout')
@section('CustomCss')
    <link rel="stylesheet" href="admincss/useraccounts/styleforall.css">
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row searchBar">
                    <h2 class="nav-icon">Contract Form</h2>
                    <a onclick=window.print() class="btn brannedbtn">
                        <i class="fas fa-print"></i> Print
                    </a>
                </div>
            </div>
        </section>



        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card contractformprint">
                            <div class="head">
                                <div class="logo">
                                    <img src="img/gas-pump.png" alt="">
                                </div>
                                <div class="text">
                                    <h3>Muradi Petroleum</h3>
                                    <h5>Customer Contract Form</h5>
                                    <h6>Print Date: {{\App\Helpers\AfghanCalendarHelper::toAfghanDate(now())}}</h6>
                                    <h6>Contract Serial No: {{$customer[0]->id}}</h6>

                                </div>
                                <div class="logo">
                                    <img src="img/logo.png" alt="">
                                </div>
                            </div>
                            <div class="card-body contentofform">
                                <div class="someinfo">
                                    <h4>Agreement <i class="fas fa-handshake"></i></h4>
                                    <ol>
                                        <li>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nesciunt odio saepe aspernatur!</li>
                                        <li>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nesciunt odio saepe aspernatur!</li>
                                        <li>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nesciunt odio saepe aspernatur!</li>
                                        <li>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nesciunt odio saepe aspernatur!</li>
                                        <li>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nesciunt odio saepe aspernatur!</li>
                                        <li>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nesciunt odio saepe aspernatur!</li>
                                    </ol>
                                </div>

                                <div class="customerinfobox">
                                    <h4>Customer Information <i class="fas fa-user"></i> </h4>
                                    <div class="p-1 pl-3">
                                        <h5><strong>ID: </strong> {{$customer[0]->id}}</h5>
                                        <h5><strong>Name: </strong> {{$customer[0]->name}}</h5>
                                        <h5><strong>Company: </strong> {{$customer[0]->company}}</h5>
                                        <h5><strong>Customer Type: </strong> 
                                            @foreach ($types as $type)
                                            {{ $customer[0]->customer_type === $type['id'] ? $type['customer_type'] : '' }}
                                            @endforeach
                                        </h5>
                                        <h5><strong>Contact: </strong> {{$customer[0]->contact}}</h5>
                                        <h5><strong>Document: </strong> {{$customer[0]->document}}</h5>
                                        <h5><strong>Details: </strong> {{$customer[0]->description}}</h5>
                                    </div>
                                </div>

                                <div class="contractinfobox">
                                    <h4>Contract Information <i class="fas fa-file-alt"></i></h4>
                                    <div class="p-1 pl-3">
                                        <h5><strong>Rate: </strong> {{$customer[0]->contract->rate}}</h5>
                                        <h5><strong>Product: </strong> {{$customer[0]->contract->product->product_name}}</h5>
                                        <h5><strong>Status: </strong> {{($customer[0]->contract->isActive)===1 ? 'Active':'Inactive'}}</h5>
                                        <h5><strong>Added By: </strong> {{$customer[0]->created_by}}</h5>
                                        <h5><strong>Added in: </strong> {{\App\Helpers\AfghanCalendarHelper::toAfghanDate($customer[0]->date)}}</h5>
                                        <h5><strong>Details: </strong> {{$customer[0]->contract->description}}</h5>
                                        {{-- <h5><strong>Details: </strong> {{$customer[0]->description}}</h5> --}}
                                    </div>
                                </div>
                                <div class="someinfo signature">
                                    <div class="row justify-content-between">
                                        <p>Muradi Petroleum <br><strong>{{ Auth::user()->name}}</strong></p>
                                        <p></p>
                                        <p>Customer <br><strong>{{$customer[0]->name}}</strong></p>
                                    </div>
                                </div>
                            </div>
                         <img class="watermark" src="img/logo.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </section>




    </div>
@endsection

@section('CustomScript')
    <script src="plugins/select2/js/select2.full.min.js"></script>
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="plugins/jszip/jszip.min.js"></script>
    <script src="plugins/pdfmake/pdfmake.min.js"></script>
    <script src="plugins/pdfmake/vfs_fonts.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    {{-- commented for the sidebar btn not worked --}}
    {{-- <script src="dist/js/adminlte.min2167.js?v=3.2.0"></script> --}}

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Date Range Picker
            $('#daterange-btn').daterangepicker({
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                            'month').endOf('month')]
                    },
                    startDate: moment().subtract(29, 'days'),
                    endDate: moment()
                },
                function(start, end) {
                    // Set values and submit form
                    const form = document.getElementById('filter-form');
                    document.getElementById('start-date').value = start.format('YYYY-MM-DD');
                    document.getElementById('end-date').value = end.format('YYYY-MM-DD');
                    form.submit();
                }
            );

            // Category Filter
            document.getElementById('category-filter').addEventListener('change', function() {
                document.getElementById('filter-form').submit();
            });
        });
    </script> --}}

    {{-- <script src="dist/js/demo.js"></script> --}}
@endsection

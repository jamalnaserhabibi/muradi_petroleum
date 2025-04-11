
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
                <div class="totalSalary searchBar row mb-1">

                    <div class=" d-flex align-items-center justify-content-end">
                        <button type="button" class="btn brannedbtn" data-toggle="modal" data-target="#filterModal">
                            فلتر <i class="fas fa-filter"></i>
                        </button>

                        @if(Auth::user()->usertype !== 'guest')
                        <a href="{{ route('adddestributionform') }}" class="btn btn-success ml-3">
                            ثبت فروشات پایه <i class="fas fa-plus"></i>
                        </a>
                        @endif
 
                        <a href="{{ route('distribution', ['cardView' => 1]) }}" class="btn btn-success ml-3">
                            نمایش کارت <i class="fas fa-th-large"></i>
                        </a>
                        @if (session('success'))
                            <div class="alert alert-success" id="success-alert">
                                {{ session('success') }}
                            </div>
                            <script>
                                setTimeout(function() {
                                    document.getElementById('success-alert').style.display = 'none';
                                }, 4000);
                            </script>
                        @endif
                    </div>
                    <h2>توزیع شروع از 
                        {{ isset($distributions) && isset($distributions[0]) ? \App\Helpers\AfghanCalendarHelper::toAfghanDate($distributions[0]->date) : 'No Data' }}
                    </h2>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped table-hover "
                                    dir="rtl">
                                    <thead>
                                        <tr>
                                            <th>مشتری</th>
                                            <th>توضیع کننده</th>
                                            <th>پایه</th>
                                            <th>نرخ</th>
                                            <th>مقدار</th>
                                            <th>مجموع</th>
                                            <th>بیلانس</th>
                                            <th>تاریخ</th>
                                            <th>توضیحات</th>
                                            <th>.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($distributions as $distribution)
                                            <tr>
                                                <td>{{ $distribution->contract->customer->name }}
                                                    {{ $distribution->contract->customer->company }}</td>
                                                <td>{{ $distribution->distributer->fullname ?? 'N/A' }}</td>
                                                <td>{{ $distribution->tower->serial }} -
                                                    {{ $distribution->tower->product->product_name }}</td>
                                                <td>{{ $distribution->rate }}</td>
                                                <td>{{ number_format($distribution->amount, 0) }}</td>
                                                <td>{{ number_format($distribution->amount * $distribution->rate, 1) }}
                                                </td>

                                                @if ($distribution->running_balance < 0)
                                                    <td class="redcolor">{{ number_format($distribution->running_balance, 1) }} </td>
                                                @elseif ($distribution->running_balance == 0)
                                                    <td style="background-color: rgb(0, 179, 0); color: white;" >{{ number_format($distribution->running_balance) }}</td>
                                                @else
                                                    <td>{{ number_format($distribution->running_balance,1) }}</td>
                                                @endif

                                                <td>{{ \App\Helpers\AfghanCalendarHelper::toAfghanDate($distribution->date) }}
                                                </td>
                                                <td>{{ $distribution->details }}</td>
                                                <td>
                                                    @if(Auth::user()->usertype == 'admin')
                                                    <form action="{{ route('distribution_delete', $distribution->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-icon btn-danger"
                                                            title="Delete"
                                                            onclick="return confirm('Are you sure you want to delete this?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                    @endif
                                                  
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" style="text-align:right">مجموع:</th>
                                            <th id="totalAmount"></th>
                                            <th id="totalSum"></th>
                                            <th id="totalbalance"></th>
                                            <th colspan="3"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

     
    <div class="modal fade filter-modal" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filters</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="filter-form" action="{{ route('indexfortable') }}" method="GET">
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input value="{{ isset($afghaniStartDate) ? $afghaniStartDate : '' }}" type="text"
                                name="start_date" id="start_date" class="form-control" placeholder="Start Date" required />
                        </div>
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input value="{{ isset($afghaniEndDate) ? $afghaniEndDate : '' }}" type="text"
                                name="end_date" id="end_date" class="form-control" placeholder="End Date" required />
                        </div>
                        <div class="form-group">
                            <label for="distributer-filter">Distributer</label>
                            <select id="distributer-filter" name="distributer[]" class="select2 form-control"
                                multiple="multiple" data-placeholder="Select Distributer" style="width:100%">
                                @if (count($distributers) > 0)
                                    <option value="">All Distributers</option>
                                    @foreach ($distributers as $distributer)
                                        <option value="{{ $distributer->id }}"
                                            {{ in_array($distributer->id, request('distributer', [])) ? 'selected' : '' }}>
                                            {{ $distributer->fullname }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>No Data Available</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="product-filter">Product</label>
                            <select id="product-filter" name="product[]" class="select2 form-control" multiple="multiple"
                                data-placeholder="Select Product" style="width:100%">
                                @if (count($contracts) > 0)
                                    <option value="">All Products</option>
                                    @foreach ($contracts->unique('product.id') as $contract)
                                        <option value="{{ $contract->product->id }}"
                                            {{ in_array($contract->product->id, request('product', [])) ? 'selected' : '' }}>
                                            {{ $contract->product->product_name }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>No Data Available</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="contract-filter">Contract</label>
                            <select id="contract-filter" name="contract[]" class="select2 form-control"
                                multiple="multiple" data-placeholder="Select Contract" style="width:100%">
                                @if (count($contracts) > 0)
                                    <option value="">All Contracts</option>
                                    @foreach ($contracts as $contract)
                                        <option value="{{ $contract->id }}"
                                            {{ in_array($contract->id, request('contract', [])) ? 'selected' : '' }}>
                                            {{ $contract->customer->name }} {{ $contract->customer->company }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>No Data Available</option>
                                @endif
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="applyFilters()">Apply Filters</button>
                </div>
            </div>
        </div>
    </div>
@endsection
 @section('CustomScript')
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
    <script src="dist/js/demo.js"></script>
    <script src="plugins/select2/js/select2.full.min.js"></script>
    <script src="//cdn.datatables.net/plug-ins/1.10.21/i18n/Persian.json"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                placeholder: 'Select Products',
                allowClear: true
            });

            const table = $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [{
                        extend: 'excel',
                        footer: true,
                        exportOptions: {
                            columns: ':not(:last-child)'  
                        }
                    },
                    {
                        extend: 'pdf',
                        footer: true,
                        exportOptions: {
                            columns: ':not(:last-child)'  
                        }
                    },
                    {
                        extend: 'print',
                        footer: true,
                        exportOptions: {
                            columns: '*' // Include all columns
                        }
                    }
                ],
                "footerCallback": function(row, data, start, end, display) {
                    let api = this.api();

                    let totalAmount = api
                        .column(4, {
                            search: 'applied'
                        }) // Column index for Amount
                        .data()
                        .reduce((a, b) => {
                            return a + parseFloat(b.replace(/,/g, ''));
                        }, 0);

                    let totalSum = api
                        .column(5, {
                            search: 'applied'
                        }) // Column index for Total
                        .data()
                        .reduce((a, b) => {
                            return a + parseFloat(b.replace(/,/g, ''));
                        }, 0);
                    let totalbalance = api
                        .column(6, {
                            search: 'applied'
                        }) // Column index for Total
                        .data()
                        .reduce((a, b) => {
                            return a + parseFloat(b.replace(/,/g, ''));
                        }, 0);
                    $('#totalAmount').html(totalAmount.toLocaleString());
                    $('#totalSum').html(totalSum.toLocaleString());
                    // $('#totalbalance').html(totalbalance.toLocaleString());
                }
            });


            table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });

        function applyFilters() {
            $('#filter-form').submit();
        }
    </script>
@endsection

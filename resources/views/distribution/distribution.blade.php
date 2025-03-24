{{--
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
                        <a href="{{ route('adddestributionform') }}" class="btn btn-success ml-3">
                            ثبت فروشات پایه <i class="fas fa-plus"></i>
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
                    <h2>توزیع ماه
                        {{ isset($distributions) && isset($distributions[0]) ? \App\Helpers\AfghanCalendarHelper::getAfghanMonth($distributions[0]->date) : 'No Data' }}
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
                                            <th>تاریخ</th>
                                            <th>توضیعات</th>
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
                                                <td>{{ \App\Helpers\AfghanCalendarHelper::toAfghanDate($distribution->date) }}
                                                </td>
                                                <td>{{ $distribution->description }}</td>
                                                <td>
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
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" style="text-align:right">مجموع:</th>
                                            <th id="totalAmount"></th>
                                            <th id="totalSum"></th>
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
                    <form id="filter-form" action="{{ route('distribution') }}" method="GET">
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
                    $('#totalAmount').html(totalAmount.toLocaleString());
                    $('#totalSum').html(totalSum.toLocaleString());
                }
            });


            table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });

        function applyFilters() {
            $('#filter-form').submit();
        }
    </script>
@endsection --}}

@extends('admin.layout')
@section('CustomCss')
    <link rel="stylesheet" href="admincss/useraccounts/styleforall.css">
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <style>
        .distribution-card {
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            margin-bottom: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            overflow: hidden;
        }

        .distribution-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .distribution-card .card-header {
            background-color: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 1px solid #e0e0e0;
            font-weight: 600;
            font-size: 1.25em;
            color: #333;
            text-align: right;
        }

        .distribution-card .card-body {
            padding: 20px;
            text-align: right;
        }

        .distribution-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 12px 0;
            margin: 10px 0;
            border: 1px solid #b4b4b4;
            border-radius: 8px;

        }

        .distribution-item:last-child {
            border-bottom: none;
        }

        .distribution-item .details {
            flex: 1;
            margin-right: 15px;
        }

        .distribution-item .details div {
            margin-bottom: 8px;
            font-size: 0.95em;
            color: #555;
        }

        .distribution-item .details div strong {
            color: #333;
            font-weight: 600;
        }

        .distribution-item .actions {
           
        }

        .total-summary {
            background-color: #f8f9fa;
            padding: 15px 20px;
            border-top: 1px solid #e0e0e0;
            font-weight: 600;
            font-size: 1.1em;
            color: #333;
            text-align: right;
        }

        .grand-total {
            background-color: #f8f9fa;
            padding: 15px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.2em;
            color: #333;
            text-align: right;
            margin-top: 20px;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            padding: 8px 12px;
            font-size: 0.9em;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            padding: 8px 12px;
            font-size: 0.9em;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }

        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
            padding-right: 15px;
            padding-left: 15px;
        }
        .details {
    display: flex;
    flex-direction: column;
    gap: 0px; /* Space between rows */
    flex: 1;
    margin-right: 10px; /* Reduced margin */
}

.detail-row {
    display: flex;
    gap: 10px; /* Space between items in a row */
}

.detail-item {
    flex: 1; /* Each item takes equal space */
    font-size: 0.9em; /* Smaller font size */
    color: #555;
    white-space: nowrap; /* Prevent text from wrapping */
    overflow: hidden;
    text-overflow: ellipsis; /* Add ellipsis for overflow */
}

.detail-item strong {
    color: #333; /* Darker color for labels */
    font-weight: 600;
}
        @media (max-width: 768px) {
            .col-md-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="totalSalary searchBar row mb-1">
                    <div class="d-flex align-items-center justify-content-end">
                        <button type="button" class="btn brannedbtn" data-toggle="modal" data-target="#filterModal">
                            فلتر <i class="fas fa-filter"></i>
                        </button>
                        <a href="{{ route('adddestributionform') }}" class="btn btn-success ml-3">
                            ثبت فروشات پایه <i class="fas fa-plus"></i>
                        </a>
                        <a href="{{ route('indexfortable') }}" class="btn btn-success ml-3">
                            نمایش جدول <i class="fas fa-table"></i>
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
                    @php
                        $grandTotalAmount = 0; // Initialize grand total for amount
                        $grandTotalSum = 0; // Initialize grand total for sum
                    @endphp

                    @foreach ($distributions->groupBy('distributer.fullname') as $distributerName => $distributionsGroup)
                        <div class="col-md-6">
                            <div class="distribution-card">
                                <div class="card-header">
                                    {{ $distributerName ?? 'N/A' }}    <i class="nav-icon fas fa-user"></i>
                                </div>
                                <div class="card-body">
                                    @foreach ($distributionsGroup as $distribution)
                                        <div class="distribution-item">
                                            <div class="actions">
                                                <form action="{{ route('distribution_delete', $distribution->id) }}"
                                                    method="POST"  >
                                                    @csrf
                                                    @method('DELETE')
                                                        <button type="submit" class="btn btn-icon btn-danger" title="Delete"
                                                        onclick="return confirm('Are you sure you want to delete this?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="details">
                                                <div class="detail-row">
                                                    <div class="detail-item">
                                                        <strong>مشتری:</strong> {{ $distribution->contract->customer->name }} {{ $distribution->contract->customer->company }}
                                                    </div>
                                                    <div class="detail-item">
                                                        {{ $distribution->tower->serial }} - {{ $distribution->tower->product->product_name }}<strong> :پایه</strong> 
                                                    </div>
                                                </div>
                                                <div class="detail-row">
                                                    <div class="detail-item">
                                                        <strong>نرخ:</strong> {{ $distribution->rate }}
                                                    </div>
                                                    <div class="detail-item">
                                                        <strong>مقدار:</strong> {{ number_format($distribution->amount, 0) }}
                                                    </div>
                                                </div>
                                                <div class="detail-row">
                                                    <div class="detail-item">
                                                        <strong>مجموع:</strong> {{ number_format($distribution->amount * $distribution->rate, 1) }}
                                                    </div>
                                                    <div class="detail-item">
                                                        <strong>تاریخ:</strong> {{ \App\Helpers\AfghanCalendarHelper::toAfghanDate($distribution->date) }}
                                                    </div>
                                                </div>
                                                <div class="detail-row">
                                                    <div class="detail-item">
                                                        <strong>توضیحات:</strong> {{ $distribution->details }}
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    @endforeach
                                </div>
                                <div class="total-summary">
                                    @php
                                        $totalAmount = $distributionsGroup->sum('amount'); // Total amount for this card
                                        $totalSum = $distributionsGroup->sum(function ($item) {
                                            return $item->amount * $item->rate;
                                        }); // Total sum for this card
                                        $grandTotalAmount += $totalAmount; // Add to grand total amount
                                        $grandTotalSum += $totalSum; // Add to grand total sum
                                    @endphp
                                    مقدار: {{ number_format($totalAmount, 0) }} |
                                    مجموع: {{ number_format($totalSum, 1) }}
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Display Grand Total -->
                    <div class="col-md-12 d-flex justify-content-end pb-3">
                        <div class="grand-total">
                            <strong>مجموع عمومی:</strong>
                            مقدار: {{ number_format($grandTotalAmount, 0) }} |
                            مجموع: {{ number_format($grandTotalSum, 1) }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Filter Modal -->
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
                    <form id="filter-form" action="{{ route('distribution') }}" method="GET">
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
    <script>
        $(document).ready(function() {
            // Initialize Select2 with Bootstrap4 theme
            $('.select2').select2({
                theme: 'bootstrap4',
                placeholder: 'Select Products',
                allowClear: true
            });
        });

        function applyFilters() {
            $('#filter-form').submit();
        }
    </script>
@endsection

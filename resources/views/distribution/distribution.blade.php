@extends('admin.layout')
@section('CustomCss')
    <link rel="stylesheet" href="admincss/useraccounts/styleforall.css">
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
   
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="totalSalary searchBar row mb-1">
                    <h2>Distribution of
                        {{ isset($distributions) && isset($distributions[0]) ? \App\Helpers\AfghanCalendarHelper::getAfghanMonth($distributions[0]->date) : 'No Data' }}
                    </h2>
                    <div class="col-8 d-flex align-items-center justify-content-end">
                        <button type="button" class="btn brannedbtn" data-toggle="modal" data-target="#filterModal">
                            <i class="fas fa-filter"></i> Filters
                        </button>
                        <a href="{{ route('adddestributionform') }}" class="btn btn-success ml-3">
                            <i class="fas fa-plus"></i> Add  
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
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Customer</th>
                                            <th>Distributer</th>
                                            <th>Tower</th>
                                            <th>Rate</th>
                                            <th>Amount</th>
                                            <th>Total</th>
                                            <th>Date</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($distributions as $distribution)
                                            <tr>
                                                <td>{{ $distribution->contract->customer->name }} {{ $distribution->contract->customer->company }}</td>
                                                <td>{{ $distribution->distributer->fullname ?? 'N/A' }}</td>
                                                <td>{{ $distribution->tower->serial }} - {{ $distribution->tower->product->product_name }}</td>
                                                <td>{{ $distribution->rate }}</td>
                                                <td>{{ number_format($distribution->amount, 0) }}</td>
                                                <td>{{ number_format($distribution->amount * $distribution->rate, 1) }}</td>
                                                <td>{{ \App\Helpers\AfghanCalendarHelper::toAfghanDate($distribution->date) }}</td>
                                                <td>{{ $distribution->description }}</td>
                                                <td>
                                                    <form action="{{ route('distribution_delete', $distribution->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-icon btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" style="text-align:right">Total:</th>
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

    <!-- Filter Modal -->
    <div class="modal fade filter-modal" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
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
                            <input value="{{ isset($afghaniStartDate) ? $afghaniStartDate : '' }}" type="text" name="start_date" id="start_date" class="form-control" placeholder="Start Date" required />
                        </div>
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input value="{{ isset($afghaniEndDate) ? $afghaniEndDate : '' }}" type="text" name="end_date" id="end_date" class="form-control" placeholder="End Date" required />
                        </div>
                        <div class="form-group">
                            <label for="distributer-filter">Distributer</label>
                            <select id="distributer-filter" name="distributer[]" class="select2 form-control" multiple="multiple" data-placeholder="Select Distributer" style="width:100%">
                                @if (count($distributers) > 0)
                                    <option value="">All Distributers</option>
                                    @foreach ($distributers as $distributer)
                                        <option value="{{ $distributer->id }}" {{ in_array($distributer->id, request('distributer', [])) ? 'selected' : '' }}>
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
                            <select id="product-filter" name="product[]" class="select2 form-control" multiple="multiple" data-placeholder="Select Product" style="width:100%">
                                @if (count($contracts) > 0)
                                    <option value="">All Products</option>
                                    @foreach ($contracts->unique('product.id') as $contract)
                                        <option value="{{ $contract->product->id }}" {{ in_array($contract->product->id, request('product', [])) ? 'selected' : '' }}>
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
                            <select id="contract-filter" name="contract[]" class="select2 form-control" multiple="multiple" data-placeholder="Select Contract" style="width:100%">
                                @if (count($contracts) > 0)
                                    <option value="">All Contracts</option>
                                    @foreach ($contracts as $contract)
                                        <option value="{{ $contract->id }}" {{ in_array($contract->id, request('contract', [])) ? 'selected' : '' }}>
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

            const table = $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": [{
                extend: 'excel',
                footer: true,
                exportOptions: {
                    columns: ':not(:last-child)' // Exclude the last column (Action column)
                }
            },
            {
                extend: 'pdf',
                footer: true,
                exportOptions: {
                    columns: ':not(:last-child)' // Exclude the last column (Action column)
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

            // Calculate total amount
            let totalAmount = api
                .column(4, { search: 'applied' }) // Column index for Amount
                .data()
                .reduce((a, b) => {
                    return a + parseFloat(b.replace(/,/g, ''));
                }, 0);

            // Calculate total sum (Amount * Rate)
            let totalSum = api
                .column(5, { search: 'applied' }) // Column index for Total
                .data()
                .reduce((a, b) => {
                    return a + parseFloat(b.replace(/,/g, ''));
                }, 0);

            // Update footer
            $('#totalAmount').html(totalAmount.toLocaleString());
            $('#totalSum').html(totalSum.toLocaleString());
        }
    });


            // Append buttons to the container
            table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });

        function applyFilters() {
            $('#filter-form').submit();
        }
    </script>
@endsection
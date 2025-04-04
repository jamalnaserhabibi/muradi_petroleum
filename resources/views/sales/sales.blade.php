@extends('admin.layout')
@section('CustomCss')
    <link rel="stylesheet" href="admincss/useraccounts/styleforall.css">
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="totalamount searchBar row mb-1">
                    <h2>
                        Sales of
                        {{ isset($sales) && isset($sales[0]) ? \App\Helpers\AfghanCalendarHelper::getAfghanMonth($sales[0]->date) : 'No Data' }}

                        {{ isset(request()->id) && isset($sales[0]) ? 'From ' . '-' . $sales[0]->contract->customer->company : '' }}
                    </h2>
                    <form id="filter-form" action="{{ route('sales') }}" method="GET">
                        <input type="hidden" name="start_date" id="start-date">
                        <input type="hidden" name="end_date" id="end-date">
                        <div class="form-group d-flex">
                            <div>
                                <div style="max-width: 400px;" id="reservationdate"
                                    class="d-flex align-items-center justify-content-between">
                                    <input value="{{ isset($astart) ? $astart : '' }}" type="text" name="start_date"
                                        id="start_date" class="form-control" placeholder="Start Date"
                                        style="max-width: 150px;" required />
                                    <span style="margin: 0 10px; font-weight: bold;">to</span>
                                    <input value="{{ isset($aend) ? $aend : '' }}" type="text" name="end_date"
                                        id="end_date" class="form-control" placeholder="End Date" style="max-width: 150px;"
                                        required />
                                </div>
                            </div>
                            <div class="dropdown ml-4">
                                {{-- <label for="product-filter">Select</label> --}}
                                <select id="product-filter" name="product_id[]" class="select2 form-control"
                                multiple="multiple" data-placeholder="Type" style="width:100%">
                                    @if (count($products) > 0)
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ in_array($product->id, request('product_id', [])) ? 'selected' : '' }}>
                                            {{ $product->product_name }}
                                        </option>
                                    @endforeach
                                    @else
                                        <option value="" disabled>No Data in Purchase</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </form>

                    <a href="{{ route('addnewsaleform') }}" class="btn brannedbtn">+ New</a>

                    @if (session('success'))
                        <ol>
                            <div class="alert alert-success" id="success-alert">
                                {{ session('success') }}
                            </div>
                            <script>
                                setTimeout(function() {
                                    document.getElementById('success-alert').style.display = 'none';
                                }, 4000); 
                            </script>
                        </ol>
                    @endif
                </div>

            </div>
        </section>



        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped useraccounts">
                                    <thead>
                                        <tr>
                                            <th>Customer</th>
                                            <th>Tower</th>
                                            <th>Product</th>
                                            <th>Rate</th>
                                            <th>Amount</th>
                                            <th>Total</th>
                                            <th>Date</th>
                                            <th>Details</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($sales as $sale)
                                            <tr>
                                                <td> {{ $sale->contract->customer->name }} -
                                                    {{ $sale->contract->customer->company }} </td>
                                                <td>{{ $sale->tower->serial }}</td>
                                                <td>{{ $sale->contract->product->product_name }}</td>
                                                <td>{{ number_format($sale->rate, 0) }}</td>
                                                <td>{{ $sale->amount }}</td>
                                                <td>{{ $sale->amount * $sale->rate }}</td>
                                                <td style="white-space: nowrap;">
                                                    {{ \App\Helpers\AfghanCalendarHelper::toAfghanDateTime($sale->date) }}
                                                </td>
                                                <td>{{ $sale->details }}</td>
                                                <td>
                                                    {{-- <a href="{{ route('editsale', $sale->id) }}"
                                                        class="btn pt-0 pb-0 btn-warning fa fa-edit" title="Edit">
                                                    </a> --}}

                                                    <form action="{{ route('saledelete', $sale->id) }}" method="POST"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn pt-0 pb-0 btn-danger"
                                                            title="Delete"
                                                            onclick="return confirm('Are you sure you want to delete this Sale?')">
                                                            <li class="fas fa-trash"></li>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Total</th>
                                            <th id="total-footer-ton"></th>
                                            <th></th> 
                                            <th></th> 
                                            <th id="total-footer-amount"></th> 
                                            <th id="total-footer-liter"></th>
                                            <th></th> 
                                            <th></th> 
                                            <th></th> 
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
@endsection

@section('CustomScript')
    <script src="plugins/select2/js/select2.full.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 with Bootstrap4 theme
            $('.select2').select2({
                theme: 'bootstrap4',
                placeholder: 'Select Products',
                allowClear: true
            });

            // Auto-submit on change
            $('#product-filter').on('change', function() {
                $('#filter-form').submit();
            });
        });
    </script>
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

    <script>
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
            document.getElementById('product-filter').addEventListener('change', function() {

                document.getElementById('filter-form').submit();
            });


            $(function() {

                const table = $("#example1").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    // "dom": 'Bfrtip',
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
                                columns: ':not(:last-child)'
                            }
                        },
                        {
                            extend: 'print',
                            footer: true,
                            exportOptions: {
                                columns: ':not(:last-child)'
                            }
                        }
                    ]

                });

                // Append buttons to the container
                table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

                // Calculate total of the "Amount" column
                function calculateColumnTotal(columnIndex) {
                    let total = 0;
                    table.rows({
                        search: 'applied'
                    }).every(function() {
                        const rowData = this.data();
                        const value = parseFloat(rowData[columnIndex].replace(/,/g,
                            '')); // Remove commas
                        if (!isNaN(value)) {
                            total += value;
                        }
                    });
                    return total;
                }

                // Update the footer with calculated totals
                function updateFooterTotals() {
                    const tonTotal = calculateColumnTotal(1); // Ton column
                    const amountTotal = calculateColumnTotal(4); // Total Amount column
                    const literTotal = calculateColumnTotal(5); // Total Liter column

                    // Format the totals
                    const formattedTonTotal = tonTotal.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                    const formattedAmountTotal = amountTotal.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                    const formattedLiterTotal = literTotal.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    // Update the footer
                    // $('#total-footer-ton').text(formattedTonTotal);
                    $('#total-footer-amount').text(formattedAmountTotal);
                    $('#total-footer-liter').text(formattedLiterTotal);
                }
                updateFooterTotals();

                // Update totals on table draw (e.g., pagination, search)
                table.on('draw', function() {
                    updateFooterTotals();
                });
            });
        });
    </script>
@endsection

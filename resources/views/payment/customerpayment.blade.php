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
                    @if(isset($payments) && count($payments) > 0)
                        <h2>
                            Payments of
                            {{ $payments[0]->contract->customer->name }} - {{ $payments[0]->contract->customer->company }}
                        </h2>
                    @else
                        <h2>No Payments on this Date</h2>
                    @endif
                    <div class="col-6 d-flex align-items-center justify-content-end">
                        <form id="filter-form" action="{{ route('singlecustomerpayments', $contractId) }}" method="GET">
                            <input type="hidden" name="start_date" id="start-date">
                            <input type="hidden" name="end_date" id="end-date">
                            <div class="form-group d-flex">

                                <div>
                                    <div style="max-width: 400px;" id="reservationdate"
                                        class="d-flex align-items-center justify-content-between">
                                        @if(isset($payments) && count($payments) > 0)
                                            <input type="hidden" name="contractId" value="{{ $payments[0]->contract->id }}">
                                        @else
                                            <input type="hidden" name="contractId" value="{{ $contractId }}">
                                        @endif
                                        <input value="{{ isset($astart) ? $astart : '' }}" type="text" name="start_date"
                                            id="start_date" class="form-control" placeholder="Start Date"
                                            style="max-width: 150px;" required />
                                        <span style="margin: 0 10px; font-weight: bold;">to</span>
                                        <input value="{{ isset($aend) ? $aend : '' }}" type="text" name="end_date"
                                            id="end_date" class="form-control" placeholder="End Date" style="max-width: 150px;"
                                            required />
                                    </div>
                                </div>
                            </div>
                        </form>
                        <a href="{{ route('addpaymentform') }}" class="btn brannedbtn ml-2">+ New</a>
                    </div>
                    @if (session('success'))
                        <ol>
                            <div class="alert alert-success" id="success-alert">
                                {{ session('success') }}
                            </div>
                            <script>
                                setTimeout(function() {
                                    document.getElementById('success-alert').style.display = 'none';
                                }, 4000); // 2000ms = 2 seconds
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
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Details</th>
                                            <th>Action</th> <!-- Ensure this column exists -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($payments as $payment)
                                        <tr>
                                            <td>{{ $payment->contract->customer->name }} - {{ $payment->contract->customer->company }}</td>
                                            <td>{{ number_format($payment->amount, 0) }}</td>
                                            <td>{{ \App\Helpers\AfghanCalendarHelper::toAfghanDate($payment->date); }}</td>
                                            <td>{{ $payment->details }}</td>
                                            <td>
                                               
                                                    <a href="{{ route('editpayment', $payment->id) }}"
                                                        class="btn pt-0 pb-0 btn-warning fa fa-edit" title="Edit">
                                                    </a>

                                                    <form action="{{ route('deletepayment', $payment) }}" method="POST"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn pt-0 pb-0 btn-danger"
                                                            title="Delete"
                                                            onclick="return confirm('Are you sure you want to delete this payment?')">
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
                                            <th id="total-sales"></th> <!-- Only total sales is needed -->
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
            $(document).ready(function() {
    const table = $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "footerCallback": function(row, data, start, end, display) {
            updateFooterTotals(this.api());
        },
        "buttons": [
            {
                extend: 'excel',
                footer: true,
                exportOptions: {
                    columns: ':not(:last-child)' // Exclude last column
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

    table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    function updateFooterTotals(api) {
        let totalAmount = api
            .column(1, { search: "applied" }) // Ensure we use the correct column index (1 = Amount)
            .data()
            .reduce((total, value) => {
                let numericValue = parseFloat(value.toString().replace(/[^0-9.-]+/g, "")); // Remove non-numeric characters
                return !isNaN(numericValue) ? total + numericValue : total;
            }, 0);

        // Format total amount
        let formattedTotal = totalAmount.toLocaleString("en-US", {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        });

        // Apply to footer
        $("#total-sales").html(formattedTotal);
    }

    // Trigger footer calculation after table draw
    table.on("draw", function() {
        updateFooterTotals(table);
    });

    updateFooterTotals(table); // Initial footer calculation
});


        });
    </script>
@endsection

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
                <div dir="rtl" class="totalamount searchBar row mb-1">
                    <h2>
                        بیلانس حساب شرکت
                    </h2>
               

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
                                            <th>شرکت</th>
                                            <th>مجموعه خریداری</th>
                                            <th>مجموع پرداخت</th>
                                            <th>بیلانس</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                
                                    <tbody>
                                        @foreach ($balances as $balance)
                                            <tr dir="ltr">
                                                <td> {{ $balance->supplier }}</td>
                                                <td>{{ number_format($balance->total_purchases, 0) }}</td>
                                                <td>{{ number_format($balance->total_payments, 0) }}</td>
                                                @if ($balance->balance < 0)
                                                    <td class="redcolor">{{ number_format($balance->balance, 0) }}</td>
                                                @else
                                                    <td>{{ number_format($balance->balance, 0) }}</td>
                                                @endif
                                               <td>
                                                <a href="{{ route('hesabSherkat_purchase', ['supplier' => e($balance->supplier)]) }}"
                                                    class="btn btn-sm btn-success" title="Purchase">خریداری
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('hesabSherkat_payment', ['supplier' => e($balance->supplier)]) }}"
                                                    class="btn btn-sm btn-info" title="Payments">پرداخت
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                               </td>
                                            </tr>
                                        @endforeach
                                    </tbody> 
                                    <tfoot dir="ltr">
                                        <tr>
                                            <th>Total</th>
                                            <th id="total-sales"></th>
                                            <th id="total-payment"></th>
                                            <th id="total-balance"></th>
                                            <td></td>
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
            $(function() {
                const table = $("#example1").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "footerCallback": function(row, data, start, end, display) {
                        updateFooterTotals(this.api());
                    },
                    "buttons": [{
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

                // Append buttons
                table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

                // Function to calculate column total
                function calculateColumnTotal(api, columnIndex) {
                    return api.column(columnIndex, {
                        search: 'applied'
                    }).data().reduce((total, value) => {
                        const numericValue = parseFloat(value.replace(/,/g, '')); // Remove commas
                        return !isNaN(numericValue) ? total + numericValue : total;
                    }, 0);
                }

                // Update the footer with calculated totals
                function updateFooterTotals(api) {
                    const totalSales = calculateColumnTotal(api, 1); // Column index 1: Total Sales
                    const totalPayment = calculateColumnTotal(api, 2); // Column index 2: Total Payment
                    const totalBalance = calculateColumnTotal(api, 3); // Column index 3: Balance

                    // Format the totals
                    const formattedTotalSales = totalSales.toLocaleString('en-US', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                    const formattedTotalPayment = totalPayment.toLocaleString('en-US', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                    const formattedTotalBalance = totalBalance.toLocaleString('en-US', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });

                    // Update the footer
                    $('#total-sales').text(formattedTotalSales);
                    $('#total-payment').text(formattedTotalPayment);
                    $('#total-balance').text(formattedTotalBalance);
                }

                // Initial footer update
                updateFooterTotals(table);

                // Update totals when the table is redrawn (pagination, search, filter)
                table.on('draw', function() {
                    updateFooterTotals(table);
                });
            });
        });
    </script>
@endsection

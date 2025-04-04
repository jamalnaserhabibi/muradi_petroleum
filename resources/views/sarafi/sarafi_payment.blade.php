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

                <div class="totalamount searchBar row mb-1" dir="rtl">
                    <h2>
                        رسیدات شروع از
                        {{ isset($sarafiPayments) && isset($sarafiPayments[0]) ? \App\Helpers\AfghanCalendarHelper::toAfghanDate($sarafiPayments[0]->date) : 'No Data' }}

                    </h2>
                    <form id="filter-form" action="{{ route('sarafipayments') }}" method="GET">
                        {{-- <input type="hidden" name="start_date" id="start-date"> --}}
                        {{-- <input type="hidden" name="end_date" id="end-date"> --}}
                        <div class="form-group d-flex">
                            <div>
                                {{-- <label>Date range:</label> --}}
                                <div style="max-width: 400px;" id="reservationdate" class="d-flex align-items-center justify-content-between">
                                    <input value="{{ isset($afghaniEndDate) ? $afghaniEndDate : '' }}" type="text" name="end_date" id="end_date" class="form-control" placeholder="ختم تاریخ" style="max-width: 150px;" required />
                                    <span style="margin: 0 10px; font-weight: bold;">to</span>
                                    <input value="{{ isset($afghaniStartDate) ? $afghaniStartDate : '' }}" type="text" name="start_date" id="start_date" class="form-control" placeholder="شروع تاریخ" style="max-width: 150px" required />
                                </div>
                            </div>
                        </div>
                    </form>
                    <button type="button" class="btn brannedbtn" data-toggle="modal" data-target="#addPaymentModal">
                        <i class="fas fa-plus"></i>
                        ثبت برداشت جدید
                    </button>

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
                                            <th>مقدار افغانی</th>
                                            <th>معادل به دالر</th>
                                            <th>نرخ دالر</th>
                                            <th>مقدار دالر</th>
                                            <th>معادل به افغانی</th>
                                            <th>نرخ دالر</th>
                                            <th>از درک</th>
                                            <th>تاریخ</th>
                                            <th>ملاحظات</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sarafiPayments as $Payments)
                                            <tr>
                                                <td>{{ number_format($Payments->amount_afghani,1) }}</td>
                                                <td>{{ number_format($Payments->equivalent_dollar,1) }}</td>
                                                <td>{{ number_format($Payments->amount_afghani / $Payments->equivalent_dollar,1) }}</td>
                                                <td>{{ number_format($Payments->amount_dollar,1) }}</td>
                                                <td>{{ number_format($Payments->moaadil_afghani,1) }}</td>
                                                <td>{{ number_format($Payments->moaadil_afghani / $Payments->amount_dollar,1) }}</td>

                                                <td>{{ $Payments->az_darak}}</td>
                                                <td>{{ \App\Helpers\AfghanCalendarHelper::toAfghanDate($Payments->date); }}</td>
                                                <td>{{ $Payments->details }}</td>
                                                <td>
                                                    <a href="{{ route('purchaseedit', $Payments->id) }}"
                                                        class="btn pt-0 pb-0 btn-warning fa fa-edit" title="Edit">
                                                    </a>

                                                    <form action="{{ route('purchasedelete', $Payments) }}" method="POST"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn pt-0 pb-0 btn-danger"
                                                            title="Delete"
                                                            onclick="return confirm('Are you sure you want to delete this purchase?')">
                                                            <li class="fas fa-trash"></li>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>مجموع</th>
                                            <th id="total-footer-ton"></th> <!-- Ton column -->
                                            <th></th> <!-- Empty cell to keep alignment -->
                                            <th></th> <!-- Empty cell to keep alignment -->
                                            <th id="total-footer-amount"></th> <!-- Total Amount column -->
                                            <th id="total-footer-liter"></th> <!-- Total Liter column -->
                                            <th></th> <!-- Empty cells for the other columns (Details, Edit, Delete) -->
                                            <th></th> <!-- Empty cells for the other columns (Details, Edit, Delete) -->
                                            <th></th> <!-- Empty cells for the other columns (Details, Edit, Delete) -->
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
     <!-- Modal new -->
     <div dir="rtl" class="modal fade" id="addPaymentModal" tabindex="-1" role="dialog" aria-labelledby="assignTowerModalLabel"
     aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="assignTowerModalLabel">ثبت برداشت جدید</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                <form action="{{ route('sarafi_payment.store') }}" method="POST">
                    @csrf
            
                    <div class="form-group">
                        <input type="number" placeholder="مقدار افغانی" step="0.01" name="amount_afghani" class="form-control" required>
                    </div>
            
                    <div class="form-group">
                        <input type="number" placeholder="معدل دالر" step="0.01" name="equivalent_dollar" class="form-control" required>
                    </div>
            
                    <div class="form-group">
                        <input type="number" placeholder="مقدار دالر" step="0.01" name="amount_dollar" class="form-control" required>
                    </div>
            
                    <div class="form-group">
                        <input type="number" placeholder="معادل افغانی" step="0.01" name="moaadil_afghani" class="form-control" required>
                    </div>
            
                    <div>
                            <input type="text" name="date" id="date" class="form-control mb-3" value={{ $afghancurrentdate }} required />
                    </div>
            
                    <div class="form-group">
                        {{-- <label>از درک</label> --}}
                        <input type="text" placeholder="از درک" name="az_darak" class="form-control" required>
                    </div>
            
                    <div class="form-group">
                        {{-- <label>جزیات</label> --}}
                        <textarea name="details" placeholder="تفصیلات" class="form-control" rows="3"></textarea>
                    </div>
            
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        <button type="submit" class="btn btn-primary">ثبت</button>
                    </div>
                </form>
            </div>
            
         </div>
     </div>
 </div>
@endsection

@section('CustomScript')
    <script src="plugins/select2/js/select2.full.min.js"></script>
    <script>
        $(document).ready(function() {
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
                        minimumFractionDigits: 3,
                        maximumFractionDigits: 3
                    });
                    const formattedAmountTotal = amountTotal.toLocaleString('en-US', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                    const formattedLiterTotal = literTotal.toLocaleString('en-US', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });

                    // Update the footer
                    $('#total-footer-ton').text(formattedTonTotal);
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

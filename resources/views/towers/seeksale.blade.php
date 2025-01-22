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
                <div class="totalamount searchBar row mb-1 ">
                    <h2>

                        @if(isset($tower[0]))
                            {{ $tower[0]->tower->serial}}-{{ $tower[0]->tower->name}}-{{ $tower[0]->contract->product->product_name }} Tower {{ explode(' ', \App\Helpers\AfghanCalendarHelper::toAfghanDate($tower[0]->tower->date))[1] }}
                        @endif
                        
                        Sale(s)
                    </h2>


                    <form class="expensefilterform" id="filter-form" action="{{ route('expensefilterdate') }}"
                        method="GET">
                        <div class="form-group d-flex">
                            <div style="max-width: 400px;" id="reservationdate"
                                class="d-flex align-items-center justify-content-between">
                                <input value="{{ isset($afghaniStartDate) ? $afghaniStartDate : '' }}" type="text"
                                    name="start_date" id="start_date" class="form-control" placeholder="Start Date"
                                    style="max-width: 150px;" required />
                                <span style="margin: 0 10px; font-weight: bold;">to</span>
                                <input value="{{ isset($afghaniEndDate) ? $afghaniEndDate : '' }}" type="text"
                                    name="end_date" id="end_date" class="form-control" placeholder="End Date"
                                    style="max-width: 150px;" required />
                            </div>


                            <!-- Category Filter -->
                            <div class="ml-4">
                                {{-- <label>Category:</label> --}}
                                <select id="category-filter" name="category" class="form-control">
                                    <option value="">All Category</option>
                                    <option value="personal" {{ request('category') == 'personal' ? 'selected' : '' }}>
                                        Personal</option>
                                    <option value="Tank Maintenance"
                                        {{ request('category') == 'Tank Maintenance' ? 'selected' : '' }}>Tank Maintenance
                                    </option>
                                    <option value="Staff Salary"
                                        {{ request('category') == 'Staff Salary' ? 'selected' : '' }}>Staff Salary</option>
                                    <option value="tank" {{ request('category') == 'tank' ? 'selected' : '' }}>Tank
                                    </option>
                                    <option value="Fuel" {{ request('category') == 'Fuel' ? 'selected' : '' }}>Fuel
                                    </option>
                                    <option value="Tax" {{ request('category') == 'Tax' ? 'selected' : '' }}>Tax
                                    </option>
                                    <option value="office" {{ request('category') == 'office' ? 'selected' : '' }}>Office
                                    </option>
                                    <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other
                                    </option>
                                </select>
                            </div>
                        </div>
                    </form>

                    <a href="{{ route('towers') }}" class="btn brannedbtn">Back To Towrs</a>

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
                                        @foreach ($tower as $tower)
                                            <tr>
                                                <td> {{ $tower->contract->customer->name }} - {{ $tower->contract->customer->company }} </td>
                                                <td>{{ $tower->tower->serial}}-{{ $tower->tower->name}}</td>
                                                <td>{{ $tower->contract->product->product_name }}</td>
                                                <td>{{ number_format($tower->rate, 0) }}</td>
                                                <td>{{ $tower->amount }}</td>
                                                <td>{{ $tower->amount *$tower->rate }}</td>
                                                <td style="white-space: nowrap;">{{ \App\Helpers\AfghanCalendarHelper::toAfghanDateTime($tower->date); }}</td>
                                                <td>{{ $tower->details }}</td>
                                                <td>
                                                    {{-- <a href="{{ route('edittower', $tower->id) }}"
                                                        class="btn pt-0 pb-0 btn-warning fa fa-edit" title="Edit">
                                                    </a> --}}
{{-- 
                                                    <form action="{{ route('towerdelete', $tower->id) }}" method="POST"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn pt-0 pb-0 btn-danger"
                                                            title="Delete"
                                                            onclick="return confirm('Are you sure you want to delete this tower?')">
                                                            <li class="fas fa-trash"></li>
                                                        </button>
                                                    </form> --}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Total</th>
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
    {{-- commented for the sidebar btn not worked --}}
    {{-- <script src="dist/js/adminlte.min2167.js?v=3.2.0"></script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $(function() {
                const table = $("#example1").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    // "dom": 'fBrtip',
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

             


                // Add a label for the total amount
                // const totalLabel = $('<h2>')
                //     .addClass('ml-3') // Add styling
                //     .attr('id', 'total-amount-label')
                //     .text('Total: 0.00'); // Initial value

                // $('.totalamount').children().eq(0).after(totalLabel);

                // Update both the footer and the <h2> label
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

    {{-- <script src="dist/js/demo.js"></script> --}}
@endsection

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
                        Expenses of
                        {{ isset($expenses) && isset($expenses[0]) ? \App\Helpers\AfghanCalendarHelper::getAfghanMonth($expenses[0]->date) : 'No Data' }}

                    </h2>


                    <form class="expensefilterform" id="filter-form" action="{{ route('expensefilterdate') }}"
                        method="GET">
                        {{-- <input type="hidden" name="start_date" id="start-date"> --}}
                        {{-- <input type="hidden" name="end_date" id="end-date"> --}}
                        <!-- Date Range Picker and Category Dropdown -->
                        <div class="form-group d-flex">
                            <!-- Date Range Picker -->
                            <div style="max-width: 400px;" id="reservationdate"
                                class="d-flex align-items-center justify-content-between">
                                {{-- {{ \App\Helpers\AfghanCalendarHelper::toAfghanDate($expenses[0]->date) }} --}}
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
                                    <option value="Tax" {{ request('category') == 'Tax' ? 'selected' : '' }}>Tax</option>
                                    <option value="office" {{ request('category') == 'office' ? 'selected' : '' }}>Office
                                    </option>
                                    <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other
                                    </option>
                                </select>
                            </div>
                        </div>
                    </form>
                    <a href="{{ route('expenseaddform') }}" class="btn brannedbtn">+ New</a>

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
                                            <th>Item</th>
                                            <th>Amount</th>
                                            <th>Category</th>
                                            <th>Date</th>
                                            {{-- <th>Document</th> --}}
                                            <th>Description</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($expenses as $expense)
                                            <tr>
                                                <td>{{ $expense->item }}</td>
                                                <td>{{ number_format($expense->amount, 2) }}</td>
                                                <td>{{ $expense->category }}</td>
                                                <td>{{ \App\Helpers\AfghanCalendarHelper::toAfghanDate($expense->date) }}
                                                </td>
                                                <td>
                                                    @if ($expense->document)
                                                        <a href="{{ asset('storage/' . $expense->document) }}"
                                                            target="_blank">
                                                            {{ $expense->description ?? 'Document' }}
                                                            {{-- <img class="useraccountsimage" src={{ asset('storage/' . $expense->document) }} alt="Document"> --}}
                                                        </a>
                                                    @else
                                                        {{ $expense->description }} (No Document)
                                                    @endif
                                                </td>
                                                {{-- <td></td> --}}
                                                <td>
                                                    <a href="{{ route('expenses.edit', $expense) }}"
                                                        class="btn pt-0 pb-0 btn-warning fa fa-edit" title="Edit"></a>
                                                    <form action="{{ route('expenses.destroy', $expense) }}" method="POST"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn pt-0 pb-0 btn-danger"
                                                            title="Delete"
                                                            onclick="return confirm('Are you sure you want to delete this user?')">
                                                            <li class="fas fa-trash"></li>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="1">Total</th>
                                            <th id="total-footer"></th> <!-- Footer for the total amount -->
                                            <th colspan="4"></th>
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

                // Calculate total of the "Amount" column
                function calculateTotal() {
                    let total = 0;
                    table.rows({
                        search: 'applied'
                    }).every(function() {
                        const rowData = this.data();
                        const amount = parseFloat(rowData[1].replace(/,/g,
                            ''));
                        if (!isNaN(amount)) {
                            total += amount;
                        }
                    });
                    return total;
                }


                // Add a label for the total amount
                // const totalLabel = $('<h2>')
                //     .addClass('ml-3') // Add styling
                //     .attr('id', 'total-amount-label')
                //     .text('Total: 0.00'); // Initial value

                // $('.totalamount').children().eq(0).after(totalLabel);

                // Update both the footer and the <h2> label
                function updateFooterTotal() {
                    const total = calculateTotal();
                    const formattedTotal = parseFloat(total).toLocaleString('en-US', {
                        minimumFractionDigits: 1,
                        maximumFractionDigits: 1
                    });
                    $('#total-footer').text(formattedTotal);
                    // totalLabel.text(`Total: ${formattedTotal}`);
                }

                // Update total after DataTable initialization
                updateFooterTotal();

                // Update total on table draw (e.g., pagination, search)
                table.on('draw', function() {
                    updateFooterTotal();
                });
            });
        });
    </script>

    {{-- <script src="dist/js/demo.js"></script> --}}
@endsection

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

                <div class="totalamount searchBar row mb-1">
                    <h2>
                        Purchases of
                        {{ isset($purchases) && isset($purchases[0]) ? $purchases[0]->date->format('F') : 'No Data' }}
                    </h2>


                    <form id="filter-form" action="{{ route('purchasefilter') }}" method="GET">
                        <input type="hidden" name="start_date" id="start-date">
                        <input type="hidden" name="end_date" id="end-date">
                        <!-- Date Range Picker and Category Dropdown -->
                        <div class="form-group d-flex">
                            <!-- Date Range Picker -->
                            <div>
                                {{-- <label>Date range:</label> --}}
                                <div class="input-group">
                                    <button type="button" class="btn btn-default float-right" id="daterange-btn">
                                        <i class="far fa-calendar-alt"></i> Date Range
                                        <i class="fas fa-caret-down"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="ml-4">
                              
                                <select id="product-filter" name="product_id" class="form-control">
                                    <option value="">All Types</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->product_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>

                    <a href="{{ route('addpurchaseform') }}" class="btn brannedbtn">+ New</a>

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
                                            <th>Type</th>
                                            <th>Ton</th>
                                            <th>Weight</th>
                                            <th>Ton Rate</th>
                                            <th>Total Amount</th>
                                            <th>Total Liter</th>
                                            <th>Date</th>
                                            <th>Details</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($purchases as $purchase)
                                            <tr>
                                                <td>{{ $purchase->product->product_name }}</td>
                                                <td>{{ $purchase->amount  }}<sup>T</sub></td>
                                                <td>{{ $purchase->heaviness }}</td>
                                                <td>{{ number_format($purchase->rate ,2)}}</td>
                                                <td>{{ number_format($purchase->rate * $purchase->amount,2)}}</td>
                                                <td>{{ number_format(($purchase->amount/$purchase->heaviness)*1000,2)  }}</td>
                                                <td>{{ $purchase->date->format('d M Y') }}</td>
                                                <td>{{ $purchase->details }}</td>
                                                <td>
                                                    <a href="{{ route('purchaseedit', $purchase->id) }}"
                                                        class="btn pt-0 pb-0 btn-warning fa fa-edit" title="Edit">
                                                    </a>

                                                    <form action="{{ route('purchasedelete', $purchase) }}" method="POST"
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
                "responsive": false,
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
            const totalLabel = $('<h2>')
                .addClass('ml-3') // Add styling
                .attr('id', 'total-amount-label')
                .text('Total: 0.00'); // Initial value

            $('.totalamount').children().eq(0).after(totalLabel);

            // Update both the footer and the <h2> label
            function updateFooterTotal() {
                const total = calculateTotal();
                const formattedTotal = parseFloat(total).toLocaleString('en-US', {
                    minimumFractionDigits: 1,
                    maximumFractionDigits: 1
                });
                $('#total-footer').text(formattedTotal);  
                totalLabel.text(`Total: ${formattedTotal}`); 
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

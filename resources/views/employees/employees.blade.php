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

                    <h2>Employees</h2>

                    <div class="col-6 d-flex align-items-center justify-content-end">
                        @if (session('success'))
                            <ol>
                                <div class="alert alert-success" id="success-alert">
                                    {{ session('success') }}
                                </div>
                                <script>
                                    setTimeout(function() {
                                        document.getElementById('success-alert').style.display = 'none';
                                    }, 4000); //
                                </script>
                            </ol>
                        @endif
                        <a href="{{ route('addemployee') }}" class="btn brannedbtn">+ New</a>
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
                                <table id="example1" class="table table-bordered table-striped useraccounts">
                                    <thead>
                                        <tr>
                                            {{-- <th>ID</th> --}}
                                            <th>Fullname</th>
                                            <th>Salary</th>
                                            <th>Hired</th>
                                            <th>Photo</th>
                                            <th>Description</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($employees as $employee)
                                            <tr>
                                                {{-- <td>{{ $employee->id }}</td> --}}
                                                <td>{{ $employee->fullname }}</td>
                                                <td>{{ number_format($employee->salary, 2) }}</td>
                                                <td>{{ $employee->date->format('d M Y') }}</td>
                                                <td>
                                                    @if ($employee->photo)
                                                        <img src="{{ asset('storage/' . $employee->photo) }}"
                                                            alt="Employee Photo" class="useraccountsimage">
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>{{ $employee->description }}</td>
                                                <td>
                                                    <a href="{{ route('editemployee', $employee->id) }}" title="Edit"
                                                        class="btn btn-warning pt-0 pb-0  fa fa-edit">

                                                    </a>
                                                    <form action="{{ route('employee.delete', $employee->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" title="Delete" class="btn btn-danger pt-0 pb-0 btn-danger"
                                                            onclick="return confirm('Are you sure you want to delete this employee?')">
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
                                            <th id="total-footer"></th>
                                            <!-- Footer for the total amount -->
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

    <script src="dist/js/demo.js"></script>

    <script>
      $(function() {
            // Initialize DataTable and store the instance
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
                            columns: ':not(:last-child)' // Exclude the last column (Action column)
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
                    const amount = parseFloat(rowData[1].replace(/,/g, '')); // Assuming 'Amount' is the 2nd column (index 1)
                    if (!isNaN(amount)) {
                        total += amount;
                    }
                });
                return total; // Format total to 2 decimal places
            }


            // Add a label for the total amount
            const totalLabel = $('<h2>')
                .addClass('ml-3') // Add styling
                .attr('id', 'total-amount-label')
                .text('Total: 0.00'); // Initial value

            $('.totalSalary').children().eq(0).after(totalLabel);

            // Update both the footer and the <h2> label
                function updateFooterTotal() {
    const total = calculateTotal();
    const formattedTotal = parseFloat(total).toLocaleString('en-US', { minimumFractionDigits: 1, maximumFractionDigits: 1 });
    $('#total-footer').text(formattedTotal); // Update the footer cell with the formatted total
    totalLabel.text(`Total: ${formattedTotal}`); // Update the <h2> label with the formatted total
}
            // Update total after DataTable initialization
            updateFooterTotal();

            // Update total on table draw (e.g., pagination, search)
            table.on('draw', function() {
                updateFooterTotal();
            });
        });

    </script>
    @endsection

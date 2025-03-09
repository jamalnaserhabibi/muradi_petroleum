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
                    <h2>Distributers</h2>
                    <div class="col-6 d-flex align-items-center justify-content-end">
                        <!-- Button to trigger the modal -->
                        <button type="button" class="btn brannedbtn" data-toggle="modal" data-target="#assignTowerModal">
                            Assign Tower
                        </button>

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
                                            <th>Fullname</th>
                                            <th>Tower Assigned</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($employees as $employee)
                                            <tr>
                                                <td>{{ $employee->fullname }}</td>
                                                <td>
                                                    <div class="d-flex flex-wrap">
                                                        @foreach ($employee->towers as $tower)
                                                            <div class="d-flex align-items-center mb-2 me-2">
                                                                <!-- Combined Tower Information and Delete Button -->
                                                                <div class="tower-info-delete d-flex align-items-center p-2 rounded">
                                                                    <!-- Tower Information -->
                                                                    <span class="btn btn-info mr-2">{{ $tower->serial }} - {{ $tower->name }} - {{ $tower->product->product_name }}</span>
                                                    
                                                                    <form action="{{ route('delete_distributer', ['employee_id' => $employee->id, 'tower_id' => $tower->id]) }}" method="POST" style="display:inline;">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" title="Delete" class="btn btn-link p-0 border-0 bg-transparent" onclick="return confirm('Are you sure you want to remove this tower assignment?')">
                                                                            <i class="fas fa-trash text-danger"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal for Assigning Tower -->
    <div class="modal fade" id="assignTowerModal" tabindex="-1" role="dialog" aria-labelledby="assignTowerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignTowerModalLabel">Assign Tower to Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('distributors.assign') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="employee_id">Select Employee</label>
                            <select class="form-control" id="employee_id" name="employee_id" required>
                                <option value="">Select Employee</option>
                                @foreach ($allemployees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->fullname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tower_id">Select Tower(s)</label>
                            <select class="form-control" id="tower_id" name="tower_id[]" multiple required>
                                @foreach ($availableTowers as $tower)
                                    <option value="{{ $tower->id }}">{{ $tower->serial }} - {{ $tower->name }} -{{$tower->product->product_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Assign Tower</button>
                        </div>
                    </form>
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
                            columns: '*' // Include all columns
                        }
                    }
                ]
            });
    
            // Append buttons to the container
            table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>
    
@endsection

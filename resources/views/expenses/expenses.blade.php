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
                    <div  class="row mb-2">
                        <div class="col-6">
                            <h1>Expenses</h1>
                        </div>
                        <div class="col-6 d-flex align-items-center justify-content-end">
                                
                            @if(session('success'))
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
                            <a href="{{ route('expenseaddform') }}" class="btn brannedbtn">Add New</a>
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
                                                <th>Item</th>
                                                <th>Amount</th>
                                                <th>Category</th>
                                                <th>Info</th>
                                                <th>Date</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($expenses as $expense)
                                            <tr>
                                                <td>{{ $expense->item }}</td>
                                                <td>{{ $expense->amount }}</td>
                                                <td>{{ $expense->category }}</td>
                                                <td>{{ $expense->description }}</td>
                                                <td>{{ $expense->updated_at }}</td>
                                                <td>
                                                    <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-warning" >Edit</a>
                                                    <form action="{{ route('expenses.destroy', $expense) }}"  method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                                    </form>
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
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["excel", "pdf", "print"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>


@endsection
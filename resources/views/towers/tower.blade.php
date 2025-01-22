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
                            <h1>Towers</h1>
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
                            <a href="{{ route('addtowerform') }}" class="btn brannedbtn">+ New</a>
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
                                                <th>Name</th>
                                                <th>Product</th>
                                                <th>Sales</th>
                                                <th>Details</th>
                                                <th></th>
                                               
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($towers as $tower)
                                            <tr>
                                                
                                                <td><span class=" mr-2">{{ $tower->serial }}</span>{{ $tower->name }}</td>
                                                <td>{{ $tower->product->product_name }}</td>
                                                <td>{{ $tower->sales_sum_amount ?? 0 }}</td>
                                                <td>{{ $tower->details }}</td>
                                                <td>
                                                    <a href="{{ route('tower.seeksale', $tower->id) }}"
                                                        class="btn pt-0 pb-0 btn-info fa fa-eye " title="Search">
                                                    </a>

                                                    <a href="{{ route('tower.edit', $tower->id) }}"
                                                        class="btn pt-0 pb-0 btn-warning fa fa-edit" title="Edit">
                                                    </a>

                                                    <form action="{{ route('tower.destroy', $tower->id) }}" method="POST"
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
                                        {{-- <tfoot>
                                            <tr>
                                                <th>Rendering engine</th>
                                                <th>Browser</th>
                                                <th>Platform(s)</th>
                                                <th>Engine version</th>
                                                <th>CSS grade</th>
                                            </tr>
                                        </tfoot> --}}
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
            "buttons": [
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: ':not(:last-child)' // Exclude the last column (Action column)
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: ':not(:last-child)' // Exclude the last column (Action column)
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':not(:last-child)' // Exclude the last column (Action column)
                    }
                }
            ]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
       
    });
</script>


@endsection
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
        {{-- <section class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
              <div class="col-sm-6">
                  <h1>Add New Expense Item</h1>
              </div>
              <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="#">Home</a></li>
                      <li class="breadcrumb-item active">General Form</li>
                  </ol>
              </div>
          </div>
      </div>
  </section> --}}

        <section class="content ">
            <div class="container-fluid">
                <div class="row ">
                    <div class="col-md-12">
                        <div class="card mt-3">
                            @if (session('success'))
                                {{-- <ol> --}}
                                <div class="alert alert-success" id="success-alert">
                                    {{ session('success') }}
                                </div>
                                <script>
                                    setTimeout(function() {
                                        document.getElementById('success-alert').style.display = 'none';
                                    }, 4000); // 2000ms = 2 seconds
                                </script>
                                {{-- </ol> --}}
                            @endif
                            <form
                                action="{{ isset($serialNumber) ? route('serial_numbers_update', $serialNumber->id) : route('serial_numbers_store') }}"
                                method="POST">
                                <div class="card-header brannedbtn">
                                    <h3 class="card-title ">Tower's Meter</h3>
                                </div>
                                @csrf

                                @if (isset($serialNumber))
                                    @method('PATCH')
                                @endif
                                <div class="card-body formserial">
                                    @error('tower_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <!-- Type Dropdown -->
                                    <select class="form-control mb-3" name="tower_id" id="tower_id" required>
                                        <option value="" disabled {{ isset($serialNumber) ? '' : 'selected' }}>
                                            Select Tower</option>
                                        @foreach ($towers as $tower)
                                            <option value="{{ $tower->id }}"
                                                {{ old('id', $tower->id ?? '') == ($serialNumber->tower_id ?? '') ? 'selected' : '' }}>
                                                {{ $tower->serial }} -
                                                {{ $tower->product->product_name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('serial')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <input class="form-control form-control mb-2 ml-3" name="serial" type="number"
                                        step="1" id="serial" placeholder="Serial"
                                        value="{{ old('serial', $serialNumber->serial ?? request('serial')) }}" required>

                                    @error('date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                    <div class="input-group date ml-3" id="reservationdate">
                                        <input 
                                            type="text" 
                                            name="date" 
                                            id="date" 
                                            class="form-control" 
                                            {{-- value="{{ old('date', $serialNumber->date ?? '') }}"  --}}
                                            required 
                                        />
                                        <div class="input-group-append h-10">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                    

                                    <div class="ml-3">
                                        <button type="submit" class="btn brannedbtn">
                                            {{ isset($serialNumber) ? 'Update' : 'Add' }}</button>
                                    </div>
                                </div>
                            </form>
                         
                        </div>
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
                                            <th>ID</th>
                                            <th>Tower</th>
                                            <th>Serial</th>
                                            <th>Differences</th>
                                            <th>Sold</th>
                                            <th>Date</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($finalResults as $record)
                                            <tr>
                                                <td>{{ $record->sid }}</td>
                                                <td>{{ $record->tower_number }} - {{ $record->name }} -  {{ $record->product_name }}</td>
                                                <td>{{ $record->serial_number }}</td>
                                                <td>{{ $record->petrol_sold }}</td>
                                                <td>{{ $record->total_amount ?? 0 }}</td>
    
                                                @if (!empty($record->date) && \Carbon\Carbon::parse($record->date)->isToday())
                                                    <td style="color:red;white-space: nowrap;">Today:
                                                        {{ \App\Helpers\AfghanCalendarHelper::toAfghanDate($record->date); }}
                                                    </td>
                                                @elseif(!empty($record->date))
                                                    <td style="white-space: nowrap;"> {{ \App\Helpers\AfghanCalendarHelper::toAfghanDate($record->date); }}</td>
                                                @endif
    
                                                <td>
    
                                                    <form action="{{ route('editserialnumber', $record->sid) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn pt-0 pb-0 btn-warning fa fa-edit"
                                                            title="Edit"></button>
                                                    </form>
    
                                                    <form action="{{ route('deleteserialnumber', $record->sid) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn pt-0 pb-0 btn-danger" title="Delete"
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
                                            <th>Total</th>
                                            <th id="total-footer-ton"></th> <!-- Ton column -->
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $(function() {

                const table = $("#example1").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    // "dom": 'tBfrip',
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

            });
        });
    </script>
@endsection


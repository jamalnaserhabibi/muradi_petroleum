@extends('admin.layout')
@section('CustomCss')
    <link rel="stylesheet" href="admincss/useraccounts/styleforall.css">
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endsection
@section('content')
    <div class="content-wrapper">
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
                                        <option value="" disabled {{ isset($serialNumber) ? '' : 'selected' }}>Select Tower</option>
                                        @foreach ($towers as $tower)
                                            @if ($tower->product->id != 13 && $tower->product->id != 14)
                                                <option value="{{ $tower->id }}"
                                                    {{ old('id', $tower->id ?? '') == ($serialNumber->tower_id ?? '') ? 'selected' : '' }}>
                                                    {{ $tower->serial }}  -
                                                    {{ $tower->product->product_name }}
                                                </option>
                                            @endif
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
                                            {{-- <th>ID</th> --}}
                                            <th>Tower Name</th>
                                            <th>Date</th>
                                            <th>Current Reading</th>
                                            {{-- <th>Previous Reading</th> --}}
                                            <th>Differences</th>
                                            <th> </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $previousKey = null;
                                            $rowspan = [];
                                        @endphp
                                    
                                        @foreach ($result as $row)
                                            @php
                                                $currentKey = $row['tower_serial'] . '-' . $row['product_name'];
                                                
                                                // Count occurrences for rowspan
                                                if (!isset($rowspan[$currentKey])) {
                                                    $rowspan[$currentKey] = 1;
                                                } else {
                                                    $rowspan[$currentKey]++;
                                                }
                                            @endphp
                                        @endforeach
                                    
                                        @foreach ($result as $row)
                                        
                                            @php
                                            
                                                $currentKey = $row['tower_serial'] . '-' . $row['product_name'];
                                            @endphp
                                            <tr>
                                                {{-- <td>{{ $row['id'] }}</td> --}}

                                                @if ($rowspan[$currentKey] > 0)
                                                    <td  style="vertical-align: middle; text-align: center;" rowspan="{{ $rowspan[$currentKey] }}">
                                                       <span class="btn btn-info mr-2 mb-2">
                                                        <a style="color: white" href="{{ route('singletowereadings', ['tower_id' => $row['tower_id']]) }}"> <i class="fas fa-gas-pump"></i>- {{ $row['tower_serial'] }} - {{ $row['product_name'] }}</a>
                                                       
                                                       </span>
                                                    </td>
                                                    @php $rowspan[$currentKey] = 0; @endphp
                                                @endif
                                                <td>
                                                    @php
                                                        $today = \Carbon\Carbon::now()->toDateString(); // Get today's date in Y-m-d format
                                                        $date = \Carbon\Carbon::parse($row['date'])->toDateString();
                                                    @endphp
                                                
                                                    @if ($date === $today)
                                                     <span style="margin-left: -25px">🔴</span>    
                                                    @endif
                                                
                                                    {{ \App\Helpers\AfghanCalendarHelper::toAfghanDate($row['date']) }}
                                                </td>
                                                <td>{{ $row['current_reading'] }}</td>
                                                {{-- <td>{{ $row['previous_reading'] ?? 'N/A' }}</td> --}}
                                                    <td>{{ $row['sold_petrol']!=0 ? $row['sold_petrol'] :'' }}</td>
                                            <td>
                                                <a href="{{ route('singletowereadings', ['tower_id' => $row['tower_id']]) }}"

                                                    class="btn pt-0 pb-0 btn-success  fa fa-eye " title="Search">
                                                </a>
                                                <form action="{{ route('deleteserialnumber', $row['id']) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn pt-0 pb-0 btn-danger" title="Delete"
                                                        onclick="return confirm('Are you sure you want to delete this reading?')">
                                                        <li class="fas fa-trash"></li>
                                                    </button>
                                                </form>
                                            </td>
                                                </tr>
                                        @endforeach
                                    </tbody>
                                    
                                </table>
                                {{-- <table id="example1" class="table table-bordered table-striped useraccounts">
                                    <thead>
                                        <tr>
                                            <th>Tower</th>
                                            <th>Serial</th>
                                            <th>Date</th>
                                            <th>Difference</th>
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
                                                        </a>
                                                    @else
                                                        {{ $expense->description }} (No Document)
                                                    @endif
                                                </td>
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
                                </table> --}}
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

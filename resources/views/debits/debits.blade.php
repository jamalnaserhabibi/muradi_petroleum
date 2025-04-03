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

                <div dir="rtl" class="totalamount searchBar row mb-1 ">
                    <form class="expensefilterform" id="filter-form" action="{{ route('debits') }}"
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


                            <!-- contract Filter -->
                            <div class="mr-4">
                                {{-- <label>Category:</label> --}}
                                <select id="contract-filter" name="contract[]" class="select2 form-control"
                                multiple="multiple" data-placeholder="نوع" style="width:100%">
                                @if (count($contracts) > 0)
                                    <option value="">همه</option>
                                    @foreach ($contracts as $contract)
                                        <option value="{{ $contract->id }}"
                                            {{ in_array($contract->id, request('contract', [])) ? 'selected' : '' }}>
                                            {{ $contract->customer->name }} {{ $contract->customer->company }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>No Data Available</option>
                                @endif
                            </select>
                            </div>
                            <!-- cust Filter -->
                            <div class="mr-4">
                                {{-- <label>Category:</label> --}}
                                <select id="employee-filter" name="distributer[]" class="select2 form-control"
                                multiple="multiple" data-placeholder="کارمند" style="width:100%">
                                @if (count($debits) > 0)
                                    <option value="">تمام کارمندان</option>
                                    @php
                                        // Extract unique distributers
                                        $uniqueDistributers = [];
                                        foreach ($debits as $contract) {
                                            $distributerId = $contract['distributer']['id'];
                                            if (!isset($uniqueDistributers[$distributerId])) {
                                                $uniqueDistributers[$distributerId] = $contract['distributer'];
                                            }
                                        }
                                    @endphp
                                    
                                    @foreach ($uniqueDistributers as $distributer)
                                        <option value="{{ $distributer['id'] }}"
                                            {{ in_array($distributer['id'], request('distributer', [])) ? 'selected' : '' }}>
                                            {{ $distributer['fullname'] }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>No Data Available</option>
                                @endif
                            </select>
                            </div>
                        </div>
                    </form>
                    
                    {{-- <a href="{{ route('expenseaddform') }}" class="btn brannedbtn">+ New</a> --}}
                    <h2>
                        قرض شروع از
                         {{ isset($debits) && isset($debits[0]) ? \App\Helpers\AfghanCalendarHelper::toAfghanDate($debits[0]->date) : 'No Data' }}
 
                     </h2>
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
                                <table id="example1" class="table table-bordered table-striped table-hover "
                                dir="rtl">
                                <thead>
                                    <tr>
                                        <th>مشتری</th>
                                        <th>کارمند</th>
                                        {{-- <th>پایه</th> --}}
                                        <th>نرخ</th>
                                        <th>مقدار</th>
                                        <th>مجموع</th>
                                        <th>تاریخ</th>
                                        <th>توضیعات</th>
                                        <th>.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($debits as $distribution)
                                        <tr>
                                            <td>{{ $distribution->contract->customer->name ?? 'N/A'}}
                                                {{ $distribution->contract->customer->company ?? 'N/A'}}</td>
                                            <td>{{ $distribution->distributer->fullname ?? 'N/A' }}</td>
                                            {{-- <td>{{ $distribution->tower->serial ?? 'N/A'}} -
                                                {{ $distribution->tower->product->product_name ?? 'N/A'}}</td> --}}
                                            <td>{{ $distribution->rate }}</td>
                                            <td>{{ number_format($distribution->amount, 0) }}</td>
                                            <td>{{ number_format($distribution->amount * $distribution->rate, 1) }}
                                            </td>
                                            <td>{{ \App\Helpers\AfghanCalendarHelper::toAfghanDate($distribution->date) }}
                                            </td>
                                            <td>{{ $distribution->details }}</td>
                                            <td>
                                                <form action="{{ route('distribution_delete', $distribution->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-icon btn-danger"
                                                        title="Delete"
                                                        onclick="return confirm('Are you sure you want to delete this?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th id="total-sum"></th> <!-- This will display the sum -->
                                        <th></th>
                                        <th></th>
                                        <th></th>
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
    <script src="plugins/select2/js/select2.full.min.js"></script>

    {{-- commented for the sidebar btn not worked --}}
    {{-- <script src="dist/js/adminlte.min2167.js?v=3.2.0"></script> --}}
    <script>
    $(document).ready(function() {
    // Initialize Select2 with Bootstrap4 theme
    $('.select2').select2({
        theme: 'bootstrap4',
        placeholder: 'Select Products',
        allowClear: true
    });

    $(function() {
        const table = $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api();
                
                // Calculate the total of the 'مجموع' column (index 4)
                var total = api
                    .column(4, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        // Remove any formatting and convert to number
                        return a + parseFloat(b.replace(/,/g, ''));
                    }, 0);

                // Update footer
                $(api.column(4).footer()).html(
                    number_format(total, 1) // Format the number with 1 decimal place
                );
            },
            "buttons": [{
                    extend: 'excel',
                    footer: true,
                    exportOptions: {
                        columns: ':not(:last-child)'
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

// Helper function to format numbers (similar to PHP's number_format)
function number_format(number, decimals) {
    number = parseFloat(number);
    decimals = decimals || 0;
    
    var parts = number.toFixed(decimals).split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    
    return parts.join(".");
}
    </script>

    {{-- <script src="dist/js/demo.js"></script> --}}
@endsection

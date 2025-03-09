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
                    <h2>Distribution</h2>
                    <div class="col-6 d-flex align-items-center justify-content-end">
                        <form class="expensefilterform" id="filter-form" action="{{ route('distribution') }}" method="GET">
                            {{-- <input type="hidden" name="start_date" id="start-date"> --}}
                            {{-- <input type="hidden" name="end_date" id="end-date"> --}}
                            <!-- Date Range Picker and Category Dropdown -->
                            <div class="form-group d-flex">
                                <!-- Date Range Picker -->
                                <div style="max-width: 400px;" id="reservationdate" class="d-flex align-items-center justify-content-between">
                                    {{-- {{ \App\Helpers\AfghanCalendarHelper::toAfghanDate($expenses[0]->date) }} --}}
                                    <input value="{{ isset($afghaniStartDate) ? $afghaniStartDate : '' }}" type="text" name="start_date" id="start_date" class="form-control" placeholder="Start Date" style="max-width: 150px;" required />
                                    <span style="margin: 0 10px; font-weight: bold;">to</span>
                                    <input value="{{ isset($afghaniEndDate) ? $afghaniEndDate : '' }}" type="text" name="end_date" id="end_date" class="form-control" placeholder="End Date" style="max-width: 150px;" required />
                                </div>
                                
    
                                <!-- Category Filter -->
                                <div class="ml-4">
                                    {{-- <label>Category:</label> --}}
                                    {{-- <select id="category-filter" name="category" class="form-control">
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
                                    </select> --}}
                                </div>
                            </div>
                        </form>
                        <!-- Button to trigger the modal -->
                        <button type="button" class="btn brannedbtn" data-toggle="modal" data-target="#assignTowerModal">
                            Add Distribution
                        </button>
                        <a href="{{ route('adddestributionform') }}" class="btn brannedbtn ml-3  fluid-right">Add </a>


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
                                                <th>ID</th>
                                                <th>Contract</th>
                                                <th>Distributer</th>
                                                <th>Tower</th>
                                                <th>Rate</th>
                                                <th>Amount</th>
                                                <th>Total</th>
                                                <th>Date</th>
                                                <th>Description</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($distributions as $distribution)
                                                <tr>
                                                    <td>{{ $distribution->id }}</td>
                                                    <td>{{ $distribution->contract->customer->name ?? 'N/A' }}</td>
                                                    <td>{{ $distribution->distributer->fullname ?? 'N/A' }}</td>
                                                    <td>{{ $distribution->tower->serial ?? 'N/A' }}-{{ $distribution->tower->name ?? 'N/A' }} </td>
                                                    <td>{{ $distribution->rate }}</td>
                                                    <td>{{ number_format($distribution->amount,0) }}</td>
                                                    <td>{{number_format($distribution->amount*$distribution->rate,1)}}</td>
                                                    <td>{{  \App\Helpers\AfghanCalendarHelper::toAfghanDate($distribution->date) }}</td>
                                                    <td>{{ $distribution->description }}</td>
                                                    <td>
                                                        
                                                          <form action="{{ route('distribution_delete', $distribution->id) }}" method="POST"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn pt-0 pb-0 btn-danger"
                                                            title="Delete"
                                                            onclick="return confirm('Are you sure you want to delete this?')">
                                                            <li class="fas fa-trash"></li>
                                                        </button>
                                                    </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                    </table>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
<!-- Add Distribution Modal -->
<div class="modal fade" id="assignTowerModal" tabindex="-1" role="dialog" aria-labelledby="assignTowerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignTowerModalLabel">Add Distribution</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addDistributionForm" action="{{ route('distribution_store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="distributer_id">Distributer</label>
                        <select class="form-control" id="distributer_id" name="distributer_id" required>
                            <option value="">Select Distributer</option>
                            @foreach($distributers as $distributer)
                                <option value="{{ $distributer->id }}">{{ $distributer->fullname }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tower_id">Tower</label>
                        <select class="form-control" id="tower_id" name="tower_id" required>
                            <option value="">Select Tower</option>
                            <!-- Towers will be populated dynamically using JavaScript -->
                        </select>
                    </div>
 
                    <div class="form-group">
                        <label for="contract_id">Contract</label>
                        <select class="form-control" id="contract_id" name="contract_id" required>
                            <option value="">Select Contract</option>
                            <!-- Contracts will be populated dynamically using JavaScript -->
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="rate">Rate</label>
                        <input type="number" class="form-control" id="contractrate" name="rate" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" class="form-control" id="contractrate" name="amount" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('CustomScript')
    <!-- Include your existing scripts -->
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
    // Initialize DataTable
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

    // Fetch towers based on selected distributer
  // Fetch towers based on selected distributer
  $('#distributer_id').on('change', function() {
        const distributerId = $(this).val();
        const towerDropdown = $('#tower_id');

        // Clear existing options
        towerDropdown.empty();
        towerDropdown.append('<option value="">Select Tower</option>');

        if (distributerId) {
            // Fetch towers related to the selected distributer
            $.ajax({
                url: '/get-towers', // Route to fetch towers
                type: 'GET',
                data: {
                    distributer_id: distributerId
                },
                success: function(response) {
                    if (response.length > 0) {
                        response.forEach(tower => {
                            // Include product_name in the dropdown option
                            towerDropdown.append(`<option value="${tower.id}" data-product-id="${tower.product_id}">${tower.serial} - ${tower.name} - ${tower.product.product_name}</option>`);
                        });
                    }
                },
                error: function(xhr) {
                    console.error('Error fetching towers:', xhr.responseText);
                }
            });
        }
    });

    // Fetch contracts based on selected tower
    $('#tower_id').on('change', function() {
        const towerId = $(this).val();
        const productId = $(this).find(':selected').data('product-id'); // Get the product_id from the selected tower
        const contractDropdown = $('#contract_id');

        // Clear existing options
        contractDropdown.empty();
        contractDropdown.append('<option value="">Select Contract</option>');

        if (towerId && productId) {
            // Fetch contracts related to the selected tower's product
            $.ajax({
                url: '/get-contracts', // Route to fetch contracts
                type: 'GET',
                data: {
                    product_id: productId
                },
                success: function(response) {
                    if (response.length > 0) {
                        response.forEach(contract => {
                            contractDropdown.append(`<option value="${contract.id}" data-rate="${contract.rate}">${contract.customer.name} - ${contract.product.product_name}</option>`);
                        });
                    }
                },
                error: function(xhr) {
                    console.error('Error fetching contracts:', xhr.responseText);
                }
            });
        }
    });

    // Fetch rate based on selected contract
    $('#contract_id').on('change', function() {
        const contractId = $(this).val();
        const rateInput = $('#contractrate');

        if (contractId) {
            // Get the rate from the selected contract's data attribute
            const rate = $(this).find(':selected').data('rate');
            rateInput.val(rate); // Update the rate input field
        } else {
            rateInput.val(''); // Clear the rate input field if no contract is selected
        }
    });
});
    </script>
@endsection

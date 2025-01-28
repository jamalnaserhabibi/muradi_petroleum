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
                <div class="row searchBar">
                        <h2 class="nav-icon"> Customers</h2>
                        <div class="row">
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
                            {{-- </ol>  --}}
                        @endif
                        <form id="filter-form" action="{{ route('typefilter') }}" method="GET">
                            <div class="dropdown ml-1">
                                <select id="product-filter" name="type_id[]" class="select2 form-control"
                                multiple="multiple" data-placeholder="Type" style="width:100%">
                                @if ($types->count() > 0)
                                    @foreach ($types as $type)
                                        @if ($type->customerType) <!-- Ensure the related customerType exists -->
                                            <option value="{{ $type->customerType->id }}" 
                                                {{ in_array($type->customerType->id, request('type_id', [])) ? 'selected' : '' }}>
                                                {{ $type->customerType->customer_type }}
                                            </option>
                                        @endif
                                    @endforeach
                                @else
                                    <option value="" disabled>No Data Available</option>
                                @endif
                            </select>

                            </div>
                        </form>        
                   
                        <span>
                            <a href="{{ route('customers') }}" class="btn btn-info ml-3  fluid-right">Active</a>
                            <a href="{{ route('customers0') }}" class="btn btn-danger fluid-right">Inactive</a>
                            <a href="{{ route('customeradd') }}" class="btn brannedbtn fluid-right ml-3">+ New</a>
                        </span>
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
                                {{-- {{$customer}} --}}
                                <table id="example1" class="table table-bordered table-striped useraccounts">
                                    <thead>
                                        <tr>
                                            {{-- <th>ID</th> --}}
                                            <th>Name</th>
                                            <th>Company</th>
                                            {{-- <th>Contact</th> --}}
                                            <th>Type</th>
                                            <th>Rate</th>
                                            <th>Product</th>
                                            {{-- <th>Doc</th> --}}
                                            <th>Sales</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            {{-- <th>AddedBy</th> --}}
                                            <th>Details</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($customer as $customer)
                                            <tr>
                                                {{-- <td>{{ $customer->id }}</td> --}}
                                                <td>{{ $customer->name }}</td>
                                                <td>{{ $customer->company }}</td>
                                                {{-- <td>{{ $customer->contact }}</td> --}}
                                                <td>{{ $customer->customerType->customer_type }}</td>
                                                <td>{{ $customer->contract->rate }}</td>
                                                <td>{{ $customer['contract']['product']->product_name }}</td>
                                                {{-- <td>{{ $customer->document }}</td> --}}
                                                <td>{{ $customer->current_month_sales_total  }}</td>
                                                <td style="white-space: nowrap;"> {{ \App\Helpers\AfghanCalendarHelper::toAfghanDate($customer->date)}}</td>
                                                <td>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input isActiveSwitch" 
                                                               id="switch{{ $customer->contract->id }}" 
                                                               {{ $customer->contract->isActive ? 'checked' : '' }} 
                                                               data-customer-id="{{ $customer->contract->id }}">
                                                        <label  class="custom-control-label" for="switch{{ $customer->contract->id }}">
                                                            {{-- {{ $customer->contract->isActive ? 'Active' : 'Inactive' }} --}}
                                                        </label>
                                                    </div>
                                                </td>
                                                {{-- <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                    <input type="checkbox" class="custom-control-input" id="customSwitch3">
                                                    <label class="custom-control-label" for="customSwitch3">ss</label>
                                                    </div> --}}
                                                    
                                                {{-- <td>{{ $customer->created_by }}</td> --}}
                                                <td>{{ $customer->description  }} | {{$customer->contract->details}} </td>
                                                <td>
                                                    <a href="{{ route('singlecustomerinfo', $customer) }}"
                                                        class="btn pt-0 pb-0 btn-info fas fa-info-circle" title="Info">i
                                                    </a>
                                                    <a href="{{ route('singlecustomersalescustomer', $customer->id) }}"
                                                        class="btn pt-0 pb-0 btn-info fa fa-eye " title="Search">
                                                    </a>
                                                    
                                                    <a href="{{ route('customer.edit', $customer) }}"
                                                        class="btn pt-0 pb-0 btn-warning fa fa-edit" title="Edit">
                                                    </a>

                                                    <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" title="Delete" class="btn pt-0 pb-0 btn-danger"
                                                            onclick="return confirm('Are you sure you want to delete this customer?')">
                                                        <li class="fas fa-trash"></li></button>
                                                    </form>
                                                 </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                    {{-- <tfoot>
                                        <tr>
                                            <th colspan="1">Total</th>
                                            <th  id="totafooter"></th> <!-- Footer for the total amount -->
                                            <th colspan="9"></th>

                                            
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
    <script src="plugins/select2/js/select2.full.min.js"></script>
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
        $(document).ready(function () {
            // Listen for changes on the switch
            $('.isActiveSwitch').on('change', function () {
                var isActive = $(this).prop('checked') ? 1 : 0; // Get the new status (1 or 0)
                var customerId = $(this).data('customer-id'); // Get the customer ID from the data attribute
                // Send AJAX request to update the status
                $.ajax({ 
                url: '/updatecontractstatus', // Route to update status
                method: 'POST',
                data: {
                _token: '{{ csrf_token() }}', // CSRF token for security
                customer_id: customerId,
                is_active: isActive
    },
        success: function (response) {
        if (response.success) {
            // Show success message on UI
            $('#success-alert').text(response.message).show();
            setTimeout(function () {
                $('#success-alert').fadeOut();
            }, 4000); // Hide after 4 seconds

            // Optionally update the switch UI (if needed)
            $('#switch' + customerId).prop('checked', isActive);
        } else {
            // Show error message if update failed
            alert(response.message);
        }
    },
    error: function () {
        alert('Error while updating status');
    }
        });

            });
        });

    
        $('.select2').select2({
                theme: 'bootstrap4',
                placeholder: 'Type',
                allowClear: true
            });

            // Auto-submit on change
            $('#product-filter').on('change', function() {
                $('#filter-form').submit();
        });
    </script>
    {{-- <script>
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
            document.getElementById('category-filter').addEventListener('change', function() {
                document.getElementById('filter-form').submit();
            });
        });
    </script> --}}

    {{-- <script src="dist/js/demo.js"></script> --}}

    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                {
                    extend: 'colvis'
                },
                {
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
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection

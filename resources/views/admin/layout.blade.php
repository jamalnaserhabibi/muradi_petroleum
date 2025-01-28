<!DOCTYPE html>
<html lang="en">

<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Muradi Petroleum</title>
    <base href="{{ asset('admin-lte') }}/" />
    <link rel="stylesheet" href="admincss/useraccounts/styleforall.css">
    <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link
        rel="stylesheet"href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">

    <link rel="stylesheet" href="ionicons/2.0.1/css/ionicons.min.css">

    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">

    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">

    <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">

    <link rel="stylesheet" href="dist/css/adminlte.min2167.css?v=3.2.0">

    <link rel="stylesheet" href="{{ mix('node_modules/persian-datepicker/dist/css/persian-datepicker.min.css') }}">

    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">

    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">

    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">

    @yield('CustomCss')

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        {{-- <div class="preloader">
            <img class="animation__wobble" src="img/logo.png" alt="Muradi Petroleum" height="120">
        </div> --}}

        <nav class="main-header navbar navbar-expand navbar-white navbar-light">

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block usernameinlayout">
                    <a class="nav-link">{{ strtoupper(Auth::user()->name) }}</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a class="nav-link">|</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block usernameinlayout">
                    <a class="nav-link">{{ strtoupper(Auth::user()->usertype) }}</a>

                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                {{-- <li class="nav-item">
                    <a class="nav-link">
                        <i class="far fa-clock"></i>
                        <span id="currentDateTime"></span>
                    </a>
                </li>
                <script>
                    function updateDateTime() {
                        var now = new Date();
                        var formattedDateTime = now.toLocaleString();
                        document.getElementById('currentDateTime').textContent = formattedDateTime;
                    }
                    setInterval(updateDateTime, 1000);
                    updateDateTime();
                </script> --}}

                {{-- <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">15</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">15 Notifications</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 4 new messages
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-users mr-2"></i> 8 friend requests
                            <span class="float-right text-muted text-sm">12 hours</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-file mr-2"></i> 3 new reports
                            <span class="float-right text-muted text-sm">2 days</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>
                </li> --}}

                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link" data-toggle="dropdown">
                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                            class="user-image profileImage img-circle elevation-2" alt="User Image">
                        {{-- <span class="d-none d-md-inline">{{ strtoupper(Auth::user()->name) }} </span> --}}

                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <li class="user-header">
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                                class="img-circle profileImageView elevation-2" alt="User Image">
                            <p>
                                {{ strtoupper(Auth::user()->name) }} <!-- Replace with dynamic user role -->
                                <small>{{ strtoupper(Auth::user()->usertype) }}</small>
                            </p>
                        </li>

                        <div class="userbox">
                            <li class="user-footer">
                                <a href="{{ route('logout') }}" class="fas fa-sign-out-alt"> Sign Out</a>
                            </li>
                            <li class="user-footer">
                                <a href="{{ route('admin.useraccounts') }}" class="fas fa-user"> Users</a>
                            </li>
                        </div>
                    </ul>
                </li>

            </ul>


        </nav>


        <aside class="main-sidebar sidebar-dark-primary elevation-4">

            <a href="{{ route('admin.dashboard') }}"
                class="brand-link d-flex justify-content-center align-items-center flex-column">
                <img src="img/logo.png" alt="AdminLTE Logo" class="brand-image">
                <span class="brand-text font-weight-light">Muradi Petroleum</span>
            </a>

            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview"
                        role="menu" data-accordion="false">
                        <div class="form-inline mb-1">
                            <div class="input-group" data-widget="sidebar-search">
                                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                                    aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-sidebar">
                                        <i class="fas fa-search fa-fw"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                                <i class="nav-icon fas fa-th"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.useraccounts') }}" class="nav-link">
                                <i class="nav-icon fas fa-dollar-sign"></i>
                                <p>
                                    Payments
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('sales') }}" class="nav-link">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>
                                    Sales
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('meter_reading') }}" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Meter Reading
                                </p>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>
                                    Sales
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="pages/charts/chartjs.html" class="nav-link">
                                       <i class="fas fa-gas-pump nav-icon"></i> <!-- Icon for Petrol -->
                                        <p>Petrol</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="pages/charts/flot.html" class="nav-link">
                                        <i class="fas fa-oil-can nav-icon"></i> <!-- Icon for Super Petrol -->
                                        <p>Super Petrol</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="pages/charts/inline.html" class="nav-link">
                                        <i class="fas fa-truck-pickup nav-icon"></i> <!-- Icon for Diesel -->
                                        <p>Diesel</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="pages/charts/uplot.html" class="nav-link">
                                        <i class="fas fa-burn nav-icon"></i> <!-- Icon for Gas -->
                                        <p>Gas</p>
                                    </a>
                                </li>
                            </ul>
                        </li>                    --}}

                        <li class="nav-item">
                            <a href="{{ route('expenses') }}" class="nav-link">
                                <i class="nav-icon fas fa-file-invoice-dollar"></i>
                                <p>
                                    Expenses
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('purchase') }}" class="nav-link">
                                <i class="nav-icon fas fas fa-shopping-cart"></i>
                                <p>
                                    Purchase
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employees') }}" class="nav-link">
                                <i class="nav-icon fas fa-user-tie"></i>
                                <p>
                                    Employees
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('customers') }}" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Customers
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('towers') }}" class="nav-link">
                                <i class="fas fa-gas-pump nav-icon"></i>

                                <p>
                                    Towers
                                </p>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a href="" class="nav-link">
                                <i class="nav-icon fas fa-sticky-note"></i>
                                <p>
                                    Note
                                </p>
                            </a>
                        </li> --}}
                        {{-- <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fas fa-shopping-cart"></i>
                                <p>
                                    Purchase
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="pages/charts/chartjs.html" class="nav-link">
                                        <i class="fas fa-gas-pump nav-icon"></i> <!-- Icon for Petrol -->
                                        <p>Petrol</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="pages/charts/flot.html" class="nav-link">
                                        <i class="fas fa-oil-can nav-icon"></i> <!-- Icon for Super Petrol -->
                                        <p>Super Petrol</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="pages/charts/inline.html" class="nav-link">
                                        <i class="fas fa-truck-pickup nav-icon"></i> <!-- Icon for Diesel -->
                                        <p>Diesel</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="pages/charts/uplot.html" class="nav-link">
                                        <i class="fas fa-burn nav-icon"></i> <!-- Icon for Gas -->
                                        <p>Gas</p>
                                    </a>
                                </li>
                            </ul>
                        </li> --}}
                    </ul>
                </nav>
            </div>

        </aside>
        @yield('content')

        {{-- <footer class="main-footer">
            <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io/">AdminLTE.io</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 3.2.0
            </div>
        </footer> --}}

        <aside class="control-sidebar control-sidebar-dark">

        </aside>

    </div>


    <script src="plugins/jquery/jquery.min.js"></script>

    <script src="plugins/jquery-ui/jquery-ui.min.js"></script>

    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>

    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="plugins/chart.js/Chart.min.js"></script>

    <script src="plugins/sparklines/sparkline.js"></script>

    <script src="plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>

    <script src="plugins/jquery-knob/jquery.knob.min.js"></script>

    <script src="plugins/moment/moment.min.js"></script>
    <script src="plugins/daterangepicker/daterangepicker.js"></script>

    <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

    <script src="plugins/summernote/summernote-bs4.min.js"></script>
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>

    <script src="dist/js/adminlte2167.js?v=3.2.0"></script>
    <script src="plugins/select2/js/select2.full.min.js"></script>

    <script src="dist/js/demo.js"></script>
    <script src="dist/js/pages/dashboard.js"></script>
    <script type="module" src="{{ mix('resources/js/app.js') }}"></script>

    <!-- Persian Datepicker JS -->
    <script src="{{ mix('node_modules/persian-datepicker/dist/js/persian-datepicker.min.js') }}"></script>
    <script src="{{ mix('node_modules/persian-date/dist/persian-date.min.js') }}"></script>

    <script>
        $(function() {
            $(document).ready(function() {
                $('#date').persianDatepicker({
                    format: 'YYYY/MM/DD hh:mm a', // 12-hour format with AM/PM
                    initialValueType: 'persian', // Use Jalali date format
                    initialValue: true,
                    autoClose: true, // Close after selection
                    timePicker: {
                        enabled: true, // Enable time selection
                    },
                    calendar: {
                        persian: {
                            locale: 'en' // Use English for the calendar numbers
                        }
                    },
                    observer: true, // Automatically update the input field
                    altField: '#altField', // Optional: For additional hidden fields
                });
                $('#start_date').persianDatepicker({
                    format: 'YYYY/MM/DD',
                    initialValueType: 'persian',
                    initialValue: false,
                    autoClose: true,
                    calendar: {
                        persian: {
                            locale: 'en', // English numbers
                        },
                    },
                    observer: true,
                    onSelect: function(start) {
                        $('#end_date').persianDatepicker({
                            format: 'YYYY/MM/DD',
                            initialValueType: 'persian',
                            initialValue: false,
                            minDate: start, // Restrict end date to be greater or equal to start date
                            autoClose: true,
                            calendar: {
                                persian: {
                                    locale: 'en',
                                },
                            },
                            observer: true,
                            onSelect: function(end) {
                                if (start && end) {
                                    $('#filter-form').submit();
                                }
                            },
                        });
                    },
                });

                $('#end_date').persianDatepicker({
                    initialValue: false,
                    format: 'YYYY/MM/DD',
                    initialValueType: 'persian',
                    autoClose: true,
                    calendar: {
                        persian: {
                            locale: 'en',
                        },
                    },
                    observer: true,
                    onSelect: function(end) {
                        const start = $('#start_date').val();
                        if (start && end) {
                            $('#filter-form').submit();
                        }
                    },
                });
                $('#filter-form').on('change', function() {
                    $(this).submit();
                });

            });
        });

        $(document).ready(function() {
            $('[data-widget="fullscreen"]').on('click', function() {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen();
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    }
                }
            });
            // Initialize select2 on the dropdown
            // $('#contract').select2();
            // $('#tower').prop('disabled', true);
            // // Event listener for contract selection change
            // $('#contract').on('change', function() {
            //     // Get the selected option and its data-rate attribute
            //     const selectedOption = this.options[this.selectedIndex];
            //     const rate = selectedOption.getAttribute('data-rate');

            //     // Update the value of the rate input
            //     const rateInput = $('#rate'); // Use jQuery to select the input
            //     rateInput.val(rate ||
            //         ''); // Set the value to the rate or an empty string if no rate is found

            //     // If rate is greater than 1, disable the input field
            //     if (rate && parseFloat(rate) > 1) {
            //         rateInput.prop('readonly', true);
            //     } else {
            //         rateInput.prop('readonly', false);
            //     }


            //     var selectedProduct = $(this).find('option:selected').data('product');
            //     if (selectedProduct) {
            //         $('#amount').prop('disabled', false);
            //         $('#rate').prop('disabled', false);
            //         $('#date').prop('disabled', false);
            //         $('#tower').prop('disabled', false);
            //     } else {
            //         $('#amount').prop('disabled', true);
            //         $('#rate').prop('disabled', true);
            //         $('#date').prop('disabled', true);
            //         $('#tower').prop('disabled', true);
            //     }

            //     // Filter tower options based on the selected product
            //     $('#tower option').each(function() {
            //         var towerProduct = $(this).data('product');

            //         // Show only the towers that match the selected product
            //         if (towerProduct === selectedProduct || selectedProduct === undefined) {
            //             $(this).show();
            //         } else {
            //             $(this).hide();
            //         }
            //     });
            // });
            $('#tower').select2();
            $('#contract').prop('disabled', true); // Initially disable the customer select
            $('#amount, #rate').prop('disabled', true); // Disable other fields initially

            // Event listener for tower selection change
            $('#tower').on('change', function() {
                // Get the selected tower and its product
                const selectedTowerOption = $(this).find('option:selected');
                const selectedProduct = selectedTowerOption.data('product');

                if (selectedProduct) {
                    // Enable the customer select when a valid tower is selected
                    $('#contract').prop('disabled', false);

                    // Filter customer options based on the selected product
                    $('#contract option').each(function() {
                        const contractProduct = $(this).data('product');
                        if (contractProduct === selectedProduct) {
                            $(this).show(); // Show matching customers
                        } else {
                            $(this).hide(); // Hide non-matching customers
                        }
                    });

                    // Reset the customer select when the tower changes
                    $('#contract').val(null).trigger('change');
                } else {
                    // Disable and reset the customer select if no tower is selected
                    $('#contract').prop('disabled', true).val(null).trigger('change');
                }

                // Disable other fields
                $('#amount, #rate, #date').prop('disabled', true);
            });

            // Event listener for customer selection change
            $('#contract').on('change', function() {
                // Get the selected customer option and its data attributes
                const selectedCustomerOption = $(this).find('option:selected');
                const rate = selectedCustomerOption.data('rate');

                if (rate !== undefined) {
                    // Enable other fields
                    $('#amount, #date').prop('disabled', false);

                    // Enable or disable the rate field based on the rate value
                    if (parseFloat(rate) === 0) {
                        $('#rate').prop('disabled', false).val(''); // Allow editing if rate is 0
                    } else {
                        $('#rate').prop('disabled', false).val(
                        rate); // Set the rate value and make it read-only
                        $('#rate').prop('readonly', true); // Set the rate value and make it read-only
                    }
                }
            });
        });
    </script>
    @yield('CustomScript')
</body>

</html>

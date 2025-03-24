@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <!-- Distributer Card -->
                    <div class="card mt-3">
                        <div class="card-header brannedbtn d-flex justify-content-between">
                            <div class="card-tools mr-auto">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>  
                                </button>
                            </div>
                            <h3 class="card-title">پایه های مربوط</h3>

                        </div>
                        <div class="card-body">
                            <div class="form-group p-1 mb-0">
                                <select class="form-control" id="distributer_id" name="distributer_id" required>
                                    <option value="">Select Distributer</option>
                                    @foreach($distributers as $distributer)
                                        <option value="{{ $distributer->id }}">{{ $distributer->fullname }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="towers-list" class="pl-1">
                                <!-- Towers will be listed here dynamically -->
                            </div>
                        </div>
                    </div>

                    <!-- Add Distribution Card -->
                    <div class="card mt-1">
                        <div class="card-header brannedbtn d-flex justify-content-between">
                            <div class="card-tools mr-auto">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>  
                                </button>
                            </div>
                            <h3 class="card-title">ثبت توزیع</h3>

                        </div>
                        <div class="card-body">
                            <div id="distribution-add" class="pl-2">
                                <!-- The Add Distribution form will be loaded here dynamically -->
                            </div>
                        </div>
                    </div>
                    <!-- Today's Distribution Card -->
                    <div class="card mt-1">
                        <div class="card-header brannedbtn d-flex justify-content-between">
                            <div class="card-tools mr-auto">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>  
                                </button>
                            </div>
                            <h3 class="card-title">توزیع امروز</h3>
                        </div>
                        <div class="card-body">
                            <div id="distribution-list" >
                                <!-- Today's distribution records will be listed here dynamically -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script src="plugins/jquery/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#distributer_id').change(function() {
                var distributerId = $(this).val();
                if (distributerId) {
                    // Fetch towers related to the distributer
                    $.ajax({
                        url: '/get-towers',
                        type: 'GET',
                        data: { distributer_id: distributerId },
                        success: function(response) {
                            $('#towers-list').html(response);
                        }
                    });

                    // Fetch today's distribution records for the selected distributer
                    $.ajax({
                        url: '/get-todays-distributions',
                        type: 'GET',
                        data: { distributer_id: distributerId },
                        success: function(response) {
                            $('#distribution-list').html(response);
                        }
                    });

                    // Fetch the Add Distribution form
                    $.ajax({
                        url: '/get-add-distribution-form',
                        type: 'GET',
                        data: { distributer_id: distributerId },
                        success: function(response) {
                            $('#distribution-add').html(response);
                        }
                    });
                } else {
                    $('#towers-list').html('');
                    $('#distribution-list').html('');
                    $('#distribution-add').html('');
                }
            });

            // Handle form submission
            $(document).on('submit', '#add-distribution-form', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                var distributerId = $('#distributer_id').val();

                $.ajax({
                    url: '/add-distribution',
                    type: 'POST',
                    data: formData + '&distributer_id=' + distributerId,
                    success: function(response) {
                        alert('Distribution added successfully!');
                        $('#add-distribution-form')[0].reset();
                        // Refresh the today's distribution list
                        $.ajax({
                            url: '/get-todays-distributions',
                            type: 'GET',
                            data: { distributer_id: distributerId },
                            success: function(response) {
                                $('#distribution-list').html(response);
                            }
                        });
                        $.ajax({
                        url: '/get-towers',
                        type: 'GET',
                        data: { distributer_id: distributerId },
                        success: function(response) {
                            $('#towers-list').html(response);
                        }
                    });
                        
                    },
                    error: function(response) {
                        alert('Error adding distribution. Please try again.');
                    }
                });
            });
        });
    </script>
@endsection
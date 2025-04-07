@extends('admin.layout')
@section('CustomCss')
    <link rel="stylesheet" href="admincss/useraccounts/styleforall.css">
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <style>
        /* Custom styling for reminders */
        .reminder-list {
            list-style: none;
            padding: 0;
        }
        .reminder-item {
            /* background: #ffffff; */
            /* border: 1px solid #e0e0e0; */
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }
        .reminder-item:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        .reminder-content {
            flex: 1;
            margin-right: 15px;
        }
        .reminder-content h5 {
            margin: 0;
            font-size: 16px;
            color: #333;
        }
        .reminder-content p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #666;
        }
        .reminder-actions {
            display: flex;
            gap: 10px;
        }
        .reminder-actions .btn {
            padding: 5px 10px;
            font-size: 14px;
        }
        .status {
            font-weight: bold;
            color: #1976d2; /* Blue for status */
        }
        .add-reminder-btn {
            margin-bottom: 20px;
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-6">
                        <h1>Reminders</h1>
                    </div>
                    <div class="col-6 d-flex align-items-center justify-content-end">
                        @if (session('success'))
                            <div class="alert alert-success" id="success-alert">
                                {{ session('success') }}
                            </div>
                            <script>
                                setTimeout(function() {
                                    document.getElementById('success-alert').style.display = 'none';
                                }, 4000); // Hide after 4 seconds
                            </script>
                        @endif
                        @if(Auth::user()->usertype !== 'guest')
                       <button type="button" class="btn btn-primary add-reminder-btn" data-toggle="modal" data-target="#addReminderModal">
                            <i class="fas fa-plus"></i> Add Reminder
                        </button>
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
                                <ul class="reminder-list">
                                    @foreach ($reminder as $remind)
                                        <li class="reminder-item  {{\Carbon\Carbon::parse($remind->reminder_date)->isToday()? 'reminderBadgeBorderGreen': ''}} {{\Carbon\Carbon::parse($remind->reminder_date)->isPast() && !\Carbon\Carbon::parse($remind->reminder_date)->isToday()? 'reminderBadgeBorderRed': ''}} {{\Carbon\Carbon::parse($remind->reminder_date)->isFuture()? 'reminderBadgeBorderUpcomming': ''}}">
                                            <div class="reminder-content">
                                                @if(\Carbon\Carbon::parse($remind->reminder_date)->isPast() && !\Carbon\Carbon::parse($remind->reminder_date)->isToday())
                                                <span class="reminderBadge">Overdue</span>
                                            @elseif(\Carbon\Carbon::parse($remind->reminder_date)->isToday())
                                                <span class="reminderBadge reminderBadgeRed">Today</span>
                                            @elseif(\Carbon\Carbon::parse($remind->reminder_date)->isFuture())
                                                <span class="reminderBadge reminderBadgeYellow">Upcoming</span>
                                            @endif
                                                <h5>{{ $remind->note }}</h5>
                                                <p><strong>Remind Date:</strong> 
                                                    <span>
                                                        {{ \App\Helpers\AfghanCalendarHelper::toAfghanDate($remind->reminder_date) }}
                                                    </span>
                                                </p>
                                                <p><strong>Added Date:</strong> {{ \App\Helpers\AfghanCalendarHelper::toAfghanDate($remind->date_added) }}</p>
                                                {{-- <p class="status"><strong>Status:</strong> {{ $remind->status }}</p> --}}
                                            </div>
                                            <div class="reminder-actions">  
                                                @if(Auth::user()->usertype !== 'guest')
                                                
                                                <button type="button" class="btn btn-warning btn-sm edit-reminder-btn" data-toggle="modal" data-target="#editReminderModal" data-id="{{ $remind->id }}" data-note="{{ $remind->note }}" data-reminder-date="{{  $remind->reminder_date   }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('reminder.destroy', $remind->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this reminder?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif

                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Add Reminder Modal -->
    <div class="modal fade" id="addReminderModal" tabindex="-1" role="dialog" aria-labelledby="addReminderModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    
                    <h5 class="modal-title" id="addReminderModalLabel">Add Reminder</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="add-reminder-form" action="{{ route('reminder.create') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="note">Note</label>
                            <textarea name="note" id="note" class="form-control" placeholder="Enter your note" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="remind_date">Remind Date</label>
                            <input type="text" name="remind_date" id="start_date" class="form-control" placeholder="Remind Date" required />
                        </div>
                        <input type="hidden" name="created_by" value="{{ Auth::user()->name }}" />
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Reminder Modal -->
    <div class="modal fade" id="editReminderModal" tabindex="-1" role="dialog" aria-labelledby="editReminderModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editReminderModalLabel">Edit Reminder</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="edit-reminder-form" action="{{ route('reminder.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="edit-reminder-id">
                        <div class="form-group">
                            <label for="edit-note">Note</label>
                            <textarea name="note" id="edit-note" class="form-control" placeholder="Enter your note" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="remind_date">Remind Date</label>
                            <input type="text" name="remind_date" id="start_date" class="form-control" placeholder="Remind Date" required />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
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
            // Initialize DataTable
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [{
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

            // Handle Edit Button Click
            $('.edit-reminder-btn').on('click', function() {
                var id = $(this).data('id');
                var note = $(this).data('note');
                var remindDate = $(this).data('reminder-date');

                $('#edit-reminder-id').val(id);
                $('#edit-note').val(note);
                $('#edit-remind-date').val(remindDate);
            });
        });
    </script>
@endsection
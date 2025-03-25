@extends('admin.layout')
@section('CustomCss')
    <link rel="stylesheet" href="admincss/useraccounts/styleforall.css">
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <style>
        .dataCart{
            justify-content: center
        }
     .tower-card {
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border-top: 3px solid #f30081;
    }
    .tower-card .card-header {
        background-color: #f300820d;
        border-bottom: 1px solid rgba(0,0,0,0.08);
    }
    .tower-card .card-title {
        font-weight: 600;
        color: #f30081;
        margin-bottom: 0;
    }
    .reading-item {
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }
    .reading-item:hover {
        background-color: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .reading-label {
        font-size: 0.85rem;
        color: #6c757d;
    }
    .reading-value {
        font-weight: 500;
    }
    .difference-value {
        color: #e74c3c;
        font-weight: 600;
    }
    .today-badge {
        background-color: #2ecc71;
        color: white;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.75rem;
        margin-left: 5px;
    }
    .action-btns {
        display: flex;
        gap: 5px;
    }
    </style>
@endsection
@section('content')
    <div class="content-wrapper">
       
        <section class="content ">
            <div class="container-fluid">
                <div class="row ">
                    <div class="col-md-12">
                        <div class="card mt-3">
                            @if (session('success'))
                                <div class="alert alert-success" id="success-alert">
                                    {{ session('success') }}
                                </div>
                                <script>
                                    setTimeout(function() {
                                        document.getElementById('success-alert').style.display = 'none';
                                    }, 4000); // 2000ms = 2 seconds
                                </script>
                            @endif
                            <form 
                                action="{{ isset($serialNumber) ? route('serial_numbers_update', $serialNumber->id) : route('serial_numbers_store') }}"
                                method="POST">
                                <div class="card-header brannedbtn">
                                    <h3 class="card-title ">میتر پایه ها</h3>
                                </div>
                                @csrf
                                @if (isset($serialNumber))
                                    @method('PATCH')
                                @endif
                                <div class="card-body formserial " dir="rtl">
                                    @error('tower_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <select class="form-control mb-3" name="tower_id" id="tower_id" required>
                                        <option value="" disabled {{ isset($serialNumber) ? '' : 'selected' }}>انتخاب پایه</option>
                                        @foreach ($towers as $tower)
                                            @if ($tower->product->id != 13 && $tower->product->id != 14)
                                                <option value="{{ $tower->id }}"
                                                    {{ old('id', $tower->id ?? '') == ($serialNumber->tower_id ?? '') ? 'selected' : '' }}>
                                                    {{ $tower->serial }} -
                                                    {{ $tower->product->product_name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>

                                    @error('serial')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <input class="form-control form-control mb-2 mr-3" name="serial" type="number"
                                        step="1" id="serial" placeholder="نمبر میتر"
                                        value="{{ old('serial', $serialNumber->serial ?? request('serial')) }}" required>

                                    @error('date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                    <div dir="ltr" class="input-group date mr-3" id="reservationdate">
                                        <input type="text" name="date" id="date" class="form-control"
                                            value={{ $afghancurrentdate }} required />
                                        <div class="input-group-append h-10">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>



                                    <div class="ml-3">
                                        <button type="submit" class="btn brannedbtn">
                                            {{ isset($serialNumber) ? 'Update' : 'ثبت' }}
                                        </button>
                                    </div>
                                    <a href="{{ route('meter_reading_table') }}" class="btn btn-success ml-3 h-10">
                                          <span>جدول</span>
                                    </a>
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
            
                @if(count($result) > 0)
                    @php
                        $currentTower = null;
                        $groupedReadings = [];
                        
                        // Group readings by tower
                        foreach($result as $row) {
                            $towerKey = $row['tower_serial'] . '-' . $row['product_name'];
                            $groupedReadings[$towerKey]['info'] = [
                                'tower_serial' => $row['tower_serial'],
                                'product_name' => $row['product_name'],
                                'tower_id' => $row['tower_id']
                            ];
                            $groupedReadings[$towerKey]['readings'][] = $row;
                        }
                    @endphp
                    
                    @foreach($groupedReadings as $towerKey => $towerData)
                        @php
                            $today = \Carbon\Carbon::now()->toDateString();
                        @endphp
                        
                        <div class="col-md-12 mb-4" >
                            <div class="card tower-card" >
                                <div class="card-header" >
                                    <h3 class="card-title">
                                        <i class="fas fa-gas-pump mr-2"></i>
                                        <a href="{{ route('singletowereadings', ['tower_id' => $towerData['info']['tower_id']]) }}" style="color: inherit;">
                                            {{ $towerData['info']['tower_serial'] }} - {{ $towerData['info']['product_name'] }}
                                        </a>
                                    </h3>
                                </div>
                                <div class="card-body" >
                                    <div class="row dataCart">
                                        @foreach($towerData['readings'] as $row)
                                            @php
                                                $date = \Carbon\Carbon::parse($row['date'])->toDateString();
                                            @endphp
                                            <div class="col-md-6 mb-3">
                                                <div class="reading-item p-3 border rounded">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <div class="reading-label">Date</div>
                                                            <div class="reading-value">
                                                                {{ \App\Helpers\AfghanCalendarHelper::toAfghanDate($row['date']) }}
                                                                @if ($date === $today)
                                                                    <span class="today-badge">Today</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="action-btns">
                                                            <a href="{{ route('singletowereadings', ['tower_id' => $row['tower_id']]) }}"
                                                                class="btn btn-sm btn-success" title="View Details">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <form action="{{ route('deleteserialnumber', $row['id']) }}"
                                                                method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger"
                                                                    title="Delete"
                                                                    onclick="return confirm('Are you sure you want to delete this reading?')">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row mt-2">
                                                        <div class="col-6">
                                                            <div class="reading-label">Meter Reading</div>
                                                            <div class="reading-value">{{ $row['current_reading'] }}</div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="reading-label">Difference</div>
                                                            <div class="reading-value difference-value">
                                                                {{ $row['sold_petrol'] != 0 ? $row['sold_petrol'] : 'N/A' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="card">
                        <div class="card-body no-readings">
                            <i class="fas fa-info-circle fa-2x mb-3" style="color: #bdc3c7;"></i>
                            <p>No meter readings found. Add your first reading using the form above.</p>
                        </div>
                    </div>
                @endif
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
    <script>
        $(document).ready(function() {
            // Initialize date picker
            $('#reservationdate').datetimepicker({
                format: 'YYYY-MM-DD',
                locale: 'en'
            });
            
            // Initialize select2 for better select boxes
            $('.select2').select2({
                theme: 'bootstrap4'
            });
        });
    </script>
@endsection
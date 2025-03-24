<link rel="stylesheet" href="admincss/useraccounts/styleforall.css">

<tbody>
    @if($towersWithMeterReading->isEmpty())
        <tr>
            <td class="text-muted p-1">No towers found for this distributer.</td>
        </tr>
    @else
        @foreach($towersWithMeterReading as $tower)
            <tr>
                <td></td>
                <td>
                    <div class="d-flex flex-wrap">
                        @if ($tower->product_id != 13 && $tower->product_id != 14 && $tower->product_id != 15)
                        @if($tower->meter_reading->isNotEmpty())
                        
                            @foreach($tower->meter_reading as $reading)
                                <div class="d-flex align-items-center m-2" style="border-left: 2px solid rgba(128, 128, 128, 0.453); border-radius:5px">
                                    <!-- Combined Meter Reading Information -->
                                    <div class="flex-wrap tower-info-delete d-flex align-items-center p-2 rounded">
                                        <!-- Tower Information -->
                                        <span class="btn btn-info mr-2 mb-2">
                                            <i class="fas fa-gas-pump"></i> {{ $tower->serial }} -
                                            
                                             {{ $reading->product_name }}
                                        </span>
                                        <!-- Date Information -->
                                        <span class="btn btn-info mr-2 mb-2" dir="rtl">
                                            {{-- <i class="fas fa-calendar-day"></i>  --}}
                                            @if(\Carbon\Carbon::parse($reading->date)->isToday())
                                              <span style="border:1px solid white;  border-radius:3px">  {{\App\Helpers\AfghanCalendarHelper::toAfghanDate($reading->second_date ?? ''); }}  </span>  -
                                            امروز
                                            @else
                                             <span style="border:1px solid white;  border-radius:3px">  {{\App\Helpers\AfghanCalendarHelper::toAfghanDate($reading->second_date ?? ''); }}  </span>  -
                                             <span style="border:1px solid white;  border-radius:3px">  {{\App\Helpers\AfghanCalendarHelper::toAfghanDate($reading->date ?? ''); }}  </span>  
                                            @endif
                                        </span>
                                        <!-- Serial Number Information -->
                                        <span class="btn btn-info mr-2 mb-2">
                                            <i class="fas fa-sort-numeric-up-alt"></i> {{ $reading->serial_number }}
                                        </span>
                                        <!-- Petrol Sold Information -->
                                        <span class="btn btn-info mr-2 mb-2">
                                            <i class="fas fas fa-tint"></i> {{ $reading->petrol_sold }} L
                                        </span>
                                        <!-- Total Amount Information (Today's Date) -->
                                        <span class="btn btn-success mr-2 mb-2">
                                            <i class="fas fa-check-circle"></i> ثبت امروز: {{ $tower->total_amount }} لیتر
                                        </span>
                                        <span class="btn btn-danger mr-2 mb-2">
                                            <i class="fas fa-exclamation-circle"></i> باقی مانده: {{ $reading->petrol_sold- $tower->total_amount }} لیتر
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="d-flex align-items-center mb-2 me-2">
                                <div class="tower-info-delete d-flex align-items-center p-2 rounded">
                                    <span class="btn btn-warning">
                                        <i class="fas fa-gas-pump"></i> {{ $tower->serial }} -  {{ $tower->product->product_name }} No meter reading data available.
                                    </span>
                                    <!-- Total Amount Information (Today's Date) -->
                                    <span class="btn btn-success mr-2">
                                        <i class="fas fa-coins"></i> مقدار فروش امروز: {{ $tower->total_amount }}
                                    </span>
                                </div>
                            </div>
                        @endif
                        @endif
                    
                    </div>
                </td>
            </tr>
        @endforeach
    @endif
</tbody>
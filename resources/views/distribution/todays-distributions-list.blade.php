 @if($distributions->isEmpty())
    <div class="alert alert-info text-center">
        <i class="fas fa-info-circle"></i> No distribution records found for today.
    </div>
@else
    @php
      
        $groupedDistributions = $distributions->groupBy(function($distribution) {
            return $distribution->tower->product->product_name;
        });

        $grandTotalAmount = 0;
        $grandTotalGrandTotal = 0;
    @endphp

    <div class="row">
        @foreach($groupedDistributions as $productName => $distributions)
            @php
                $totalAmount = 0;
                $grandTotal = 0;
                $moneyTotalAmount = 0;
                $moneyGrandTotal = 0;
            @endphp

            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-box"></i> {{ $distributions->first()->tower->serial }} - {{ $productName }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @foreach($distributions as $distribution)
                                @php
                                    $amount = $distribution->amount;
                                    $total = $distribution->amount * $distribution->rate;

                                    if ($distribution->tower->name == 'money') {
                                        $moneyTotalAmount += $amount;
                                        $moneyGrandTotal += $total;
                                    } else {
                                        $totalAmount += $amount;
                                        $grandTotal += $total;
                                    }
                                @endphp
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">ID: {{ $distribution->id }}</h6> 
                                            <small class="text-muted">Contract: {{ $distribution->contract->customer->name }} {{ $distribution->contract->customer->company }}</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-secondary">Rate: {{ $distribution->rate }}</span>
                                            <span class="badge bg-info">Amount: {{ number_format($amount, 0) }}</span>
                                            <span class="badge bg-success">Total: {{ number_format($total, 1) }}</span>
                                        </div>
                                    </div>
                                    <small class="text-muted">Date: {{ \App\Helpers\AfghanCalendarHelper::toAfghanDate($distribution->date) }}</small> <br>
                                    <small class="text-muted">Details: {{  $distribution->details }}</small>
                                    <div class="mt-2">
                                        <form action="{{ route('distribution_delete', $distribution->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between">
                            <strong>Total Amount:</strong>
                            <span>{{ number_format($totalAmount, 0) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <strong>Grand Total:</strong>
                            <span>{{ number_format($grandTotal, 1) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @php
               
                $grandTotalAmount += ($productName === 'money_out') ? -$totalAmount : $totalAmount;
                $grandTotalGrandTotal += ($productName === 'money_out') ? -$grandTotal : $grandTotal;
            @endphp
        @endforeach
    </div>

    <div class="card mt-4 shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-chart-bar"></i> Grand Totals
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="alert alert-primary">
                        <strong>Total Amount:</strong> {{ number_format($grandTotalAmount, 0) }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-primary">
                        <strong>Grand Total:</strong> {{ number_format($grandTotalGrandTotal, 1) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif 





{{-- @if($distributions->isEmpty())
    <p class="text-muted p-2">No distribution records found for today.</p>
@else
    @php
        // Group distributions by product name
        $groupedDistributions = $distributions->groupBy(function($distribution) {
            return $distribution->tower->product->product_name;
        });

        $grandTotalAmount = 0;
        $grandTotalGrandTotal = 0;
    @endphp

    @foreach($groupedDistributions as $productName => $distributions)
        @php
            $totalAmount = 0;
            $grandTotal = 0;
            $moneyTotalAmount = 0;
            $moneyGrandTotal = 0;
        @endphp

        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">{{ $distributions->first()->tower->serial }} - {{ $productName }}</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Contract</th>
                                <th scope="col">Rate</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Total</th>
                                <th scope="col">Date</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($distributions as $distribution)
                                @php
                                    $amount = $distribution->amount;
                                    $total = $distribution->amount * $distribution->rate;

                                    if ($distribution->tower->name == 'money') {
                                        $moneyTotalAmount += $amount;
                                        $moneyGrandTotal += $total;
                                    } else {
                                        $totalAmount += $amount;
                                        $grandTotal += $total;
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $distribution->id }}</td>
                                    <td>{{ $distribution->contract->customer->name }} {{ $distribution->contract->customer->company }}</td>
                                    <td>{{ $distribution->rate }}</td>
                                    <td>{{ number_format($amount, 0) }}</td>
                                    <td>{{ number_format($total, 1) }}</td>
                                    <td>{{ \App\Helpers\AfghanCalendarHelper::toAfghanDate($distribution->date) }}</td>
                                    <td>
                                        <form action="{{ route('distribution_delete', $distribution->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <th colspan="4" class="text-right">Total:</th>
                                <th>{{ number_format($totalAmount, 0) }}</th>
                                <th>{{ number_format($grandTotal, 1) }}</th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        @php
            // Adjust grand totals based on product name
            $grandTotalAmount += ($productName === 'money_out') ? -$totalAmount : $totalAmount;
            $grandTotalGrandTotal += ($productName === 'money_out') ? -$grandTotal : $grandTotal;
        @endphp
    @endforeach

    <div class="card mt-4 shadow-sm">
        <div class="card-header bg-success text-white">
            <h3 class="card-title mb-0">Grand Total</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Total Amount</th>
                            <th scope="col">Grand Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ number_format($grandTotalAmount, 0) }}</td>
                            <td>{{ number_format($grandTotalGrandTotal, 1) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif --}}
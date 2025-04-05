@if($distributions->isEmpty())
    <div class="alert alert-info text-center">
        <i class="fas fa-info-circle"></i> No distribution records found for today.
    </div>
@else
    @php
        $groupedDistributions = $distributions->groupBy(function($distribution) {
            return $distribution->tower->product->product_name;
        });

        // Initialize totals
        $totalInAmount = 0;
        $totalInValue = 0;
        $totalOutAmount = 0;
        $totalOutValue = 0;
    @endphp

    <div class="row">
        @foreach($groupedDistributions as $productName => $distributions)
            @php
                $productTotalAmount = 0;
                $productTotalValue = 0;
                $isOutProduct = in_array($distributions->first()->tower->product_id, [13, 15]);
            @endphp

            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-box"></i> {{ $distributions->first()->tower->serial }} - {{ $productName }}
                        </h5>
                        <div class="product-totals">
                            <span class="badge bg-info me-1">Amount: {{ number_format($distributions->sum('amount'), 0) }}</span>
                            <span class="badge bg-success">Total: {{ number_format($distributions->sum(function($item) { 
                                return $item->amount * $item->rate; 
                            }), 1) }}</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($distributions as $distribution)
                                @php
                                    $amount = $distribution->amount;
                                    $value = $distribution->amount * $distribution->rate;
                                    $productTotalAmount += $amount;
                                    $productTotalValue += $value;
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
                                            <span class="badge bg-success">Total: {{ number_format($value, 1) }}</span>
                                        </div>
                                    </div>
                                    <small class="text-muted">Date: {{ \App\Helpers\AfghanCalendarHelper::toAfghanDate($distribution->date) }}</small> <br>
                                    <small class="text-muted">Details: {{ $distribution->details }}</small>
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
                </div>
            </div>

            @php
                // Accumulate to the appropriate totals based on product type
                if ($isOutProduct) {
                    $totalOutAmount += $productTotalAmount;
                    $totalOutValue += $productTotalValue;
                } else {
                    $totalInAmount += $productTotalAmount;
                    $totalInValue += $productTotalValue;
                }
            @endphp
        @endforeach
    </div>

    {{-- Compact Grand Totals --}}
    <div class="row mt-3">
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white py-2">
                    <h6 class="card-title mb-0 text-center">Total In</h6>
                </div>
                <div class="card-body py-2 text-center">
                    <h4 class="mb-0">{{ number_format($totalInValue, 1) }}</h4>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-danger text-white py-2">
                    <h6 class="card-title mb-0 text-center">Total Out</h6>
                </div>
                <div class="card-body py-2 text-center">
                    <h4 class="mb-0">{{ number_format($totalOutValue, 1) }}</h4>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white py-2">
                    <h6 class="card-title mb-0 text-center">Cash Balance</h6>
                </div>
                <div class="card-body py-2 text-center">
                    <h4 class="mb-0">{{ number_format($totalInValue - $totalOutValue, 1) }}</h4>
                </div>
            </div>
        </div>
    </div>
@endif
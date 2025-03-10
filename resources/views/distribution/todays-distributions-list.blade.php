@if($distributions->isEmpty())
    <p class="text-muted p-2">No distribution records found for today.</p>
@else

<table id="example1" class="table table-bordered table-striped responsive useraccounts">
    <thead>
        <tr>
            <th>ID</th>
            <th>Contract</th>
            <th>Tower</th>
            <th>Rate</th>
            <th>Amount</th>
            <th>Total</th>
            <th>Date</th>
            {{-- <th>Description</th> --}}
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalAmount = 0;
            $grandTotal = 0;
            $moneyTotalAmount = 0;
            $moneyGrandTotal = 0;
        @endphp
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
                <td>{{ $distribution->contract->customer->name ?? 'N/A' }}</td>
                <td>{{ $distribution->tower->serial ?? 'N/A' }}-{{ $distribution->tower->name ?? 'N/A' }} </td>
                <td>{{ $distribution->rate }}</td>
                <td>{{ number_format($amount, 0) }}</td>
                <td>{{ number_format($total, 1) }}</td>
                <td>{{ \App\Helpers\AfghanCalendarHelper::toAfghanDate($distribution->date) }}</td>
                {{-- <td>{{ $distribution->description }}</td> --}}
                <td>
                    <form action="{{ route('distribution_delete', $distribution->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn pt-0 pb-0 btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this?')">
                            <li class="fas fa-trash"></li>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4" class=" ">Total Fuel:</th>
            <th>{{ number_format($totalAmount, 0) }}</th>
            <th>{{ number_format($grandTotal, 1) }}</th>
            <th colspan="2"></th>
        </tr>
        <tr>
            <th colspan="4" class=" ">Total Money:</th>
            <th> </th>
            <th>{{ number_format($moneyGrandTotal, 1) }}</th>
            <th colspan="2"></th>
        </tr>
        <tr>
            <th colspan="4" class=" ">Cash:</th>
            <th> </th>
            <th>{{ number_format($grandTotal - $moneyGrandTotal, 1) }}</th>
            <th colspan="2"></th>
        </tr>
    </tfoot>
</table>

@endif
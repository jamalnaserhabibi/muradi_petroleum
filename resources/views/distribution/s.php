@foreach ($distributions as $distribution)
    <tr>
        <td>{{ $distribution->contract->customer->name }} {{ $distribution->contract->customer->company }}</td>
        <td>{{ $distribution->distributer->fullname ?? 'N/A' }}</td>
        <td>{{ $distribution->tower->serial }} - {{ $distribution->tower->product->product_name }}</td>
        <td>{{ $distribution->rate }}</td>
        <td>{{ number_format($distribution->amount, 0) }}</td>
        <td>{{ number_format($distribution->amount * $distribution->rate, 1) }}</td>
        <td>{{ \App\Helpers\AfghanCalendarHelper::toAfghanDate($distribution->date) }}</td>
        <td>{{ $distribution->description }}</td>
        <td>
            <form action="{{ route('distribution_delete', $distribution->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </td>
    </tr>
@endforeach
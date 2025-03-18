<!-- resources/views/distribution/add-distribution-form.blade.php -->
<form id="add-distribution-form">
    @csrf
    <div class="d-flex flex-wrap">
        <div class="form-group mr-2">
            <select class="form-control" id="tower_id" name="tower_id" required>
                <option value="">Select Item</option>
                @foreach($towers as $tower)
                    <option value="{{ $tower->id }}" data-product-id="{{ $tower->product_id }}">
                        {{ $tower->serial }} 
                     {{-- - {{ $tower->name }}  --}}
                        - {{ $tower->product->product_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group mr-2">
            <select class="form-control select2" id="contract_id" name="contract_id" required>
                <option value="">Select Contract</option>
                @foreach($contracts as $contract)
                    <option value="{{ $contract->id }}" data-rate="{{ $contract->rate }}" data-product-id="{{ $contract->product_id }}">
                        {{ $contract->customer->name }} - {{ $contract->customer->company }} - {{ $contract->product->product_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group mr-2">
            <input placeholder="Rate" type="number" class="form-control " style="width:160px" id="contractrate" name="rate" required>
        </div>
        <div class="form-group mr-2">
            <input placeholder="Amount" type="number" class="form-control" id="amount" name="amount" required>
        </div>
        <div class="form-group mr-2">
            <input placeholder="Discribtion" type="text" class="form-control" id="discribtion" name="discribtion">
        </div>
        <button type="submit" class="btn brannedbtn h-9">Add</button>
    </div>
</form>

<script>
   $(document).ready(function() {
    // When the tower is selected, filter contracts by product
    $('#tower_id').change(function() {
        var selectedTower = $(this).find(':selected');
        var productId = selectedTower.data('product-id');
        var towerName = selectedTower.text().toLowerCase(); // Get the tower name in lowercase

        // Filter contracts by product or show all if tower name is 'money'
        // $('#contract_id option').each(function() {
        //     var contractProductId = $(this).data('product-id');
        //     if (towerName.includes('money')) {
        //         $(this).show(); 
        //     } else if (contractProductId == productId) {
        //         $(this).show(); 
        //     } else {
        //         $(this).hide();             }
        // });

        // Reset the contract and rate fields
        $('#contract_id').val('');
        $('#contractrate').val('');
    });

    // When a contract is selected, load its rate
    $('#contract_id').change(function() {
        var selectedContract = $(this).find(':selected');
        var rate = selectedContract.data('rate');
        $('#contractrate').val(rate);
    });
});
</script>
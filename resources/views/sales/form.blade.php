@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <section class="content ">
            <div class="container-fluid">
                <div class="row ">
                    <div class="col-md-12">
                        <div class="card mt-3">
                            <div class="card-header brannedbtn">
                                <h3 class="card-title ">Add Sales</h3>
                            </div>

                            {{-- @if (session('success'))
                                <ol>
                                    <div class="alert alert-success" id="success-alert">
                                        {{ session('success') }}
                                    </div>
                                    <script>
                                        setTimeout(function() {
                                            document.getElementById('success-alert').style.display = 'none';
                                        }, 4000); // 2000ms = 2 seconds
                                    </script>
                                </ol>
                            @endif --}}

                            <form action="{{ isset($sale) ? route('purchaseupdate', $sale->id) : route('addsale') }}"
                                method="POST">
                                @csrf

                                @if (isset($sale))
                                    @method('PATCH')
                                @endif
                                <div class="card-body mt-1">
                                    @error('date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                    @error('contract_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <select class="form-control mb-3 mt-3" name="tower_id" id="tower" required>
                                    <option value="" disabled {{ isset($sale) ? '' : 'selected' }}>Select Tower</option>
                                    @foreach ($towers as $tower)
                                        <option value="{{ $tower->id }}" data-product="{{ $tower->product->product_name }}">
                                            {{ $tower->serial }} - {{ $tower->name }} - {{ $tower->product->product_name }}
                                        </option>
                                    @endforeach
                                </select>
                                
                                    @error('contract_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <select class="form-control mt-3" name="contract_id" id="contract" required>
                                        <option value="" disabled {{ isset($sale) ? '' : 'selected' }}>Select Customer</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->contract->id }}"
                                                data-product="{{ $customer->contract->product->product_name }}"
                                                data-rate="{{ $customer->contract->rate }}">
                                                {{ $customer->name }} - {{ $customer->company }} - {{ $customer->contract->product->product_name }} - {{ $customer->contract->rate }}
                                            </option>
                                        @endforeach
                                    </select>
                                    
                                    
                                    

                                    @error('amount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <input class="form-control form-control mb-3 mt-3" id="amount" name="amount" type="number"
                                        step="0.01" id="saleAmount" placeholder="Amount"
                                        value="{{ old('amount', $sale->amount ?? request('amount')) }}" required>
                                    @error('rate')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                        <input class="form-control form-control mb-3" name="rate" type="number"
                                            min="1" id="rate" placeholder="Rate"
                                            value="{{ old('rate', $sale->rate ?? request('rate')) }}" required>
                                            <div class="input-group date mb-3" id="reservationdate" data-target-input="nearest">

                                            <input type="text" name="date" id="date"
                                                class="form-control datetimepicker-input" data-target="#reservationdate"
                                                value="{{ old('date', isset($sale->date) ? \Carbon\Carbon::parse($sale->date)->format('Y-m-d H:i A') : now()->format('Y-m-d H:i A')) }}"
                                                required />
    
                                            <div class="input-group-append h-10" data-target="#reservationdate"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    <textarea class="form-control form-control" name="details" id="details" rows="2" placeholder="Description">{{ old('description', $sale->details ?? '') }}</textarea>
                                    <div class="card-footer bg-white d-flex justify-content-center">
                                        <button type="submit" class="btn brannedbtn w-100">
                                            {{ isset($sale) ? 'Update Sale' : 'Add Sale' }}</button>
                                    </div>
                                </div>


                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>
        </section>


    </div>
@endsection

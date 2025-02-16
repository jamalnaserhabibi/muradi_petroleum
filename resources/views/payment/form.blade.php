@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <section class="content ">
            <div class="container-fluid">
                <div class="row ">
                    <div class="col-md-12">
                        <div class="card mt-3">
                            <div class="card-header brannedbtn">
                                <h3 class="card-title ">Add Payment</h3>
                            </div>
                            {{-- {{$payment}} --}}
                            <form action="{{ isset($payment) ? route('updatepayment', $payment->id) : route('addpayment') }}"
                                method="POST">
                                @csrf

                                @if (isset($payment))
                                    @method('PATCH')
                                @endif
                                <div class="card-body mt-1">
                                    @error('contract_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                  
                                    <select class="form-control mt-3" name="contract_id" id="tower" required>
                                        <option value="" disabled {{ isset($payment) ? '' : 'selected' }}>Select Customer</option>
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
                                    <input class="form-control form-control mb-3 mt-3" id="paymentamount" name="amount" type="number"
                                        step="0.1" id="paymentAmount" placeholder="Amount"
                                        value="{{ old('amount', $payment->amount ?? request('amount')) }}" required>                                      
                                    <textarea class="form-control form-control" name="details" id="details" rows="2" placeholder="Description">{{ old('description', $payment->details ?? '') }}</textarea>
                                    <div class="card-footer bg-white d-flex justify-content-center">
                                        <button type="submit" class="btn brannedbtn w-100">
                                            {{ isset($payment) ? 'Update payment' : 'Add payment' }}</button>
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

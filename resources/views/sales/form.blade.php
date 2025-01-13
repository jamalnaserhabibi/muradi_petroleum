@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        {{-- <section class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
              <div class="col-sm-6">
                  <h1>Add New Expense Item</h1>
              </div>
              <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="#">Home</a></li>
                      <li class="breadcrumb-item active">General Form</li>
                  </ol>
              </div>
          </div>
      </div>
  </section> --}}

        <section class="content ">
            <div class="container-fluid">
                <div class="row ">
                    <div class="col-md-12">
                        <div class="card mt-3">
                            <div class="card-header brannedbtn">
                                <h3 class="card-title ">Tower's Serial</h3>
                            </div>
{{-- {{$towers}} --}}
                            @if (session('success'))
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
                            @endif
                            
                            <form
                                action="{{ isset($serial_numbers) ? route('serial_numbers_update', $serial_numbers->id) : route('serial_numbers_store') }}"
                                method="POST">
                                @csrf

                                @if (isset($serial_numbers))
                                    @method('PATCH')
                                @endif
                            <div class="card-body formserial">
                                @error('tower_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <!-- Type Dropdown -->
                                <select class="form-control mb-3" name="tower_id" id="tower_id" required>
                                    <option value="" disabled {{ isset($serial_numbers) ? '' : 'selected' }}>Select Tower</option>
                                    @foreach ($towers as $tower)
                                        <option value="{{ $tower->id }}" {{ old('id', $tower->id ?? '') == $tower->id ? 'selected' : '' }}>
                                         {{ $tower->serial }} - {{ $tower->name }} - {{ $tower->product->product_name}}
                                        </option>
                                    @endforeach
                                </select>
                                
                                @error('serial')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <input class="form-control form-control mb-2 ml-3" name="serial" type="number" step="1" 
                                    id="serial" placeholder="Serial"
                                    value="{{ old('serial', $serial_numbers->serial ?? request('serial')) }}" required>
                                
                                    {{-- <div class="form-group ml-3"> --}}
                                        <div class="input-group date ml-3" id="reservationdate" data-target-input="nearest">
                                            <input type="text" name="date" id="date" class="form-control datetimepicker-input" 
                                                data-target="#reservationdate" 
                                                value="{{ old('date', now()->format('Y-m-d H:i')) }}" required />
                                            <div class="input-group-append h-10" data-target="#reservationdate" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    {{-- </div> --}}

                                <div class="ml-3">
                                    <button type="submit" class="btn brannedbtn">
                                        {{ isset($serial_numbers) ? 'Update' : 'Add' }}</button>
                                </div>
                            </div>


                            </form>
                        </div>
                        <div class="card mt-3">
                            <div class="card-header brannedbtn">
                                <h3 class="card-title ">Sales</h3>
                            </div>

                            @if (session('success'))
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
                            @endif
                            
                            {{-- <form
                                action="{{ isset($purchase) ? route('purchaseupdate', $purchase->id) : route('purchaseadd') }}"
                                method="POST">
                                @csrf

                                @if (isset($purchase))
                                    @method('PATCH')
                                @endif
                                <div class="card-body mt-3">
                                @error('product_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <!-- Type Dropdown -->
                                <select class="form-control mb-3" name="product_id" id="product" required>
                                    <option value="" disabled {{ isset($purchase) ? '' : 'selected' }}>Type</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" {{ old('product_id', $purchase->product_id ?? '') == $product->id ? 'selected' : '' }}>
                                            {{ $product->product_name }}
                                        </option>
                                        
                                    @endforeach
                                </select>
                                
                                    @error('amount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror 
                                    <input class="form-control form-control mb-3" name="amount" type="number" step="0.01"
                                        id="expenseAmount" placeholder="Amount"
                                        value="{{ old('amount', $purchase->amount ?? request('amount')) }}" required>

                                    @error('heaviness')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <input class="form-control form-control mb-3" name="heaviness" type="number"   min="700" 
                                    max="1000" 
                                    step="1" 
                                        id="expenseAmount" placeholder="Weight"
                                        value="{{ old('amount', $purchase->heaviness ?? request('heaviness')) }}" required>
                                    

                                    @error('rate')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <input class="form-control form-control mb-3" name="rate" type="number" 
                                        id="purchaseAmount" placeholder="Ton Rate"
                                        value="{{ old('amount', $purchase->rate ?? request('rate')) }}" required>
                                    
                                    
                                    <textarea class="form-control form-control" name="details" id="details" rows="4"
                                        placeholder="Description">{{ old('description', $purchase->details ?? '') }}</textarea>
                                    <div class="card-footer bg-white d-flex justify-content-center">
                                        <button type="submit" class="btn brannedbtn w-100">
                                            {{ isset($purchase) ? 'Update Purchase' : 'Add Purchase' }}</button>
                                    </div>
                                </div>


                            </form> --}}
                        </div>
                    </div>
                </div>
            </div>
        </section>


    </div>
@endsection

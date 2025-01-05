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
                                <h3 class="card-title ">{{ isset($contract) ? 'Update' : 'Make'}} 
                                 Contract Details</h3>
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
                            <form
                            action="{{ isset($contract) ? route('contractUpdate', $contract->id) : route('contractstore') }}"
                            method="POST">
                            @csrf
                        
                            @if (isset($contract))
                                @method('PATCH') <!-- Use PATCH for updates -->
                            @endif
                        
                            <div class="card-body mt-3">
                                <!-- Customer Name (Read-only) -->
                                <input class="form-control form-control mb-3" type="text"
                                       value="{{ $latestCustomer->name }}" readonly>
                        
                                <!-- Hidden Customer ID -->
                                <input class="form-control form-control mb-3" name="customer_id" type="hidden"
                                       value="{{ $latestCustomer->id }}">
                        
                                <!-- Customer Company (Read-only) -->
                                <input class="form-control form-control mb-3" type="text"
                                       value="{{ $latestCustomer->company }}" readonly>
                        
                                @error('product_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                        
                                <!-- Product Dropdown -->
                                <select class="form-control mb-3" name="product_id" id="product" required>
                                    <option value="" disabled {{ old('product_id', $contract->product_id ?? null) === null ? 'selected' : '' }}>Select Product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" {{ old('product_id', $contract->product_id ?? null) == $product->id ? 'selected' : '' }}>
                                            {{ $product->product_name }}
                                        </option>
                                    @endforeach
                                </select>
                        
                                @error('rate')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                        
                                <!-- Rate Input -->
                                <input class="form-control form-control mb-3" name="rate" type="number" step="0.01" min="1"
                                       id="rate" placeholder="Rate"
                                       value="{{ old('rate', $contract->rate ?? '') }}" required>
                        
                                @error('details')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                        
                                <!-- Details Input -->
                                <textarea class="form-control form-control" name="details" rows="4"
                                          placeholder="Details">{{ old('details', $contract->details ?? '') }}</textarea>
                        
                                <!-- Submit Button -->
                                <div class="card-footer bg-white d-flex justify-content-center">
                                    <button type="submit" class="btn brannedbtn w-100">
                                        {{ isset($contract) ? 'Update Contract' : 'Create Contract' }}
                                    </button>
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

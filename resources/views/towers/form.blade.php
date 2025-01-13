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
                                <h3 class="card-title ">{{ isset($towers) ? 'Update' : 'Add'}} 
                                 Tower Details</h3>
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
                            action="{{ isset($towers) ? route('towerupdate', $towers->id) : route('addtower') }}"
                            method="POST">
                            @csrf
                        
                            @if (isset($towers))
                                @method('PATCH') <!-- Use PATCH for updates -->
                            @endif
                        
                            <div class="card-body mt-3">

                                @error('serial')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <input class="form-control form-control mb-3" name="serial" type="number" 
                                id="serial" placeholder="Tower Serial"
                                value="{{ old('serial', $towers->serial ?? request('serial')) }}" required>

                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <input class="form-control form-control mb-3" name="name" type="text" 
                                id="name" placeholder="Tower Name"
                                value="{{ old('name', $towers->name ?? request('name')) }}" required>

                                @error('product_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <!-- Product Dropdown -->
                                <select class="form-control mb-3" name="product_id" id="product" required>
                                    <option value="" disabled {{ old('product_id', $towers->product_id ?? null) === null ? 'selected' : '' }}>Select Product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" {{ old('product_id', $towers->product_id ?? null) == $product->id ? 'selected' : '' }}>
                                            {{ $product->product_name }}
                                        </option>
                                    @endforeach
                                </select>
                        
                                @error('details')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                        
                                <!-- Details Input -->
                                <textarea class="form-control form-control" name="details" rows="4"
                                          placeholder="Details">{{ old('details', $towers->details ?? '') }}</textarea>
                        
                                <!-- Submit Button -->
                                <div class="card-footer bg-white d-flex justify-content-center">
                                    <button type="submit" class="btn brannedbtn w-100">
                                        {{ isset($towers) ? 'Update Tower' : 'Add Tower' }}
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

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
                                <h3 class="card-title ">Add Purchase</h3>
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
                                    <input class="form-control form-control mb-3" name="amount" type="number" step="0.001"
                                        id="expenseAmount" placeholder="Amount"
                                        value="{{ old('amount', $purchase->amount ?? request('amount')) }}" required>

                                    @error('heaviness')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <input class="form-control form-control mb-3" name="heaviness" type="number"   min="700" 
                                    max="2500" 
                                    step="1" 
                                        id="expenseAmount" placeholder="Weight"
                                        value="{{ old('amount', $purchase->heaviness ?? request('heaviness')) }}" required>
                                    

                                    @error('rate')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <input class="form-control form-control mb-3" name="rate" type="number" 
                                        id="purchaseAmount" placeholder="Ton Rate"
                                        value="{{ old('amount', $purchase->rate ?? request('rate')) }}" required>

                                    <input class="form-control form-control mb-3" name="supplier" type="text" 
                                        id="supplier" placeholder="Supplier"
                                        value="{{ old('supplier', $purchase->supplier ?? request('supplier')) }}" required>
                                    
                                    
                                    <textarea class="form-control form-control" name="details" id="details" rows="4"
                                        placeholder="Description">{{ old('description', $purchase->details ?? '') }}</textarea>
                                    
                                        @error('document')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <div class="row mb-3 ml-1 mt-3">
                                        <label class="btn brannedbtn fileinput-button">
                                            <i class="fas fa-image"></i>
                                            <span>Document</span>
                                            <input type="file" name="document" class="d-none" id="profile_photo"
                                                onchange="previewImage()">
                                        </label>
                                        <img id="profile_preview"
                                            src="{{ isset($purchase) && $purchase->document ? asset('storage/' . $purchase->document) : '#' }}"
                                            alt="Document"
                                            style="border-radius:50%; 
                                               width: 60px; height: 60px; object-fit:cover; 
                                               margin:-15px; margin-left: 25px; margin-top: -5px; 
                                               display: {{ isset($purchase) && $purchase->document ? 'block' : 'none' }};">
                                        <!-- Image Preview -->
                                        </div>
                                        <div class="card-footer bg-white d-flex justify-content-center">
                                        <button type="submit" class="btn brannedbtn w-100">
                                            {{ isset($purchase) ? 'Update Purchase' : 'Add Purchase' }}</button>
                                    </div>
                                </div>


                            </form>
                            <script>
                                function previewImage() {
                                    const file = document.getElementById('profile_photo').files[0];
                                    const reader = new FileReader();

                                    reader.onloadend = function() {
                                        const imagePreview = document.getElementById('profile_preview');
                                        imagePreview.src = reader.result;
                                        imagePreview.style.display = 'inline'; // Show the image preview
                                    }

                                    if (file) {
                                        reader.readAsDataURL(file); // Convert the file to base64
                                    }
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </section>


    </div>
@endsection

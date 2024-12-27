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
                              
                                <h3 class="card-title ">Add Customer</h3>
                            </div>
                            @if(session('success'))
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
                            <form action="{{ isset($expense) ? route('customer.edit', $expense->id) : route('customeradd') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
    @if(isset($expense))
        @method('PATCH')
    @endif
                                <div class="card-body mt-3">
  @error('item')
    <span class="text-danger">{{$message}}</span>
@enderror                                  <!-- Item Input -->
                                    <input class="form-control form-control mb-3" name="item" type="text" id="expenseItem"
                                        placeholder="Item" value="{{ old('item', $expense->item ?? '') }}" required >

                                  
                                        @error('amount')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror      <!-- Amount Input -->
                                    <input class="form-control form-control mb-3" name="amount" type="number" id="expenseAmount"
                                        placeholder="Amount" value="{{ old('amount', $expense->amount ?? '') }}" required>
                                        @error('category')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                    <!-- Category Dropdown -->
                                    <select class="form-control form-control mb-3" name="category" id="expenseCategory" required>
                                        <option value="" disabled {{ old('category', $expense->category ?? '') == '' ? 'selected' : '' }}>Category</option>
                                        <option value="personal" {{ old('category', $expense->category ?? '') == 'personal' ? 'selected' : '' }}>Personal</option>
                                        <option value="family" {{ old('category', $expense->category ?? '') == 'family' ? 'selected' : '' }}>Family</option>
                                        <option value="tank" {{ old('category', $expense->category ?? '') == 'tank' ? 'selected' : '' }}>Tank</option>
                                        <option value="office" {{ old('category', $expense->category ?? '') == 'office' ? 'selected' : '' }}>Office</option>
                                        <option value="other" {{ old('category', $expense->category ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('description')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                                    <!-- Description Input -->
                                    <textarea  class="form-control form-control" name="description" id="expenseDescription" rows="4" placeholder="Description">{{ old('description', $expense->description ?? '') }}</textarea>
                                    <div class="card-footer bg-white d-flex justify-content-center">
                                        <button type="submit" class="btn brannedbtn w-100">  {{ isset($expense) ? 'Update Expense' : 'Add Expense' }}</button>
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

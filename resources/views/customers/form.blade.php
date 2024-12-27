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
                                action="{{ isset($customers) ? route('customer.edit', $customers->id) : route('customerstore') }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf

                                @if (isset($customers))
                                    @method('PATCH')
                                @endif
                                <div class="card-body mt-3">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror <!-- Item Input -->
                                    <input class="form-control form-control mb-3" name="name" type="text"
                                        id="expenseName" placeholder="Name" value="{{ old('name', $customers->name ?? '') }}"
                                        required>

                                    <input class="form-control form-control mb-3" name="created_by" type="hidden"
                                        id="expenseName" value="{{ (Auth::user()->name) }}"
                                      >


                                    @error('company')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror <!-- company Input -->
                                    <input class="form-control form-control mb-3" name="company" type="text"
                                        id="expensecompany" placeholder="Company"
                                        value="{{ old('amount', $customers->company ?? '') }}" required>
                                        
                                    @error('contact')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror <!--Contact Input -->
                                    <input class="form-control form-control mb-3" name="contact" type="number"  
                                        id="customersContact" placeholder="Contact"
                                        value="{{ old('Contact', $customers->contact ?? '') }}" required>
                                    
                                    @error('document')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror <!-- document Input -->
                                    <input class="form-control form-control mb-3" name="document" type="text"
                                        id="documentcompany" placeholder="Document"
                                        value="{{ old('document', $customers->document ?? '') }}" required>

                                   @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <!-- Description Input -->
                                    <textarea class="form-control form-control" name="description" id="customersDescription" rows="4"
                                        placeholder="Description">{{ old('description', $customers->description ?? '') }}</textarea>
                                    <div class="card-footer bg-white d-flex justify-content-center">
                                        <button type="submit" class="btn brannedbtn w-100">
                                            {{ isset($customers) ? 'Update Customer' : 'Add Customer' }}</button>
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

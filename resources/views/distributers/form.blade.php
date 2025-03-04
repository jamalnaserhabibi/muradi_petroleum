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
                                action="{{ isset($employee) ? route('employee.update', $employee->id) : route('employeeadd') }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf

                                @if (isset($employee))
                                    @method('PATCH')
                                @endif
                                <div class="card-body mt-3">
                                    @error('fullname')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror <!-- Item Input -->
                                    <input class="form-control form-control mb-3" name="fullname" type="text"
                                        id="employeefullname" placeholder="Fullname" value="{{ old('fullname', $employee->fullname ?? '') }}"
                                        required>


                                    @error('salary')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror 
                                    <input class="form-control form-control mb-3" name="salary" type="number"
                                        id="employeeSalary" placeholder="Salary"
                                        value="{{ old('salary', $employee->salary ?? '') }}" required>
                                   
                                    <!-- Category Dropdown -->
                                
                                
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <!-- Description Input -->
                                    <textarea class="form-control form-control mb-3" name="description" id="expenseDescription" rows="4"
                                        placeholder="Description">{{ old('description', $employee->description ?? '') }}</textarea>
                                    <div class="row mb-3 ml-1">
                                            <label class="btn brannedbtn fileinput-button">
                                                <i class="fas fa-image"></i>
                                                <span>Photo</span>
                                                <input type="file" name="photo" class="d-none"  id="profile_photo" onchange="previewImage()">
                                            </label>
                                            <img id="profile_preview"    

                                            
                                            src="{{ isset($employee) && $employee->photo ? asset('storage/' .  $employee->photo) : '#' }}"  
                                            alt="Profile Photo" style="border-radius:50%;{{ isset($employee) && $employee->photo ? 'display:block' : 'display:none' ;}} width: 60px;height: 60px;object-fit:cover;margin:-15px; margin-left: 25px;margin-top: -10px">
                                            <!-- Image Preview -->
                                     </div>
                                    <div class="card-footer bg-white d-flex justify-content-center">
                                        <button type="submit" class="btn brannedbtn w-100">
                                            {{ isset($employee) ? 'Update Employee' : 'Add Employee' }}</button>
                                    </div>
                                </div>


                            </form>
                            <script>
                                function previewImage() {
                                    const file = document.getElementById('profile_photo').files[0];
                                    const reader = new FileReader();
                                    
                                    reader.onloadend = function () {
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

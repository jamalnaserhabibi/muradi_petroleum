<!DOCTYPE html>
<html lang="en">

<head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Muradi Petroleum - Register</title>
    <base href="{{ asset('admin-lte') }}/" />
    
    <link rel="stylesheet" href="admincss/useraccounts/styleforall.css">

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min2167.css?v=3.2.0">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo d-flex flex-column align-items-center">
            <img src="img/logo.png" style="width: 50px" alt="">
            <a href="#"><b>Muradi </b> Petroleum</a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg" >Add a new User</p>
                <form method="POST" action="{{ route('addnewuser') }}" enctype="multipart/form-data">
                    @csrf
                
                    <div class="input-group mb-3">
                        <input type="text" name="name" class="form-control" placeholder="Full Name"
                            value="{{ old('name') }}" required autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    @error('name')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                
                    <div class="input-group mb-3">
                        <select name="usertype" class="form-control" required>
                            <option value="" disabled selected>Select User Type</option>
                            <option value="admin">Admin</option>
                            <option value="manager">Manager</option>
                        </select>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user-tag"></span>
                            </div>
                        </div>
                    </div>
                    @error('usertype')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    @error('password')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                
                    <div class="input-group mb-3">
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Confirm Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    @error('password_confirmation')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                
                    <div class="row mb-3 ml-1">
                        <label class="btn brannedbtn fileinput-button">
                            <i class="fas fa-image"></i>
                            <span>Upload Profile Photo</span>
                            <input type="file" name="profile_photo" class="d-none" required id="profile_photo" onchange="previewImage()">
                        </label>
                        <img id="profile_preview" src="#" alt="Profile Photo" style="border-radius:50%;display: none; width: 60px;height: 60px;object-fit:cover;margin:-15px; margin-left: 25px;margin-top: -10px">
                        <!-- Image Preview -->
                    </div>
                    @error('profile_photo')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                
                    <div class="row">
                        <div class="col-8">
                            <a href="{{ route('login') }}" class="text-sm">Already have an account?</a>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn brannedbtn btn-block">Register</button>
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

    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.min2167.js?v=3.2.0"></script>
</body>

</html>

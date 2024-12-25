<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from adminlte.io/themes/v3/pages/examples/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 06 May 2024 05:17:02 GMT -->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Muradi Petroleum</title>
    <base href="{{ asset('admin-lte') }}/" />

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback">
        <link rel="stylesheet" href="admincss/useraccounts/styleforall.css">

    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">

    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">

    <link rel="stylesheet" href="dist/css/adminlte.min2167.css?v=3.2.0">
 
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo d-flex flex-column align-items-center">
            <img src="img/logo.png" style="width: 100px" alt="">
            <a href="index2.html"><b>Muradi </b> Petroleum</a>
        </div>

        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start</p>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input 
                            {{-- type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus --}}
                            
                            placeholder="Username" 
                            class="form-control" 
                            type="text"
                            name="name"
                            value="{{ old('name') }}" 
                            required
                            autofocus
                        >
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
                        <input 
                            type="password" 
                            class="form-control" 
                            name="password" 
                            placeholder="Password" 
                            required
                        >
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    @error('password')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input 
                                    type="checkbox" 
                                    id="remember" 
                                    name="remember" 
                                    {{ old('remember') ? 'checked' : '' }}
                                >
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">
                                Log In
                            </button>
                        </div>
                    </div>
                
                    <div class="mt-3">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm">
                                Forgot Your Password?
                            </a>
                        @endif
                    </div>
                </form>
                
            </div>

        </div>
    </div>
    <div class="graphicBox">
        <img src="img/tower.png" alt="">
    </div>


    <script src="plugins/jquery/jquery.min.js"></script>

    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="dist/js/adminlte.min2167.js?v=3.2.0"></script>
</body>

</html>


{{-- <!DOCTYPE html>
<html lang="en">

<!-- Mirrored from adminlte.io/themes/v3/pages/examples/login-v2.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 06 May 2024 05:17:02 GMT -->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Muradi Petroleum</title>
    <base href="{{ asset('admin-lte') }}/" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback">

    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">

    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">

    <link rel="stylesheet" href="dist/css/adminlte.min2167.css?v=3.2.0">
</head>

<body class="hold-transition login-page">
    <div class="login-box">

        <div class="card card-outline card-primary">
               <div class="login-logo d-flex flex-column align-items-center">
            <img src="img/logo.png" style="width: 100px" alt="">
            <a href="index2.html"><b>Muradi </b> Petroleum</a>
        </div>
            <div class="card-body">
                <p class="login-box-msg">Enter Email and Password</p>
                <form action="https://adminlte.io/themes/v3/index3.html" method="post">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>

                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>

                    </div>
                </form>

            </div>

        </div>

    </div>


    <script src="plugins/jquery/jquery.min.js"></script>

    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="dist/js/adminlte.min2167.js?v=3.2.0"></script>
</body>

</html> --}}

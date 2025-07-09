@extends('adminlte::auth.login')

@section('title', 'Login - Inventory System 2025')

@section('auth_header', '')

@section('auth_body')
    <div class="login-welcome mb-4">
        <h5 class="text-muted text-center">Welcome back! Please sign in to continue</h5>
    </div>

    <form action="{{ route('login') }}" method="post">
        @csrf

        {{-- Email field --}}
        <div class="input-group mb-4">
            <div class="input-group-prepend">
                <div class="input-group-text bg-white border-right-0">
                    <span class="fas fa-envelope text-primary"></span>
                </div>
            </div>
            <input type="email" name="email" class="form-control border-left-0 @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="Email address" autofocus>
            @error('email')
            <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Password field --}}
        <div class="input-group mb-4">
            <div class="input-group-prepend">
                <div class="input-group-text bg-white border-right-0">
                    <span class="fas fa-lock text-primary"></span>
                </div>
            </div>
            <input type="password" name="password" class="form-control border-left-0 @error('password') is-invalid @enderror"
                   placeholder="Password">
            <div class="input-group-append">
                <div class="input-group-text bg-white border-left-0 password-toggle">
                    <span class="fas fa-eye password-toggle-icon"></span>
                </div>
            </div>
            @error('password')
            <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Remember Me --}}
        <div class="row mb-4">
            <div class="col-7">
                <div class="custom-control custom-switch">
                    <input type="checkbox" name="remember" class="custom-control-input" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="custom-control-label text-muted" for="remember">Remember Me</label>
                </div>
            </div>

{{--            <div class="col-5 text-right">--}}
{{--                <a href="{{ route('password.request') }}" class="text-primary">Forgot password?</a>--}}
{{--            </div>--}}
        </div>

        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block py-2 login-btn">
                    <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                </button>
            </div>
        </div>

{{--        <div class="text-center mt-4">--}}
{{--            <p class="text-muted">Don't have an account? <a href="{{ route('register') }}" class="text-primary">Register now</a></p>--}}
{{--        </div>--}}
    </form>
@stop

@section('auth_footer')
{{--    <div class="social-auth-links text-center mb-3">--}}
{{--        <p class="mb-1">- OR -</p>--}}
{{--        <a href="#" class="btn btn-block btn-outline-primary">--}}
{{--            <i class="fab fa-google mr-2"></i> Sign in with Google--}}
{{--        </a>--}}
{{--    </div>--}}
@stop

@section('sytles')
    <style>
        .login-page {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.3)),
            url('https://lh3.googleusercontent.com/proxy/DixxCIOTih9haBEeXrb5K5iatJyap1wQxH06AZie3Hq0REIH8mXhmaTCvr4DSw1b5M57EGyaYWp-C7f_bez1R0eE7DCSRhWh7LE');
            background-position: center;
            background-size: cover;
            background-attachment: fixed;
            height: 100vh;
        }

        .login-card-body {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            padding: 35px;
            backdrop-filter: blur(5px);
            background-color: rgba(255, 255, 255, 0.95);
        }

        .login-box {
            width: 400px;
            margin-top: -30px;
        }

        .login-logo {
            margin-bottom: 25px;
        }

        .login-logo a {
            color: white;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        }

        .input-group-text {
            border-radius: 5px;
        }

        .form-control {
            border-radius: 5px;
            height: 45px;
        }

        .login-btn {
            border-radius: 5px;
            font-weight: bold;
            height: 45px;
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
        }

        .password-toggle {
            cursor: pointer;
        }

        .custom-switch .custom-control-label::before {
            border-color: #007bff;
        }

        .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Password visibility toggle
            $('.password-toggle').click(function() {
                var passwordInput = $('input[name="password"]');
                var icon = $('.password-toggle-icon');

                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // Add animation to the login card
            $('.login-card').addClass('animate__animated animate__fadeIn');
        });
    </script>
@stop

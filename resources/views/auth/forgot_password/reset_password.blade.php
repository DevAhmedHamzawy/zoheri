<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login V12</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
    <link rel="icon" type="image/png" href="{{ asset('panel/login_form/images/icons/favicon.ico') }}"/>
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('https://cdn.rtlcss.com/bootstrap/v4.2.1/css/bootstrap.min.css') }}">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('panel/login_form/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('panel/login_form/fonts/Linearicons-Free-v1.0.0/icon-font.min.css') }}">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css') }}">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('panel/login_form/vendor/css-hamburgers/hamburgers.min.css') }}">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css') }}">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('panel/login_form/css/util.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('panel/login_form/css/main.css') }}">
<!--===============================================================================================-->
</head>
<body>

    <div class="limiter">
        <div class="container-login100" style="background-image: url({{ asset('panel/login_form/images/img-01.jpg') }}">
            <div class="wrap-login100 pt-3 p-b-30">

                @error('password')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                @enderror

                <form method="POST" action="{{ route('reset_password') }}"  class="login100-form validate-form">
                    @csrf

                    <div class="login100-form-avatar">
                        <img src="{{ asset('panel/login_form/images/shopping-online.jpg') }}" alt="AVATAR">
                    </div>

                    <span class="login100-form-title p-t-20 p-b-45">
                        برنامـج الزهـيـرى
                    </span>

                    <input type="hidden" name="email" value="{{ $email }}">

                    <div class="wrap-input100 validate-input m-b-10 @if ($errors->has('password')) is-invalid alert-validate @endif" data-validate = "{{ $errors->first('password') == null ? 'الرجاء ادخال كلمة المرور ' : $errors->first('password') }}">
                        <input class="input100{{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" name="password" value="{{ old('password') }}" placeholder="كلمة المرور">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-user"></i>
                        </span>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="wrap-input100 validate-input m-b-10 @if ($errors->has('password_confirmation')) is-invalid alert-validate @endif" data-validate = "{{ $errors->first('password_confirmation') == null ? 'الرجاء إعادة إدخال كلمة المرور' : $errors->first('password_confirmation') }}">
                        <input class="input100{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="إعادة إدخال كلمة المرور">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-user"></i>
                        </span>
                        @if ($errors->has('password_confirmation'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="container-login100-form-btn p-t-10">
                        <button class="login100-form-btn">
                            إرسال
                        </button>
                    </div>




                </form>
            </div>
        </div>
    </div>




<!--===============================================================================================-->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<!--===============================================================================================-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
<!--===============================================================================================-->
    <script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js') }}"></script>
<!--===============================================================================================-->
    <script src="{{ asset('panel/login_form/js/main.js') }}"></script>

</body>
</html>

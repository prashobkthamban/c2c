<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>IvrManager</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/styles/css/themes/lite-purple.min.css')}}">
</head>

<body>
<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="auth-layout-wrap" style="background-image: url({{asset('assets/images/photo-wide-4.jpg')}})">
        <div class="auth-content">
            <div class="card o-hidden" style="width: 299px;margin-left: 145px;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="p-4">
                            <div class="auth-logo text-center mb-4">
                                <img src="{{asset('assets/images/logo.png')}}" alt="">
                            </div>
                            <h1 class="mb-3 text-18">Sign In</h1>
                            <form>
                                <div class="form-group">
                                    <label for="username">User Name</label>
                                    <input id="username" class="form-control form-control-rounded {{ $errors->has('username') ? ' is-invalid' : '' }}" type="text" name="username" value="{{ old('username') }}" required autofocus>
                                    @if ($errors->has('username'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input id="password" class="form-control form-control-rounded {{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" name="password" required>
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <button type="submit" class="btn btn-rounded btn-primary btn-block mt-2">Sign In</button>

                            </form>

                            <div class="mt-3 text-center">
                                <a href="{{ route('password.request') }}" class="text-muted"><u>Forgot Password?</u></a>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-6 text-center " style="background-size: cover;background-image: url({{asset('assets/images/photo-long-3.jpg')}}">
                        <div class="pr-3 auth-right">
                            <a class="btn btn-rounded btn-outline-primary btn-outline-email btn-block btn-icon-text" href="signup.html">
                                <i class="i-Mail-with-At-Sign"></i> Sign up with Email
                            </a>
                            <a class="btn btn-rounded btn-outline-primary btn-outline-google btn-block btn-icon-text">
                                <i class="i-Google-Plus"></i> Sign up with Google
                            </a>
                            <a class="btn btn-rounded btn-outline-primary btn-block btn-icon-text btn-outline-facebook">
                                <i class="i-Facebook-2"></i> Sign up with Facebook
                            </a>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</form>

<script src="{{asset('assets/js/common-bundle-script.js')}}"></script>

<script src="{{asset('assets/js/script.js')}}"></script>
</body>

</html>

@section('title', 'Dodit - Register')

@extends('auth.master')

@section('content')

<div class="card o-hidden border-0 shadow-lg my-5">
  <div class="card-body p-0">
    <!-- Nested Row within Card Body -->
    <div class="row">
      <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
      <div class="col-lg-7">
        <div class="p-5">
          <div class="text-center">
            <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
          </div>
          <form class="user" method="POST" action="{{ route('register') }}">
            {{ csrf_field() }}

            <div class="form-group">
              <input type="text" class="form-control form-control-user" id="inputName" placeholder="Your Name" name="name" value="{{ old('name') }}" required autofocus>
              @if ($errors->has('name'))
                <span class="help-block">
                  <strong>{{ $errors->first('name') }}</strong>
                </span>
              @endif
            </div>

            <div class="form-group">
              <input type="email" class="form-control form-control-user" id="inputEmail" placeholder="Email Address" name="email" value="{{ old('email') }}" required>
              @if ($errors->has('email'))
                <span class="help-block">
                  <strong>{{ $errors->first('email') }}</strong>
                </span>
              @endif
            </div>

            <div class="form-group row">
              <div class="col-sm-6 mb-3 mb-sm-0">
                <input type="password" class="form-control form-control-user" id="inputPassword" placeholder="Password" name="password" required>
                @if ($errors->has('password'))
                  <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                  </span>
                @endif
              </div>
              <div class="col-sm-6">
                <input type="password" class="form-control form-control-user" id="confirmPassword" placeholder="Confirm Password" name="password_confirmation" required>
              </div>
            </div>

            <button type="submit" class="btn btn-primary btn-user btn-block">
              Register Account
            </button>
            <!-- <hr>
            <a href="index.html" class="btn btn-google btn-user btn-block">
              <i class="fab fa-google fa-fw"></i> Register with Google
            </a>
            <a href="index.html" class="btn btn-facebook btn-user btn-block">
              <i class="fab fa-facebook-f fa-fw"></i> Register with Facebook
            </a> -->
          </form>
          <hr>
          <!-- <div class="text-center">
            <a class="small" href="forgot-password.html">Forgot Password?</a>
          </div> -->
          <div class="text-center">
            <a class="small" href="{{ route('login') }}">Already have an account? Login!</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
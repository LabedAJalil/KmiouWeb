@extends('layouts.app')

@section('content')
    <div class="auth-wrapper">
        <div class="auth-content">
            <div class="card">
                <div class="card-body text-center">
                    <form method="POST" action="{{ route('doAdminLogin') }}">
                    @csrf
                    <div class="mb-4">
                        <i class="login-icon"><img src="{{asset('public/images/logo.png')}}"></i>
                    </div>
                      
                    <p style="color:red;text-align: center;">
                      {{$errors->first('approve')}}
                    
                    </p>

                    <!-- Alert Message -->  
                      <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                          @if(Session::has('alert-' . $msg))
                          <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                          @endif
                        @endforeach
                      </div>
                    <!-- Alert Message -->

                    <h3 class="mb-4">Login</h3>
                    <div class="input-group mb-3">
                         <input placeholder="Email" id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                   <div class="input-group mb-4">
                        <input placeholder="Password" id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required autocomplete="current-password">

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                    </div>
                    <!-- <div class="form-group text-left">
                        <div class="checkbox checkbox-fill d-inline">
                            <input type="checkbox" name="checkbox-fill-1" id="checkbox-fill-a1" checked="">
                            <label for="checkbox-fill-a1" class="cr"> Save Details</label>
                        </div>
                    </div> -->
                    <button type="submit" class="btn btn-primary shadow-2 mb-4">Login</button>
                    <p class="mb-2 text-muted">Forgot password? <!-- <a href="javascript:void(0)">{{ __('Reset') }}</a> --></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@extends('layouts.app')

@section('content')

 <main>
        <!--? slider Area Start-->
        <div class="slider-area ">
            <div class="single-slider hero-overly slider-height2 d-flex align-items-center" data-background="{{asset('public/img/hero/about.jpg')}}">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="hero-cap">
                                <h2>Login</h2>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li></li> 
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- slider Area End-->
        <section class="contact-section">
            <div class="container">
                <div class="main-body">
                        <div class="page-wrapper">
                            <!-- [ Main Content ] start -->
                            <div class="row">
                                <div class="col-md-5 m-auto">
                                   <div class="auth-wrapper">
                                    <div class="auth-content">
                                        <div class="card">
                                            <div class="card-body text-center">
                                                 <form method="POST" action="{{ route('doLogin') }}">
                                                @csrf
                                                <div class="mb-4">
                                                    <i class="login-icon"><img src="{{asset('public/images/favicon.png')}}"></i>
                                                </div>
                                                 <!-- Alert Message -->  
                                                <div class="flash-message">
                                                  @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                                    @if(Session::has('alert-' . $msg))
                                                    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                                                    {{ Session::forget('alert-' . $msg) }}
                                                     {{ Session::save() }}
                                                    @endif
                                                  @endforeach
                                                </div>
                                              <!-- Alert Message -->

                                                <h3 class="mb-4">Login</h3>
                                                <div class="input-group mb-3">
                                                     <input placeholder="Email / Mobile number" id="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

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
                                                <p class="mb-2 text-muted">Forgot password? <a href="{{url('forgot_password')}}">{{ __('Reset') }}</a></p>
                                                <p class="mb-0 text-muted">Don’t have an account? <a href="{{route('showRegister')}}">Signup</a></p>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                
                            </div>
                            <!-- [ Main Content ] end -->
                        </div>
                </div>
            </div>
        </section>
</main>
@endsection

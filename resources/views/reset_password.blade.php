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
                                <h2>Reset New Password</h2>
                              <!--   <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                        <li class="breadcrumb-item"><a href="#">Contact</a></li> 
                                    </ol>
                                </nav> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- slider Area End-->
        <!-- ================ contact section start ================= -->
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
                                                 <form method="POST" action="{{ route('resetPassword') }}">
                                                        @csrf
                                                    <p style="color:red;text-align: center;">
                                                      {{$errors->first('approve')}}
                                                    </p>

                                                    <div class="mb-4">
                                                        <img class="logo-login" src="{{asset('public/images/favicon.png')}}">
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
                                                    <div class="input-group mb-3">
                                                        <input type="email" name="email" readonly="" required="" autofocus="" value="{{$user->email}}" class="form-control" placeholder="email">
                                                    </div>

                                                    <div class="input-group mb-3">
                                                        <input type="password" name="new_password" class="form-control" placeholder="New Password" minlength="8">
                                                    </div>

                                                    <div class="input-group mb-3">
                                                        <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" minlength="8">
                                                    </div>
                                                    <button class="btn btn-primary mb-4">Reset Password</button>
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
            </div>
        </section>
        <!-- ================ contact section end ================= -->
    </main>

@endsection
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
                                <h2>Verify User</h2>
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
                                                 <form method="POST" action="{{ route('verifyUser') }}">
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

                                              <input type="hidden" name="user_id" value="{{$user_id}}">

                                                <h3 class="mb-4">Enter OTP</h3>
                                               <div class="input-group mb-4">
                                                    <input placeholder="Ex: 1234" id="verification_code" type="text" class="form-control"  name="verification_code" required >
                                                </div>
                                
                                                <button type="submit" class="btn btn-primary shadow-2 mb-4">Verify</button>
                                                <p class="mb-0 text-muted"><a href="{{route('resendCode',array('user_id'=>$user_id))}}"> Resend OTP</a></p>
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

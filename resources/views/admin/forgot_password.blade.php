<!DOCTYPE html>
<html lang="en">
  <head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <!-- Meta -->
    <meta name="description" content="Responsive Bootstrap 4 Dashboard Template">
    <meta name="author" content="ThemePixels">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('resources/admin/img/favicon.png')}}">

    <title>Admin-login</title>

    <!-- vendor css -->
    <link href="{{asset('resources/admin/lib/@fortawesome/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
    <link href="{{asset('resources/admin/lib/ionicons/css/ionicons.min.css')}}" rel="stylesheet">

    <!-- DashForge CSS -->
    <link rel="stylesheet" href="{{asset('resources/admin/css/dashforge.css')}}">
    <link rel="stylesheet" href="{{asset('resources/admin/css/dashforge.auth.css')}}">
  </head>
  <body>

    <div class="content content-fixed content-auth">
      <div class="container">

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
        <div class="media align-items-stretch justify-content-center ht-100p pos-relative">
          
            <div class="sign-wrapper mg-lg-l-50 mg-xl-l-60">
         
          <form class="col s12" method="post" action="javascript:void(0)" autocomplete="off">
               <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
               <div class="mx-wd-300 wd-sm-450 ht-100p d-flex flex-column align-items-center justify-content-center">
              <h4 class="tx-20 tx-sm-24">Reset your password</h4>
              <p class="tx-color-03 mg-b-30 tx-center">Enter your email address and we will send you a link to reset your password.</p>
              <div class="wd-100p d-flex flex-column flex-sm-row mg-b-40">
                <input type="email" class="form-control wd-sm-250 flex-fill" name="email" placeholder="Enter username or email address" required>
                <button class="btn btn-brand-02 mg-sm-l-10 mg-t-10 mg-sm-t-0">Reset Password</button>
              </div>

            </div>
          </form>
            </div><!-- sign-wrapper -->
        </div><!-- media -->
      </div><!-- container -->
    </div><!-- content -->

    <footer class="footer">
     
    </footer>

    <script src="{{asset('resources/admin/lib/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('resources/admin/lib/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('resources/admin/lib/feather-icons/feather.min.js')}}"></script>
    <script src="{{asset('resources/admin/lib/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>

    <script src="{{asset('resources/admin/js/dashforge.js')}}"></script>

 
  </body>
</html>

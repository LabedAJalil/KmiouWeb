<!DOCTYPE html>
<html lang="en">

<head>
    <title>KMIOU Transport de Marchandise Intelligent</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Datta Able Bootstrap admin template made using Bootstrap 4 and it has huge amount of ready made feature, UI components, pages which completely fulfills any dashboard needs." />
    <meta name="keywords" content="admin templates, bootstrap admin templates, bootstrap 4, dashboard, dashboard templets, sass admin templets, html admin templates, responsive, bootstrap admin templates free download,premium bootstrap admin templates, datta able, datta able bootstrap admin template, free admin theme, free dashboard template"/>
    <meta name="author" content="CodedThemes"/>

    <!-- Favicon icon -->
    <link rel="icon" href="{{asset('public/admin/images/favicon.ico')}}" type="image/x-icon">
    <!-- fontawesome icon -->
    <link rel="stylesheet" href="{{asset('public/admin/fonts/fontawesome/css/fontawesome-all.min.css')}}">
    <!-- animation css -->
    <link rel="stylesheet" href="{{asset('public/admin/plugins/animation/css/animate.min.css')}}">
    <!-- vendor css -->
    <link rel="stylesheet" href="{{asset('public/admin/css/style.css')}}">

</head>

<body>
  <div class="auth-wrapper">
    <div class="auth-content">
        <div class="card">
            <div class="card-body text-center">          

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

        <form class="col s12" method="post" action="{{route('adminDoLogin')}}" autocomplete="off">
                              
                <p style="color:red; font-weight: 500;">
                  {{$errors->first('approve')}}
                </p>

                 {{csrf_field()}}

                <div class="mb-4">
                  <img class="logo-login" src="{{asset('public/admin/images/logo.png')}}">
                </div>
                <h3 class="mb-4">Log In</h3>
                <div class="form-group">
                  <input type="email" class="form-control" name="email" placeholder="yourname@yourmail.com" required="">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control" name="password" placeholder="Enter your password" required="">
                </div>

                <button class="btn btn-primary mb-4">Login</button>
                <p class="mb-2 text-muted">Forgot password? <a href="{{url('reset_password')}}">Reset</a></p>
              </div>
          </form>
          
          </div><!-- sign-wrapper -->
        </div><!-- media -->
      </div><!-- container -->
    </div><!-- content -->

    <footer class="footer">
     
    </footer>

    <script src="{{asset('public/js/vendor-all.min.js')}}"></script>
    <script src="{{asset('public/plugins/bootstrap/js/bootstrap.min.js')}}"></script>

 
  </body>
</html>

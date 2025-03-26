<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" href="/assets/images/logoaja.png">
    <title>Security Check Pelindo | Log in</title>
    <!-- Custom CSS -->
    <link href="/dist/css/style.min.css" rel="stylesheet">
    <!-- Toastr -->
    <link href="/assets/libs/toastr/build/toastr.min.css" rel="stylesheet">


    <!-- ============================================================== -->
    <!-- All Required js -->
    <!-- ============================================================== -->
    <script src="/assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="/assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="/assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- validation -->
    <script src="/assets/libs/jquery-validation/dist/jquery.validate.min.js"></script>
    <!-- Toastr -->
    <script src="/assets/libs/toastr/build/toastr.min.js"></script>
    <!-- show password -->
    <script src="/assets/libs/bootstrap-show-password.js"></script>
    <script>
        var base_url = '{{ url("") }}';
    </script>
</head>

<body>
    <div class="main-wrapper">
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center"
            style="background:url(/assets/images/background/login.svg) no-repeat; background-position-x: 20%; background-color: #f6f6f8;">
            <div class="auth-box on-sidebar">
                <div id="loginform">
                    <div class="logo">
                        <span class="db"><img src="/assets/images/logo.png" alt="logo" /></span>
                        <h5 class="font-medium m-b-20 mt-2">Sign In Aplikasi</h5>
                    </div>
                    <!-- Form -->
                    <div class="row">
                        <div class="col-12">
                            @yield('main_view')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- This page plugin js -->
    <!-- ============================================================== -->
    <script>
        $(".preloader").fadeOut();
    </script>
    <!-- Notif untuk flash data "error_login" -->
    @if (session()->has('error_login'))
    <script>
        $(window).on('load', function() {
            toastr.error('{{ session("error_login") }}');
        });
    </script>
    @endif
</body>

</html>
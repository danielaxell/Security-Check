<!DOCTYPE html>
<html dir="ltr">

<style>
    .auth-wrapper {
        background: url('{{ url('assets/images/background/login3.jpg') }}') no-repeat;
        background-size: cover;
        background-position: center;
        height: 100vh;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .auth-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(0,0,0,0.4), rgba(0,0,0,0.2));
    }

    .auth-box {
        position: relative;
        padding: 30px 30px;
        width: 90%;
        background: #ffffff;
        border-radius: 15px;
        box-shadow: 0 0 35px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
        max-width: 400px;
        z-index: 2;
    }

    .logo {
        text-align: center;
        margin-bottom: 5px;
    }

    .logo img {
        max-width: 280px;
        height: auto;
        margin-bottom: 5px;
    }

    .logo h4 {
        color: #2c3e50;
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .logo p {
        color: #7f8c8d;
        font-size: 15px;
        margin-bottom: 2px;
    }

    .form-control {
        height: 40px;
        border-radius: 8px;
        font-size: 14px;
        border: 1px solid #e0e0e0;
        padding: 0 15px;
        margin-bottom: 12px;
        transition: all 0.3s ease;
        background: rgba(255,255,255,0.9);
    }

    .form-control:focus {
        border-color: #4CAF50;
        box-shadow: 0 0 15px rgba(76, 175, 80, 0.1);
        background: #ffffff;
    }

    .input-group {
        margin-bottom: 15px;
    }

    .btn-info {
        height: 42px;
        border-radius: 8px;
        background: #4CAF50;
        border: none;
        font-weight: 600;
        font-size: 15px;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        width: 100%;
    }

    .btn-info:hover {
        background: #43A047;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(76, 175, 80, 0.3);
    }

    .custom-control-label {
        color: #666;
        font-size: 13px;
    }

    .logo-bawah {
        width: 100px;
        margin-top: 15px;
        opacity: 0.9;
    }
    

    /* Animasi */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .auth-box {
        animation: fadeIn 0.6s ease-out;
    }

    /* Responsif */
    @media (max-width: 768px) {
        .auth-box {
            width: 85%;
            padding: 20px;
            margin: 15px;
        }
    }
    
</style>


<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" href="{{ url('assets/images/logoaja.png') }}">
    <title>Security Check Pelindo | Log in</title>
    <!-- Custom CSS -->
    <link href="{{ url('dist/css/style.min.css') }}" rel="stylesheet">
    <!-- Toastr -->
    <link href="{{ url('assets/libs/toastr/build/toastr.min.css') }}" rel="stylesheet">
<style>
    .logo-bawah {
        width: 200px; /* Sesuaikan ukuran agar sebanding dengan logo atas */
        height: auto; /* Menjaga aspek rasio */
        margin-top: 50px; /* Beri jarak dari tombol */
    }
</style>


    <!-- ============================================================== -->
    <!-- All Required js -->
    <!-- ============================================================== -->
    <script src="{{ url('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ url('assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ url('assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- validation -->
    <script src="{{ url('assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ url('assets/libs/toastr/build/toastr.min.js') }}"></script>
    <!-- show password -->
    <script src="{{ url('assets/libs/bootstrap-show-password.js') }}"></script>
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
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center">
            <div class="auth-box">
                <div id="loginform">
                    <div class="logo text-center">
                        <img src="{{ url('assets/images/logo1.png') }}" alt="logo" />
                        <p>Silakan masuk untuk melanjutkan</p>
                    </div>
                    <!-- Form -->
                    <div class="row">
                        
                        <div class="col-12">
                            @yield('main_view')
                        </div>
                    </div>
                    <div class="text-center">
                        <img src="{{ url('assets/images/pelindo.png') }}" alt="logo2" class="logo-bawah"/>
                    </div>
                </div>
            </div>
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
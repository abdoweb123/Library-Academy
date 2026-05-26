<!doctype html>
<html lang="en">

<head>
    <title>{{ trns('login') }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="shortcut icon" type="image/x-icon"
      href="{{ asset(isset($setting->where('key','logo')->first()->value) ? $setting->where('key','logo')->first()->value : null)}}"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('assets/auth') }}/css/style.css">
    
</head>

<body style="background-image: url('{{ asset('login-background.jpg') }}'); background-size: cover">
    <section class="ftco-section" style="padding: 5em 0;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-6">
                    <div class="wrap" style="border-radius: 45px">
                        <div class="login-wrap p-4 p-md-5">
                            <div class="d-flex">
                                <div class="w-100 text-center">
                                    <img src="{{ getFile($setting->where('key', 'logo')->first()->value) }}" alt="logo"
                                        style="width: 100px; height: 100px; margin-bottom: 20px;">
                                    <h2 class="mb-4" style="font-weight: bolder;margin-bottom: 50px;">{{ trns('admin_login') }}</h2>
                                    <div class="d-flex justify-content-center">
                                        @session('error')
                                            <large class="text-danger fw-bold">{{ session('error') }}</large>
                                        @endsession
                                    </div>
                                </div>
                            </div>

                            @if(session('2fa_required'))
                                <!-- 2FA Verification Form -->
                                <form action="{{ route('admin.2fa.verify') }}" method="post" class="signin-form">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ session('2fa_user_id') }}">

                                    <div class="form-group mt-3 text-center">
                                        <p>{{ trns('enter_2fa_code') }}</p>
                                    </div>

                                    <div class="form-group mt-3">
                                        <input type="text" class="form-control" name="twofa_code" required
                                               placeholder="{{ trns('verification_code') }}" autocomplete="off">
                                    </div>

                                    <div class="form-group mt-5">
                                        <button type="submit" class="form-control btn btn-primary rounded submit px-3">
                                            {{ trns('verify_code') }}
                                        </button>
                                    </div>
                                </form>
                            @else
                                <!-- Regular Login Form -->
                                <form action="{{ route('dashboard.admin.dologin') }}" id="loginform" class="signin-form" method="post">
                                    @csrf
                                    <div class="form-group mt-3">
                                        <input type="text" class="form-control" name="input" required>
                                        <label class="form-control-placeholder">{{ trns('Username_or_email') }}</label>
                                    </div>
                                    <div class="form-group mt-5">
                                        <input id="password-field" type="password" name="password" class="form-control" required>
                                        <label class="form-control-placeholder">{{ trns('Password') }}</label>
                                        <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                    </div>
                                    <div class="form-group mt-5">
                                        <button type="button" id="loginBtn" class="form-control btn btn-primary rounded submit px-3">
                                            {{ trns('login') }}
                                        </button>
                                    </div>
                                </form>
                            @endif

                            <div class="w-100 d-flex justify-content-between">
                                <p class=""><a href="{{ route('dashboard.admin.change_language','ar') }}" class="text-primary">العربية</a> |
                                <a href="{{ route('dashboard.admin.change_language','en') }}" class="text-primary">English</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('assets/auth') }}/js/jquery.min.js"></script>
    <script src="{{ asset('assets/auth') }}/js/popper.js"></script>
    <script src="{{ asset('assets/auth') }}/js/bootstrap.min.js"></script>
    <script src="{{ asset('assets/auth') }}/js/main.js"></script>

    <script>
        $('#loginBtn').on('click', function(e) {
            $(this).attr('disabled', true);
            $(this).html('<i class="fa fa-spinner fa-spin"></i>');
            $('#loginform').submit();
        });
    </script>
</body>
</html>

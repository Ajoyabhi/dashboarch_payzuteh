<!DOCTYPE html>

<html lang="en">



<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Jippay</title>
    
    <!-- Favicon -->

    <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.png') }}">

    <link rel="stylesheet" href="{{ asset('assets_front/css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets_front/css/fontawesome.min.css') }}" />

    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />

    <link rel="stylesheet" href="{{ asset('assets_front/css/style.css') }} "/>

    <link rel="stylesheet" href="{{ asset('assets_front/css/header.css') }}">

    <link rel="stylesheet" href="{{ asset('assets_front/css/animate.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets_front/css/slick.css') }}">

    <link rel="stylesheet" href="{{ asset('assets_front/css/slick-theme.css') }}">

    <link rel="stylesheet" href="{{ asset('assets_front/css/login.css') }}">

</head>

<body>

    <section class="sign-in">

        <div class="container p-5">

            <div class="signin-content row">

                <div class="col-lg-6 col-md-6">

                    <div class="signin-image">

                        <figure><img src="{{ asset('assets_front/images/login_page.svg') }}" alt="sing up image"></figure>

                    </div>

                </div>

                <div class="col-lg-6 col-md-6">

                    <div class="login-form">

                        <h4>Jippay</h4>

                        <P> <span>Welcome Back !</span> <br>

                            <span>Sign in to continue to Jippay.</span></P>

                            @if(session('success'))

                                <div class="alert alert-success mb-1 mt-1">

                                    {{ session('success') }}

                                </div>

                            @endif

                        <form action="{{ url('authentication') }}" method="POST" enctype="multipart/form-data">

                            @csrf

                            <div class="row">

                                <div class="col-lg-12">

                                        <div class="mb-3">

                                            <label for="exampleInputEmail1" class="form-label">User Name</label>

                                            <input type="text" class="form-control" name="user_name" id="user_name"

                                                aria-describedby="emailHelp">

                                        </div>

                                        @error('user_name')

                                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>

                                        @enderror

                                </div>

                                <div class="col-lg-12">

                                    <div class="mb-3">

                                        <label for="exampleInputPassword" class="form-label">Password</label>

                                        <input type="password" class="form-control" name="password" id="password"

                                            aria-describedby="passwordHelp">

                                    </div>

                                    @error('password')

                                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>

                                    @enderror

                                </div>

                            </div>

                            <button type="submit" class="new-button">Login</button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <script src="{{ asset('assets_front/js/jquery.min.js') }}"></script>

    <script src="{{ asset('assets_front/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('assets_front/js/slick.js') }}"></script>

    <script src="{{ asset('assets_front/js/script.js') }}"></script>



</body>



</html>
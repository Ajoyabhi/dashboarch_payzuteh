<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/payzutech.png') }}">
    <script
    src="https://kit.fontawesome.com/5ad34692d0.js" 
          crossorigin="anonymous"
    ></script>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <title>Payzutech</title>
  </head>
  <body>
    <div class="container">
      <div class="forms-container">
        <div class="signin-signup">

        @if(session('success'))

                                <div class="alert alert-success mb-1 mt-1">

                                    {{ session('success') }}

                                </div>

                            @endif
        <form action="{{ url('authentication') }}" method="POST" enctype="multipart/form-data" class="sign-in-form">
    @csrf
    <img src="{{ asset('assets/images/logo/payzutech.png') }}" style="height: 12rem; width: 12rem;"></img>
    <h2 class="title" style="margin-top: 2rem;">Sign in</h2>

    <div class="input-field ">
        <i class="fas fa-user"></i>
        <input type="text" name="user_name" id="user_name" placeholder="Username" value="{{ old('user_name') }}" />
    </div>
    @error('user_name')
        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
    @enderror

    <div class="input-field">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" id="password" placeholder="Password" />
    </div>
    @error('password')
        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
    @enderror

    <input type="submit" value="Login" class="btn solid" />
</form>

          <!-- <form action="#" class="sign-up-form">
            <h2 class="title">Sign up</h2>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="text"  placeholder="Username" />
            </div>
            <div class="input-field">
              <i class="fas fa-envelope"></i>
              <input type="email" placeholder="Email" />
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" placeholder="Password" />
            </div>
            <input type="submit" class="btn" value="Sign up" />
            <p class="social-text">Or Sign up with social platforms</p>
            <div class="social-media">
              <a href="#" class="social-icon">
                <i class="fab fa-facebook-f"></i>
              </a>
              <a href="#" class="social-icon">
                <i class="fab fa-twitter"></i>
              </a>
              <a href="#" class="social-icon">
                <i class="fab fa-google"></i>
              </a>
              <a href="#" class="social-icon">
                <i class="fab fa-linkedin-in"></i>
              </a>
            </div>
          </form> -->
        </div>
      </div>

      <div class="panels-container">
        <div class="panel left-panel">
          <div class="content">
            <h3 style="color:#102E50;">PAYZUTECH</h3>
            <p>
              Welcome to Payzutech, your one-stop solution for all your tech needs.
              Join us today and experience the best in technology and services.
            </p>
            
          </div>
          <img src="assets\images\loginImages\log.svg" class="image" alt="" />
        </div>
        <div class="panel right-panel">
          <div class="content">
            <h3>One of us ?</h3>
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum
              laboriosam ad deleniti.
            </p>
            <button class="btn transparent" id="sign-in-btn">
              Sign in
            </button>
          </div>
          <img src="img/register.svg" class="image" alt="" />
        </div>
      </div>
    </div>

    <script src="app.js"></script>
    <script src="{{ asset('assets_front/js/script.js') }}"></script>
  </body>
</html>
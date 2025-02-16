<!DOCTYPE html>

<html lang="en">



<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Seospay</title>

    <link rel="stylesheet" href="{{ asset('assets_front/css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets_front/css/fontawesome.min.css') }}" />

    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />

    <link rel="stylesheet" href="{{ asset('assets_front/css/style.css') }} "/>

    <link rel="stylesheet" href="{{ asset('assets_front/css/header.css') }}">

    <link rel="stylesheet" href="{{ asset('assets_front/css/animate.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets_front/css/slick.css') }}">

    <link rel="stylesheet" href="{{ asset('assets_front/css/slick-theme.css') }}">

</head>



<body>

    <header>

        <nav class="navbar navbar-expand-lg navbar-light ">

            <div class="container-fluid">

                <a class="navbar-brand" href="index.html">PayNow</a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"

                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"

                    aria-expanded="false" aria-label="Toggle navigation">

                    <span class="navbar-toggler-icon"></span>

                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">



                        <li class="nav-item dropdown">

                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"> Products </a>

                            <ul class="dropdown-menu">                                

                                <li><a class="dropdown-item" href="#"> Banking <i class="fa fa-chevron-right"></i> </a>

                                    <ul class="submenu dropdown-menu">

                                        <li><a class="dropdown-item" href="#">AEPS</a></li>

                                        <li><a class="dropdown-item" href="#">DMT</a></li>

                                    </ul>

                                </li>

                                <li><a class="dropdown-item" href="#"> Payout <i class="fa fa-chevron-right"></i> </a>

                                    <ul class="submenu dropdown-menu">

                                        <li><a class="dropdown-item" href="#">Payout</a></li>

                                        <li><a class="dropdown-item" href="#">UPI Money Transfer</a></li>

                                    </ul>

                                </li>

                                <li><a class="dropdown-item" href="#"> Collection <i class="fa fa-chevron-right"></i></a>

                                    <ul class="submenu dropdown-menu">

                                        <li><a class="dropdown-item" href="#">UPI Collection</a></li>

                                    </ul>

                                </li>

                                <li><a class="dropdown-item" href="#"> Cards <i class="fa fa-chevron-right"></i></a>

                                    <ul class="submenu dropdown-menu">

                                        <li><a class="dropdown-item" href="#">Credit Card Payment</a></li>

                                    </ul>

                                </li>

                                <li><a class="dropdown-item" href="#"> Travel <i class="fa fa-chevron-right"></i></a>

                                    <ul class="submenu dropdown-menu">

                                        <li><a class="dropdown-item" href="#">Bus</a></li>

                                        <li><a class="dropdown-item" href="#">Hotel</a></li>

                                        <li><a class="dropdown-item" href="#">Flight</a></li>

                                    </ul>

                                </li>

                                <li><a class="dropdown-item" href="#"> Wallet Recharge <i class="fa fa-chevron-right"></i></a>

                                    <ul class="submenu dropdown-menu">

                                        <li><a class="dropdown-item" href="#">All Wallet</a></li>

                                    </ul>

                                </li>

                                <li><a class="dropdown-item" href="#"> Verification </a></li>

                                <li><a class="dropdown-item" href="#"> Other <i class="fa fa-chevron-right"></i></a>

                                    <ul class="submenu dropdown-menu">

                                        <li><a class="dropdown-item" href="#">Recharge</a></li>

                                        <li><a class="dropdown-item" href="#">BBPS</a></li>

                                        <li><a class="dropdown-item" href="#">Payment Gateway</a></li>

                                    </ul>

                                </li>

                            </ul>

                        </li>

                        <li class="nav-item">

                            <a class="nav-link" href="api.html">Developer API</a>

                        </li>

                        <li class="nav-item">

                            <a class="nav-link" href="pricing.html">Pricing</a>

                        </li>

                        <li class="nav-item dropdown">

                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">  Company  </a>

                            <ul class="dropdown-menu">

                              <li><a class="dropdown-item" href="about-us.html"> About Us </a></li>                              

                              <li><a class="dropdown-item" href="blog.html"> Our Blogs</a></li>

                              <li><a class="dropdown-item" href="contact-us.html"> Contact Us</a>

                            </ul>

                        </li>

                        <li class="nav-item">

                            <a class="nav-link" href="{{ url('/login') }}">Login</a>

                        </li>

                    </ul>

                </div>

            </div>

        </nav>



    </header>

    <div class="hero-section">

        <div class="container">

            <div class="row">

                <div class="col-lg-6 col-md-6 col-xl-6 col-xxl-6">

                    <div class="main-banner-text animated fadeInLeft" data-wow-delay=".3s">

                        <span>Nex-Gen API Banking</span>

                        <h3>Empowering <br> Digital Banking</h3>

                        <p>Building Bharat's Digital Banking Ecosystem <br> <br> We boost a comprehensive, sophisticated

                            API products suite across Banking, Finance & Verification. Additionally, we have created a

                            Unified Open API Platform that will transform how BHARAT transacts & lead to greater

                            consumer adoption, interface and delight.</p>

                        <div class="link">

                            <a href="#">Start Now <i class="fad fa-arrow-right"></i></a>

                        </div>

                    </div>

                </div>

                <div class="col-lg-6 col-md-6 col-xl-6 col-xxl-6">

                    <div class="main-banner-img  animated fadeInRight" data-wow-delay=".3s">

                        <img src="{{ asset('assets_front/images/3.png') }}" alt="">

                    </div>

                </div>

            </div>

        </div>

    </div>



    <div class="services">

        <div class="container">

            <div class="row">

                <div class="col-lg-6 offset-lg-3">

                    <div class="head-title">

                        <span>OUR SERVICES</span>

                        <h4>

                            MANIFOLD SERVICES <br>

                            OF PINWALLET PAYMENTS</h4>

                    </div>

                </div>

            </div>

            <div class="row">

                <div class="col-lg-4 col-md-6">

                    <div class="services-box sb-1 animated fadeInLeft" data-wow-delay=".3s">

                        <img src="{{ asset('assets_front/images/s1.jpg') }}" alt="icon">

                        <h5>Multicore Banking</h5>

                        <p>Business building it before the tab providet management, Payroll &amp; Worksite Services

                            full-fledged.</p>

                        <!-- <a href="#">Read More <i class="fad fa-arrow-right"></i></a> -->

                    </div>

                </div>

                <div class="col-lg-4 col-md-6">

                    <div class="services-box sb-1 animated fadeIn" data-wow-delay=".3s">

                        <img src="{{ asset('assets_front/images/s2.jpg') }}" alt="icon">

                        <h5>Unified Payment</h5>

                        <p>Business building it before the tab providet management, Payroll &amp; Worksite Services

                            full-fledged.</p>

                        <!-- <a href="#">Read More <i class="fad fa-arrow-right"></i></a> -->

                    </div>

                </div>

                <div class="col-lg-4 col-md-6">

                    <div class="services-box sb-1 animated fadeInRight" data-wow-delay=".3s">

                        <img src="{{ asset('assets_front/images/s3.jpg') }}" alt="icon">

                        <h5>Authentic Verification</h5>

                        <p>Business building it before the tab providet management, Payroll &amp; Worksite Services

                            full-fledged.</p>

                        <!-- <a href="#">Read More <i class="fad fa-arrow-right"></i></a> -->

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="why-us">

        <div class="container">

            <div class="row mb-lg-5">

                <div class="col-lg-8 offset-lg-2">

                    <div class="head-title">

                        <h3>Why Us ?</h3>

                        <h5>PaySprint has put together a number of solutions to create a platform that offers

                            significant advantages to startups & the entrepreneurs behind them, as well as MSMEs, NBFCs,

                            and more. Some of the key benefits include :</h5>

                    </div>

                </div>

            </div>

            <div class="row">

                <div class="col-lg-4 col-md-6">

                    <div class="why-box animated fadeInUp" data-wow-delay=".3s">

                        <img src="{{ asset('assets_front/images/icon/a.png') }}" alt="icon">

                        <h5>Open API Platform</h5>

                        <p>A Unified Open API platform that seamlessly brings all the solutions together to ensure

                            maximum success for your digital experience delivery.</p>



                    </div>

                </div>

                <div class="col-lg-4 col-md-6">

                    <div class="why-box animated fadeInUp" data-wow-delay=".3s">

                        <img src="{{ asset('assets_front/images/icon/b.png') }}" alt="icon">

                        <h5>Faster & Wholesome Integration</h5>

                        <p>Integrate our easy-to-use APIs and go live quickly to maximise your profitability & ensure

                            optimal value.</p>



                    </div>

                </div>

                <div class="col-lg-4 col-md-6">

                    <div class="why-box animated fadeInUp" data-wow-delay=".3s">

                        <img src="{{ asset('assets_front/images/icon/c.png') }}" alt="icon">

                        <h5>Seamless Onboarding</h5>

                        <p>We believe in zero manual intervention, complete the onboarding online with minimal

                            documentation to get started fast.</p>



                    </div>

                </div>

                <div class="col-lg-4 col-md-6">

                    <div class="why-box animated fadeInUp" data-wow-delay=".5s">

                        <img src="{{ asset('assets_front/images/icon/d.png') }}" alt="icon">

                        <h5>Choose Your Bank</h5>

                        <p>Choose primary & back-up banks as pipes and get the transaction flow based on your

                            preference.</p>



                    </div>

                </div>

                <div class="col-lg-4 col-md-6">

                    <div class="why-box animated fadeInUp" data-wow-delay=".5s">

                        <img src="{{ asset('assets_front/images/icon/e.png') }}" alt="icon">

                        <h5>Dedicated Customer Support</h5>

                        <p>We're always available through email, phone and chat to assist you at every step.</p>



                    </div>

                </div>

                <div class="col-lg-4 col-md-6">

                    <div class="why-box animated fadeInUp" data-wow-delay=".5s">

                        <img src="{{ asset('assets_front/images/icon/f.png') }}" alt="icon">

                        <h5>Wide-Ranging Features on a Single Dashboard</h5>

                        <p>Get a wide range of value added services on a single dashboard with sky-high success rates

                        </p>



                    </div>

                </div>

            </div>

        </div>

    </div>

    <section>

        <div class="container">

            <div class="row">

                <div class="col-lg-12 customer-logos slider">

                    <div class="slide"><img src="{{ asset('assets_front/images/logos/axis_bank.png') }}" class="logos"></div>

                    <div class="slide"><img src="{{ asset('assets_front/images/logos/bbps.png') }}" class="logos"></div>

                    <div class="slide"> <img src="{{ asset('assets_front/images/logos/indus_bank.png') }}" class="logos">

                    </div>

                    <div class="slide"><img src="{{ asset('assets_front/images/logos/npci.png') }}" class="logos">

                    </div>

                    <div class="slide"><img src="{{ asset('assets_front/images/logos/qwikcilver.png') }}" class="logos"></div>

                    <div class="slide"><img src="{{ asset('assets_front/images/logos/yes_bank.png') }}" class="logos">

                    </div>

                </div>

            </div>

        </div>

    </section>

    <footer>

        <div class="container">

            <div class="row">

                <div class="col-lg-6">

                    <div class="footer-box">

                        <img src="{{ asset('assets_front/images/logo.png') }}" alt="">

                        <p>Our approach to itis unique around know work an we know doesn't work verified factors in

                            play.</p>

                    </div>

                    <div class="social-icon">



                        <span>Social Links:</span> <br> <br>

                        <a href="#"><i class="fab fa-facebook"></i></a>

                        <a href="#"><i class="fab fa-twitter-square"></i></a>

                        <a href="#"><i class="fab fa-instagram"></i></a>

                        <a href="#"><i class="fab fa-linkedin"></i></a>

                        <a href="#"><i class="fab fa-google-plus"></i></a>

                    </div>

                </div>

                <div class="col-lg-3">

                    <div class="footer-box">



                        <div class="footer-box">

                            <h3>Support Link</h3>

                            <ul>

                                <li> <a href="#">About us

                                    </a></li>

                                <li> <a href="#"> Privacy Policy</a></li>

                                <li> <a href="#"> Grievances</a></li>

                                <li> <a href="#"> Terms & Conditions</a></li>

                            </ul>

                        </div>

                    </div>

                </div>

                <div class="col-lg-3">

                    <div class="footer-box">

                        <h3>Contact Info</h3>

                    </div>

                    <div class="call-info d-flex">

                        <div class="icons">

                            <span>

                                <i class="fas fa-phone-alt"></i>

                            </span>

                        </div>

                        <div class="info">

                            <span>Have Any Question?</span><br>

                            <a href="#"> +91 1234567890</a>

                        </div>

                    </div>



                    <div class="call-info d-flex">

                        <div class="icons">

                            <span>

                                <i class="fas fa-envelope"></i>

                            </span>

                        </div>

                        <div class="info">

                            <span>Email :</span><br>

                            <a href="#"> demo@gmail.com</a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </footer>

    <div class="copyright">

        <div class="container">

            <div class="row">

                <div class="col-lg-12">

                    <span>Copyright PayNow 2023, All Right Reserved</span>

                </div>

            </div>

        </div>

    </div>

    <script src="{{ asset('assets_front/js/jquery.min.js') }}"></script>

    <script src="{{ asset('assets_front/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('assets_front/js/slick.js') }}"></script>

    <script src="{{ asset('assets_front/js/script.js') }}"></script>

    

</body>



</html>
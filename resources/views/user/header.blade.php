<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>user Dashboard </title>

    <!-- Favicon -->

    <link rel="shortcut icon" href="{{ asset('assets/images/logo/payzutech.png') }}">

    <!-- page css -->

    <!-- Core css -->

    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>

    <div class="app">

        <div class="layout">

            <!-- Header START -->

            <div class="header">

                <div class="logo logo-dark">
                    PAYZUTECH
                    <!--<a href="{{ url('/user/dashboard') }}">-->

                    <!--    <img src="{{ asset('assets/images/logo/PAYDEX SOLUTIONS.png') }}" alt="Logo">-->

                    <!--    <img class="logo-fold" src="{{ asset('assets/images/logo/logo.jpg') }}" alt="Logo">-->



                    <!--</a>-->

                </div>

                <div class="logo logo-white">
<!-- Jippay -->
                    <!--<a href="{{ url('/user/dashboard') }}">-->

                    <!--    <img src="{{ asset('assets/images/logo/logo.jpg') }}" alt="Logo">-->

                    <!--    <img class="logo-fold" src="{{ asset('assets/images/logo/logo.jpg') }}" alt="Logo">-->

                    <!--</a>-->

                </div>

                <div class="nav-wrap">

                    <ul class="nav-left">

                        <!--<li class="desktop-toggle">-->

                        <!--    <a href="javascript:void(0);">-->

                        <!--        <i class="anticon"></i>-->

                        <!--    </a>-->

                        <!--</li>-->

                        <!--<li class="mobile-toggle">-->

                        <!--    <a href="javascript:void(0);">-->

                        <!--        <i class="anticon"></i>-->

                        <!--    </a>-->

                        <!--</li>-->

                        {{-- <li>

                            <a href="javascript:void(0);" data-toggle="modal" data-target="#search-drawer">

                                <i class="anticon anticon-search"></i>

                            </a>

                        </li> --}}

                    </ul>

                    <ul class="nav-right">

                        

                        <li class="dropdown dropdown-animated scale-left">

                            <div class="pointer" data-toggle="dropdown">

                                <div class="avatar avatar-image  m-h-10 m-r-15">
                                    <!--{{ asset('assets/images/logo/favicon.png') }}-->

                                    <img src="https://xsgames.co/randomusers/avatar.php?g=pixel" alt="">

                                </div>

                            </div>

                            <div class="p-b-15 p-t-20 dropdown-menu pop-profile">

                                <div class="p-h-20 p-b-15 m-b-10 border-bottom">

                                    <div class="d-flex m-r-50">

                                        <div class="avatar avatar-lg avatar-image">

                                            <img src="https://xsgames.co/randomusers/avatar.php?g=pixel" alt="">

                                        </div>

                                        <div class="m-l-10">

                                            <p class="m-b-0 text-dark font-weight-semibold">{{auth()->user()->name}}</p>                                            

                                        </div>

                                    </div>

                                </div>

                                <a href="{{url('/user/view-profile')}}" class="dropdown-item d-block p-h-15 p-v-10">

                                    <div class="d-flex align-items-center justify-content-between">

                                        <div>

                                            <i class="anticon opacity-04 font-size-16 anticon-user"></i>

                                            <span class="m-l-10">Profile</span>

                                        </div>

                                        <i class="anticon font-size-10 anticon-right"></i>

                                    </div>

                                </a>

                                <a href="{{url('/user/change-password')}}" class="dropdown-item d-block p-h-15 p-v-10">

                                    <div class="d-flex align-items-center justify-content-between">

                                        <div>

                                            <i class="anticon opacity-04 font-size-16 anticon-lock"></i>

                                            <span class="m-l-10">Change Password</span>

                                        </div>

                                        <i class="anticon font-size-10 anticon-right"></i>

                                    </div>

                                </a>                               

                                <a href="{{ url('/logout') }}" class="dropdown-item d-block p-h-15 p-v-10">

                                    <div class="d-flex align-items-center justify-content-between">

                                        <div>

                                            <i class="anticon opacity-04 font-size-16 anticon-logout"></i>

                                            <span class="m-l-10">Logout</span>

                                        </div>

                                        <i class="anticon font-size-10 anticon-right"></i>

                                    </div>

                                </a>

                            </div>

                        </li>

                    </ul>

                </div>

            </div>

            <!-- Header END -->



            <!-- Side Nav START -->

            <div class="side-nav">

                <div class="side-nav-inner">

                    <ul class="side-nav-menu scrollable">

                        <li>

                            <a href="{{ url('/user/dashboard') }}">

                                <span class="icon-holder">

                                    <i class="anticon anticon-dashboard"></i>

                                </span>

                                <span class="title">Dashboard</span>



                            </a>

                        </li>

                        <!-- @if(Auth::user()->user_type == 1) -->
                        <!--<li>-->
                        <!--    <a href="{{url('/user/dopayin')}}">-->
                        <!--        <span class="icon-holder">-->
                        <!--            <i class="anticon anticon-user"></i>-->
                        <!--        </span>-->
                        <!--        <span class="title">Payin</span>-->
                        <!--    </a>-->
                        <!--</li>-->
                        <!-- @endif -->

                        <!--<li>-->
                        <!--    <a href="{{url('/user/dopayout')}}">-->
                        <!--        <span class="icon-holder">-->
                        <!--            <i class="anticon anticon-table"></i>-->
                        <!--            {{-- <Icon type="bank" /> --}}-->
                        <!--        </span>-->
                        <!--        <span class="title">Payout</span>-->
                        <!--    </a>-->
                        <!--</li>-->

                        <li>
                            <a href="{{url('/user/payrequest-list')}}">
                                <span class="icon-holder">
                                    <i class="anticon anticon-fund"></i>
                                </span>
                                <span class="title">Fund Request</span>
                            </a>
                        </li>   

                        <!--<li>-->
                        <!--    <a href="{{url('/user/bank-list')}}">-->
                        <!--        <span class="icon-holder">-->
                        <!--            <i class="anticon anticon-bank"></i>-->
                        <!--            {{-- <Icon type="bank" /> --}}-->
                        <!--        </span>-->
                        <!--        <span class="title">Add Bank</span>-->
                        <!--    </a>-->
                        <!--</li>-->

                        <!-- @if(Auth::user()->user_type == 1) -->
                        <li>
                            <a href="{{url('/user/wallet-report')}}">

                                <span class="icon-holder">

                                    <i class="anticon anticon-wallet"></i>

                                </span>

                                <span class="title">Wallet Report</span>



                            </a>
                        </li>
                        <!-- @endif -->

                        <!-- @if(Auth::user()->user_type == 1) -->
                        <!-- <li>
                            <a href="{{url('user/payin-report')}}">

                                <span class="icon-holder">

                                    <i class="anticon anticon-table"></i>

                                </span>

                                <span class="title">Payin Report</span>
                            </a>
                        </li> -->
                        <!-- @endif -->

                        <li>

                            <a href="{{url('/user/payout-report')}}">

                                <span class="icon-holder">

                                    <i class="anticon anticon-swap-right"></i>

                                </span>

                                <span class="title">Payout Report</span>



                            </a>

                        </li>

                        {{-- <li>

                            <a href="{{url('/user/wallet-topup-report')}}">

                                <span class="icon-holder">

                                    <i class="anticon anticon-table"></i>

                                </span>

                                <span class="title">Wallet Topup Report</span>



                            </a>

                        </li> --}}

                        <li>
                            <a href="{{url('/user/dev-setting')}}">
                                <span class="icon-holder">
                                    <i class="anticon anticon-setting"></i>
                                </span>
                                <span class="title">Devloper Setting</span>
                            </a>
                        </li>

                        <li>
                           <a href="{{url('user/api-docs')}}">
                               <span class="icon-holder">
                                   <i class="anticon anticon-read"></i>
                               </span>
                               <span class="title">Development Docs</span>
                           </a>
                        </li>

                        

                    </ul>

                </div>

            </div>

            <!-- Side Nav END -->
@include('staff/header')

            <!-- Page Container START -->

            <div class="page-container">

                <!-- Content Wrapper START -->

                <div class="main-content">

                    <div class="row">                        

                        <div class="col-md-6 col-lg-4">

                            <div class="card">

                                <div class="card-body">

                                    <div class="media align-items-center">

                                        <div class="avatar avatar-icon avatar-lg avatar-gold">

                                            <i class="anticon anticon-dollar"></i>

                                        </div>

                                        <div class="m-l-15">

                                            <h2 class="m-b-0">{{$usersWithBalances}}</h2>

                                            <p class="m-b-0 text-muted">Wallet balance</p>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="col-md-6 col-lg-4">

                            <div class="card">

                                <div class="card-body">

                                    <div class="media align-items-center">

                                        <div class="avatar avatar-icon avatar-lg avatar-green">

                                            <i class="anticon anticon-arrow-up"></i>

                                        </div>

                                        <div class="m-l-15">

                                            <h2 class="m-b-0">{{$todayTopUp}}</h2>

                                            <p class="m-b-0 text-muted">TopUp</p>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="col-md-6 col-lg-4">

                            <div class="card">

                                <div class="card-body">

                                    <div class="media align-items-center">

                                        <div class="avatar avatar-icon avatar-lg avatar-red">

                                            <i class="anticon anticon-arrow-down"></i>

                                        </div>

                                        <div class="m-l-15">

                                            <h2 class="m-b-0">{{$todayPayout}}</h2>

                                            <p class="m-b-0 text-muted">Payout</p>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-12 col-lg-12">

                            <div class="card">

                                <div class="card-body">

                                    <div class="d-flex justify-content-between align-items-center">

                                        <h5>Recent Transactions</h5>

                                        <div>

                                            <a href="{{url('/staff/wallet-report')}}" class="btn btn-sm btn-primary">View All</a>

                                        </div>

                                    </div>

                                    <div class="m-t-30">

                                        <div class="table-responsive">

                                            <table class="table table-hover table-bordered">

                                                <thead>

                                                    <tr>

                                                        <th>Type</th>

                                                        <th>Date</th>

                                                        <th>OrderId</th>

                                                        <th>User</th>                                                        

                                                        <th>Descrption</th>

                                                        <th>Amount</th>

                                                        <th>Wallet balance</th>

                                                    </tr>

                                                </thead>

                                                <tbody>

                                                    @if (!empty($usertransaction))

                                                        @foreach ($usertransaction as $rows)

                                                        <tr>

                                                            <td>

                                                                @if ($rows->type == "CREDIT")

                                                                    <button class="btn btn-icon btn-success">

                                                                        <i class="anticon anticon-arrow-down"></i>

                                                                    </button>

                                                                @else

                                                                    <button class="btn btn-icon btn-danger">

                                                                        <i class="anticon anticon-arrow-up"></i>

                                                                    </button>

                                                                @endif

                                                            </td>   

                                                            <td>{{$rows->created_at}}</td>

                                                            <td>{{$rows->orderId}}</td>

                                                            <td>{{$rows->user->name}}</td>

                                                                                                                             

                                                            <td>{{$rows->remark}}</td>

                                                            <td>{{$rows->amount}}</td>     

                                                            <td>{{$rows->walletBalance}}</td>                                           

                                                        </tr>

                                                        @endforeach

                                                    @endif

                                                </tbody>

                                            </table>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                       

                    </div>

                </div>

                <!-- Content Wrapper END -->

                <!-- model -->

                

               

@include('staff/footer')
@include('agent/header')
            <!-- Page Container START -->
            <div class="page-container">
                <!-- Content Wrapper START -->
                <div class="main-content">
                    <div class="row">
                        <div class="col-md-6 col-lg-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="media align-items-center">
                                        <div class="avatar avatar-icon avatar-lg avatar-green">
                                            <i class="anticon anticon-arrow-down"></i>
                                        </div>
                                        <div class="m-l-15">
                                            <h6 class="m-b-0">{{ $available_balance }}</h6>
                                            <p class="m-b-0 text-muted">Available Balance</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="media align-items-center">
                                        <div class="avatar avatar-icon avatar-lg avatar-green">
                                            <i class="anticon anticon-arrow-down"></i>
                                        </div>
                                        <div class="m-l-15">
                                            <h6 class="m-b-0">{{ $usable_balance }}</h6>
                                            <p class="m-b-0 text-muted">Usable Balance</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="media align-items-center">
                                        <div class="avatar avatar-icon avatar-lg avatar-green">
                                            <i class="anticon anticon-arrow-down"></i>
                                        </div>
                                        <div class="m-l-15">
                                            <h6 class="m-b-0">{{ $todayProfit }}</h6>
                                            <p class="m-b-0 text-muted">Today Profit</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="media align-items-center">
                                        <div class="avatar avatar-icon avatar-lg avatar-green">
                                            <i class="anticon anticon-arrow-down"></i>
                                        </div>
                                        <div class="m-l-15">
                                            <h6 class="m-b-0">{{ $totalProfit }}</h6>
                                            <p class="m-b-0 text-muted">Total Profit</p>
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
                                            <a href="{{url('/agent/wallet-report')}}" class="btn btn-sm btn-primary">View All</a>
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
                                                        <th>Status</th>
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
                                                            <td>{{$rows->status}}</td>                                           
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
                
               
@include('agent/footer')
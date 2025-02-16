@include('agent/header')
            <!-- Page Container START -->
            <div class="page-container">
                <!-- Content Wrapper START -->
                <div class="main-content ag-courses_box">
                    <div class="row">
                        <div class="col-md-6 col-lg-3 ag-courses_item">
                          <a href="#" class="ag-courses-item_link">
                            <div class="ag-courses-item_bg"></div>
                            <div class="ag-courses-item_title">
                              <i class="anticon anticon-user"></i>
                              <span>Users</span>
                            </div>
                            <div class="ag-courses-item_date-box">
                              <span class="ag-courses-item_date">
                                {{$totalUsers}}
                              </span>
                            </div>
                          </a>
                        </div>
                        <div class="col-md-6 col-lg-3 ag-courses_item">
                          <a href="#" class="ag-courses-item_link">
                            <div class="ag-courses-item_bg"></div>
                            <div class="ag-courses-item_title">
                              <i class="anticon anticon-dollar"></i>
                              <span>Wallet balance</span>
                            </div>
                            <div class="ag-courses-item_date-box">
                              <span class="ag-courses-item_date">
                                {{$usersWithBalances}}
                              </span>
                            </div>
                          </a>
                        </div>
                        <div class="col-md-6 col-lg-3 ag-courses_item">
                          <a href="#" class="ag-courses-item_link">
                            <div class="ag-courses-item_bg"></div>
                            <div class="ag-courses-item_title">
                              <i class="anticon anticon-dollar"></i>
                              <span>Today Payin</span>
                            </div>
                            <div class="ag-courses-item_date-box">
                              <span class="ag-courses-item_date">
                                {{$todayPayin}}
                              </span>
                            </div>
                          </a>
                        </div>
                        <div class="col-md-6 col-lg-3 ag-courses_item">
                          <a href="#" class="ag-courses-item_link">
                            <div class="ag-courses-item_bg"></div>
                            <div class="ag-courses-item_title">
                              <i class="anticon anticon-dollar"></i>
                              <span>Today Payout</span>
                            </div>
                            <div class="ag-courses-item_date-box">
                              <span class="ag-courses-item_date">
                                {{$todayPayout}}
                              </span>
                            </div>
                          </a>
                        </div>
                        <div class="col-md-6 col-lg-3 ag-courses_item">
                          <a href="#" class="ag-courses-item_link">
                            <div class="ag-courses-item_bg"></div>
                            <div class="ag-courses-item_title">
                            <i class="anticon anticon-dollar"></i>
                              <span>Today Profit</span>
                            </div>
                            <div class="ag-courses-item_date-box">
                              <span class="ag-courses-item_date">
                                {{$todayProfit}}
                              </span>
                            </div>
                          </a>
                        </div>
                        <div class="col-md-6 col-lg-3 ag-courses_item">
                          <a href="#" class="ag-courses-item_link">
                            <div class="ag-courses-item_bg"></div>
                            <div class="ag-courses-item_title">
                            <i class="anticon anticon-dollar"></i>
                              <span>Total Profit</span>
                            </div>
                            <div class="ag-courses-item_date-box">
                              <span class="ag-courses-item_date">
                                {{$totalProfit}}
                              </span>
                            </div>
                          </a>
                        </div>
                        <div class="col-md-6 col-lg-3 ag-courses_item">
                          <a href="#" class="ag-courses-item_link">
                            <div class="ag-courses-item_bg"></div>
                            <div class="ag-courses-item_title">
                              <i class="anticon anticon-dollar"></i>
                              <span>Total Payin</span>
                            </div>
                            <div class="ag-courses-item_date-box">
                              <span class="ag-courses-item_date">
                                {{$totalPayin}}
                              </span>
                            </div>
                          </a>
                        </div>
                        <div class="col-md-6 col-lg-3 ag-courses_item">
                          <a href="#" class="ag-courses-item_link">
                            <div class="ag-courses-item_bg"></div>
                            <div class="ag-courses-item_title">
                              <i class="anticon anticon-dollar"></i>
                              <span>Total Payout</span>
                            </div>
                            <div class="ag-courses-item_date-box">
                              <span class="ag-courses-item_date">
                                {{$totalPayout}}
                              </span>
                            </div>
                          </a>
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
                                                                @if ($rows['type'] == "CREDIT")
                                                                    <button class="btn btn-icon btn-success">
                                                                        <i class="anticon anticon-arrow-down"></i>
                                                                    </button>
                                                                @else
                                                                    <button class="btn btn-icon btn-danger">
                                                                        <i class="anticon anticon-arrow-up"></i>
                                                                    </button>
                                                                @endif
                                                            </td>   
                                                            <td>{{$rows['created_at']}}</td>
                                                            <td>{{$rows['orderId']}}</td>
                                                            <td>{{$rows['userName']}}</td>
                                                                                                                             
                                                            <td>{{$rows['remark']}}</td>
                                                            <td>{{$rows['amount']}}</td>     
                                                            <td>{{$rows['walletBalance']}}</td> 
                                                            <td>{{$rows['status']}}</td>                                           
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
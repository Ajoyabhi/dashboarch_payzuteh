@include('admin/header')
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
                              <span>Total Users</span>
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
                              <span>Available balance</span>
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
                              <span>Usable Balance</span>
                            </div>
                            <div class="ag-courses-item_date-box">
                              <span class="ag-courses-item_date">
                              {{round(($usersWithBalances)-($usableBalnce), 2)}}
                              </span>
                            </div>
                          </a>
                        </div>
                        <div class="col-md-6 col-lg-3 ag-courses_item">
                          <a href="#" class="ag-courses-item_link">
                            <div class="ag-courses-item_bg"></div>
                            <div class="ag-courses-item_title">
                              <i class="anticon anticon-arrow-down"></i>
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
                              <i class="anticon anticon-arrow-up"></i>
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
                              <i class="anticon anticon-arrow-down"></i>
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
                              <i class="anticon anticon-arrow-up"></i>
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
                                    <div class="row">
                                        <div class="col-4">
                                            <h5 class="card-heading">Recent Transactions</h5>
                                        </div>
                                        <div class="col text-right">
                                            <div class="container">
                                                  <a href="{{url('/admin/wallet-report')}}" class="new-button">View All</a>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="m-t-30">
                                        <div class="tbl-header">
                                            <table class="table-bordered" cellpadding="0" cellspacing="0" border="0">
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
                                            </table>
                                          </div>
                                          <div class="tbl-content">
                                            <table class="table-bordered" cellpadding="0" cellspacing="0" border="0">
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
                
               
@include('admin/footer')
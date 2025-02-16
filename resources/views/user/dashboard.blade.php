@include('user/header')

            <!-- Page Container START -->

            <div class="page-container">

                <!-- Content Wrapper START -->

                <div class="main-content ag-courses_box">

                    <div class="row">
                        
                        <div class="col-md-6 col-lg-3 ag-courses_item">
                          <a href="#" class="ag-courses-item_link">
                            <div class="ag-courses-item_bg"></div>
                            <div class="ag-courses-item_title">
                            <i class="anticon anticon-dollar"></i>
                              <span>Available balance</span>
                            </div>
                            <div class="ag-courses-item_date-box">
                              <span class="ag-courses-item_date">
                                {{round($usersBalances,2)}}
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
                              <!-- {{round(($usersBalances)-($user->lien)-($user->rolling_reserve), 2)}} -->
                              {{round($user->settlement, 2)}}
                              </span>
                            </div>
                          </a>
                        </div>

                        <div class="col-md-6 col-lg-3 ag-courses_item">
                          <a href="#" class="ag-courses-item_link">
                            <div class="ag-courses-item_bg"></div>
                            <div class="ag-courses-item_title">
                              <i class="anticon anticon-user"></i>
                              <span>Number of Trades</span>
                            </div>
                            <div class="ag-courses-item_date-box">
                              <span class="ag-courses-item_date">
                              {{$totalTrades}}
                              </span>
                            </div>
                          </a>
                        </div>

                        <div class="col-md-6 col-lg-3 ag-courses_item">
                          <a href="#" class="ag-courses-item_link">
                            <div class="ag-courses-item_bg"></div>
                            <div class="ag-courses-item_title">
                              <i class="anticon anticon-arrow-down"></i>
                              <span>Payin</span>
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
                              <span>Payout</span>
                            </div>
                            <div class="ag-courses-item_date-box">
                              <span class="ag-courses-item_date">
                              {{$totalPayout}}
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

                        <!-- <div class="col-md-6 col-lg-3 ag-courses_item">
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
                        </div> -->

                        <!-- <div class="col-md-6 col-lg-3 ag-courses_item">
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
                        </div> -->

                        <div class="row" style="width:100%">
                            <div class="col-md-12 col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 col-lg-6" style="text-align: center;">
                                                <h4>Today Transaction</h4>
                                                <div style="width: 50%; margin: auto;">
                                                    <canvas id="pieChart"></canvas>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-6" style="text-align: center;">
                                                <h4>Total Transaction</h4>
                                                <div style="width: 50%; margin: auto;">
                                                    <canvas id="pieChart1"></canvas>
                                                </div>
                                            </div>
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

                                            <a href="{{url('/user/wallet-report')}}" class="btn btn-sm btn-primary">View All</a>

                                        </div>

                                    </div>

                                    <div class="m-t-30">

                                        <div class="table-responsive">

                                            <table class="table table-hover table-bordered">

                                                <thead>

                                                    <tr>

                                                        <th>Date</th>                                                        

                                                        <th>Type</th>

                                                        <th>Descrption</th>

                                                        <th>Amount</th>

                                                        <th>Wallet balance</th>
                                                        <th>Callback</th>
                                                        <th>Status</th>

                                                    </tr>

                                                </thead>

                                                <tbody>

                                                    @if (!empty($usertransaction))

                                                        @foreach ($usertransaction as $rows)

                                                        <tr>

                                                            <td>{{date("d-m-Y h:i:s", strtotime($rows['created_at']))}}</td>                                                            

                                                            <td>

                                                                @if ($rows['type'] == "CREDIT")

                                                                    <button class="btn btn-icon btn-success">

                                                                        <i class="anticon anticon-arrow-up"></i>

                                                                    </button>

                                                                @else

                                                                    <button class="btn btn-icon btn-danger">

                                                                        <i class="anticon anticon-arrow-down"></i>

                                                                    </button>

                                                                @endif

                                                            </td>                                                                    

                                                            <td>{{$rows['remark']}}</td>

                                                            <td>{{$rows['amount']}}</td>     

                                                            <td>{{$rows['walletBalance']}}</td> 
                                                            <td>{{isset($rows['callbackReceived']) && $rows['callbackReceived'] ? "Received" :"Not Received"}}</td> 
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

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>      
                <script>
        var ctx = document.getElementById('pieChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: @json($data['labels']),
                datasets: [{
                    data: @json($data['todayData']),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                    ],
                    borderWidth: 1
                }]
            },
        });
        
        var ctx1 = document.getElementById('pieChart1').getContext('2d');
        var myChart = new Chart(ctx1, {
            type: 'pie',
            data: {
                labels: @json($data['labels']),
                datasets: [{
                    data: @json($data['totalData']),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                    ],
                    borderWidth: 1
                }]
            },
        });
    </script>
               

@include('user/footer')
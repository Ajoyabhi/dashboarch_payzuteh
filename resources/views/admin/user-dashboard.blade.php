@include('admin/header')
<style>
.accordion {
  background-color: #eee;
  color: #444;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 15px;
  transition: 0.4s;
}

.active, .accordion:hover {
  background-color: #ccc; 
}

.panel {
  padding: 0 18px;
  display: none;
  background-color: white;
  overflow: hidden;
}
</style>
<div class="page-container">
    <!-- Content Wrapper START -->
    <div class="main-content ag-courses_box">
            <div class="row">
                        
                        <div class="col-md-6 col-lg-3 ag-courses_item">
                          <a href="#" class="ag-courses-item_link">
                            <div class="ag-courses-item_bg"></div>
                            <div class="ag-courses-item_title">
                              <i class="anticon anticon-dollar"></i>
                              <span>Available Balance</span>
                            </div>
                            <div class="ag-courses-item_date-box">
                              <span class="ag-courses-item_date">
                                {{round($user->wallet, 2)}}
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
                              {{round($user->settlement, 2)}}
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
            <div class="row" style="width:100%">
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-lg-6" style="text-align: center;">
                                    <h4>Today Transaction</h4>
                                    <div style="width: 50%; margin: auto;">
                                        <canvas id="pieChart"></canvas>
                                        <!--<canvas id="myChart" style="width:100%;max-width:600px"></canvas>-->
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
            <div class="container card mt-4 pt-5">	
              <button class="accordion active"><i class="fa fa-caret-down" aria-hidden="true"></i> <b>Callback Url</b></button>
              <div class="panel pt-2" style="display: block;">
                <p><b style="color:black;">Payin Callback : </b>{{ $user->payin_callback }}</p>
                <p><b style="color:black;">Payin Callback : </b>{{ $user->payout_callback }}</p>
              </div>

              <button class="accordion active"><i class="fa fa-caret-down" aria-hidden="true"></i> <b>IP Address</b></button>
              <div class="panel p-0" style="display: block;">
                  @if(session('ip-success'))
                <div class="alert alert-success mb-1 mt-1">
                    {{ session('ip-success') }}
                </div>
                @endif
                @if(session('ip-error'))
                <div class="alert alert-danger mb-1 mt-1">
                    {{ session('ip-error') }}
                </div>
                @endif
              <table class="table table-hover mt-2">
                <thead>
                   <tr>
                    <th >IP</th>
                     <th >Date</th>
                     <th >Action</th>
                  
                   </tr>
                </thead>
                <tbody>
                  @if(!@empty($user_ips))
                    @foreach($user_ips as $user_ip)
                    <tr>
                        <td>{{ $user_ip->ipAddress }}</td>
                        <td>{{ $user_ip->created_at }}</td> 
                        <td><button class="btn btn-danger btn-sm ip_delete_btn" data-id="{{ $user_ip->id }}"><a href="{{ url('admin/delete-user-ip/'.$user_ip->id) }}">Delete</a></button></td>                    
                    </tr> 
                    @endforeach
                  @endif    
              </tbody>
            </table>
</div>

            <button class="accordion active"><i class="fa fa-caret-down" aria-hidden="true"></i> <b>UPI Charges</b></button>
            <div class="panel p-0" style="display: block;">
                @if(session('success'))
                <div class="alert alert-success mb-1 mt-1">
                    {{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger mb-1 mt-1">
                    {{ session('error') }}
                </div>
                @endif
                <table class="table table-hover mt-2">
                  <thead>
                    <tr>
                        <th>Amount start</th>
                        <th>Amount End</th>
                        <th>Admin Payin Charge</th>
                        <th>Admin Payout Charge</th>
                        <th>Agent Payin Charge</th>
                        <th>Agent Payout Charge</th>
                        <th>Total Payin Charge</th>
                        <th>Total Payout Charge</th>
                        <th>Payin Charge type</th>
                        <th>Payout Charge type</th>
                        <th>Date</th>
                        <th>Action</td>
                    </tr>
                  </thead>
                  <tbody> 
                  @if(!@empty($userCharge))
                    @foreach($userCharge as $rows)            
                    <tr>
                      <td>{{$rows->start_amount}}</td>
                      <td>{{$rows->end_amount}}</td>
                      <td>{{$rows->payin_charge}}</td>
                      <td>{{$rows->payout_charge}}</td>
                      <td>{{$rows->agent_payin_charge}}</td>
                      <td>{{$rows->agent_payout_charge}}</td>
                      <td>{{$rows->payin_total_charge}}</td>
                      <td>{{$rows->payout_total_charge}}</td>
                      <td>{{$rows->payin_charge_type}}</td>
                      <td>{{$rows->payout_charge_type}}</td>
                      <td>{{$rows->created_at}}</td>
                      <td><button class="btn btn-danger btn-sm user_charge_delete_btn" data-id="{{ $rows->id }}"><a href="{{ url('admin/delete-admin-user-charge/'.$rows->id) }}">Delete</a></button></td> 
                    </tr> 
                    @endforeach
                  @endif
                  </tbody>
                </table>
            </div>
	
</div>
        </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    <script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.display === "block") {
      panel.style.display = "none";
    } else {
      panel.style.display = "block";
    }
  });
}
  


</script>
@include('admin/footer')
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
                              {{round(($user->wallet)-($user->lien)-($user->rolling_reserve), 2)}}
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
  <p><b style="color:black;">Payin Callback : </b>https://channel.skyrummy.in/poi/pay/pexnotify</p>
  <p><b style="color:black;">Payin Callback : </b>https://channel.skyrummy.in/poi/dai/pexnotify</p>
</div>

<button class="accordion active"><i class="fa fa-caret-down" aria-hidden="true"></i> <b>IP Address</b></button>
<div class="panel p-0" style="display: block;">
  <table class="table table-hover mt-2">
				
            
                <thead>
                   <tr>
                    <th >IP</th>
                     <th >Date</th>
                     <th >Delete</th>
                  
                   </tr>
                </thead>
                <tbody>
                                             <tr>
                            <td>13.127.226.46</td>
                            <td>2024-02-29 10:53:30</td>
							<td><a href="https://api.paydexsolutions.com/admin/delete-ip/6" onclick="return confirm('Are you sure?')"><button class="btn btn-danger btn-sm">Delete</button></a>							
                             </td>                         
                        </tr> 
                                        <tr>
                            <td>65.0.118.126</td>
                            <td>2024-02-29 10:52:53</td>
							<td><a href="https://api.paydexsolutions.com/admin/delete-ip/5" onclick="return confirm('Are you sure?')"><button class="btn btn-danger btn-sm">Delete</button></a>							
                             </td>                         
                        </tr> 
                                </tbody>
            </table>
</div>

<button class="accordion active"><i class="fa fa-caret-down" aria-hidden="true"></i> <b>UPI Charges</b></button>
<div class="panel p-0" style="display: block;">
  <table class="table table-hover mt-2">
                <thead>
                   <tr>
                    <th >Amount Start</th>
                     <th>Amount End</th>
                       <th >My Charge</th>
                       <th >Payin Charge</th>
                     <th >Payin Charge Type</th>
                     <th >My Charge</th>
                      <th>Payout Charge</th>
                     <th>Payout Charge Type</th>
                      <th >Action</th>
                     
                  
                   </tr>
                </thead>
                <tbody>
                                          
                        <tr>
                              
                            <td>  401.00</td>
                            <td> 100000.00</td>
                             <td> 1.50 </td>
                             <td> 2.85 </td>
                            <td>P</td>
                            <td> 1.00 </td>
                            <td> 1.90 </td>
                            <td>P</td>
                            <td><a href="https://api.paydexsolutions.com/admin/delete-charge/21" onclick="return confirm('Are you sure?')"><button class="btn btn-danger btn-sm">Delete</button></a> 

                             
                           
                                                      
                        </td></tr> 
                                              
                        <tr>
                              
                            <td>  1.00</td>
                            <td> 400.00</td>
                             <td> 6.00 </td>
                             <td> 11.50 </td>
                            <td>F</td>
                            <td> 6.00 </td>
                            <td> 9.60 </td>
                            <td>F</td>
                            <td><a href="https://api.paydexsolutions.com/admin/delete-charge/20" onclick="return confirm('Are you sure?')"><button class="btn btn-danger btn-sm">Delete</button></a> 

                             
                           
                                                      
                        </td></tr> 
                           
						<!-- <tr>
                            <td>Payout upto 500 - 2.85 F + 18% GST<br>
                            Payout - 1.90% + 18% GST
                           </td>
                         
                             <td>Payout upto 500 - 6.50 F (Included GST)<br>
                            Payout Above 500 - 1.47% (Included GST)</td>
                           
                                                      
                        </tr> -->

               
                </tbody>
            </table>
</div>
	
</div>
        </div>
</div>

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
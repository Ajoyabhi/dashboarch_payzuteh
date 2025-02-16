@include('admin/header')
<style>
        .vertical {
            border-left: 6px solid white;
            height: 50px;
            position: absolute;
            left: 50%;
        }
    </style>
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
                            <!--<div class="vertical"></div>-->
                            <div class="ag-courses-item_date-box">
                              <span class="ag-courses-item_date">
                                {{round($usersWithBalances,2)}}
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
                        <div class="col-lg-6 col-sm-6 mb-2">
                          <div class="card">
                            <div class="card-header pb-1">
                              <h4 class="card-title mb-1">Today Payin Top Performer </h4>
                            </div>
                            <div class="card-body">
                              <div class="row d-flex align-items-center justify-content-between">
                                <div class="col-12">
                                  <div class="m-t-30">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Txn. Count</th>
                                                    <th>User Name</th>
                                                    <th>Amount</th>
                                                    <th>Profit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($todayTopPayinUsersResult))
                                                  @foreach ($todayTopPayinUsersResult as $rows)
                                                  <tr>
                                                    <td>{{$rows['numberOfTransaction']}}</td>
                                                    <td>{{$rows['_id']['userName']}}</td>
                                                    <td>{{$rows['transactionAmount']}}</td>
                                                    <td>{{$rows['totalPayinProfit']}}</td>
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
                        <div class="col-lg-6 col-sm-6 mb-2">
                          <div class="card">
                            <div class="card-header pb-1">
                              <h4 class="card-title mb-1">Today Payout Top Performer</h4>
                            </div>
                            <div class="card-body">
                              <div class="row d-flex align-items-center justify-content-between">
                                <div class="col-12">
                                  <div class="m-t-30">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Txn. Count</th>
                                                    <th>User Name</th>
                                                    <th>Amount</th>
                                                    <th>Profit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($todayTopPayoutUsersResult))
                                                  @foreach ($todayTopPayoutUsersResult as $rows)
                                                  <tr>
                                                    <td>{{$rows['numberOfTransaction']}}</td>
                                                    <td>{{$rows['_id']['userName']}}</td>
                                                    <td>{{$rows['transactionAmount']}}</td>
                                                    <td>{{$rows['totalPayinProfit']}}</td>
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
                        <div class="col-lg-6 col-sm-6 mb-2">
                          <div class="card">
                            <div class="card-header pb-1">
                              <h4 class="card-title mb-1">Total Payin Top Performer </h4>
                            </div>
                            <div class="card-body">
                              <div class="row d-flex align-items-center justify-content-between">
                                <div class="col-12">
                                  <div class="m-t-30">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Txn. Count</th>
                                                    <th>User Name</th>
                                                    <th>Amount</th>
                                                    <th>Profit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($totalTopPayinUsersResult))
                                                  @foreach ($totalTopPayinUsersResult as $rows)
                                                  <tr>
                                                    <td>{{$rows['numberOfTransaction']}}</td>
                                                    <td>{{$rows['_id']['userName']}}</td>
                                                    <td>{{$rows['transactionAmount']}}</td>
                                                    <td>{{$rows['totalPayinProfit']}}</td>
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
                        <div class="col-lg-6 col-sm-6 mb-2">
                          <div class="card">
                            <div class="card-header pb-1">
                              <h4 class="card-title mb-1">Total Payout Top Performer</h4>
                            </div>
                            <div class="card-body">
                              <div class="row d-flex align-items-center justify-content-between">
                                <div class="col-12">
                                  <div class="m-t-30">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Txn. Count</th>
                                                    <th>User Name</th>
                                                    <th>Amount</th>
                                                    <th>Profit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($totalTopPayoutUsersResult))
                                                  @foreach ($totalTopPayoutUsersResult as $rows)
                                                  <tr>
                                                    <td>{{$rows['numberOfTransaction']}}</td>
                                                    <td>{{$rows['_id']['userName']}}</td>
                                                    <td>{{$rows['transactionAmount']}}</td>
                                                    <td>{{$rows['totalPayinProfit']}}</td>
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
                                            <div class="container" style="display:flex;justify-content: end;">
                                                 <select class="btn mr-2" onchange="" name="cars" id="cars">
                                                  <option value="ALL">ALL</option>
                                                  <option value="PENDING">PENDING</option>
                                                  <option value="SUCCESS">SUCCESS</option>
                                                  <option value="FAILED">FAILED</option>
                                                 </select>
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
                                                  <th>Usable Balence</th>
                                                  <th>Callback</th>
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
                                                            <td>{{date("d-m-Y h:i:s", strtotime($rows['created_at']))}}</td>
                                                            <td>{{$rows['orderId']}}</td>
                                                            <td>{{isset($rows['userName']) ? $rows['userName'] : "NA"}}</td>
                                                                                                                             
                                                            <td>{{$rows['remark']}}</td>
                                                            <td>{{$rows['amount']}}</td>     
                                                            <td>{{$rows['walletBalance']}}</td> 
                                                            <td>{{$rows['closingSettlementBalence']}}</td>
                                                            <td>{{$rows['status']}}</td> 
                                                            <td>{{isset($rows['callbackReceived']) && $rows['callbackReceived'] ? "Sent to client" :"Not Received"}}</td> 
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
                <script type="text/javascript">
window.onload = function () {

var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	title:{
		text: "Transaction Status This week"
	},
	axisY: {
		title: "Transaction Count",
		includeZero: true
	},
	legend: {
		cursor:"pointer",
		itemclick : toggleDataSeries
	},
	toolTip: {
		shared: true,
		content: toolTipFormatter
	},
	data: [{
		type: "bar",
		showInLegend: true,
		name: "Success",
		color: "green",
		dataPoints: [
			{ y: 243, label: "Mon" },
			{ y: 236, label: "Tues" },
			{ y: 243, label: "Wed" },
			{ y: 273, label: "Thurs" },
			{ y: 269, label: "Fri" },
			{ y: 196, label: "Sat" },
			{ y: 1118, label: "Sun" }
		]
	},
	{
		type: "bar",
		showInLegend: true,
		name: "Pending",
		color: "orange",
		dataPoints: [
			{ y: 212, label: "Mon" },
			{ y: 186, label: "Tues" },
			{ y: 272, label: "Wed" },
			{ y: 299, label: "Thurs" },
			{ y: 270, label: "Fri" },
			{ y: 165, label: "Sat" },
			{ y: 896, label: "Sun" }
		]
	},
	{
		type: "bar",
		showInLegend: true,
		name: "Failed",
		color: "red",
		dataPoints: [
			{ y: 236, label: "Mon" },
			{ y: 172, label: "Tues" },
			{ y: 309, label: "Wed" },
			{ y: 302, label: "Thurs" },
			{ y: 285, label: "Fri" },
			{ y: 188, label: "Sat" },
			{ y: 788, label: "Sun" }
		]
	}]
});
chart.render();

function toolTipFormatter(e) {
	var str = "";
	var total = 0 ;
	var str3;
	var str2 ;
	for (var i = 0; i < e.entries.length; i++){
		var str1 = "<span style= \"color:"+e.entries[i].dataSeries.color + "\">" + e.entries[i].dataSeries.name + "</span>: <strong>"+  e.entries[i].dataPoint.y + "</strong> <br/>" ;
		total = e.entries[i].dataPoint.y + total;
		str = str.concat(str1);
	}
	str2 = "<strong>" + e.entries[0].dataPoint.label + "</strong> <br/>";
	str3 = "<span style = \"color:Tomato\">Total: </span><strong>" + total + "</strong><br/>";
	return (str2.concat(str)).concat(str3);
}

function toggleDataSeries(e) {
	if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
		e.dataSeries.visible = false;
	}
	else {
		e.dataSeries.visible = true;
	}
	chart.render();
}

}
</script>
               
@include('admin/footer')
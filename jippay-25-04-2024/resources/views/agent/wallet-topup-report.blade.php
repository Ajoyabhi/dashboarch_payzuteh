@include('agent/header')
<!-- Page Container START -->
<div class="page-container">
    <!-- Content Wrapper START -->
    <div class="main-content"> 
        <div class="row">
            
                <div class="col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="media align-items-center">
                                <div class="avatar avatar-icon avatar-lg avatar-green">
                                    <i class="anticon anticon-arrow-down"></i>
                                </div>
                                <div class="m-l-15">
                                    <h6 class="m-b-0">{{$todayTopUp}}</h6>
                                    <p class="m-b-0 text-muted">Today TopUp</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="media align-items-center">
                                <div class="avatar avatar-icon avatar-lg avatar-green">
                                    <i class="anticon anticon-arrow-down"></i>
                                </div>
                                <div class="m-l-15">
                                    <h6 class="m-b-0">{{$totalTopUp}}</h6>
                                    <p class="m-b-0 text-muted">Total TopUp</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="horizontal-form">
                    <form action="{{ url('/agent/topup-report-export') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-6 mb-6">
                                <input id="date" type="date" class="form-control" name="txndate" value="">
                            </div>

                            <div class="col-md-2  mb-2">
                                <div class="form-group text-left">
                                    <!-- <button type="submit" class="btn btn-md btn-primary" name="submit" value="EXPORT">Export</button> -->
                                    <button type="submit" class="btn btn-md btn-primary" name="submit" value="VIEW">View</button>
                                </div>                                        
                            </div>                                    
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success mb-1 mt-1">
                                {{ session('success') }}
                            </div>
                        @endif
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Wallet TopUp Report</h5>                                        
                        </div>
                        <div class="m-t-30">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="datatable">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Amount</th>
                                            <!-- <th>Charge</th>
                                            <th>Gst</th>
                                            <th>Net Amount</th> -->
                                            <th>Utr</th>                                                                                                
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($walletTopup))
                                            @foreach ($walletTopup as $rows)
                                            <tr>
                                                <td>{{$rows->user->name}}</td>
                                                <td>{{$rows->amount}}</td>
                                                <!-- <td>{{$rows->charge}}</td>
                                                <td>{{$rows->gst}}</td>
                                                <td>{{$rows->totalAmount}}</td>   -->
                                                <td>{{$rows->utr}}</td>                                                 
                                                <td>{{$rows->created_at}}</td>                                                                                                                                                     
                                            </tr>                                            
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                
                            </div>
                            {{-- {{$usertransaction->links()}} --}}
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <!-- Content Wrapper END -->
    <!-- model -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js"></script>
    

<script type="text/javascript">
    $(document).ready(function () {
        let dataTable = new DataTable('#datatable', {
            ordering: false,
            dom: 'Bfrtip',
            buttons: [
                'csv', 'excel', 'pdf'
            ]
        });
    });
</script>

@include('agent/footer')
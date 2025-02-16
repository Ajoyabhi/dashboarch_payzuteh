@include('agent/header')
<div class="page-container">
    <!-- Content Wrapper START -->
    <div class="main-content">        
        <div class="card">
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success mb-1 mt-1">
                    {{ session('success') }}
                </div>
                @endif
                <div class="row">
                    <div class="col-md-12 col-lg-12">                        
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Charge Setting</h5>
                            <div>
                                <button data-toggle="modal" data-target="#modelCharge" class="btn btn-sm btn-primary">Add Charge</button>
                            </div>
                        </div>
                        <div class="m-t-30">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!@empty($userCharge))
                                            @foreach ($userCharge as $rows)
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

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 col-lg-12">                        
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Platform Charge</h5>
                            <div>
                                <button data-toggle="modal" data-target="#modelPlatformCharge"  class="btn btn-sm btn-primary">Add platform charge</button>
                            </div>
                        </div>
                            <div class="m-t-30">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Charge(%)</th> 
                                                <th>GST(%)</th>                                                
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!@empty($PlatformCharge))
                                                @foreach ($PlatformCharge as $rows)
                                                    <tr>
                                                        <td>{{$rows->charge}}</td>  
                                                        <td>{{$rows->gst}}</td>                                                      
                                                        <td>{{$rows->created_at}}</td>
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

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 col-lg-12">                        
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Ip Address Setting</h5>
                            <div>
                                <button data-toggle="modal" data-target="#modelIp"  class="btn btn-sm btn-primary">Add IP</button>
                            </div>
                        </div>
                            <div class="m-t-30">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <tr>                                                
                                                <th>Ip Address</th>                                                
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!@empty($userIps))
                                                @foreach ($userIps as $rows)
                                                    <tr>
                                                        <td>{{$rows->ipAddress}}</td>                                                        
                                                        <td>{{$rows->created_at}}</td>
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

 <!-- model charge-->
 <div class="modal fade" id="modelCharge">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Add User Charge</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <i class="anticon anticon-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{url('agent/save-user-charge')}}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputEmail4">Amount Start</label>
                            <input type="text" class="form-control" name="start_amount" placeholder="Amount Start" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputPassword4">Amount End</label>
                            <input type="text" class="form-control" name="end_amount" placeholder="Amount End" required>
                        </div>
                   
                        <div class="form-group col-md-6">
                            <label for="inputAddress">Payin Charge</label>
                            <input type="text" class="form-control" name="agent_payin_charge" placeholder="Payin Charge" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputAddress2">Payin Charge Type</label>
                            <select class="form-control" name="payin_charge_type">
                                <option value="P">Percent(%)</option>
                                <option value="F">Flat</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputAddress">payout Charge</label>
                            <input type="text" class="form-control" name="agent_payout_charge" placeholder="payout Charge" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputAddress2">payout Charge Type</label>
                            <select class="form-control" name="payout_charge_type">
                                <option value="P">Percent(%)</option>
                                <option value="F">Flat</option>
                            </select>
                        </div>
                        <input type="hidden" name="userid" value="{{$userId}}"/>
                    </div>                                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Charge</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- model Ip-->
<div class="modal fade" id="modelIp">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Add IP Address</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <i class="anticon anticon-close"></i>
                </button>
            </div>
                <div class="modal-body">
                <form action="{{url('agent/save-user-ip')}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="inputAddress">IP Address</label>
                    <input type="text" class="form-control" name="ipaddress" placeholder="IP Address">
                </div>
                <input type="hidden" name="userid" value="{{$userId}}"/>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Ip</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Platform Charge-->
<div class="modal fade" id="modelPlatformCharge">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Add Platform Charge</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <i class="anticon anticon-close"></i>
                </button>
            </div>
                <div class="modal-body">
                <form action="{{url('agent/save-user-platform-charge')}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="inputAddress">Platform Charge (%)</label>
                    <input type="text" class="form-control" name="platform_charge" placeholder="Platform Charge" required>
                </div>
                <div class="form-group">
                    <label for="inputAddress">Gst (%)</label>
                    <input type="text" class="form-control" name="gst" placeholder="Gst" value="18" required>
                </div>
                <input type="hidden" name="userid" value="{{$userId}}"/>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


@include('agent/footer')
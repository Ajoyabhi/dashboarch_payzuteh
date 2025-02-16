@include('admin/header')
<div class="page-container">
    <!-- Content Wrapper START -->
    <div class="main-content">        
        @if(session('success'))
        <div class="alert alert-success mb-1 mt-1">
            {{ session('success') }}
        </div>
        @endif

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
                <form action="{{url('admin/save-satff-ip')}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="inputAddress">IP Address</label>
                    <input type="text" class="form-control" name="ipaddress" placeholder="IP Address">
                </div>
                <input type="hidden" name="userid" value="{{$userid}}"/>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Ip</button>
                </div>
            </form>
        </div>
    </div>
</div>



@include('admin/footer')
@include('agent/header')
<div class="page-container">
    <!-- Content Wrapper START -->
    <div class="main-content">
        <div class="page-header">
            <h2 class="header-title">Users</h2>
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{url('/agent/dashboard')}}" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Home</a>                    
                    <span class="breadcrumb-item active">Users</span>
                </nav>
            </div>
        </div>
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
                            <h5>Users</h5>
                            <div>

                                <a href="{{url('/agent/add-agent')}}" class="btn btn-sm btn-primary">Add User</a>

                            </div>
                        </div>
                            <div class="m-t-30">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Wallet Bal</th>
                                                <th>Mobile</th>
                                                <th>Email</th>
                                                <th>Company Name</th>
                                                <th>Status</th>                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($user as $rows)
                                                <tr>
                                                    <td>{{$rows->name}}</td>
                                                    <td>{{$rows->wallet}}</td>
                                                    <td>{{$rows->mobile}}</td>
                                                    <td>{{$rows->email}}</td>    
                                                    <td>{{$rows->company_name}}</td>   
                                                    <td>
                                                        @if($rows->status == 1)
                                                            <button class="btn btn-sm btn-success" onClick="changeStatus({{$rows->id}},'DEACTIVE','status')">ACTIVE</button>
                                                        @else
                                                            <button class="btn btn-sm btn-danger" onClick="changeStatus({{$rows->id}},'ACTIVE','status')">DEACTIVE</button>
                                                        @endif
                                                    </td>                                       
                                                </tr>
                                            @endforeach                                            
                                        </tbody>
                                    </table>
                                    {!! $user->links() !!}
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('agent/footer')
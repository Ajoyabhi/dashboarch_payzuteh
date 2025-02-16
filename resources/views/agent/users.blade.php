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

                                <a href="{{url('/agent/add-user')}}" class="btn btn-sm btn-primary">Add User</a>

                            </div>
                        </div>
                            <div class="m-t-30">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered" id="datatable">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Wallet Bal</th>
                                                <th>Mobile</th>
                                                <th>Email</th>
                                                <th>Company Name</th>
                                                <th>Status</th>  
                                                <th>Action</th>                                                 
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
                                                    <td>                                                    

                                                        <a class="btn btn-icon btn-success" href="{{url('agent/manage-user-charge/'.$rows->id)}}">

                                                            <i class="anticon anticon-plus"></i>

                                                        </a>

                                                        <a class="btn btn-icon btn-secondary" href="{{url('/agent/user-dashboard/'.$rows->id)}}">

                                                            <i class="anticon anticon-eye" ></i>

                                                        </a>

                                                        <button class="btn btn-icon btn-primary" data-toggle="modal" data-target="#setting_{{$rows->id}}">

                                                            <i class="anticon anticon-setting"></i>

                                                        </button>

                                                    </td>                                    
                                                </tr>

                                                <!-- model Stting-->

                                                <div class="modal fade" id="setting_{{$rows->id}}">

                                                    <div class="modal-dialog modal-dialog-scrollable">

                                                        <div class="modal-content">

                                                            <div class="modal-header">

                                                                <h5 class="modal-title" id="exampleModalScrollableTitle">Setting</h5>

                                                                <button type="button" class="close" data-dismiss="modal">

                                                                    <i class="anticon anticon-close"></i>

                                                                </button>

                                                            </div>

                                                                <div class="modal-body">

                                                                <form action="{{url('agent/save-user-setting')}}" method="POST">

                                                                @csrf

                                                                <div class="form-group">

                                                                    <label for="inputAddress">Payin Callback</label>

                                                                    <input type="text" class="form-control" name="payin_callback" placeholder="Payin Callback" value="{{$rows->payin_callback}}">

                                                                </div>

                                                                <div class="form-group">

                                                                    <label for="inputAddress">Payout Callback</label>

                                                                    <input type="text" class="form-control" name="payout_callback" placeholder="Payout Callback" value="{{$rows->payout_callback}}">

                                                                </div>

                                                                <input type="hidden" name="userid" value="{{$rows->id}}"/>

                                                                    

                                                                </div>

                                                                <div class="modal-footer">

                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                    <button type="submit" class="btn btn-primary">Save</button>

                                                                </div>

                                                            </form>

                                                        </div>

                                                    </div>

                                                </div>

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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-1.13.5/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-1.13.5/datatables.min.js"></script>
<script>    


let table = new DataTable('#datatable');
    function changeStatus(id,status,type){

        var csrfToken = $('meta[name="csrf-token"]').attr('content');



        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': csrfToken

            }

        });

        var url = '{{url('/agent/update-users-status')}}';

        $.ajax({

            type: 'POST',

            url: url,

            data: {

                'id':id,

                'status':status,

                'type':type

            },

            success: function (response) {

               alert(response);

               location.reload();

            }

        });

            

    }



</script>
@include('agent/footer')
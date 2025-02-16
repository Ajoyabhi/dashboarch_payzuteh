

@include('admin/header')

<div class="page-container">

    <!-- Content Wrapper START -->

    <div class="main-content">

        <div class="page-header">

            <h2 class="header-title">Staff</h2>

            <div class="header-sub-title">

                <nav class="breadcrumb breadcrumb-dash">

                    <a href="{{url('/admin/dashboard')}}" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Home</a>                    

                    <span class="breadcrumb-item active">Staff</span>

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

                            <h5>Staff</h5>

                            <div>

                                <a href="{{url('/admin/add-staff')}}" class="btn btn-sm btn-primary">Add Staff</a>

                            </div>

                        </div>

                            <div class="m-t-30">

                                <div class="table-responsive">

                                    <table class="table table-hover table-bordered">

                                        <thead>

                                            <tr>

                                                <th>Name</th>

                                                <th>Mobile</th>

                                                <th>Email</th>

                                                <th>Status</th>

                                                <th>Action</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            @foreach ($user as $rows)

                                                <tr>

                                                    <td>{{$rows->name}}</td>                                                    

                                                    <td>{{$rows->mobile}}</td>

                                                    <td>{{$rows->email}}</td>

                                                    <td>

                                                        @if($rows->status == 1)

                                                            <button class="btn btn-sm btn-success" onClick="changeStatus({{$rows->id}},'DEACTIVE','status')">ACTIVE</button>

                                                        @else

                                                            <button class="btn btn-sm btn-danger" onClick="changeStatus({{$rows->id}},'ACTIVE','status')">DEACTIVE</button>

                                                        @endif

                                                    </td>                                                    

                                                    <td>                                                    
                                                        <button class="btn btn-sm btn-info" onClick="resetPssword({{$rows->id}})">Reset Password</button>
                                                        <a class="btn btn-icon btn-success" href="{{url('admin/manage-staff-ip/'.$rows->id)}}">

                                                            <i class="anticon anticon-plus"></i>
                                                        </a>
                                                        <a class="btn btn-icon btn-secondary" href="{{url('admin/edit-staff/'.$rows->id)}}" title="Edit">

                                                            <i class="anticon anticon-edit"></i>
                                                        </a>
                                                    </td>
                                                </tr>

                                                 <!-- model Stting-->

                                                 <div class="modal fade" id="setting_{{$rows->id}}">

                                                    <div class="modal-dialog modal-dialog-scrollable">

                                                    <div class="modal-content">

                                                    <div class="modal-header">

                                                        <h5 class="modal-title" id="exampleModalScrollableTitle">Ip Setting</h5>

                                                        <button type="button" class="close" data-dismiss="modal">

                                                            <i class="anticon anticon-close"></i>

                                                        </button>

                                                    </div>

                                                        <div class="modal-body">

                                                        <form action="{{url('admin/save-satff-ip')}}" method="POST">

                                                        @csrf
                                                    
                                                        <div class="form-group">

                                                            <label for="inputAddress">Ip Address</label>

                                                            <input type="text" class="form-control" name="ipaddress" placeholder="Ip Address" value="">

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

<script>    



    function changeStatus(id,status,type){

        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });

        var url = '{{url('/admin/update-users-status')}}';
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

    function resetPssword(id){

        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });

        var url = '{{url('/admin/reset-users-password')}}';
        $.ajax({

            type: 'POST',
            url: url,
            data: {
                'id':id,
            },
            success: function (response) {
            alert(response);
            location.reload();
            }
        });

        }



</script>

    

@include('admin/footer')
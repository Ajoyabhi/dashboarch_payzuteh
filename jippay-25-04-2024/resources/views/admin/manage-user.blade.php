@include('admin/header')

<div class="page-container">

    <!-- Content Wrapper START -->

    <div class="main-content">

        <div class="page-header">

            <h2 class="header-title">Users</h2>

            <div class="header-sub-title">

                <nav class="breadcrumb breadcrumb-dash">

                    <a href="{{url('/admin/dashboard')}}" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Home</a>                    

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

                                <a href="{{url('/admin/add-user')}}" class="btn btn-sm btn-primary">Add User</a>

                            </div>

                        </div>

                            <div class="m-t-30">

                                <div class="table-responsive">

                                    <table class="table table-hover table-bordered">

                                        <thead>

                                            <tr>
                                                <th>Sr no.</th>
    
                                                <th>Name</th>
                                                <th>Username</th>

                                                <th>Wallet Bal</th>

                                                <th>Mobile</th>

                                                <th>Payin</th>
                                                <th>Payout</th>

                                                <th>Status</th>

                                                <!--<th>Api Status</th>-->
                                                <!--<th>Technical issue</th>-->
                                                <!--<th>Deactivated by Bank</th>-->
                                                <!--<th>IserveU</th>-->
                                                <!--<th>Vouch</th>-->
                                                <th>Action</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            @foreach ($user as $key=>$rows)

                                                <tr>
                                                    <td>{{$key+1}}</td>

                                                    <td>{{$rows->name}}</td>
                                                    <td>{{$rows->user_name}}</td>

                                                    <td>{{$rows->wallet}}</td>

                                                    <td>{{$rows->mobile}}</td>

                                                    <td>0</td>
                                                    <td>0</td>

                                                    <td>

                                                        @if($rows->status == 1)

                                                            <button class="btn btn-sm btn-success" onClick="changeStatus({{$rows->id}},'DEACTIVE','status')">ACTIVE</button>

                                                        @else

                                                            <button class="btn btn-sm btn-danger" onClick="changeStatus({{$rows->id}},'ACTIVE','status')">DEACTIVE</button>

                                                        @endif

                                                    </td>

                                                    <!--<td>-->

                                                    <!--    @if($rows->api_status == 1)-->

                                                    <!--        <button class="btn btn-sm btn-success"  onClick="changeStatus({{$rows->id}},'DEACTIVE','API')">ACTIVE</button>-->

                                                    <!--    @else-->

                                                    <!--        <button class="btn btn-sm btn-danger"  onClick="changeStatus({{$rows->id}},'ACTIVE','API')">DEACTIVE</button>-->

                                                    <!--    @endif-->

                                                    <!--</td>-->

                                                    <!--<td>-->

                                                    <!--@if($rows->tecnical_issue == 0)-->

                                                    <!--    <button class="btn btn-sm btn-danger"  onClick="changeStatus({{$rows->id}},'ACTIVE','TECHNICAL')">DEACTIVE</button>-->

                                                    <!--@else-->

                                                    <!--    <button class="btn btn-sm btn-success"  onClick="changeStatus({{$rows->id}},'DEACTIVE','TECHNICAL')">ACTIVE</button>-->

                                                    <!--@endif-->

                                                    <!--</td>-->
                                                    <!--<td>-->
                                                    <!--    @if($rows->bank_deactive == 0)-->
                                                    <!--        <button class="btn btn-sm btn-danger"  onClick="changeStatus({{$rows->id}},'ACTIVE','BYBANK')">DEACTIVE</button>-->
                                                    <!--    @else-->
                                                    <!--        <button class="btn btn-sm btn-success"  onClick="changeStatus({{$rows->id}},'DEACTIVE','BYBANK')">ACTIVE</button>-->
                                                    <!--    @endif-->
                                                    <!--</td>-->

                                                    <!--<td>-->
                                                    <!--    @if($rows->iserveu == 0)-->
                                                    <!--        <button class="btn btn-sm btn-danger"  onClick="changeStatus({{$rows->id}},'ACTIVE','iserveu')">DEACTIVE</button>-->
                                                    <!--    @else-->
                                                    <!--        <button class="btn btn-sm btn-success"  onClick="changeStatus({{$rows->id}},'DEACTIVE','iserveu')">ACTIVE</button>-->
                                                    <!--    @endif-->
                                                    <!--</td>-->

                                                    <!--<td>-->
                                                    <!--    @if($rows->vouch == 0)-->
                                                    <!--        <button class="btn btn-sm btn-danger"  onClick="changeStatus({{$rows->id}},'ACTIVE','vouch')">DEACTIVE</button>-->
                                                    <!--    @else-->
                                                    <!--        <button class="btn btn-sm btn-success"  onClick="changeStatus({{$rows->id}},'DEACTIVE','vouch')">ACTIVE</button>-->
                                                    <!--    @endif-->
                                                    <!--</td>-->

                                                    <td>                                                    

                                                        <a class="btn btn-icon btn-success" href="{{url('admin/manage-user-charge/'.$rows->id)}}">

                                                            <i class="anticon anticon-plus"></i>

                                                        </a>
                                                        <a class="btn btn-icon btn-secondary" href="{{url('/admin/edit-user/'.$rows->id)}}" title="Edit">

                                                            <i class="anticon anticon-edit"></i>

                                                        </a>

                                                        <button class="btn btn-icon btn-primary" data-toggle="modal" data-target="#setting_{{$rows->id}}">

                                                            <i class="anticon anticon-setting"></i>

                                                        </button>

                                                        <button class="btn btn-icon btn-warning" data-toggle="modal" data-target="#fund_{{$rows->id}}">

                                                            <i class="anticon anticon-money-collect"></i>

                                                        </button>


                                                        <a class="btn btn-icon btn-secondary" href="{{url('/admin/user-dashboard/'.$rows->id)}}" title="Edit">

                                                            <i class="anticon anticon-eye" ></i>

                                                        </a>

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

                                                                <form action="{{url('admin/save-user-setting')}}" method="POST">

                                                                @csrf

                                                                <div class="form-group">

                                                                    <label for="inputAddress">Lien Amount</label>

                                                                    <input type="text" class="form-control" name="lien" placeholder="Lien Amount" value="{{$rows->lien}}">

                                                                </div>

                                                                <div class="form-group">

                                                                    <label for="inputAddress">Rolling Reserve Amount</label>

                                                                    <input type="text" class="form-control" name="rolling_reserve" placeholder="Rolling Reserve Amount" value="{{$rows->rolling_reserve}}">

                                                                </div>

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

                                                <!-- model Fund-->

                                                <div class="modal fade" id="fund_{{$rows->id}}">

                                                    <div class="modal-dialog modal-dialog-scrollable">

                                                        <div class="modal-content">

                                                            <div class="modal-header">

                                                                <h5 class="modal-title" id="exampleModalScrollableTitle">User Fund</h5>

                                                                <button type="button" class="close" data-dismiss="modal">

                                                                    <i class="anticon anticon-close"></i>

                                                                </button>

                                                            </div>

                                                                <div class="modal-body">

                                                                <form action="{{url('admin/update-user-fund')}}" method="POST">

                                                                @csrf

                                                                <div class="form-group">

                                                                    <label for="inputAddress">Type</label>

                                                                    <select class="form-control" name="fund_type" required>

                                                                        <option value="CREDIT">CREDIT</option>

                                                                        <option value="DEBIT">DEBIT</option>

                                                                    </select>

                                                                </div>

                                                                <div class="form-group">

                                                                    <label for="inputAddress">Amount</label>

                                                                    <input type="text" class="form-control" name="amount" placeholder="Amount" required>

                                                                </div>

                                                                <div class="form-group">

                                                                    <label for="inputAddress">Remark</label>

                                                                    <input type="text" class="form-control" name="remark" placeholder="Remark" required>

                                                                </div>                                                                

                                                                <input type="hidden" name="userid" value="{{$rows->id}}"/>                                                                    

                                                                </div>

                                                                <div class="modal-footer">

                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                    <button type="submit" class="btn btn-primary">Update Fund</button>

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



</script>

    

@include('admin/footer')
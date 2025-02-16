@include('admin/header')

            <!-- Page Container START -->

            <div class="page-container">

                <!-- Content Wrapper START -->

                <div class="main-content">

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
                                        <h5>Fund Requests</h5>
                                    </div>

                                    <div class="m-t-30">

                                        <div class="table-responsive-md table-responsive-sm table-responsive">

                                            <table class="table table-hover table-bordered" id="datatable"> 

                                                <thead>

                                                    <tr>

                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Amount</th>
                                                        <th>Wallet</th>
                                                        <th>Reference Id</th>
                                                        <th>From Bank</th>
                                                        <th>To Bank</th>
                                                        <th>Payment Type</th>
                                                        <th>Remarks</th>
                                                        <th>Reason</th>
                                                        <th>Action</th>

                                                    </tr>

                                                </thead>

                                                <tbody>
                                                    @foreach($payrequest as $key => $bank)
                                                    <tr>
                                                        <?php $payReqId = $bank->id; ?>
                                                        <th scope="row">{{ $key+1 }}</th>
                                                        <td>{{ $bank->name }}</td>
                                                        <td>{{ $bank->amount }}</td>
                                                        <td>{{ $bank->wallet }}</td>
                                                        <td>{{ $bank->reference_number }}</td>
                                                        <td>{{ $bank->from_bank  }}</td>
                                                        <td>{{ $bank->to_bank }}</td>
                                                        <td>{{ $bank->payment_type }}</td>
                                                        <td>{{ $bank->remarks }}</td>
                                                        <td>{{ $bank->approve_reject_remarks }}</td>
                                                        @if($bank->is_approved == 0)
                                                            <td>
                                                                <button data-toggle="modal" data-target="#modelApproveReject" onclick="setId({{$bank->id}}, {{$bank->userId}}, {{$bank->amount}})" class="btn btn-sm btn-primary">Approve/Reject</button>
                                                            </td>
                                                        @elseif($bank->is_approved == 1)
                                                            <td>
                                                                <button class="btn btn-sm btn-success">Approved</button>
                                                            </td>
                                                        @elseif($bank->is_approved == 2)
                                                        <td>
                                                            <button class="btn btn-sm btn-danger">Rejected</button>
                                                        </td>
                                                        @endif
                                                    </tr>
                                                    @endforeach
                                                </tbody>

                                            </table>
                                            
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                       

                    </div>

                </div>

                <!-- Approve/Reject Model Start-->
                <div class="modal fade" id="modelApproveReject">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalScrollableTitle">Approve/Reject</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <i class="anticon anticon-close"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{url('admin/approve-reject')}}" method="POST">
                                        @csrf
                                    <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="inputAddress2">Action</label>
                                                <select class="form-control" name="approve_reject">
                                                    <option value="APPROVE">APPROVE</option>
                                                    <option value="REJECT">REJECT</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="remarks">Remarks</label>
                                                <input type="text" class="form-control" name="remarks" placeholder="Remarks">
                                                <input type="hidden" name="payReqId" id="payReqId"/>
                                                <input type="hidden" name="payReqUserId" id="payReqUserId"/>
                                                <input type="hidden" name="payReqAmount" id="payReqAmount"/>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </div>
                                    </div>       
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Approve/Reject Model End-->
                <!-- Content Wrapper END -->

                <!-- model -->

                

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<link href="https://cdn.datatables.net/v/dt/dt-1.13.5/datatables.min.css" rel="stylesheet">

<script src="https://cdn.datatables.net/v/dt/dt-1.13.5/datatables.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<script type="text/javascript">

    $(document).ready(function () {
        let table = new DataTable('#datatable');
    });

    function setId(id, userId, amount){
        document.getElementById('payReqId').value=id;
        document.getElementById('payReqUserId').value=userId;
        document.getElementById('payReqAmount').value=amount;
    }


</script>



@include('admin/footer')
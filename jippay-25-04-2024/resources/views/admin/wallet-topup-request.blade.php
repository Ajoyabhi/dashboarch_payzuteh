@include('admin/header')

<!-- Page Container START -->

<div class="page-container">

    <!-- Content Wrapper START -->

    <div class="main-content"> 

        <div class="row">

            <div class="col-lg-12">

                <div class="horizontal-form">

                    <form action="{{ url('/admin/wallet-topup-search') }}" method="POST" enctype="multipart/form-data">

                        @csrf

                        <div class="form-group row">

                            <div class="col-md-4 mb-2">

                                <select class="form-select form-control" aria-label="Default select example" name="user">

                                    <option value="ALL">ALL</option>

                                    @if (!empty($users))

                                        @foreach ($users as $rows)

                                            <option value="{{$rows->id}}">{{$rows->name}}</option>

                                        @endforeach                                    

                                    @endif

                                </select>

                            </div>

                            <div class="col-md-4 mb-2">

                                <select class="form-select form-control" aria-label="Default select example" name="status">

                                    <option value="ALL">ALL</option>

                                    <option value="PENDING">PENDING</option>

                                    <option value="APPROVED">APPROVED</option>

                                    <option value="DENIED">DENIED</option>

                                </select>

                            </div>

                            <div class="col-md-2 mb-2">

                                <input id="date" type="date" class="form-control" name="date">

                            </div>

                            <div class="col-md-2  mb-2">

                                <div class="form-group text-left">

                                    <button type="submit" class="btn btn-md btn-success">View</button>

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

                            <h5>Wallet TopUp Request</h5>                                        

                        </div>

                        <div class="m-t-30">

                            <div class="table-responsive">

                                <table class="table table-hover table-bordered" id="datatable">

                                    <thead>

                                        <tr>

                                            <th>Name</th>

                                            <th>Amount</th>

                                            <th>Req Rmk</th>

                                            <th>Status Rmk</th>

                                            <th>Status</th>

                                            <th>Req By</th>  

                                            <th>Appr By</th>                                                        

                                            <th>Created At</th>

                                            <th>Updated At</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        @if (!empty($walletTopup))

                                            @foreach ($walletTopup as $rows)

                                            <tr>

                                                <td>{{$rows->name}}</td>

                                                <td>{{$rows->amount}}</td>

                                                <td>{{$rows->requestedRemark}}</td>

                                                <td>{{$rows->approvedRemark}}</td>

                                                <td>

                                                    @if ($rows->status == "PENDING")

                                                        <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#fundupdate_{{$rows->id}}">

                                                            PENDING

                                                        </button>

                                                    @elseif($rows->status == "APPROVED")

                                                        <button class="btn btn-sm btn-success">

                                                            APPROVED

                                                        </button>

                                                    @elseif($rows->status == "DENIED")

                                                        <button class="btn btn-sm btn-danger">

                                                            DENIED

                                                        </button>

                                                    @endif

                                                </td>                                                                    

                                                <td>{{$rows->requestedBy}}</td>

                                                <td>{{$rows->approvedBy}}</td>   

                                                <td>{{$rows->created_at}}</td>  

                                                    @if ($rows->status == "PENDING")

                                                        <td></td>  

                                                    @else

                                                        <td>{{$rows->updated_at}}</td>  

                                                    @endif                                                                                                

                                            </tr>

                                            <!-- model Fund update-->

                                            <div class="modal fade" id="fundupdate_{{$rows->id}}">

                                                <div class="modal-dialog modal-dialog-scrollable">

                                                    <div class="modal-content">

                                                        <div class="modal-header">

                                                            <h5 class="modal-title" id="exampleModalScrollableTitle">Topup</h5>

                                                            <button type="button" class="close" data-dismiss="modal">

                                                                <i class="anticon anticon-close"></i>

                                                            </button>

                                                        </div>

                                                            <div class="modal-body">

                                                            <form action="{{url('admin/wallet-topup-request')}}" method="POST">

                                                            @csrf

                                                            <div class="form-group">

                                                                <label for="inputAddress">Approval Status</label>

                                                                <select class="form-control" name="status" required>

                                                                    <option value="APPROVED">APPROVED</option>

                                                                    <option value="DENIED">DENIED</option>

                                                                </select>

                                                            </div>

                                                            <div class="form-group">

                                                                <label for="inputAddress">UTR No.</label>

                                                                <input type="text" minlength="12" maxlength="22" class="form-control" name="utr" placeholder="utr" required>

                                                            </div>

                                                            <div class="form-group">

                                                                <label for="inputAddress">Remark</label>

                                                                <input type="text" class="form-control is-invalid1" id="remark" name="remark" value="WALLETLOAD" >

                                                            </div>                                                                

                                                            <input type="hidden" name="requestId" value="{{$rows->id}}"/>                                                                    

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



@include('admin/footer')
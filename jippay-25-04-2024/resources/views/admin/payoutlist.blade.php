@include('admin/header')
<!-- Page Container START -->

<div class="page-container">

    <!-- Content Wrapper START -->

    <div class="main-content">

        <div class="row">

            <div class="col-md-12 col-lg-12">

                <div class="card">

                    <div class="card-body">

                        @if(count($errors) > 0)
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                            {{$error}}
                            @endforeach
                        </div>
                        @endif

                        @if(session('success'))
                        <div class="alert alert-success mb-1 mt-1">
                            {{ session('success') }}
                        </div>
                        @endif

                        @if(session('error'))
                        <div class="alert alert-danger mb-1 mt-1">
                            {{ session('error') }}
                        </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Payout Requests</h5>
                        </div>

                        <div class="m-t-30">

                            <div class="table-responsive-md table-responsive-sm table-responsive">

                                <table class="table table-hover table-bordered" id="datatable">

                                    <thead>

                                        <tr>

                                            <th>SI.NO</th>

                                            <th>OrderId</th>

                                            <th>Date</th>

                                            <th>User</th>

                                            <th>Descrption</th>

                                            <th>Amount</th>

                                            <th>Wallet balance</th>

                                            <th>Status</th>

                                        </tr>

                                    </thead>

                                    <tbody>
                                        @foreach($payout_lists as $payout_list)
                                        <tr>
                                            <th scope="row">{{ $payout_list->id }}</th>
                                            <td>{{ $payout_list->ref_number }}</td>
                                            <td>{{ date("d-m-Y", strtotime($payout_list->created_at)) }}</td>
                                            <td>{{ $payout_list->name }}</td>
                                            <td>{{ $payout_list->description }}</td>
                                            <td>{{ $payout_list->amount }}</td>
                                            <td>{{ number_format($payout_list->wallet_bal,2) }}</td>
                                            <td>
                                                @if($payout_list->status == 0)
                                                <span class="btn btn-warning btn-sm" data-toggle="modal"
                                                    data-target="#setting_{{$payout_list->id}}"> Pending </span>
                                                @elseif($payout_list->status == 1)
                                                <span class="btn btn-success btn-sm"> Approved </span>
                                                @else
                                                <span class="btn btn-danger btn-sm"> Rejected </span>
                                                @endif
                                            </td>
                                        </tr>

                                        {{-- Status Modal start --}}
                                        <div class="modal fade" id="setting_{{$payout_list->id}}">

                                            <div class="modal-dialog modal-dialog-scrollable">

                                                <div class="modal-content">

                                                    <div class="modal-header">

                                                        <h5 class="modal-title" id="exampleModalScrollableTitle">Status
                                                        </h5>

                                                        <button type="button" class="close" data-dismiss="modal">

                                                            <i class="anticon anticon-close"></i>

                                                        </button>

                                                    </div>

                                                    <div class="modal-body">
                                                        <form action="{{ route('admin.payoutStatus',$payout_list->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="form-group">
                                                                <label for="inputAddress">Status</label>
                                                                <input type="hidden" name="id"
                                                                    value="{{ $payout_list->id }}">
                                                                <select name="status" id="status" class="form-control" required>
                                                                    <option value="">-- Select Status --</option>
                                                                    <option value="1">Approve</option>
                                                                    <option value="2">Reject</option>
                                                                </select>
                                                            </div>

                                                            <div class="form-group admin_code_div"
                                                                style="display: none;" id="admin_code_div">
                                                                <label for="inputAddress">Admin Code</label>
                                                                <input name="admin_code" id="admin_code"
                                                                    class="form-control" maxlength="10">
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default"
                                                                    data-dismiss="modal">Close</button>
                                                                <button type="submit" name="submit"
                                                                    class="btn btn-primary">Submit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                        {{-- Status Modal end --}}

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

<!-- Content Wrapper END -->

<!-- model -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-1.13.5/datatables.min.css" rel="stylesheet">

<script src="https://cdn.datatables.net/v/dt/dt-1.13.5/datatables.min.js"></script>

<script type="text/javascript">

    $(document).ready(function () {
        let table = new DataTable('#datatable');

        $(document).on('change', '#status', function () {
            var _this = $(this);
            var staus_val = _this.val();
            if (staus_val == 1) {
                $('.admin_code_div').show();
                $('.admin_code').attr('required', true);
            } else {
                $('.admin_code_div').hide();
                $('.admin_code').attr('required', false);
            }
        });
    });
</script>
@include('admin/footer')
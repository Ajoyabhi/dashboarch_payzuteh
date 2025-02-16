@include('payout-user/header')

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
                                        <h5>Bank Accounts</h5>
                                        <div style="background-color: #d7eea8; font-weight: bold; text-align: center; width: 31%; font-size: 16px; ">
                                            Account Holder Name: TARAYAH SYSTEMS <br>
                                            Account No : 50200071771681 <br>
                                            IFSC Code : HDFC0009527 <br>
                                            Bank Name : HDFC Bank
                                        </div>
                                        <div>
                                            <a href="{{url('/payout-user/add-payrequest')}}" class="btn btn-sm btn-primary">Add Pay Request</a>
                                        </div>
                                    </div>

                                    <div class="m-t-30">

                                        <div class="table-responsive-md table-responsive-sm table-responsive">

                                            <table class="table table-hover table-bordered" id="datatable"> 

                                                <thead>

                                                    <tr>

                                                        <th>SI.NO</th>
                                                        <th>Amount</th>
                                                        <th>Reference Id</th>
                                                        <th>From Bank</th>
                                                        <th>To Bank</th>
                                                        <th>Payment Type</th>
                                                        <th>Remarks</th>
                                                        <th>Status</th>

                                                    </tr>

                                                </thead>

                                                <tbody>
                                                    @foreach($payrequest as $key => $bank)
                                                    <tr>
                                                        <th scope="row">{{ $key+1 }}</th>
                                                        <td>{{ $bank->amount }}</td>
                                                        <td>{{ $bank->reference_number }}</td>
                                                        <td>{{ $bank->from_bank }}</td>
                                                        <td>{{ $bank->to_bank }}</td>
                                                        <td>{{ $bank->payment_type }}</td>
                                                        <td>{{ $bank->remarks }}</td>
                                                        <td>{{ $bank->status }}</td>
                                                        <!--<td>
                                                            <a class="btn btn-icon btn-success" href="{{ route('user.editBank',$bank->id)}}" title="Edit">
                                                                <i class="anticon anticon-edit"></i>
                                                            </a>
                                                            <a class="btn btn-icon btn-danger" href="{{ route('user.deleteBank',$bank->id)}}" title="Delete">
                                                                <i class="anticon anticon-delete"></i>
                                                            </a>
                                                        </td>-->
                                                    </tr>
                                                    @endforeach
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

                

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<link href="https://cdn.datatables.net/v/dt/dt-1.13.5/datatables.min.css" rel="stylesheet">

<script src="https://cdn.datatables.net/v/dt/dt-1.13.5/datatables.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<script type="text/javascript">

    $(document).ready(function () {
        let table = new DataTable('#datatable');
    });



</script>



@include('payout-user/footer')
@include('agent/header')

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
                                        <div>
                                            <a href="{{url('/agent/add-bank')}}" class="btn btn-sm btn-primary">Add Account</a>
                                        </div>
                                    </div>

                                    <div class="m-t-30">

                                        <div class="table-responsive-md table-responsive-sm table-responsive">

                                            <table class="table table-hover table-bordered" id="datatable"> 

                                                <thead>

                                                    <tr>

                                                        <th>SI.NO</th>

                                                        <th>Beneficiary</th>

                                                        <th>Bank</th>
                                                        
                                                        <th>A/C No</th>                                                        

                                                        <th>IFSC</th>

                                                        <th>Action</th>

                                                    </tr>

                                                </thead>

                                                <tbody>
                                                    @foreach($banks as $bank)
                                                    <tr>
                                                        <th scope="row">{{ $bank->id }}</th>
                                                        <td>{{ $bank->cus_name }}</td>
                                                        <td>{{ $bank->bank_name }}</td>
                                                        <td>{{ $bank->acc_number }}</td>
                                                        <td>{{ $bank->ifsc_code }}</td>
                                                        <td>
                                                            <a class="btn btn-icon btn-success" href="{{ route('agent.editBank',$bank->id)}}" title="Edit">
                                                                <i class="anticon anticon-edit"></i>
                                                            </a>
                                                            <a class="btn btn-icon btn-danger" href="{{ route('agent.deleteBank',$bank->id)}}" title="Delete">
                                                                <i class="anticon anticon-delete"></i>
                                                            </a>
                                                        </td>
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



@include('agent/footer')
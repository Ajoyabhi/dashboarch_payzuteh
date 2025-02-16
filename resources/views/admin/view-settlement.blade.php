@include('admin/header')
<?php 
$uri = $_SERVER["REQUEST_URI"];
$uriArray = explode('/', $uri);?>

<div class="page-container">

    <!-- Content Wrapper START -->

    <div class="main-content">

        <div class="page-header">

            <h2 class="header-title">Settlement History</h2>

            <div class="header-sub-title">

                <nav class="breadcrumb breadcrumb-dash">

                    <a href="{{url('/admin/dashboard')}}" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Home</a>                    

                    <span class="breadcrumb-item active">Settlement History</span>

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
                                <span><b>Total Settlement : <?php echo $totalSettlement;?></b></span>

                                <a href="{{url('/admin/add-settlement/'.$uriArray[count($uriArray) -1])}}" class="btn btn-sm btn-primary">Add Settlement</a>

                            </div>

                        </div>

                            <div class="m-t-30">

                                <div class="table-responsive">

                                    <table class="table table-hover table-bordered" id="datatable">

                                        <thead>

                                            <tr>
                                                <th>Sr no.</th>
                                                <th>Opening Settlement</th>
                                                <th>Requested Amount</th>
                                                <th>Closing Settlement</th>
                                                <th>Requested Date</th>
                                            </tr>

                                        </thead>

                                        <tbody>

                                            @foreach ($user as $key=>$rows)

                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td>{{$rows->opening_settlement}}</td>
                                                    <td>{{$rows->amount}}</td>
                                                    <td>{{$rows->closing_settlement}}</td>
                                                    <td>{{$rows->created_at}}</td>
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
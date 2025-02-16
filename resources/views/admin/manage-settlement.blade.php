@include('admin/header')

<div class="page-container">

    <!-- Content Wrapper START -->

    <div class="main-content">

        <div class="page-header">

            <h2 class="header-title">Settlement</h2>

            <div class="header-sub-title">

                <nav class="breadcrumb breadcrumb-dash">

                    <a href="{{url('/admin/dashboard')}}" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Home</a>                    

                    <span class="breadcrumb-item active">Settlement</span>

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

                        <!-- <div class="d-flex justify-content-between align-items-center">

                            <h5>Users</h5>

                            <div>

                                <a href="{{url('/admin/add-settlement')}}" class="btn btn-sm btn-primary">Manage Settlement</a>

                            </div>

                        </div> -->

                            <div class="m-t-30">

                                <div class="table-responsive">

                                    <table class="table table-hover table-bordered" id="datatable">

                                        <thead>

                                            <tr>
                                                <th>Sr no.</th>
                                                <th>Name</th>
                                                <th>Username</th>
                                                <th>Mobile</th>
                                                <th>Wallet Bal</th>
                                                <th>Payout Bal</th>
                                                <th>Settlement</th>
                                            </tr>

                                        </thead>

                                        <tbody>

                                            @foreach ($user as $key=>$rows)

                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td>{{$rows->name}}</td>
                                                    <td>{{$rows->user_name}}</td>
                                                    <td>{{$rows->mobile}}</td>
                                                    <td>{{$rows->wallet}}</td>
                                                    <td>{{$rows->settlement}}</td>
                                                    <td>
                                                    <a class="btn btn-icon btn-secondary" href="{{url('/admin/add-settlement/'.$rows->id)}}" title="settle">
                                                        <i class="anticon anticon-plus"></i>
                                                    </a>
                                                    <a class="btn btn-icon btn-secondary" href="{{url('/admin/view-settlement/'.$rows->id)}}" title="settle">
                                                        <i class="anticon anticon-eye"></i>
                                                    </a>
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
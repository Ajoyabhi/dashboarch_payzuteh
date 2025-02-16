@include('payout-user/header')

            <!-- Page Container START -->

            <div class="page-container">

                <!-- Content Wrapper START -->

                <div class="main-content">   
                <div class="row">
                    <div class="col-lg-12">
                        <div class="horizontal-form">
                            <form action="{{ url('/payout-user/payout-report-export') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-md-6 mb-6">
                                        <input id="date" type="date" class="form-control" name="txndate" value="">
                                    </div>

                                    <div class="col-md-2  mb-2">
                                        <div class="form-group text-left">
                                            <button type="submit" class="btn btn-md btn-primary">Export</button>
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

                                    <div class="d-flex justify-content-between align-items-center">

                                        <h5>Payout Transactions</h5>                                        

                                    </div>

                                    <div class="m-t-30">

                                        <div class="table-responsive-md table-responsive-sm table-responsive">

                                            <table class="table table-hover table-bordered" id="datatable"> 

                                                <thead>

                                                    <tr>

                                                        <th>OrderId</th>

                                                        <th>Transaction Id</th>

                                                        <th>UTR</th>

                                                        <th>Name</th>

                                                        <th>A/C No</th>  

                                                        <th>Ifsc</th> 

                                                        <th>Amount</th>                                                        

                                                        <th>Charge</th>

                                                        <th>Gst</th>

                                                        <th>Net Amount</th>

                                                        <th>Status</th>

                                                        <th>Date</th>

                                                    </tr>

                                                </thead>

                                                <tbody>

                                                    

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



        var url = "{{ route('/payout-user/payout-report-data') }}";

        let dataTable = new DataTable('#datatable', {



            ajax: {

                url: url,

                type: 'POST',

                data:{"type":"payout",_token: '{{ csrf_token() }}'},

            },

            processing: true,

            serverSide: true,

            ordering: false,

           

            columns : [

                { "data": "orderId"},

                { "data": "txnId" },

                { "data": "utr" },

                { "data": "beneName" },

                { "data": "beneAccount" },

                { "data": "beneIfsc" },                 

                { "data": "amount" },

                { "data": "charge" },

                { "data": "gst" },

                { "data": "totalAmount" },

                { 

                    data: 'status',

                    render: function(data, type, row) {

                        if (data === 'SUCCESS') {

                            return ' <button class="btn btn-sm btn-success">'+data+'</button>';

                        } else if (data === 'FAILED') {

                            return ' <button class="btn btn-sm btn-danger">'+data+'</i></button>';

                        } else {

                            return '<button class="btn btn-sm btn-danger">'+data+'</i></button>';

                        }

                    }

                },

                {
                data:"created_at",
                render: function (data, type, row, meta) {
                return moment.utc(data).local().format('DD/MM/YYYY HH:mm:ss');
                }
            },

            ],



            columnDefs: [

            {

                targets: 11, // Index of the column you want to modify

                render: function(data, type, row) {

                    if (type === 'display' || type === 'filter') {

                        // 'data' is the cell content

                        // Use moment.js to format the date

                        return moment(data).format('DD-MM-YYYY  HH:MM:ss');

                    }

                    return data;

                }

            }

        ]    

        });



        $('#datepicker').on('change', function () {

            var newdate = $("#datepicker").val();

            $("#datepicker").val(newdate)

            //dataTable.draw(); // Redraw the table, which triggers a new AJAX request

            dataTable.ajax.reload();

        });    



    });



</script>



@include('payout-user/footer')
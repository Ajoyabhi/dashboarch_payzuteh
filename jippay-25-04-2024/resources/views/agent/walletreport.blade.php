@include('agent/header')
    <!-- Page Container START -->
    <div class="page-container">
        <!-- Content Wrapper START -->
        <div class="main-content">    
        <div class="row">
            <div class="col-lg-12">
                <div class="horizontal-form">
                    <!-- <form action="{{ url('/agent/wallet-report-export') }}" method="POST" enctype="multipart/form-data">
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
                    </form> -->
                </div>
            </div>
        </div>                
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>Wallet Transactions</h5>                                        
                            </div>
                            <div class="m-t-30">
                                <div class="table-responsive-md table-responsive-sm table-responsive">
                                    <table class="table table-hover table-bordered" id="datatable"> 
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Date</th>
                                                <th>OrderId</th>
                                                <th>User</th>                                                        
                                                <th>Descrption</th>
                                                <th>Open Balance</th>
                                                <th>Amount</th>
                                                <th>Wallet balance</th>
                                                <th>status</th>
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
var url = "{{ route('/agent/wallet-report-data') }}";
let dataTable = new DataTable('#datatable', {
    ajax: {
        url: url,
        type: 'POST',
        data:{"type":"wallet",_token: '{{ csrf_token() }}'},
    },
    processing: true,
    serverSide: true,
    ordering: false,
    columns : [
        { 
            data: 'type',
            render: function(data, type, row) {

                if (data === 'CREDIT') {
                    return ' <button class="btn btn-icon btn-success"><i class="anticon anticon-arrow-down"></i></button>';
                } else if (data === 'DEBIT') {
                    return ' <button class="btn btn-icon btn-danger"><i class="anticon anticon-arrow-up"></i></button>';
                } else {
                    return '';
                }
            }
        },
        { "data": "created_at"},
        { "data": "orderId" },
        { "data": "name" },
        { "data": "remark" },
        { "data" : "openBalance"},
        { "data": "amount" },                 
        { "data": "walletBalance" },
        { "data": "status" },               
    ],
    columnDefs: [
    {
        targets: 1, // Index of the column you want to modify
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
@include('agent/footer')
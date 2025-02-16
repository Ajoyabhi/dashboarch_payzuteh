@include('staff/header')

<div class="page-container">

    <!-- Content Wrapper START -->

    <div class="main-content">

        <div class="page-header">

            <h2 class="header-title">Wallet Topup</h2>

            <div class="header-sub-title">

                <nav class="breadcrumb breadcrumb-dash">

                    <a href="{{url('/staff/dashboard')}}" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Home</a>                    

                    <span class="breadcrumb-item active">Wallet Topup</span>

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

                <form action="{{ url('/staff/wallet-topup') }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    <div class="form-row">

                        <div class="col-md-4 mb-3">

                            <label for="">User</label>

                            <select class="form-control" name="user">

                                <option value="">Select User</option>

                                @if (!empty($users))

                                    @foreach ($users as $rows)

                                        <option value="{{$rows->id}}">{{$rows->name}}</option>

                                    @endforeach                                    

                                @endif                                

                            </select>

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('user')

                                    {{ $message }}

                                @enderror

                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label for="">Amount</label>

                            <input type="text" maxlength="6" class="form-control is-invalid1" id="amount" name="amount" placeholder="Enter Amount" maxlength="6">

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('amount')

                                    {{ $message }}

                                @enderror

                            </div>
                            <div style="font-size:17px !important" class="text-danger" id="amountInWord"></div>
                        </div>

                        <div class="col-md-4 mb-3">

                            <label for="">Remark</label>

                            <input type="text" class="form-control is-invalid1" id="remark" name="remark" value="WALLETLOAD" >

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('remark')

                                {{ $message }}

                            @enderror

                            </div>
                            
                        </div>

                        <div class="form-group text-center">

                            <button class="btn btn-primary">Request</button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>
<script>
    var a = ['','One ','Two ','Three ','Four ', 'Five ','Six ','Seven ','Eight ','Nine ','Ten ','Eleven ','Twelve ','Thirteen ','Fourteen ','Fifteen ','Sixteen ','Seventeen ','Eighteen ','Nineteen '];
    var b = ['', '', 'Twenty','Thirty','Forty','Fifty', 'Sixty','Seventy','Eighty','Ninety'];

function inWords (num) {
    if ((num = num.toString()).length > 9) return 'overflow';
    n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
    if (!n) return; var str = '';
    str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'Crore ' : '';
    str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'Lakh ' : '';
    str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'Thousand ' : '';
    str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'Hundred ' : '';
    str += (n[5] != 0) ? ((str != '') ? 'And ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) + 'Only ' : '';
    return str;
}

document.getElementById('amount').onkeyup = function () {
    document.getElementById('amountInWord').innerHTML = inWords(document.getElementById('amount').value);
};

</script>
@include('staff/footer')
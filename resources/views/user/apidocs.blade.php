@include('user/header')
<!-- Page Container START -->
<div class="page-container">
<!-- Content Wrapper START -->
<div class="main-content">    
    <div class="row">
        <div class="col-md-12 col-lg-12">

            <!-- Check User Balance API -->
         

            <!-- Payout API -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Payout API</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>URL</th>
                                    <td style="font-size: 18px; font-weight: bold;">https://api.payzutech.in/api/v6/doPayoutApi</td>
                                </tr>
                                <tr>
                                    <th>Method</th>
                                    <td>POST</td>
                                </tr>
                                <tr>
                                    <th>Header</th>
                                    <td>
                                        Pass: Authorization Key 
                                        <a href="{{url('/user/dev-setting')}}" target="_blank">Click here</a> <br>
                                        Example: <code>Authorization: yourkey</code>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Request</th>
                                    <td>
<pre class="mb-0"><code>{
    "name": "V******ma",
    "accountNumber": "1********2",
    "bankIfsc": "K********6",
    "mobileNumber": "9********8",
    "beneBankName": "K********K",
    "referenceNumber": "3***********9",
    "transferAmount": "100",
    "transferMode": "IMPS"
}</code></pre>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Response</th>
                                    <td>
<pre class="mb-0"><code>{
    "status": "SUCCESS",
    "message": "CO00 - Transaction is Successful.",
    "data": {
        "payout_ref": "311541447890789",
        "bank_ref": "325415029910"
    }
}</code></pre>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Check Payout Status API -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Check Payout Status API</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>URL</th>
                                    <td style="font-size: 17px; font-weight: bold;">https://api.payzutech.in/api/v6/payoutCheckStatus</td>
                                </tr>
                                <tr>
                                    <th>Method</th>
                                    <td>POST</td>
                                </tr>
                                <tr>
                                    <th>Header</th>
                                    <td>
                                        Pass: Authorization Key 
                                        <a href="{{url('/user/dev-setting')}}" target="_blank">Click here</a> <br>
                                        Example: <code>Authorization: yourkey</code>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Request</th>
                                    <td>
<pre class="mb-0"><code>{
    "referenceNumber": "311541447890655"
}</code></pre>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Response</th>
                                    <td>
<pre class="mb-0"><code>{
    "status": "SUCCESS",
    "message": "Transaction Successful",
    "data": {
        "payout_ref": "311541447890655",
        "bank_ref": "325419122605"
    }
}</code></pre>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Check User Balance API</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>URL</th>
                                    <td style="font-size: 18px; font-weight: bold;">https://api.payzutech.in/api/v6/checkuserbalance</td>
                                </tr>
                                <tr>
                                    <th>Method</th>
                                    <td>POST</td>
                                </tr>
                                <tr>
                                    <th>Header</th>
                                    <td>
                                        Pass: Authorization Key 
                                        <a href="{{url('/user/dev-setting')}}" target="_blank">Click here</a> <br>
                                        Example: <code>Authorization: yourkey</code>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Response</th>
                                    <td>
<pre class="mb-0"><code>{
    "status": "SUCCESS",
    "message": "Transaction is Successful",
    "data": {
        "balance": 11604.82
    }
}</code></pre>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Content Wrapper END -->
<!-- model -->        
@include('user/footer')
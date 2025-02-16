@include('payout-user/header')
<!-- Page Container START -->
<div class="page-container">
<!-- Content Wrapper START -->
<div class="main-content">    
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5>Check User Balance Api</h5>                                        
                    </div>
                    <div class="m-t-30">
                        <div class="table-responsive-md table-responsive-sm">
                            <table class="table table-hover table-bordered" id="datatable"> 
                                <thead>
                                    <tr>
                                        <th>URL</th>
                                        <th>https://api.paydexsolutions.in/api/v1/checkuserbalance</th>
                                    </tr>
                                    <tr>
                                        <th>Method</th>
                                        <th>POST</th>
                                    </tr>
                                    <tr>
                                        <th>Header</th>
                                        <th>Pass : Authorization Key <a target="_blank" href="/payout-user/dev-setting">click</a> Like : Authorization:yourkey</th>
                                    </tr>
                                    <tr>
                                        <th>Response</th>
                                        <th>{
                                                "status": "SUCCESS",
                                                "message": "Transaction is Successful",
                                                "data": {
                                                    "balance": 11604.8199999999997089616954326629638671875
                                                }
                                            }
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5>Payout Api</h5>                                        
                    </div>
                    <div class="m-t-30">
                        <div class="table-responsive-md table-responsive-sm">
                            <table class="table table-hover table-bordered" id="datatable"> 
                                <thead>
                                    <tr>
                                        <th>URL</th>
                                        <th>https://api.paydexsolutions.in/api/v1/doPayout</th>
                                    </tr>
                                    <tr>
                                        <th>Method</th>
                                        <th>POST</th>
                                    </tr>
                                    <tr>
                                        <th>Header</th>
                                        <th>Pass : Authorization Key <a target="_blank" href="/payout-user/dev-setting">click</a> Like : Authorization:yourkey</th>
                                    </tr>
                                    <tr>
                                        <th>REQUEST</th>
                                        <th>
                                            {
                                                "name": "V******ma",
                                                "accountNumber": "1********2",
                                                "bankIfsc": "K********6",
                                                "mobileNumber": "9********8",
                                                "beneBankName": "K********K",
                                                "referenceNumber": "3***********9",
                                                "transferAmount": "100",
                                                "transferMode": "IMPS"
                                            }
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Response</th>
                                        <th>{
                                                "status": "SUCCESS",
                                                "message": "CO00 - Transaction is Successful.",
                                                "data": {
                                                    "payout_ref": "311541447890789",
                                                    "bank_ref": "325415029910"
                                                }
                                            }

                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5>Check Payout Status Api</h5>                                        
                    </div>
                    <div class="m-t-30">
                        <div class="table-responsive-md table-responsive-sm">
                            <table class="table table-hover table-bordered" id="datatable"> 
                                <thead>
                                    <tr>
                                        <th>URL</th>
                                        <th> https://api.paydexsolutions.in/api/v1/checkstatus</th>
                                    </tr>
                                    <tr>
                                        <th>Method</th>
                                        <th>POST</th>
                                    </tr>
                                    <tr>
                                        <th>Header</th>
                                        <th>Pass : Authorization Key <a target="_blank" href="/payout-user/dev-setting">click</a> Like : Authorization:yourkey</th>
                                    </tr>
                                    <tr>
                                        <th>REQUEST</th>
                                        <th>
                                            {
                                                "referenceNumber": "311541447890655"
                                            }
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Response</th>
                                        <th>{"status":"SUCCESS","message":"Transaction Successful","data":{"payout_ref":"311541447890655","bank_ref":"325419122605"}}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
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
@include('payout-user/footer')
@include('agent/header')

<div class="page-container">

    <!-- Content Wrapper START -->

    <div class="main-content">

        <div class="page-header">

            <h2 class="header-title">Bank</h2>

            <div class="header-sub-title">

                <nav class="breadcrumb breadcrumb-dash">

                    <a href="{{url('/agent/dashboard')}}" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Home</a>                    

                    <span class="breadcrumb-item active">Bank</span>

                </nav>

            </div>

        </div>

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
                
                <form id="addbank" action="{{ route('user.storeBank') }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="">Name</label>
                            <input type="text" name="cus_name" class="form-control is-valid1" value="" id="cus_name" required placeholder="Enter Customer Name">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="">Account Number</label>
                            <input type="text" name="acc_number" class="form-control is-invalid1" value="" required id="acc_number" placeholder="Enter Account Number">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="">IFSC Code</label>
                            <input type="text" name="ifsc_code" class="form-control is-invalid1" value="" required id="ifsc_code" placeholder="Enter IFSC Code">
                        </div> 
                        
                        <div class="col-md-4 mb-3">
                            <label for="">Mobile</label>
                            <input type="text" name="mobile_no" class="form-control is-invalid1" value="" required id="mobile_no" maxlength="10" placeholder="Enter Mobile Number">
                        </div> 

                        <div class="col-md-4 mb-3">
                            <label for="">Bank Name</label>
                            <input type="text" name="bank_name" class="form-control is-invalid1" value="" required id="bank_name" placeholder="Enter Bank Name">
                           
                        </div> 

                        <div class="col-md-4 mb-3">
                            <label for="">Pincode</label>
                            <input type="text" name="pincode" class="form-control is-invalid1" value="" required id="pincode" maxlength="6" placeholder="Enter Pincode">
                        </div> 

                        <div class="col-md-4 mb-3">
                            <label for="">Select Payment Type</label>
                            <select name="payment_type" id="payment_type" class="form-control is-invalid1" required>
                                <option value="NEFT">NEFT</option>
                                <option value="RTGS">RTGS</option>
                                <option value="IMPS">IMPS</option>
                            </select>                      
                        </div> 

                        <div class="col-md-12 mb-3">
                            <input type="submit" class="btn btn-success" value="Add Bank">                          
                        </div>    
                    </div>
                </form>
            </div>

        </div>

    </div>

</div>

@include('agent/footer')
@include('payout-user/header')

<div class="page-container">

    <!-- Content Wrapper START -->

    <div class="main-content">

        <div class="page-header">

            <h2 class="header-title">Profile</h2>

            <div class="header-sub-title">

                <nav class="breadcrumb breadcrumb-dash">

                    <a href="{{url('/payout-user/dashboard')}}" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Home</a>                    

                    <span class="breadcrumb-item active">Profile</span>

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
                <form id="addbank" action="{{ route('payout-user.updateBank',$bank->id) }}" method="PUT">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="">Name</label>
                            <input type="text" name="cus_name" class="form-control is-valid1" value="{{ $bank->cus_name }}" id="cus_name" required>
                            
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="">Account Number</label>
                            <input type="text" name="acc_number" class="form-control is-invalid1" value="{{ $bank->acc_number }}" required id="acc_number">
                           
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="">IFSC Code</label>
                            <input type="text" name="ifsc_code" class="form-control is-invalid1" value="{{ $bank->ifsc_code }}" required id="ifsc_code">
                            
                        </div> 
                        
                        <div class="col-md-4 mb-3">
                            <label for="">Mobile</label>
                            <input type="text" name="mobile_no" class="form-control is-invalid1" value="{{ $bank->mobile_no }}" required id="mobile_no" maxlength="10">
                            
                        </div> 

                        <div class="col-md-4 mb-3">
                            <label for="">Bank Name</label>
                            <input type="text" name="bank_name" class="form-control is-invalid1" value="{{ $bank->bank_name }}" required id="bank_name">
                           
                        </div> 

                        <div class="col-md-4 mb-3">
                            <label for="">Pincode</label>
                            <input type="text" name="pincode" class="form-control is-invalid1" value="{{ $bank->pincode }}" required id="pincode" maxlength="6">
                        </div> 

                        <div class="col-md-4 mb-3">
                            <label for="">Select Payment Type</label>
                            <select name="payment_type" id="payment_type" class="form-control is-invalid1" required>
                                <option value="NEFT" @if($bank->payment_type=="NEFT") selected @endif>NEFT</option>
                                <option value="RTGS" @if($bank->payment_type=="RTGS") selected @endif>RTGS</option>
                                <option value="IMPS" @if($bank->payment_type=="IMPS") selected @endif>IMPS</option>
                            </select>                      
                        </div> 

                        <div class="col-md-12 mb-3">
                            <input type="submit" class="btn btn-success" value="Update Bank">                          
                        </div>    

                        {{-- <div class="col-md-8 mb-6">
                            <p id="showRes"></p>                
                        </div> --}}
                    </div>
                </form>
            </div>

        </div>

    </div>

</div>

@include('payout-user/footer')
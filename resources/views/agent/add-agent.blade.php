@include('agent/header')

<div class="page-container">

    <!-- Content Wrapper START -->

    <div class="main-content">

        <div class="page-header">

            <h2 class="header-title">Make User</h2>

            <div class="header-sub-title">

                <nav class="breadcrumb breadcrumb-dash">

                    <a href="{{url('/agent/dashboard')}}" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Home</a>                    

                    <span class="breadcrumb-item active">Make User</span>

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

                <!-- <form action="{{ url('/agent/add-agent') }}" method="POST" enctype="multipart/form-data"> -->
                <form action="{{ route('add-agent-user') }}" method="POST" enctype="multipart/form-data">
                
                    @csrf

                    <div class="form-row">

                        <div class="col-md-4 mb-3">

                            <label for="">Name</label>

                            <input type="text" class="form-control is-valid1" id="name" name="name" placeholder="Enter Name" value="" >

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('name')

                                    {{ $message }}

                                @enderror

                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label for="">User Name</label>

                            <input type="text" class="form-control is-valid1" id="user_name" name="user_name" placeholder="Enter User Name" value="" >

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('user_name')

                                    {{ $message }}

                                @enderror

                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label for="">Mobile</label>

                            <input type="text" class="form-control is-invalid1" id="mobile" name="mobile" placeholder="Enter Mobile" >

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('mobile')

                                    {{ $message }}

                                @enderror

                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label for="">Email</label>

                            <input type="email" class="form-control is-invalid1" id="email" name="email" placeholder="Enter Email" >

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('email')

                                {{ $message }}

                            @enderror

                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label for="">Password</label>

                            <input type="password" class="form-control is-invalid1" id="password" name="password" placeholder="Enter Password" >

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('password')

                                {{ $message }}

                            @enderror

                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label for="">Pancard</label>

                            <input type="text" class="form-control is-invalid1" id="pancard" name="pancard" placeholder="Enter Pancard" >

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('pancard')

                                {{ $message }}

                            @enderror

                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label for="">Aadhar Card</label>

                            <input type="text" class="form-control is-invalid1" id="aadhaar" name="aadhaar" placeholder="Enter Aadhar Card" >

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('aadhaar')

                                {{ $message }}

                            @enderror

                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label for="">User Type</label>
                            <!-- <label for="">User Type {0: Admin,1: payin-payout ,2: staff,3:agent,4:payout}</label> -->

                            <!-- <input type="text" class="form-control is-invalid1" id="user_type" name="user_type" placeholder="Enter User Type" > -->
                            <select name="user_type" id="user_type" class="form-control is-invalid1">
                                <option value="">-- Select User type --</option>
                                <option value="1">Payin-Payout</option>
                                <option value="4">Payout</option>
                            </select>

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('user_type')

                                {{ $message }}

                            @enderror

                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label for="">Company Name</label>

                            <input type="text" class="form-control is-invalid1" id="company" name="company" value="">

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('company')

                                {{ $message }}

                            @enderror

                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label for="">GST Number</label>

                            <input type="text" class="form-control is-invalid1" id="gst_no" name="gst_no" placeholder="Enter GST Number" >

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('gst_no')

                                {{ $message }}

                            @enderror

                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label for="">Business Type</label>

                            <select name="business_type" id="business_type" class="form-control is-invalid1">
                                <option value="">-- Select Business type --</option>
                                <option value="pvtltd">Private Limited</option>
                                <option value="partnership">Partnership</option>
                                <option value="llp">Limited Liability Partnership</option>
                                <option value="proprietorship">Proprietorship</option>
                            </select>

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('address')

                                {{ $message }}

                            @enderror

                            </div>

                        </div>  


                        <div class="col-md-4 mb-3">

                            <label for="">Address</label>

                            <input type="text" class="form-control is-invalid1" id="address" name="address" placeholder="Enter Address" >

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('address')

                                {{ $message }}

                            @enderror

                            </div>

                        </div>                        

                        <div class="col-md-4 mb-3">

                            <label for="">City</label>

                            <input type="text" class="form-control is-invalid1" id="city" name="city" placeholder="Enter City" >

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('city')

                                {{ $message }}

                            @enderror

                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label for="">State</label>

                            <input type="text" class="form-control is-invalid1" id="state" name="state" placeholder="Enter State" >

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('state')

                                {{ $message }}

                            @enderror

                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label for="">Pincode</label>

                            <input type="text" class="form-control is-invalid1" id="pincode" name="pincode" placeholder="Enter Pincode" >

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('pincode')

                                {{ $message }}

                            @enderror

                            </div>

                        </div>

                        <div class="col-md-4 mt-4 form-group text-center">

                            <button type="submit" class="btn btn-primary">Add User</button>

                        </div>

                    </div>

                </form>

            </div>

        </div>
    </div>
</div>
@include('agent/footer')
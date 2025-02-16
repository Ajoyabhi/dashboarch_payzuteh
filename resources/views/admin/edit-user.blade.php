@include('admin/header')

<div class="page-container">

    <!-- Content Wrapper START -->

    <div class="main-content">

        <div class="page-header">

            <h2 class="header-title">Edit User</h2>

            <div class="header-sub-title">

                <nav class="breadcrumb breadcrumb-dash">

                    <a href="{{url('/admin/dashboard')}}" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Home</a>                    

                    <span class="breadcrumb-item active">Edit User</span>

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

                <form action="{{ url('/admin/update-user',$user->id) }}" method="post" enctype="multipart/form-data">

                    @csrf
                    @method('PUT')

                    <div class="form-row">

                        <div class="col-md-4 mb-3">

                            <label for="">Name</label>

                            <input type="text" class="form-control is-valid1" id="name" name="name" placeholder="Enter Name" value="{{ $user->name }}" >

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('name')

                                    {{ $message }}

                                @enderror

                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label for="">Mobile</label>

                            <input type="text" class="form-control is-invalid1" id="mobile" name="mobile" placeholder="Enter Mobile" value="{{ $user->mobile }}">

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('mobile')

                                    {{ $message }}

                                @enderror

                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label for="">Email</label>

                            <input type="email" class="form-control is-invalid1" id="email" name="email" placeholder="Enter Email" value="{{ $user->email }}">

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('email')

                                {{ $message }}

                            @enderror

                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label for="">Password</label>

                            <input type="password" class="form-control is-invalid1" id="password" name="password" placeholder="Enter Password" value="{{ $user->password }}">

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('password')

                                {{ $message }}

                            @enderror

                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label for="">Pancard</label>

                            <input type="text" class="form-control is-invalid1" id="pancard" name="pancard" placeholder="Enter Pancard" value="{{ $user->pancard }}">

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('pancard')

                                {{ $message }}

                            @enderror

                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label for="">Aadhar Card</label>

                            <input type="text" class="form-control is-invalid1" id="aadhaar" name="aadhaar" placeholder="Enter Aadhar Card" value="{{ $user->aadhaar_card }}">

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
                                <option value="1" {{ $user->user_type == 1 ? 'selected' : '' }}>Payin-Payout</option>
                                <option value="2" {{ $user->user_type == 2 ? 'selected' : '' }}>Staff</option>
                                <option value="3" {{ $user->user_type == 3 ? 'selected' : '' }}>Agent</option>
                                <option value="4" {{ $user->user_type == 4 ? 'selected' : '' }}>Payout</option>
                            </select>

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('user_type')

                                {{ $message }}

                            @enderror

                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label for="">Company Name</label>

                            <input type="text" class="form-control is-invalid1" id="company" name="company" placeholder="Enter Company Name" value="{{ $user->company_name }}">

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('company')

                                {{ $message }}

                            @enderror

                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label for="">GST Number</label>

                            <input type="text" class="form-control is-invalid1" id="gst_no" name="gst_no" placeholder="Enter GST Number" value="{{ $user->gst_no }}">

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
                                <option value="pvtltd" {{ $user->business_type == 'pvtltd' ? 'selected' : '' }}>Private Limited</option>
                                <option value="partnership" {{ $user->business_type == 'partnership' ? 'selected' : '' }}>Partnership</option>
                                <option value="llp" {{ $user->business_type == 'llp' ? 'selected' : '' }}>Limited Liability Partnership</option>
                                <option value="proprietorship" {{ $user->business_type == 'proprietorship' ? 'selected' : '' }}>Proprietorship</option>
                            </select>

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('address')

                                {{ $message }}

                            @enderror

                            </div>

                        </div>  

                        <div class="col-md-4 mb-3">

                            <label for="">Address</label>

                            <input type="text" class="form-control is-invalid1" id="address" name="address" placeholder="Enter Address" value="{{ $user->address }}">

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('address')

                                {{ $message }}

                            @enderror

                            </div>

                        </div>                        

                        <div class="col-md-4 mb-3">

                            <label for="">City</label>

                            <input type="text" class="form-control is-invalid1" id="city" name="city" placeholder="Enter City" value="{{ $user->city }}">

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('city')

                                {{ $message }}

                            @enderror

                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label for="">State</label>

                            <input type="text" class="form-control is-invalid1" id="state" name="state" placeholder="Enter State" value="{{ $user->state }}">

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('state')

                                {{ $message }}

                            @enderror

                            </div>

                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="">Pincode</label>
                            <input type="text" class="form-control is-invalid1" id="pincode" name="pincode" placeholder="Enter Pincode" value="{{ $user->pincode }}">
                            <div class="invalid-feedback" style="display: block!important;">
                                @error('pincode')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4 mt-4 form-group text-center">
                            <button class="btn btn-primary">Update User</button>
                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@include('admin/footer')
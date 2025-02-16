@include('admin/header')

<div class="page-container">

    <!-- Content Wrapper START -->

    <div class="main-content">

        <div class="page-header">

            <h2 class="header-title">Make Users</h2>

            <div class="header-sub-title">

                <nav class="breadcrumb breadcrumb-dash">

                    <a href="{{url('/admin/dashboard')}}" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Home</a>                    

                    <span class="breadcrumb-item active">Make Users</span>

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

                <form action="{{ url('/admin/add-user') }}" method="POST" enctype="multipart/form-data">

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

                            <label for="">User Type {0: Admin,1: payin-payout ,2: staff,3:agent,4:payout}</label>

                            <input type="text" class="form-control is-invalid1" id="user_type" name="user_type" placeholder="Enter User Type" >

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('user_type')

                                {{ $message }}

                            @enderror

                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label for="">Company Name</label>

                            <input type="text" class="form-control is-invalid1" id="company" name="company" placeholder="Enter Company Name" >

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('company')

                                {{ $message }}

                            @enderror

                            </div>

                        </div>

                        <div class="col-md-8 mb-3">

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

                        <!--<div class="col-md-4 mb-3">-->
                        <!--    <label for="">Agent</label>-->
                        <!--    <select class="form-control is-invalid1" id="agent" name="agent">-->
                        <!--        <option value="0">Select Agent</option>-->
                        <!--        @foreach($agent as $data)-->
                        <!--            <option value="{{$data->id}}">{{$data->name}}</option>-->
                        <!--        @endforeach-->
                        <!--    </select> -->
                        <!--    <div class="invalid-feedback" style="display: block!important;">-->
                        <!--        @error('pincode')-->
                        <!--            {{ $message }}-->
                        <!--        @enderror-->
                        <!--    </div>-->
                        <!--</div>-->
                        <div class="col-md-4 mt-4 form-group text-center">
                            <button class="btn btn-primary">Add User</button>
                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@include('admin/footer')
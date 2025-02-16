@include('user/header')
<div class="page-container">
    <!-- Content Wrapper START -->
    <div class="main-content">
        <div class="page-header">
            <h2 class="header-title">Change Password</h2>
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{url('/user/dashboard')}}" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Home</a>                    
                    <span class="breadcrumb-item active">Change Password</span>
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
                <form action="{{ url('/user/change-password') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="">Old Password</label>
                            <input type="text" class="form-control is-valid1" id="oldpassword" name="oldpassword" placeholder="Old Password" value="" >
                            <div class="invalid-feedback" style="display: block!important;">
                                @error('oldpassword')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="">New Password</label>
                            <input type="text" class="form-control is-invalid1" id="password" name="password" placeholder="New Password" >
                            <div class="invalid-feedback" style="display: block!important;">
                                @error('password')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="">Confirm Password</label>
                            <input type="text" class="form-control is-invalid1" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" >
                            <div class="invalid-feedback" style="display: block!important;">
                                @error('password_confirmation')
                                {{ $message }}
                            @enderror
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('user/footer')
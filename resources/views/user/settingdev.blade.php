@include('user/header')
<div class="page-container">
    <!-- Content Wrapper START -->
    <div class="main-content">
        <div class="page-header">
            <h2 class="header-title">Profile</h2>
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="{{url('/user/dashboard')}}" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Home</a>                    
                    <span class="breadcrumb-item active">Profile</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">

                <div class="form-row">
                    <div class="col-md-4 mb-3">
                        <label for="">Authorization Key</label>
                        <input type="text" class="form-control is-valid1" value="{{auth()->user()->user_token}}" readonly>                       
                    </div>
                          
            </div>
        </div>
    </div>
</div>
@include('user/footer')
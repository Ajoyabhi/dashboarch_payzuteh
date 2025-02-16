@include('admin/header')

<div class="page-container">

    <!-- Content Wrapper START -->

    <div class="main-content">

        <div class="page-header">

            <h2 class="header-title">Edit Settlement</h2>

            <div class="header-sub-title">

                <nav class="breadcrumb breadcrumb-dash">

                    <a href="{{url('/admin/dashboard')}}" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Home</a>                    

                    <span class="breadcrumb-item active">Edit Settlement</span>

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

                @if(session('error'))

                <div class="alert alert-danger mb-1 mt-1">

                    {{ session('error') }}

                </div>

                @endif

                <form action="{{ url('/admin/update-settlement',$user->id) }}" method="post" enctype="multipart/form-data">

                    @csrf
                    @method('PUT')

                    <div class="form-row">

                    <div class="col-md-4 mb-3">

                        <label for="">Amount</label>

                        <input type="text" class="form-control is-valid1" id="amount" name="previousamount" placeholder="Previous Amount" value="{{ $user->settlement }}" readonly>
                        <input type="text" class="form-control is-valid1" id="amount" name="requestedamount" placeholder="Enter Amount" value="" >

                        <div class="invalid-feedback" style="display: block!important;">

                            @error('requestedamount')

                                {{ $message }}

                            @enderror

                        </div>

                        </div>

                        <div class="col-md-4 mb-3">

                        <label for="">PIN</label>

                        <input type="text" class="form-control is-valid1" id="pin" name="pin" placeholder="Enter Pin" value="" >

                        <div class="invalid-feedback" style="display: block!important;">

                            @error('pin')

                                {{ $message }}

                            @enderror

                        </div>

                        </div>

                        <div class="col-md-4 mt-4 form-group text-center">
                            <button class="btn btn-primary">Update Settlement</button>
                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@include('admin/footer')
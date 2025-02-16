@include('admin/header')

<div class="page-container">

    <!-- Content Wrapper START -->

    <div class="main-content">

        <div class="page-header">

            <h2 class="header-title">Deduct ChargeBack</h2>

            <div class="header-sub-title">

                <nav class="breadcrumb breadcrumb-dash">

                    <a href="{{url('/admin/dashboard')}}" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Home</a>                    

                    <span class="breadcrumb-item active">Deduct ChargeBack</span>

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

                <form action="{{ url('/admin/deduct-chargeback') }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    <div class="form-row">

                        <div class="col-md-4 mb-3">

                            <label for="">UTR</label>

                            <input type="text" class="form-control is-valid1" id="utr" name="utr" placeholder="enter utr" value="" >

                            <div class="invalid-feedback" style="display: block!important;">

                                @error('utr')
                                    {{ $message }}
                                @enderror

                            </div>

                        </div>
                        <div class="col-md-4 mb-3">
                        </div>
                        <div class="col-md-4 mb-3">
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

@include('admin/footer')
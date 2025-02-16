@include('user/header')

<div class="page-container">

    <!-- Content Wrapper START -->

    <div class="main-content">

        <div class="page-header">

            <h2 class="header-title">Payment Request</h2>

            <div class="header-sub-title">

                <nav class="breadcrumb breadcrumb-dash">

                    <a href="{{url('/user/dashboard')}}" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Home</a>                    

                    <span class="breadcrumb-item active">Payment</span>

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
                
                @if(session('error'))
                <div class="alert alert-danger">
                        @foreach (json_decode(session('error'), true) as $member)
                             {{ $member[0] }}
                             <br>
                        @endforeach
                    </div>
                @endif
                
                <form id="addbank" action="{{ route('user.payrequest') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="">Amount</label>
                            <input type="text" name="amount" class="form-control is-valid1" value="" id="amount" >
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="">Reference Id</label>
                            <input type="text" name="reference_number" class="form-control is-invalid1" value="" id="reference_number" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="">From Bank</label>
                            <input type="text" name="from_bank" class="form-control is-invalid1" value="" id="from_bank" required>
                        </div> 
                        
                        <div class="col-md-4 mb-3">
                            <label for="">To Bank</label>
                            <input type="text" name="to_bank" class="form-control is-invalid1" value="" id="to_bank" required>
                        </div> 

                        <div class="col-md-4 mb-3">
                            <label for="">Select Payment Type</label>
                            <select name="payment_type" id="payment_type" class="form-control is-invalid1" required>
                                <option value="NEFT">NEFT</option>
                                <option value="RTGS">RTGS</option>
                                <option value="IMPS">IMPS</option>
                            </select>                      
                        </div> 

                        <!--<div class="col-md-4 mb-3">
                            <label for="">Payment Proof</label>
                            <input type="file" name="file" class="form-control is-invalid1" value="" id="file">
                        </div> -->

                        <div class="col-md-4 mb-3">
                            <label for="">Remarks</label>
                            <input type="text" name="remarks" class="form-control is-invalid1" value="" id="remarks">
                        </div> 

                        <div class="col-md-12 mb-3">
                            <input type="submit" class="btn btn-success" value="Add Payment">                          
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

@include('user/footer')
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

                @if(session('error'))

                <div class="alert alert-danger mb-1 mt-1">

                    {{ session('error') }}

                </div>

                @endif

                

                <form id="payout" action="{{ route('payout-user.storePayout') }}" method="POST">

                    @csrf

                    <div class="form-row">

                        <div class="col-md-6 mb-3">

                            <label for="">Amount</label>

                            <input type="text" name="amount" class="form-control is-valid1" value="" id="amount" placeholder="Enter Amount" required>                   

                        </div>



                        <div class="col-md-6 mb-3">

                            <label for="">Select Payment Option</label>

                            <select name="payment_type" id="payment_type" class="form-control is-invalid1" required>

                                <option value="">-- Select Bank --</option>

                                @foreach($banks as $bank)

                                <option value="{{ $bank->id }}">{{ $bank->bank_name }}</option>

                                @endforeach

                            </select>                         

                        </div>   

                        

                        <div class="col-md-6 mb-3">

                            <label for="">Reference Number</label>

                            <input type="text" name="ref_number" class="form-control is-valid1" value="" placeholder="Enter Reference Number" id="ref_number" required>                   

                        </div>



                        <div class="col-md-6 mb-3">

                            <label for="">Descrption</label>

                            <input type="text" name="description" class="form-control is-valid1" value="" placeholder="Enter Descrption" id="description">                   

                        </div>



                        <div class="col-md-4 mb-3">

                            <input type="submit" class="btn btn-success" value="Submit">                          

                        </div>    

                    </div>

                </form>

            </div>



        </div>



    </div>



</div>



@include('payout-user/footer')



<script>    

    function changeStatus(){



        // var url = 'http://127.0.0.1:8000/api/v2/generateUpi';
        var url = 'https://dashboard.jippay.com/api/v2/generateUpi';

       

        var amount = ($("#amount").val() != "") ? $("#amount").val() : 100;

        var mobile = $("#mobile").val();

        var email = $("#email").val();

        var user_token = $('#user_token').val();



        $.ajax({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),

                'Authorization' : user_token,

            },

            type: 'POST',

            url: url,

            dataType : "json",

            data: {

                    "name": "payment",

                    "email": email,

                    "phone": mobile,

                    "amount": amount

                },



            success: function (response) {



                //    alert(response);

               //location.reload();

               $("#showRes").append(response);



            }



        });

    }

</script>
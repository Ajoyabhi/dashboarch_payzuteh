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

                {{-- <form id="dopay"> --}}

                    <div class="form-row">

                        <div class="col-md-4 mb-3">

                            <label for="">Amount</label>

                            <input type="text" class="form-control is-valid1" value="" id="amount">                       

                        </div>

                        <div class="col-md-4 mb-3">

                            <label for="">Mobile</label>

                            <input type="text" class="form-control is-invalid1" value="{{auth()->user()->mobile}}" readonly id="mobile"> 
                            <input type="hidden" class="form-control is-invalid1" value="{{auth()->user()->user_token}}" readonly id="user_token">                        

                        </div>

                        <div class="col-md-4 mb-3">

                            <label for="">email</label>

                            <input type="text" class="form-control is-invalid1" value="{{auth()->user()->email}}"readonly id="email">                       

                        </div>      

                        <div class="col-md-4 mb-3">

                            <input type="submit" class="btn btn-success" onClick="changeStatus();" value="Generate">                          

                        </div>    

                        <div class="col-md-8 mb-6">
                            <p id="showRes"></p>                
                        </div>
                    </div>
                {{-- </form> --}}
            </div>

        </div>

    </div>

</div>

@include('user/footer')

<script>    
    function changeStatus(){
        var url = 'http://127.0.0.1:8000/api/v2/generateUpi';
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
               $("#showRes").append(response);
            }

        });
    }
</script>
@extends('layout.index')
@section('content')
<section class="signup_area section--padding2">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <form role="form" method="POST" action="{{ url('/register') }}">
                    @csrf
                    <div class="cardify signup_form">
                        <div class="login--header">
                            <h3>Create Your Account</h3>
                        </div>
                        <!-- end .login_header -->
                        <div class="login--form">
                            
                            <div class="form-group required">
                                <label for="first_name">First Name </label>
                                <input id="first_name" name="first_name" type="text" class="text_field" value="{{old('first_name')}}" placeholder="Enter your First Name">
                                <span class="mcolor4">
                                    {{$errors->first('first_name')}}
                                </span>
                            </div>
                            <div class="form-group required">
                                <label for="last_name">Last Name </label>
                                <input id="last_name" name="last_name" type="text" class="text_field" value="{{old('last_name')}}" placeholder="Enter your Last Name">
                                <span class="mcolor4">
                                    {{$errors->first('last_name')}}
                                </span>
                            </div>
                            <div class="form-group required userName">
                                <label for="user_name">Username </label>
                                <input id="user_name" name="user_name" value="{{old('user_name')}}" type="text" class="text_field userNameData" placeholder="Enter your username...">
                                <span class="mcolor4 errorMsg">
                                    {{$errors->first('user_name')}}
                                </span>
                                <span class="mcolor1 successMsg">
                                </span>
                            </div>
                            
                            <div class="form-group required emailData">
                                <label for="email">Email Address </label>
                                <input id="email" name="email" value="{{old('email')}}" type="text" class="text_field" placeholder="Enter your email address">
                                <span class="mcolor4 errorMsg">
                                    {{$errors->first('email')}}
                                </span>
                                <span class="mcolor1 successMsg">
                                </span>
                            </div>
                            
                            <div class="form-group required">
                                <label for="password">Password </label>
                                <input id="password" name="password" type="password" class="text_field">
                                <span class="mcolor4">
                                    {{$errors->first('password')}}
                                </span>
                            </div>
                            <div class="form-group required">
                                <label for="con_pass">Confirm Password </label>
                                <input id="password_confirmation" name="password_confirmation" type="password" class="text_field">
                                <span class="mcolor4">
                                    {{$errors->first('password_confirmation')}}
                                </span>
                            </div>
                            <button class="btn btn--md btn--round register_btn" type="submit">Register Now</button>
                            <div class="login_assist">
                                <p>Already have an account?
                                    <a href="{{route('login')}}">Login</a>
                                </p>
                            </div>
                        </div>
                        <!-- end .login--form -->
                    </div>
                    <!-- end .cardify -->
                </form>
            </div>
            <!-- end .col-md-6 -->
        </div>
        <!-- end .row -->
    </div>
    <!-- end .container -->
</section>
@stop

@section('scripts')
<script type="text/javascript">
    
    jQuery(document).ready(function() {

        /** user name  */
        jQuery(document).on('blur',".userNameData",function(e) {
            e.preventDefault();
            jQuery(".userName").find(".successMsg,.errorMsg").html("");
            var userName = jQuery(this).val();
            var userRole = $('input[type=radio][name=user_role]:checked').length;
            if(userName != '') {
                jQuery.ajax({
                    type: "post",
                    url: public_path + "check/user",
                    data: {
                        "user_name": userName,
                    },
                    success: function(response) {
                        if (response.success) {
                            jQuery(".userName").find(".successMsg").html(response.msg);
                        } else {
                            jQuery(".userName").find(".errorMsg").html(response.msg);
                        }
                    }
                });
            }
        });

        /** email */
        jQuery(document).on('blur',"#email",function(e) {
            e.preventDefault();
            jQuery(".emailData").find(".successMsg,.errorMsg").html("");
            var email = jQuery(this).val();
            var userRole = $('input[type=radio][name=user_role]:checked').length;
            if(email != '') {
                jQuery.ajax({
                    type: "post",
                    url: public_path + "check/email",
                    data: {
                        "email": email,
                    },
                    success: function(response) {
                        if (response.success) {
                            jQuery(".emailData").find(".successMsg").html(response.msg);
                        } else {
                            jQuery(".emailData").find(".errorMsg").html(response.msg);
                        }
                    }
                });
            }
        });
    });
</script>
@stop

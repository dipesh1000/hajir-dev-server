<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Register | {{ returnSiteSetting('site_title') ?? "Hajir"}} </title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="{{ asset('backendfiles/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backendfiles/assets/css/plugins.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backendfiles/assets/css/authentication/form-1.css') }}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('backendfiles/assets/css/forms/theme-checkbox-radio.css') }} ">
    <link rel="stylesheet" type="text/css" href="{{ asset('backendfiles/assets/css/forms/switches.css') }}">
    <link rel="stylesheet" href="https://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
</head>

<body class="form">
    <div class="form-container">
        <div class="form-form">
            <div class="form-form-wrap">
                <div class="form-container">
                    <div class="form-content">
                        <h1 class="mb-4">Register to <a href="{{route('employer.register')}}"><span class="brand-name">{{ returnSiteSetting('site_title') ?? "Hajir"}} </span></a></h1>
                        <form  method="POST" action="{{ route('employer.registerSubmit') }}" class="text-left mt-2">
                            @csrf
                            <div class="form">
                                <label class="text-black mb-0">Phone Number</label>
                                <div id="username-field" class="field-wrapper input">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    <input id="phone" name="phone" type="number" min="0" maxlength="13" required value="{{ old('phone') }}" type="phone" class="form-control" placeholder="e.g 98XXXXXXXX">
                                    @if ($errors->has('phone'))
                                    <small class="text-danger">{{ $errors->first('phone') }}</small>
                                    @endif
                                </div>

                                {{-- <div id="password-field" class="field-wrapper input mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                    <input id="password" name="password" type="password" class="form-control" placeholder="Password">
                                    @if ($errors->has('password'))
                                    <small class="text-danger">{{ $errors->first('password') }}</small>
                                    @endif
                                </div> --}}
                                <div class="d-sm-flex justify-content-between">
                                    {{-- <div class="field-wrapper toggle-pass">
                                        <p class="d-inline-block">Show Password</p>
                                        <label class="switch s-primary">
                                            <input type="checkbox"  id="toggle-change-password" class="d-none">
                                            <span class="slider round"></span>
                                        </label>
                                    </div> --}}
                                    <div class="field-wrapper">
                                        <button type="submit" class="btn btn-primary" value="">Register Now</button>
                                    </div>
                                </div>
                                
                                
                            </div>
                            
                        </form>

                        {{-- <p class="terms-conditions">© 2019 All Rights Reserved. <a href="index.html">CORK</a> is a
                            product of Designreset. <a href="javascript:void(0);">Cookie Preferences</a>, <a
                                href="javascript:void(0);">Privacy</a>, and <a href="javascript:void(0);">Terms</a>.</p> --}}

                    </div>
                </div>
            </div>
        </div>
        <div class="form-image">
            <div class="l-image" style="background-image: url('{{ getOrginalUrl(returnSiteSetting('logo')) ?? "" }}')">
            </div>
        </div>
    </div>


    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('backendfiles/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('backendfiles/bootstrap/js/popper.min.js')}}"></script>
    <script src="{{ asset('backendfiles/bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('backendfiles/assets/js/authentication/form-1.js')}}"></script>
    <script src="https://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
    {!! Toastr::message() !!}

    

    <script>


        $("#toggle-change-password").click(function() {
          
            input = $('#password');
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    </script>
</body>

</html>

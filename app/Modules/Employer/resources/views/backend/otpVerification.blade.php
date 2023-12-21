<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>OTP Verification | {{ returnSiteSetting('site_title') ?? "Hajir"}} </title>
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
    <style>
        .verification-code {
            max-width: 300px;
            position: relative;
            margin:50px auto;
            text-align:center;
        }
        .control-label{
        display:block;
        margin:40px auto;
        font-weight:900;
        }
        .verification-code--inputs input[type=text] {
            border: 2px solid #e1e1e1;
            width: 46px;
            height: 46px;
            padding: 10px;
            text-align: center;
            display: inline-block;
        box-sizing:border-box;
        }
    </style>
</head>

<body class="form">


    <div class="form-container">
        <div class="form-form">
            <div class="form-form-wrap">
                <div class="form-container">
                    <div class="form-content">
                        <h1 class="">Verify OTP - <span class="brand-name">{{ returnSiteSetting('site_title') ?? "Hajir"}} </span></a></h1>
                        {{-- <p class="signup-link">New Here? <a href="auth_register.html">Create an account</a></p> --}}
                        <form  method="POST" action="{{ route('employer.verifyOtpSubmit') }}" class="text-left">
                            @csrf
                            <div class="form">

                                {{-- <div id="username-field" class="field-wrapper input">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    <input id="otp" name="otp" required value="{{ old('otp') }}" type="otp" class="form-control" placeholder="e.g test@gmail.com">
                                    @if ($errors->has('otp'))
                                    <small class="text-danger">{{ $errors->first('otp') }}</small>
                                    @endif
                                </div> --}}

                                <div class="verification-code">
                                    <label class="control-label">Verification Code</label>
                                    <div class="verification-code--inputs">
                                      <input type="text" maxlength="1" />
                                      <input type="text" maxlength="1" />
                                      <input type="text" maxlength="1" />
                                      <input type="text" maxlength="1" />
                                    </div>
                                    <input type="hidden" id="verificationCode" name="verificationCode" />
                                </div>

                                  <input type="hidden" name="phone" value="{{ $phone_no }}">
                               
                                <div class="d-sm-flex justify-content-center">
                                    
                                    <div class="field-wrapper">
                                        <button type="submit" class="btn btn-primary" value="">Verfiy OTP</button>
                                    </div>

                                </div>
                            </div>
                        </form>

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
        //Code Verification
        var verificationCode = [];
        $(".verification-code input[type=text]").keyup(function (e) {
        
        // Get Input for Hidden Field
        $(".verification-code input[type=text]").each(function (i) {
            verificationCode[i] = $(".verification-code input[type=text]")[i].value; 
            $('#verificationCode').val(Number(verificationCode.join('')));
            //console.log( $('#verificationCode').val() );
        });

        //console.log(event.key, event.which);

        if ($(this).val() > 0) {
            if (event.key == 1 || event.key == 2 || event.key == 3 || event.key == 4 || event.key == 5 || event.key == 6 || event.key == 7 || event.key == 8 || event.key == 9 || event.key == 0) {
            $(this).next().focus();
            }
        }else {
            if(event.key == 'Backspace'){
                $(this).prev().focus();
            }
        }

        }); // keyup

        $('.verification-code input').on("paste",function(event,pastedValue){
        console.log(event)
        $('#txt').val($content)
        console.log($content)
        //console.log(values)
        });

        $('#verificationCode').on('paste, keyup', function() {
        var $self = $(this);            
                    setTimeout(function(){ 
                        var $content = $self.html();             
                        $clipboard.val($content);
                    },50);
            });
    </script>
</body>

</html>

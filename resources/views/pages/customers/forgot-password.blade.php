<!DOCTYPE html>
<html>
<head> 
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /> 
	<meta name=viewport content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="/favico.png" />
    <title>Login | Enchanted Kingdom</title>

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="/dist/semantic.min.css"> 
    <link rel="stylesheet" type="text/css" href="/css/plugins/iziToast.min.css">
	<link rel="stylesheet" type="text/css" href="/css/plugins/jquery-confirm.min.css">
	<link rel="stylesheet" type="text/css" href="/css/plugins/introjs.min.css">
	<link rel="stylesheet" type="text/css" href="/css/config.css">
	<!-- CUSTOM CSS --> 
	<style type="text/css">
		body {
		     background-color: #393e46;
		   }
		   body > .grid {
		     height: 100%;
		   }
		   .image {
		     margin-top: -100px;
		   }
		   .column {
		     max-width: 450px;
		   }
	</style>
</head>
<body> 
	<!-- <div class="ui container" style="padding-top: 25px;"> -->
	  	<!-- CONTENT -->
	  	<div class="ui middle aligned center aligned grid " style="padding:5px;">
	  	  <div class="column g-padding">
	  	    <h2 class="ui teal image header">
	  	      <img src="assets/images/cropped-EK-Fav2018-192x192.png" class="image">
	  	      <div class="content">
	  	        Enchanted Kingdom
	  	      </div>
			</h2>
					
            <div class="ui segment">
                    <div style=" display:block; text-align:left;">
                        <div class="ui small breadcrumb">
                            <a href="/" class="section step3">
                                <i class="home icon"></i>
                                Home
                            </a>
                            <i class="right chevron icon divider"></i> 
                            <div class="active section">Forgot Password</div>
                        </div>
                    </div> 

            </div>

            <div class="ui two column grid">
                <div class="six wide column">
                    <div class="ui fluid vertical steps">
                        <div class="step 1">
                            {{-- // class completed step --}}
                            <i class="mobile icon"></i>
                            <div class="content">
                            <div class="title">Verification</div>
                            <div class="description">Enter Mobile number.</div>
                            </div>
                        </div>
                        <div class="step 2">
                            {{-- // class active step --}}
                            <i class="code icon"></i>
                            <div class="content">
                            <div class="title">Code Confirmation</div>
                            <div class="description">Enter the Code you received from your Mobile/Email.</div>
                            </div>
                        </div>
                        <div class="step 3">
                            {{-- // class step --}}
                            <i class="key icon"></i>
                            <div class="content">
                                <div class="title">New Password</div>
                                <div class="description">Set your new password.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ten wide column">
                    <div class="ui large form">  
                        <div class="ui segment" id="step1" style="display:none">
                            <div class="field" >
                                <label class="" style="text-align: left;">Enter Mobile number</label>
                                {{-- <div class="ui left icon input">
                                    <i class="user icon"></i>
                                    <input id="mobile_number" type="text" name="mobile_number" placeholder="ex. 09xx-xxxx-xxx" value="{{ old('mobile_number') }}">
                                </div> --}} 
                                <div class="ui mini labeled input">
                                    <div class="ui label">
                                        +63
                                    </div>
                                    <input id="mobile_number" type="text" placeholder="ex. 9xxxxxxxxx">
                                </div>
                            </div>
                            <div class="field" style="text-align: left;">
                                <div class="ui green animated button" tabindex="0" id="btnStepOneNext" >
                                    <div class="visible content">Next</div>
                                    <div class="hidden content">
                                        <i class="right arrow icon"></i>
                                    </div>
                                </div> 
                            </div>

                        </div>
                        <div class="ui segment" id="step2" style="display:none">
                            <div class="field" >
                                <label class="" style="text-align: left;">Enter Verification Code</label>
                                {{-- <div class="ui left icon input">
                                    <i class="user icon"></i>
                                    <input id="mobile_number" type="text" name="mobile_number" placeholder="ex. 09xx-xxxx-xxx" value="{{ old('mobile_number') }}">
                                </div> --}} 
                                <div class="ui mini labeled input">
                                    <div class="ui label">
                                        CODE
                                    </div>
                                    <input id="code" type="text" placeholder="xxxxxx">
                                </div>
                            </div>
                            <div class="field" style="text-align: left;">
                                <div class="ui green animated button" tabindex="0" id="btnStepTwoNext" >
                                    <div class="visible content">Next</div>
                                    <div class="hidden content">
                                        <i class="right arrow icon"></i>
                                    </div>
                                </div> 
                            </div>

                        </div>
                        <div class="ui segment" id="step3" style="display:none">
                            <div class="field" >
                                <label class="" style="text-align: left;">Enter New Password</label>
                                <div class="ui left icon input">
                                    <i class="key icon"></i>
                                    <input id="password" type="password">
                                </div> 
                            </div>
                            <div class="field" >
                                <label class="" style="text-align: left;">Retype New Password</label>
                                <div class="ui left icon input">
                                    <i class="key icon"></i>
                                    <input id="repassword" type="password">
                                </div> 
                            </div>
                            <div class="field" style="text-align: left;">
                                <div class="ui green animated button" tabindex="0" id="btnStepFinish" >
                                    <div class="visible content">Finish</div>
                                    <div class="hidden content">
                                        <i class="right arrow icon"></i>
                                    </div>
                                </div> 
                            </div>

                        </div>
                    </div>
                </div>
            </div> 

	  	  </div>
	  	</div>
	 	<!-- END CONTENT --> 
	<!-- </div> -->
	<!-- JS -->
	<script src="/js/app.js"></script>
	<script src="/dist/semantic.min.js"></script>
    <script src="/js/plugins/iziToast.min.js"></script>
	<script src="/js/plugins/jquery-confirm.min.js"></script>
	<script src="/js/plugins/intro.min.js"></script>
	<script src="/js/config.js"></script>
	<!-- CUSTOM JS --> 
	<script src="/js/pages/forgot-password.js"></script>
	<script>
		$('.message .close')
		  .on('click', function() {
		    $(this)
		      .closest('.message')
		      .transition('fade');
		  });
	</script>
</body>
</html> 
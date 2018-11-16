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

	  	<!-- CONTENT -->
	  	<div class="ui middle aligned center aligned grid">
	  	  <div class="column">
	  	    <h2 class="ui teal image header">
	  	      <img src="assets/images/cropped-EK-Fav2018-192x192.png" class="image">
	  	      <div class="content">
	  	        Enchanted Kingdom
	  	      </div>
	  	    </h2>
	  	    <form class="ui large form" method="post" action="/signup">
	  	    @csrf
	  	      <div class="ui stacked segment">
	  	      	@if (count($errors) > 0)
	  	      	<div class="ui message">
  		  	      	<i class="close icon"></i>
  			  	    <div class="header text-left">
  			  	      	Something went wrong..
  			  	    </div>
  		  	      	<ul class="list">
  		  	      		@foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach 
  		  	      	</ul>
  		  	    </div>
	  	      	@endif
	  	      	@if (session('error'))
	  	      		<div class="ui message">
	  	      			<i class="close icon"></i>
	  	      	  		<div class="header text-left">
	  	      	    		Something went wrong..
	  	      	  		</div>
	  	      	  		<p>{{ session('error') }}</p>
	  	      		</div> 
                @endif 
	  	      	<div class="field">
	  	      		<label class="" style="text-align: left;">Name</label>
	  	          <div class="ui left icon input">
	  	            <i class="user icon"></i>
	  	            <input type="text" name="name" placeholder="Name" value="{{ old('name') }}">
	  	          </div>
	  	        </div>
	  	      	<div class="field">
	  	      		<label class="" style="text-align: left;">Email</label>
	  	          <div class="ui left icon input">
	  	            <i class="envelope icon"></i>
	  	            <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}"> 
	  	          </div>
	  	        </div>
	  	        <div class="field">
	  	        	<label class="" style="text-align: left;">Mobile number</label>
	  	          <div class="ui left icon input">
	  	            <i class="user icon"></i>
	  	            <input type="text" name="mobile_number" placeholder="Mobile Number" value="{{ old('mobile_number') }}">
	  	          </div>
	  	        </div>
	  	        <div class="field">
	  	        	<label class="" style="text-align: left;">Password</label>
	  	          <div class="ui left icon input">
	  	            <i class="lock icon"></i>
	  	            <input type="password" name="password" placeholder="Password" value="{{ old('password') }}">
	  	          </div>
	  	        </div>
	  	        <button  type="submit" class="ui fluid large teal submit button">Signup</button> 
	  	      </div> 
	  	    </form>

	  	    <div class="ui message">
	  	      Already have an account? <a href="/login">Login</a>
	  	    </div>
	  	  </div>
	  	</div>
	 	<!-- END CONTENT -->
	
	<!-- JS -->
	<script src="/js/app.js"></script>
	<script src="/dist/semantic.min.js"></script>
	<script src="/js/config.js"></script>
	<!-- CUSTOM JS -->
	<script>
		
	</script>
</body>
</html> 
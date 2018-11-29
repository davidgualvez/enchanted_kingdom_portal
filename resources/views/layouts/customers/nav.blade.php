{{-- <div id="mobile-nav" class="ui container">  --}}
<div id="mobile-nav" class="ui secondary menu" style="padding: 10px;">
  <a id="mBtnMenu" class="item">
    <i class="bars icon"></i>
    <img src="/assets/images/cropped-EK-Fav2018-32x32.png" style="width: 32px; height: 32px;">
  </a>
  <!-- <div class="header item">Admin</div> -->
  <div class="right menu">  
    @auth  
    	 <div class="ui buttons fluid" style="height: 30px; margin: 10px;">
    	   	<button class="ui button">
    	   		<i class="copyright icon"></i>
    	   		{{ \Auth::user()->customer->wallet }}
    		</button>
    	   <div class="or"></div>
    	   	<button class="ui button">
    	   		&nbsp;
    	   		<i class="gift icon"></i>
    	   		{{ \Auth::user()->customer->points }} 
    	   	</button>
			
			<a  href="/cart" class="ui green button item ">
				<i class="shopping cart icon"></i>
  			   	<div class="floating ui red label cart_count">{{ \Auth::user()->cartPerBranch->count() }} </div> 
      		</a> 
    	   	  			    
    	 </div> 
    @else 
      	<a href="/login" class="ui item"> Login / Register </a>
    @endauth
  </div>
</div>
{{-- </div> --}}

{{-- <div id="desktop-nav" class="ui container"> --}}
<div id="desktop-nav" class="ui secondary  menu">
	<div class="header item">
    	<div class="item">
    	    <img src="/assets/images/cropped-EK-Fav2018-192x192.png">
    	 </div>
  	</div>
	<a href="/" class=" item">
	    Home
	</a>  
	<div class="right menu" style="padding-right: 5px;">  
	  	@auth 
	  		{{-- <a href="#" class="ui item"> --}}
  			<div class="ui buttons fluid" style="height: 30px; margin-top: 25px; margin-bottom: 25px;">
  			   	<button class="ui button">
  			   		<i class="copyright icon"></i>
  			   		{{ \Auth::user()->customer->wallet }}
  				</button>
  			   <div class="or"></div>
  			   	<button class="ui button">
  			   		&nbsp;
  			   		<i class="gift icon"></i>
  			   		{{ \Auth::user()->customer->points }} 
  			   	</button>
				
				<a  href="/cart" class="ui button item ">
					<i class="shopping cart icon"></i>
	  			   	<div class="floating ui red label cart_count">{{ \Auth::user()->cartPerBranch->count() }} </div> 
	      		</a> 
  			</div>
	  			 
	  		{{-- </a> --}} 
	  		<div class="ui right dropdown item">
	    	    {{ ucfirst(\Auth::user()->name) }}
	    	    <i class="dropdown icon"></i>
	    	    <div class="menu">
	    	    	{{-- <div class="item">
		    	      	 <div class="ui buttons fluid">
		    	      	   	<button class="ui button">
		    	      	   		<i class="copyright icon"></i>
		    	      	   		{{ \Auth::user()->customer->wallet }}
		    	      		</button>
		    	      	   <div class="or"></div>
		    	      	   	<button class="ui button">
		    	      	   		&nbsp;
		    	      	   		<i class="gift icon"></i>
		    	      	   		{{ \Auth::user()->customer->points }}
		    	      	   	</button>
		    	      	 </div>
	    	      	</div> 
	    	      	<div class="item">
	    	      		<div class="ui left labeled button" tabindex="0">
	    	      		  <a class="ui basic right pointing label cart_count">
	    	      		    {{ \Auth::user()->cartPerBranch->count() }} 
	    	      		  </a>
	    	      		  <a href="/cart" class="ui button">
	    	      		  	<i class="shopping cart icon"></i> Cart
	    	      		  </a> 
	    	      		</div>
	    	      	</div> --}}
	    	      <a href="/me" class="item">Account Details</a>
	    	      <div class="divider"></div>
	    	      <a href="/logout" class="item">Logout</a>
	    	    </div>
	    	</div> 
	    @else  
	    	<a href="/login" class="ui item">
	    		Login / Register
	    	</a> 
	    @endauth
	</div>
</div>
{{-- </div> --}}
 
<!-- //SIDEBARD MENU --> 
<div id="mMenu" class="ui sidebar vertical menu">
  	<a class="active item">
    	Enchanted Kingdom
  	</a>  

  	<div id="mdropdownmenu" class="ui item"> 
	    <!-- Home -->
	    <div class="menu">
	      <a href="/"><div class="item">Home</div></a>  
	    </div> 
  	</div> 

  	<div id="mdropdownmenu" class="ui item">  
	    <div class="menu"> 
		  	@auth
		  	  	<!-- <a href="/logout" class="ui item">Logout</a> -->
		  	  	<a href="/me"><div class="item">Account Details</div></a>  
		  	  	<a href="/logout"><div class="item">Logout</div></a>
		  	@else
		  	  	<!-- <a href="/login" class="ui item">Login / Register</a> -->
		  	  	<a href="/login"><div class="item">Login / Register</div></a>
		  	@endauth 
	    </div> 
  	</div>   
</div>

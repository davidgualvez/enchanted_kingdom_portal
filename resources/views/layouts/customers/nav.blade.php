<div id="mobile-nav" class="ui container"> 
	<div class="ui menu">
	  <a id="mBtnMenu" class="item">
	    <i class="bars icon"></i>
	    <img src="/assets/images/cropped-EK-Fav2018-32x32.png" style="width: 32px; height: 32px;">
	  </a>
	  <!-- <div class="header item">Admin</div> -->
	  <div class="right menu">  
        @auth  
          	<div class="ui right dropdown item">
	    	    {{ \Auth::user()->name }}
	    	    <i class="dropdown icon"></i>
	    	    <div class="menu">
	    	      <div class="item">
		    	      	<div class="ui left labeled button" tabindex="0">
		    	      	  <a class="ui basic right pointing label">
		    	      	    {{ \Auth::user()->customer->wallet }}
		    	      	  </a>
		    	      	  <div class="ui button">
		    	      	    <i class="tags icon"></i> eWallet
		    	      	  </div>
		    	      	</div>
	    	      </div>
	    	      <div class="item">
	    	      		<div class="ui left labeled button" tabindex="0">
	    	      		  <a class="ui basic right pointing label">
	    	      		    {{ \Auth::user()->cart->count() }}
	    	      		  </a>
	    	      		  <div class="ui button">
	    	      		    <i class="shopping cart icon"></i> Cart
	    	      		  </div>
	    	      		</div>
	    	      </div>
	    	      <div class="item">Account Details</div>
	    	      <div class="divider"></div>
	    	      <a href="/logout" class="item">Logout</a>
	    	    </div>
	    	</div> 
        @else 
          	<a href="/login" class="ui item"> Login / Register </a>
        @endauth
	  </div>
	</div>
</div>

<div id="desktop-nav" class="ui container">
	<div class="ui secondary  menu">
	<div class="header item">
    	<div class="item">
    	    <img src="/assets/images/cropped-EK-Fav2018-192x192.png">
    	 </div>
  	</div>
	  <a class="active item">
	    Home
	  </a>
	  <a class="item">
	    Ticket's
	  </a>
	  <a class="item">
	    Load Wallet
	  </a>
	  <a class="item">
	    Shop
	  </a>
	  <div class="right menu">  
	  	@auth 
	  		<div class="ui right dropdown item">
	    	    {{ \Auth::user()->name }}
	    	    <i class="dropdown icon"></i>
	    	    <div class="menu">
	    	      <div class="item">
		    	      	<div class="ui left labeled button" tabindex="0">
		    	      	  <a class="ui basic right pointing label">
		    	      	    {{ \Auth::user()->customer->wallet }}
		    	      	  </a>
		    	      	  <div class="ui button">
		    	      	    <i class="tags icon"></i> eWallet
		    	      	  </div>
		    	      	</div>
	    	      </div>
	    	      <div class="item">
	    	      		<div class="ui left labeled button" tabindex="0">
	    	      		  <a class="ui basic right pointing label">
	    	      		    {{ \Auth::user()->cart->count() }}
	    	      		  </a>
	    	      		  <div class="ui button">
	    	      		    <i class="shopping cart icon"></i> Cart
	    	      		  </div>
	    	      		</div>
	    	      </div>
	    	      <div class="item">Account Details</div>
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
</div>

<!-- //SIDEBARD MENU -->
<div id="mMenu" class="ui sidebar vertical menu">
  <a class="active item">
    Enchanted Kingdom
  </a>  
  <div id="mdropdownmenu" class="ui item"> 
    <!-- Home -->
    <div class="menu">
      <a href=""><div class="item">Home</div></a>
      <a href=""><div class="item">Ticket's</div></a>
      <a href=""><div class="item">Load Wallet</div></a>
      <a href=""><div class="item">Shop</div></a>
      <!-- <div class="item">Starred</div>
      <div class="item">Sent Mail</div>
      <div class="item">Drafts (143)</div>
      <div class="divider"></div>
      <div class="item">Spam (1009)</div>
      <div class="item">Trash</div> -->
    </div>
  </div> 
  	@auth
    	<!-- <a href="/logout" class="ui item">Logout</a> -->
    	<a href="/logout"><div class="ui item">Logout</div></a>
    @else
    	<!-- <a href="/login" class="ui item">Login / Register</a> -->
    	<a href="/login"><div class="ui item">Login / Register</div></a>
    @endauth
  
</div>

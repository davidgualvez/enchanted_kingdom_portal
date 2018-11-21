@extends('layouts.customers.main')
@section('title','My Cart')

@section('js')
<script src="https://cdn.jsdelivr.net/jsbarcode/3.6.0/JsBarcode.all.min.js"> </script>
<!-- <script src="https://cdn.jsdelivr.net/jsbarcode/3.6.0/barcodes/JsBarcode.ean-upc.min.js"></script> -->
<script src="/js/pages/me.js"></script> 
@endsection

@section('content')
<div class="ui page dimmer">
    <div class="content">
      	<h2 class="ui inverted icon header">
        	<!-- <i class="barcode icon"></i> -->
        	<svg id="barcode"></svg>

       		<div id="purchase_order">
       			
       		</div>
      	</h2>
    </div>
</div>

	<div class="ui container"> 
		<div class="ui stackable two column grid">
			<div class="six wide column " >
				
				<div class="ui two column divided grid padded" style="padding-top: 10px;">
					<div class="eight wide column">

						<div class="ui container center aligned">
							<div class="ui tiny statistic">
							    <div class="value">
							      <i class="copyright icon"></i> 
							      {{ $user->customer->wallet }} 
							    </div>
							    <div class="label">
							      Wallet
							    </div>
							</div> 
						</div>
						
					</div>
					<div class="eight wide column">

						<div class="ui container center aligned">
							<div class="ui tiny statistic">
							    <div class="value">
							      <i class="gift icon"></i> 
							      {{ $user->customer->points }} 
							    </div>
							    <div class="label">
							      Points
							    </div>
							</div> 
						</div>

					</div>
				</div>

				<div class="ui column grid"> 
					<div class="column">
						<div class="ui segment">
							<div class="ui header">Active Purchase</div>
						   	<div class="ui divided items"> 

						   	  <div class="item">
							   	    <div class="image"  style="width: 100px; height: 100px;">
							   	      <img src="https://source.unsplash.com/random/100x100" class="">
							   	    </div>
							   	    <div class="content">
							   	      <a class="header">Product name1</a>
							   	      <div class="meta">
							   	        <span class="cinema">Category</span>
							   	      </div>
							   	      <div class="description">
							   	        <p></p>
							   	      </div>
							   	      <div class="extra">
							   	        <div class="ui right floated primary tiny button btnActiveOrder" id="1">
							   	          Show Code
							   	          <i class="right chevron icon"></i>
							   	        </div>
							   	        <div class="ui mini label">Valid until</div>
							   	        <div class="ui mini label">Qty : 1</div>
							   	      </div>
							   	    </div>
						   	  </div> 

						   	  <div class="item">
							   	    <div class="image"  style="width: 100px; height: 100px;">
							   	      <img src="https://source.unsplash.com/random/100x100" class="">
							   	    </div>
							   	    <div class="content">
							   	      <a class="header">Product name2</a>
							   	      <div class="meta">
							   	        <span class="cinema">Category</span>
							   	      </div>
							   	      <div class="description">
							   	        <p></p>
							   	      </div>
							   	      <div class="extra">
							   	        <div class="ui right floated primary tiny button btnActiveOrder" id="2">
							   	          Show Code
							   	          <i class="right chevron icon"></i>
							   	        </div>
							   	        <div class="ui mini label">Valid until</div>
							   	        <div class="ui mini label">Qty : 1</div>
							   	      </div>
							   	    </div>
						   	  </div>

						   	</div>
						</div>
					</div>
				</div>

			</div>
			<div class="ten wide column ">
				<div class="ui top attached tabular menu">
				  	<a class="item active" data-tab="first">My Profile</a>
				  	<a class="item" data-tab="second">Transaction History</a> 
				</div>
				<div class="ui bottom attached tab segment active" data-tab="first">
	 		  	    <div class="ui large form"> 
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
	 		  	      	  		<p class="text-left">{{ session('error') }}</p>
	 		  	      			</div> 
	 	                	@endif 

	 		                <div class="field" >
	 			  	        	<label class="" style="text-align: left;">Full name</label>
	 			  	          	<div class="ui left icon input">
	 			  	            	<i class="user icon"></i>
	 			  	            	<input type="text" id="full_name" placeholder="Full name" value="{{ $user->customer->full_name }}">
	 			  	          	</div>
	 			  	        </div>
	 			  	        <div class="field" >
	 			  	        	<label class="" style="text-align: left;">Email</label>
	 			  	          	<div class="ui left icon input">
	 			  	            	<i class="mail icon"></i>
	 			  	            	<input type="email" id="email" placeholder="Enter email address" value="{{ $user->email }}"> 
	 			  	          	</div>
	 			  	          	<div class="ui toggle checkbox" style="margin-top: 5px;">
	 	  	            	        <input id="email_notification" type="checkbox" tabindex="0" class="hidden">
	 	  	            	        <label>Check to received emails for latest promo's</label>
	 	  	            	    </div>
	 			  	        </div>
	 			  	        <div class="field" >
	 			  	        	<label class="" style="text-align: left;">Mobile number</label>
	 			  	          	<div class="ui left icon input">
	 			  	            	<i class="phone icon"></i>
	 			  	            	<input type="text" id="mobile_number" placeholder="ex. 09xx-xxxx-xxx" value="{{ $user->mobile_number }}">
	 			  	          	</div>
	 			  	        </div>
	 			  	        <div class="field">
	 			  	        	<label class="" style="text-align: left;">New Password</label>
	 			  	          	<div class="ui left icon input">
	 			  	            	<i class="lock icon"></i>
	 			  	            	<input type="password" id="password" placeholder="New Password" value="{{ old('password') }}">
	 			  	          	</div>
	 			  	        </div>

	 			  	        <button type="submit" class="ui large teal submit button" id="btn_update_information">
	 			  	        	<i class="recycle icon"></i>
	 			  	        	Update
	 			  	        </button>   
	 		  	      	</div>  
	 		  	    </div> 
				</div>
				<div class="ui bottom attached tab segment" data-tab="second">
				  	Transaction History
				</div>  
			</div>
		</div>
	</div>
@endsection
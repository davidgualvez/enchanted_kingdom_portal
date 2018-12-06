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
						   	<div class="ui divided items" id="active_purchase"> 

						   	  {{-- <div class="item">
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
						   	  </div> --}}

						   	</div>
						</div>
					</div>
				</div>

			</div>
			<div class="ten wide column ">
				<div class="ui top attached tabular menu">
				  	<a class="item active" data-tab="first">My Profile</a>
				  	<a class="item" data-tab="second">Purchase History</a> 
				  	<a class="item" data-tab="third">Order History</a> 
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
	 			  	            	<input type="text" id="full_name" placeholder="Full name" value="{{ $user->customer->NAME }}">
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
				  	<h4 class="ui header">Summary of all Purchase's</h4>
				  	{{-- =============================================== --}} 
				  	<div id="purchaseHistory">
				  		
			  		  	<div class="ui vertical segment">
			  		  		<div style="padding: 10px;">
			  		  			<div class="ui middle aligned divided list">
			  		  				<div class="item">
			  			  			    <div class="right floated content">
			  			  			      	<strong>xx-xx-xxxx</strong>
			  			  			    </div> 
			  			  			    <div class="content">
			  			  			      Date/Time
			  			  			    </div>
			  		  			  	</div> 
			  		  			  	<div class="item">
			  			  			    <div class="right floated content">
			  			  			      	<strong>WEB/POS</strong>
			  			  			    </div> 
			  			  			    <div class="content">
			  			  			      Type
			  			  			    </div>
			  		  			  	</div> 
			  		  			  	<div class="item">
			  			  			    <div class="right floated content">
			  			  			      	<strong>0.00</strong>
			  			  			    </div> 
			  			  			    <div class="content">
			  			  			      Total Amount
			  			  			    </div>
			  		  			  	</div> 
			  		  			  	<div class="item">
			  			  			    <div class="right floated content">
			  			  			      	<strong>0.00</strong>
			  			  			    </div> 
			  			  			    <div class="content">
			  			  			      Total Discount
			  			  			    </div>
			  		  			  	</div> 
			  		  			  	<div class="item">
			  			  			    <div class="right floated content">
			  			  			      	<strong>0.00</strong>
			  			  			    </div> 
			  			  			    <div class="content">
			  			  			      Net Amount
			  			  			    </div>
			  		  			  	</div> 
			  		  			  	<div class="item">
			  			  			    <div class="right floated content">
			  			  			      	<strong>0.00</strong>
			  			  			    </div> 
			  			  			    <div class="content">
			  			  			      Earned Points
			  			  			    </div>
			  		  			  	</div> 
			  		  			  	<div class="item">
			  			  			    <div class="right floated content">
			  			  			      	<strong>0.00</strong>
			  			  			    </div> 
			  			  			    <div class="content">
			  			  			      Used Wallet
			  			  			    </div>
			  		  			  	</div> 
			  		  			  	<div class="item">
			  			  			    <div class="right floated content">
			  			  			      	<strong>0.00</strong>
			  			  			    </div> 
			  			  			    <div class="content">
			  			  			      Used Points
			  			  			    </div>
			  		  			  	</div> 
			  		  			  	<div class="item">
			  			  			    <div class="right floated content">
			  			  			      	<strong>0.00</strong>
			  			  			    </div> 
			  			  			    <div class="content">
			  			  			      Wallet Balance
			  			  			    </div>
			  		  			  	</div> 
			  		  			  	<div class="item">
			  			  			    <div class="right floated content">
			  			  			      	<strong>0.00</strong>
			  			  			    </div> 
			  			  			    <div class="content">
			  			  			      Points Balance
			  			  			    </div>
			  		  			  	</div> 
			  		  			</div>
			  		  			<div class="ui accordion">
			  		  			  <div class="title">
			  		  			    <i class="dropdown icon"></i>
			  		  			    Detail's
			  		  			  </div>
			  		  			  <div class="content">
			  		  			    <p class="transition" style="display: block !important;">
			  		  			    	{{-- //--- --}}
			  		  			    	<table class="ui small celled table">
			  		  			    	  <thead>
			  		  			    	    <tr>
			  		  			    	    	<th>Name</th>
				  		  			    	    <th>Srp</th>
				  		  			    	    <th>Qty</th>
				  		  			    	    <th>Amount</th>
				  		  			    	    <th>Discount</th>
				  		  			    	    <th>Discounted Amount</th>
				  		  			    	    <th>Valid Until</th>
			  		  			    	  	</tr>
			  		  			    	  </thead>
			  		  			    	  <tbody>
			  		  			    	    <tr>
			  		  			    	      <td>James</td>
			  		  			    	      <td>24</td>
			  		  			    	      <td>Engineer</td>
			  		  			    	      <td></td>
			  		  			    	      <td></td>
			  		  			    	      <td></td>
			  		  			    	      <td></td>
			  		  			    	    </tr> 
			  		  			    	  </tbody>
			  		  			    	</table>
			  		  			    	{{-- //--- --}}
			  		  				</p>
			  		  			  </div> 
			  		  			</div>
			  		  		</div> 
			  		  	</div> 

				  	</div>
				  	{{-- =============================================== --}}
				  	<div class="ui grid">
				  		<div class="column">
				  			<div class="tiny ui buttons">
				  			  <button class="ui button" id="prev_page_url_purchase_history">
				  			  	<i class="angle left icon"></i>
				  			  	Prev
				  			  </button> 
				  			  <button class="ui button" id="next_page_url_purchase_history">
				  			  	<i class="angle right icon"></i>
				  			  	Next
				  			  </button>
				  			</div>
				  		</div> 
				  	</div> 
				  	<br>
				  	
				</div>  
				<div class="ui bottom attached tab segment" data-tab="third">
					<h4 class="ui header">Summary of all Order's</h4>
				  	{{-- =============================================== --}} 
				  	<div id="orderHistory">
				  		
			  		  	<div class="ui vertical segment">
			  		  		<div style="padding: 10px;">
			  		  			<div class="ui middle aligned divided list">
			  		  				<div class="item">
			  			  			    <div class="right floated content">
			  			  			      	<strong>xx-xx-xxxx</strong>
			  			  			    </div> 
			  			  			    <div class="content">
			  			  			      Date/Time
			  			  			    </div>
			  		  			  	</div> 
			  		  			  	<div class="item">
			  			  			    <div class="right floated content">
			  			  			      	<strong>WEB/POS</strong>
			  			  			    </div> 
			  			  			    <div class="content">
			  			  			      Type
			  			  			    </div>
			  		  			  	</div> 
			  		  			  	<div class="item">
			  			  			    <div class="right floated content">
			  			  			      	<strong>0.00</strong>
			  			  			    </div> 
			  			  			    <div class="content">
			  			  			      Total Amount
			  			  			    </div>
			  		  			  	</div> 
			  		  			  	<div class="item">
			  			  			    <div class="right floated content">
			  			  			      	<strong>0.00</strong>
			  			  			    </div> 
			  			  			    <div class="content">
			  			  			      Total Discount
			  			  			    </div>
			  		  			  	</div> 
			  		  			  	<div class="item">
			  			  			    <div class="right floated content">
			  			  			      	<strong>0.00</strong>
			  			  			    </div> 
			  			  			    <div class="content">
			  			  			      Net Amount
			  			  			    </div>
			  		  			  	</div>   
			  		  			  	<div class="item">
			  			  			    <div class="right floated content">
			  			  			      	<a class="ui green label">Completed</a>
			  			  			      	<a class="ui yellow label">Pending</a>
			  			  			    </div> 
			  			  			    <div class="content">
			  			  			      Status
			  			  			    </div>
			  		  			  	</div>
			  		  			</div>
			  		  			<div class="ui accordion">
			  		  			  <div class="title">
			  		  			    <i class="dropdown icon"></i>
			  		  			    Detail's
			  		  			  </div>
			  		  			  <div class="content">
			  		  			    <p class="transition" style="display: block !important;">
			  		  			    	{{-- //--- --}}
			  		  			    	<table class="ui small celled table">
			  		  			    	  <thead>
			  		  			    	    <tr>
			  		  			    	    	<th>Name</th>
				  		  			    	    <th>Srp</th>
				  		  			    	    <th>Qty</th>
				  		  			    	    <th>Amount</th>
				  		  			    	    <th>Discount</th>
				  		  			    	    <th>Discounted Amount</th> 
			  		  			    	  	</tr>
			  		  			    	  </thead>
			  		  			    	  <tbody>
			  		  			    	    <tr>
			  		  			    	      <td>James</td>
			  		  			    	      <td>24</td>
			  		  			    	      <td>Engineer</td>
			  		  			    	      <td></td>
			  		  			    	      <td></td>
			  		  			    	      <td></td> 
			  		  			    	    </tr> 
			  		  			    	  </tbody>
			  		  			    	</table>
			  		  			    	{{-- //--- --}}
			  		  				</p>
			  		  			  </div> 
			  		  			</div>
			  		  		</div> 
			  		  	</div> 

				  	</div>
				  	{{-- =============================================== --}}
				  	<div class="ui grid">
				  		<div class="column">
				  			<div class="tiny ui buttons">
				  			  <button class="ui button" id="prev_page_url_order_history">
				  			  	<i class="angle left icon"></i>
				  			  	Prev
				  			  </button> 
				  			  <button class="ui button" id="next_page_url_order_history">
				  			  	<i class="angle right icon"></i>
				  			  	Next
				  			  </button>
				  			</div>
				  		</div> 
				  	</div> 
				  	<br>
				</div>
			</div>
		</div>
	</div>
@endsection
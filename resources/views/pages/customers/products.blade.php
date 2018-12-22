@extends('layouts.customers.main')
@section('title','Store')

@section('css') 
	<style type="text/css">
		
	</style>
@endsection

@section('js') 
	<script src="/js/pages/products.js"></script>
@endsection

@section('content')
	
	<h4 class="ui horizontal divider header">
	  	<i class="gift icon"></i>
	  	Our Products
	</h4>
		
	<div class="ui secondary menu"> 
	  <div class="right menu">
	    <div class="item">
	      <div class="ui icon input">
	        <input type="text" id="search_our_products" placeholder="Search products...">
	        <i class="search link icon" id="btn_search_our_products"></i>
	      </div>
	    </div>
	  </div>
	</div>

	<div class="ui five doubling cards" id="items_product">
	  <!-- <div class="card">
		    <div class="image">
		      <img src="https://source.unsplash.com/random/250x250">
		    </div>
		    <div class="content">
		      <div class="header">Matt Giampietro</div>
		      <div class="meta">
		        <a>Friends</a> 
		      </div>
		      <div class="description">
		        Matthew is an interior designer living in New York.
		      </div>
		    </div>
		    <div class="extra content">
		      <span class="right floated"> 
		        <div class="ui vertical animated button" tabindex="0">
		          <div class="hidden content">Shop</div>
		          <div class="visible content">
		            <i class="shop icon"></i>
		          </div>
		        </div>
		      </span>
		      <span> 
		        <a class="ui tag label">P 0.00</a>
		      </span>
		    </div>
	  </div>  --> 
	</div> 

	<div class="ui grid">
		<div class="column">
			<div class="tiny ui buttons">
			  <button class="ui button" id="prev_page_url_product">
			  	<i class="angle left icon"></i>
			  	Prev
			  </button> 
			  <button class="ui button" id="next_page_url_product">
			  	<i class="angle right icon"></i>
			  	Next
			  </button>
			</div>
		</div> 
	</div> 

	{{-- //DIMMER --}}
	<div class="ui page dimmer">
	    <div class="content">
	      	 <div class="ui piled segment">
	      	   <h4 class="ui header" id="product_name"></h4>
				
				<div>

                  <img id="product_image" src="">
                </div>


	      	   <p style="color: black; text-align: left; padding: 10px;" id="product_description"></p>

	      	   	<div style="text-align: left;">
	      	   		<span>
	      	       		<a class="ui violet tag label" id="product_price">P 0.00</a>
	      	     	</span>
	      	     	&nbsp;&nbsp;&nbsp;
	      	     	<span style="float: right;">
	      	       		<div id="btn_single_add_to_cart" data-id="" class="ui violet vertical animated  button" tabindex="0">
	      	         		<div class="hidden content">Add</div>
		      	         	<div class="visible content">
		      	           		<i class="shop icon"></i>
		      	         	</div>
	      	       		</div>
	      	     	</span>
	      	     	
	      	   	</div>
	      	 </div>
	    </div>
	</div>
	<br>

@endsection
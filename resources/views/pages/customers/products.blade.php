@extends('layouts.customers.main')
@section('title','Store')

@section('css') 
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

@endsection
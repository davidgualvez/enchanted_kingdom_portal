@extends('layouts.customers.main')
@section('title','Home')


@section('css')
	<link rel="stylesheet" type="text/css" href="/css/plugins/slider.css">
@endsection

@section('js')
	<script src="http://cdn.jsdelivr.net/jquery.glide/1.0.6/jquery.glide.min.js"></script>
	<script src="/js/pages/home.js"></script>
	<script> 
		$('.slider').glide({ 
		  autoplay: false,
		  arrowsWrapperClass: 'slider-arrows',
		  arrowRightText: '',
		  arrowLeftText: '' 
		});
	</script> 
@endsection

@section('content')

{{-- header --}}
<h2 class="ui center aligned icon header">
  {{-- <i class="circular birthday cake icon"></i> --}}
  Welcome to Enchanted Kingdom!
</h2>

<!-- SLIDER -->
	{{-- @include('pages.customers.components.slider') --}}
<!-- END SLIDER -->


<!-- TODAYS SPECIAL -->
	{{-- @include('pages.customers.components.today_special') --}}
	<div  style="display: flex;align-items: center!important;flex-direction: column;/* justify-items: center; */">
		<div class="ui people shape">
		  <div class="sides">
			    <div class="active side">
			    	<img class="ui fluid massive image" src="/assets/images/default.jpg" > 
			    </div>
			    <div class="side">
					<img class="ui massive image" src="/assets/images/default.jpg"> 
			    </div>
			    <div class="side">
			      	<img class="ui massive image" src="/assets/images/default.jpg"> 
			    </div>
			    <div class="side">
			      	<img class="ui massive image" src="/assets/images/default.jpg"> 
			    </div>
			    <div class="side">
			       	<img class="ui massive image" src="/assets/images/default.jpg"> 
			    </div>
			    <div class="side">
			      	<img class="ui massive image" src="/assets/images/default.jpg"> 
			    </div>
		  </div>
		</div>
	</div>
	
	<div class="ui container" style="margin-top: 10px;">
		<div class="ui animated button left floated" tabindex="0" id="btnPrev">
		  	<div class="visible content">Prev</div>
		  	<div class="hidden content">
		    	<i class="left arrow icon"></i>
		  	</div>
		</div>
		<div class="ui animated button right floated" tabindex="0" id="btnNext">
		  <div class="visible content">Next</div>
		  <div class="hidden content">
		    <i class="right arrow icon"></i>
		  </div>
		</div>
	</div>
<!-- END TODAYS SPECIAL --> 
<br>
{{-- what to do --}}
<h4 class="ui horizontal divider header">
  <i class="gift icon"></i>
 	What to do?
</h4>
<div class="ui three column stackable grid" style="padding: 25px;">
  <div class="column" >
  	
  	<div class="ui fluid card" style="padding: 20px;">
  		<div class="image">
  		   <img src="/assets/images/products/default.jpg">
  		</div>
	    <div class="content">
	      <div class="header">Site Map!</div>
	      <div class="description">
	        ...
	      </div>
	    </div>
	    <a href="/site-map" class="ui bottom attached button">
	    	Show
	    </a> 
  	</div>
  </div>

  <div class="column" >
	
  	<div class="ui fluid card" style="padding: 20px;">
  		<div class="image">
  		   <img src="/assets/images/products/default.jpg">
  		</div>
	    <div class="content">
	      <div class="header">Order Something.</div>
	      <div class="description">
	        ...
	      </div>
	    </div>
	    <a href="/store" class="ui bottom attached button">
	    	Show
	    </a> 
  	</div>
  </div>

  <div class="column" >
	
  	<div class="ui fluid card" style="padding: 20px;">
  		<div class="image">
  		   <img src="/assets/images/products/default.jpg">
  		</div>
	    <div class="content">
	      <div class="header">Things to do</div>
	      <div class="description">
	        ...
	      </div>
	    </div>
	    <a href="/things-to-do" class="ui bottom attached button">
	    	Show
	    </a> 
  	</div>
  </div>
</div>
{{-- end --}}


	

 
@endsection
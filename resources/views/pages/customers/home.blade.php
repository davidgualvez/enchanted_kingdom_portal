@extends('layouts.customers.main')
@section('title','Home')


@section('css')
<link rel="stylesheet" type="text/css" href="/css/plugins/slider.css">
@endsection

@section('js')
<script src="http://cdn.jsdelivr.net/jquery.glide/1.0.6/jquery.glide.min.js"></script>
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
<div class="slider slider1" style="height: 500px;">
  <div class="slides">
    <div class="slide-item item1"> 
    </div>
    <div class="slide-item item2"> 
    </div>
    <div class="slide-item item3">
    </div>
    <div class="slide-item item4">
    </div>
  </div>
</div>

<!-- TODAYS SPECIAL -->
<h4 class="ui horizontal divider header">
  <i class="tag icon"></i>
  Today's Special
</h4>

<div class="ui link four doubling cards">
  <div class="card">
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
	        <!-- Joined in 2013 -->
	        <div class="ui vertical animated button" tabindex="0">
	          <div class="hidden content">Shop</div>
	          <div class="visible content">
	            <i class="shop icon"></i>
	          </div>
	        </div>
	      </span>
	      <span>
	        <!-- <i class="user icon"></i>
	        75 Friends -->
	        <a class="ui tag label">P 0.00</a>
	      </span>
	    </div>
  </div> 

   <div class="card">
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
	        <!-- Joined in 2013 -->
	        <div class="ui vertical animated button" tabindex="0">
	          <div class="hidden content">Shop</div>
	          <div class="visible content">
	            <i class="shop icon"></i>
	          </div>
	        </div>
	      </span>
	      <span>
	        <!-- <i class="user icon"></i>
	        75 Friends -->
	        <a class="ui tag label">P 0.00</a>
	      </span>
	    </div>
  </div>

   <div class="card">
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
	        <!-- Joined in 2013 -->
	        <div class="ui vertical animated button" tabindex="0">
	          <div class="hidden content">Shop</div>
	          <div class="visible content">
	            <i class="shop icon"></i>
	          </div>
	        </div>
	      </span>
	      <span>
	        <!-- <i class="user icon"></i>
	        75 Friends -->
	        <a class="ui tag label">P 0.00</a>
	      </span>
	    </div>
  </div>

   <div class="card">
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
	        <!-- Joined in 2013 -->
	        <div class="ui vertical animated button" tabindex="0">
	          <div class="hidden content">Shop</div>
	          <div class="visible content">
	            <i class="shop icon"></i>
	          </div>
	        </div>
	      </span>
	      <span>
	        <!-- <i class="user icon"></i>
	        75 Friends -->
	        <a class="ui tag label">P 0.00</a>
	      </span>
	    </div>
  </div>

   <div class="card">
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
	        <!-- Joined in 2013 -->
	        <div class="ui vertical animated button" tabindex="0">
	          <div class="hidden content">Shop</div>
	          <div class="visible content">
	            <i class="shop icon"></i>
	          </div>
	        </div>
	      </span>
	      <span>
	        <!-- <i class="user icon"></i>
	        75 Friends -->
	        <a class="ui tag label">P 0.00</a>
	      </span>
	    </div>
  </div>
</div> 
<div class="ui grid">
	<div class="column">
		<div class="tiny ui buttons">
		  <button class="ui button">
		  	<i class="angle left icon"></i>
		  	Prev
		  </button> 
		  <button class="ui button">
		  	<i class="angle right icon"></i>
		  	Next
		  </button>
		</div>
	</div> 
</div> 
<!-- END TODAYS SPECIAL --> 

<!-- PRODUCT -->
<h4 class="ui horizontal divider header">
  <i class="gift icon"></i>
  Our Products
</h4>

<div class="ui link six doubling cards">
  <div class="card">
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
	        <!-- Joined in 2013 -->
	        <div class="ui vertical animated button" tabindex="0">
	          <div class="hidden content">Shop</div>
	          <div class="visible content">
	            <i class="shop icon"></i>
	          </div>
	        </div>
	      </span>
	      <span>
	        <!-- <i class="user icon"></i>
	        75 Friends -->
	        <a class="ui tag label">P 0.00</a>
	      </span>
	    </div>
  </div> 

   <div class="card">
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
	        <!-- Joined in 2013 -->
	        <div class="ui vertical animated button" tabindex="0">
	          <div class="hidden content">Shop</div>
	          <div class="visible content">
	            <i class="shop icon"></i>
	          </div>
	        </div>
	      </span>
	      <span>
	        <!-- <i class="user icon"></i>
	        75 Friends -->
	        <a class="ui tag label">P 0.00</a>
	      </span>
	    </div>
  </div>

   <div class="card">
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
	        <!-- Joined in 2013 -->
	        <div class="ui vertical animated button" tabindex="0">
	          <div class="hidden content">Shop</div>
	          <div class="visible content">
	            <i class="shop icon"></i>
	          </div>
	        </div>
	      </span>
	      <span>
	        <!-- <i class="user icon"></i>
	        75 Friends -->
	        <a class="ui tag label">P 0.00</a>
	      </span>
	    </div>
  </div>

   <div class="card">
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
	        <!-- Joined in 2013 -->
	        <div class="ui vertical animated button" tabindex="0">
	          <div class="hidden content">Shop</div>
	          <div class="visible content">
	            <i class="shop icon"></i>
	          </div>
	        </div>
	      </span>
	      <span>
	        <!-- <i class="user icon"></i>
	        75 Friends -->
	        <a class="ui tag label">P 0.00</a>
	      </span>
	    </div>
  </div>

   <div class="card">
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
	        <!-- Joined in 2013 -->
	        <div class="ui vertical animated button" tabindex="0">
	          <div class="hidden content">Shop</div>
	          <div class="visible content">
	            <i class="shop icon"></i>
	          </div>
	        </div>
	      </span>
	      <span>
	        <!-- <i class="user icon"></i>
	        75 Friends -->
	        <a class="ui tag label">P 0.00</a>
	      </span>
	    </div>
  </div>
</div> 
<div class="ui grid">
	<div class="column">
		<div class="tiny ui buttons">
		  <button class="ui button">
		  	<i class="angle left icon"></i>
		  	Prev
		  </button> 
		  <button class="ui button">
		  	<i class="angle right icon"></i>
		  	Next
		  </button>
		</div>
	</div> 
</div> 
<!-- END PRODUCT --> 
@endsection
@extends('layouts.customers.main')
@section('title','Todays Special Item Details')
@section('css') 
	<link rel="stylesheet" type="text/css" href="/css/pages/banner-detail.css">
@endsection

@section('content')

<div class="featured-banner">
	<img src="https://source.unsplash.com/user/erondu/1600x900" class="ui fluid image">
</div>

<div class="featureds">
	<div class="featured">
		<div class="img-feature">
			<img src="https://images.unsplash.com/photo-1513407030348-c983a97b98d8?ixlib=rb-0.3.5&ixid=eyJhcHBfaWQiOjEyMDd9&s=477ae5a62fd5ade3f1e3a08c013af882&auto=format&fit=crop&w=1502&q=80" class="ui fluid image">
		</div>
		<div class="featured-text">
			<h2 class="title">Max Chuchu</h2>
			<p class="description">Sed lacus leo, auctor ut odio non, tincidunt blandit nunc. Integer mauris dolor, maximus eu consectetur non, imperdiet sit amet nisi. Nam in sem finibus mi hendrerit ornare eu eu leo. Interdum et malesuada fames ac ante ipsum primis in faucibus. Sed vestibulum, magna sit amet interdum fringilla.</p>
			<p class="price">P399.00</p>
			<a href="" class="btn">Buy Now</a>
		</div>
	</div>
	<div class="featured">
		<div class="img-feature">
			<img src="https://images.unsplash.com/photo-1513407030348-c983a97b98d8?ixlib=rb-0.3.5&ixid=eyJhcHBfaWQiOjEyMDd9&s=477ae5a62fd5ade3f1e3a08c013af882&auto=format&fit=crop&w=1502&q=80" class="ui fluid image">
		</div>
		<div class="featured-text">
			<h2 class="title">Max Chuchu</h2>
			<p class="description">Sed lacus leo, auctor ut odio non, tincidunt blandit nunc. Integer mauris dolor, maximus eu consectetur non, imperdiet sit amet nisi. Nam in sem finibus mi hendrerit ornare eu eu leo. Interdum et malesuada fames ac ante ipsum primis in faucibus. Sed vestibulum, magna sit amet interdum fringilla.</p>
			<div class="actions">
				<div class="ui left labeled button big" tabindex="0" style="margin-top: 1.25rem; ">
			  <a class="ui basic right pointing label">
			    P2,048.00
			  </a>
			  <div class="ui violet button">
			    <i class="cart icon"></i> Buy now
			  </div>
			</div>
			</div>
			
		</div>
	  
	</div>
	<div class="featured">
		<div class="img-feature">
			<img src="https://images.unsplash.com/photo-1513407030348-c983a97b98d8?ixlib=rb-0.3.5&ixid=eyJhcHBfaWQiOjEyMDd9&s=477ae5a62fd5ade3f1e3a08c013af882&auto=format&fit=crop&w=1502&q=80" class="ui fluid image">
		</div>
		<div class="featured-text">
			<h2 class="title">Max Chuchu</h2>
			<p class="description">Sed lacus leo, auctor ut odio non, tincidunt blandit nunc. Integer mauris dolor, maximus eu consectetur non, imperdiet sit amet nisi. Nam in sem finibus mi hendrerit ornare eu eu leo. Interdum et malesuada fames ac ante ipsum primis in faucibus. Sed vestibulum, magna sit amet interdum fringilla.</p>
			<p class="price">P399.00</p>
			<a href="" class="btn">Buy Now</a>
		</div>
	  
	</div>
</div>
	


@endsection
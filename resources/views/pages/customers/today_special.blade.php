@extends('layouts.customers.main')
@section('title','Today Special')

@section('css') 
	<link rel="stylesheet" type="text/css" href="/css/pages/today_specials.css">
@endsection

@section('js') 
	<script src="/js/pages/today_special.js"></script>
@endsection

@section('content')


<h4 class="ui horizontal divider header">
  <i class="gift icon"></i>
 	Today Special's 
</h4> 
	
<div class="item-banner">
	<img src="{{ $banner_img }}" class="ui fluid image">
</div>

<div class="items">
	@foreach($bt as $_b)
		<div class="featured">
			<div class="img-feature">
				<img src="{{ $_b['image'] }}" class="ui fluid image">
			</div>
			<div class="featured-text">
				<h2 class="title">{{ $_b['name'] }}</h2>
				<p class="description">{{ $_b['details'] }}</p>
				<p class="price">{{ $_b['srp'] }}</p>
				<a class="btn btnAddToCart" data-id="{{ $_b['product_id'] }}">
					<i class="cart icon"></i>
					Add to Cart
				</a>
			</div>
		</div>
	@endforeach  
</div>


@endsection
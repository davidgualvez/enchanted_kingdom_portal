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

<!-- SLIDER -->
	@include('pages.components.slider')
<!-- END SLIDER -->


<!-- TODAYS SPECIAL -->
	@include('pages.components.today_special')
<!-- END TODAYS SPECIAL --> 

<!-- PRODUCT -->
	@include('pages.components.products')
<!-- END PRODUCT --> 
@endsection
@extends('layouts.customers.main')
@section('title','Rewards')


@section('css')
<link rel="stylesheet" type="text/css" href="/css/plugins/slider.css">

@endsection

@section('js')
<script src="http://cdn.jsdelivr.net/jquery.glide/1.0.6/jquery.glide.min.js"></script> 
<script src="/js/pages/rewards.js"></script>

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
	@include('pages.customers.components.slider')
<!-- END SLIDER --> 

<!-- PRODUCT -->
	@include('pages.customers.components.rewards')
<!-- END PRODUCT --> 
@endsection
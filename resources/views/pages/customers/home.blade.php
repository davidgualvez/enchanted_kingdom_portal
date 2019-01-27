@extends('layouts.customers.main')
@section('title','Home')


@section('css')
	<link rel="stylesheet" type="text/css" href="/css/plugins/slider.css">
@endsection

@section('js') 
	<script src="/js/pages/home.js"></script> 
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
	{{-- what to do --}}
	<div  data-intro='This is the area where you can find all the items with big discount.' >
	<h4 class="ui horizontal divider header">
	  <i class="gift icon"></i>
	 	Today's Specials
	</h4> 
	@if( !$dash->isEmpty() )

		<div  style="display: flex;align-items: center!important;flex-direction: column;/* justify-items: center; */">
			<div class="ui people shape">
			  <div class="sides">
					<?php $ctr = 0; ?>
			  		@foreach($dash as $d) 
			  			<?php $ctr++ ?>
			  			@if($ctr == 1)
						    <div class="active side"> 
								<a href="/today_specials/{{ $d['id'] }}">
									<img class="ui massive image" src="/assets/images/default.jpg"> 
								</a>
						    </div>
						@else
						    <div class="side"> 
								<a href="/today_specials/{{ $d['id'] }}">
									<img class="ui massive image" src="/assets/images/default.jpg"> 
								</a>
						    </div>
						@endif 
			  		@endforeach


				    {{-- <div class="active side">
				    	<a href="/today_specials"><img class="ui massive image" src="/assets/images/default.jpg" > </a>
				    </div>
				    <div class="side">
						<a href="/today_specials"><img class="ui massive image" src="/assets/images/default.jpg"> </a>
				    </div>
				    <div class="side">
				      	<a href="/today_specials"><img class="ui massive image" src="/assets/images/default.jpg"> </a>
				    </div>
				    <div class="side">
				      	<a href="/today_specials"><img class="ui massive image" src="/assets/images/default.jpg"> </a>
				    </div>
				    <div class="side">
				       	<a href="/today_specials"><img class="ui massive image" src="/assets/images/default.jpg"> </a>
				    </div>
				    <div class="side">
				      	<a href="/today_specials"><img class="ui massive image" src="/assets/images/default.jpg"> </a>
				    </div> --}}
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
	@else
		<div class="ui container center aligned">
			<h3>Nothing to display...</h3>
		</div>
	@endif
	</div>
<!-- END TODAYS SPECIAL --> 
<br>
{{-- what to do --}}
<h4 class="ui horizontal divider header">
  	<i class="gift icon"></i>
 		&nbsp;&nbsp;Fun Things to Do!&nbsp;&nbsp;
 	<i class="arrow down icon"></i>
</h4>
<div class="ui three column stackable grid" style="padding: 25px;">
  <div class="column" >
  	
  	<div class="ui fluid card" style="">
  		<div class="image">
  		   {{-- <img src="/assets/images/route.png" style="padding: 50px;"> --}}
				 <img src="/assets/images/ek-map.jpg" style="padding: 10px;">
  		</div>
	    <div class="content">
	      <div class="header">Site Map!</div>
	      <div class="description">
	        First time going to Enchanted Kingdom? Check out our handy park map below to know where to go on the day of your visit!
	      </div>
	    </div>
	    {{-- <a href="/site-map" class="ui bottom attached button" id="step3">
	    	Show
	    </a>  --}}
  	</div>
  </div>

  <div class="column center aligned" >
	
  	<div class="ui fluid card" style="">
  		<div class="image">
  		   <img src="/assets/images/store.png" style="padding: 50px;">
  		</div>
	    <div class="content">
	      <div class="header">Order Something.</div>
	      <div class="description">
	        Now there’s more magic and more memories to create with the EKsperience the Magic eWallet!
Enjoy EKsclusive perks and start your magical year today!
	      </div>
	    </div>
	    <a href="/store" class="ui bottom attached button" id="step4">
	    	Show
	    </a> 
  	</div>
  </div>

  <div class="column" >
	
  	<div class="ui fluid card" style="">
  		<div class="image">
  		   <img src="/assets/images/to-do.png" style="padding: 50px;">
  		</div>
	    <div class="content">
	      <div class="header">Things to do</div>
	      <div class="description">
	        We have a variety of world-class themed rides and attractions for all our guests, big or small, young or the young at heart!
From family friendly to exhilarating and extreme thrill rides!
	      </div>
	    </div>
	    <a href="/things-to-do" class="ui bottom attached button" id="step5">
	    	Show
	    </a> 
  	</div>
  </div>
</div>
{{-- end --}}


	

 
@endsection
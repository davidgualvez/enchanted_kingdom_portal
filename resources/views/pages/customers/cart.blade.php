@extends('layouts.customers.main')
@section('title','My Cart')

@section('js')
<script src="/js/pages/cart.js"></script>
@endsection

@section('content')
	<div class="ui container padded" style="padding: 25px;"> 
		<h3 class="ui header">My Cart</h3> 
		<!-- table -->
		<table class="ui single line table">
		  <thead>
		    <tr>
		      <th class="one wide">#</th>
		      <th class="six wide">Product</th>
		      <th class="two wide">Qty</th>
		      <th class="two wide">Unit Price</th>
		      <th class="two wide right aligned" style="padding-right: 25px;">Amount</th>
		    </tr>
		  </thead>
		  <tbody> 
		  	@forelse($carts as $key=>$cart) 
  			    <tr>
  			    	<td class="ui small header"> {{ ++$key }}</td>
  			      	<td> {{ $cart->part->name }} </td> 
  			      	<td>
  			      		<i class="question circle outline icon" data-title="*Note" data-content="The item will be automatically remove if the quantity reach negative value."></i>
  			      		
  			      		<form action="/cart/{{ $cart->id }}/decrease" method="post" style="display: inline;">
  			      			@csrf
	  			      		<button type="submit" class="ui icon mini red button">
	  		      			    <i class="minus icon"></i>
	  		      			</button>
	  		      		</form>
  		      			<div class="ui disabled mini input" style="width: 75px;">
  						  <input type="text" class="center aligned" value="{{ $cart->qty }}" >
  						</div>
  						<form action="/cart/{{ $cart->id }}/increase" method="post" style="display: inline;">
  							@csrf
							<button type="submit" class="ui icon mini green button">
			      			    <i class="plus icon"></i>
			      			</button> 
  						</form> 
  			      	</td>
  			      	<td>{{ number_format( $cart->part->srp , 2, '.', ',')  }}</td>
  			      	<td class="right aligned" style="padding-right: 25px;">{{ number_format( $cart->part->srp * $cart->qty , 2, '.', ',') }}</td>
  			    </tr>  
		  	@empty
		  		<div class="center aligned">
		  			<h3 class="ui header">Nothing to display..</h3>
		  		</div>
		  	@endforelse
		    
		  </tbody>
		  	<tfoot>
		  		<tr class="right aligned">
		  			<th colspan="4">
		  				<strong>Sub Total :  </strong> 
		  			</th>
		  			<th class="right aligned" style="padding-right: 25px;">
		  				{{ number_format( $gross , 2, '.', ',')  }}
		  			</th>
		  		</tr> 
		  		<tr class="right aligned">
		  			<th colspan="4">
		  				<strong>Discount / Voucher :  </strong> 
		  			</th>
		  			<th class="right aligned" style="padding-right: 25px;">
		  				0.00
		  			</th>
		  		</tr> 
		  		<tr>
		  			<th colspan="5">
		  				<div class="ui right floated small success icon primary button" id="checkout">
  				          <i class="check icon"></i> Checkout
  				        </div>
		  			</th>
		  		</tr>
		      	<!-- <tr>
			      	<th colspan="5">
				        <div class="ui right floated mini pagination menu">
				          <a class="icon item">
				            <i class="left chevron icon"></i>
				          </a>

				          <a class="item">1</a>
				          <a class="item">2</a>
				          <a class="item">3</a>
				          <a class="item">4</a>

				          <a class="icon item">
				            <i class="right chevron icon"></i>
				          </a>
				        </div>
			      	</th>
		    	</tr> -->
			</tfoot>
		</table>
	</div> 
  	@if (session('error')) 
			<script>
      			showWarning('Warning','{{ session('error') }}', function(){ 
      			});
      		</script> 
    @endif 
    @if (session('message')) 
	      	<script>
      			showSuccess('Success','{{ session('message') }}', function(){ 
      			});
      	</script> 
    @endif 
@endsection
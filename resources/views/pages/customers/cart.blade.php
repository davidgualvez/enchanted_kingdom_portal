@extends('layouts.customers.main')
@section('title','My Cart')

@section('js')
<script src="/js/pages/cart.js"></script>
@endsection

@section('content')
<div class="ui container padded" style="padding: 15px;" id="cart_page"> 
	<h3 class="ui header">My Cart</h3>   
		<!-- table -->
		<table class="ui single line table step1">
		  <thead>
		    <tr>
		      <th class="one wide">#</th>
		      <th class="six wide">Product</th>
		      <th class="two wide center aligned">Qty</th>
		      <th class="two wide center aligned">Unit Price</th>
		      <th class="right aligned">Discount</th>
		      <th class="two wide right aligned" style="padding-right: 25px;">Amount</th>
		    </tr>
		  </thead>
		  <tbody>  

			<?php $ctr = 0; ?>
		  	@forelse($result['items'] as $key=>$cart)  
  			    <tr>
  			    	<td class="ui small header"> {{ ++$key }}</td>
  			      	<td>
						{{ $cart['product_name'] }}  
					</td> 
  			      	<td class="center aligned">
  			      		<i class="question circle outline icon" data-title="*Note" data-content="The item will be automatically remove if the quantity is less than or equal to zero."></i>
 
  			      		<form action="/cart/{{ $cart['cart_id'] }}/decrease" method="post" style="display: inline;">
  			      			@csrf
	  			      		<button type="submit" class="ui icon mini red button btn-decrease">
	  		      			    <i class="minus icon"></i>
	  		      			</button>
	  		      		</form>
  		      			<div class="ui disabled mini input" style="width: 75px;">
  						  <input type="text" class="center aligned" value="{{ $cart['qty'] }}" >
  						</div>
  						<form action="/cart/{{ $cart['cart_id'] }}/increase" method="post" style="display: inline;">
  							@csrf
							<button type="submit" class="ui icon mini green button btn-increase">
			      			    <i class="plus icon"></i>
			      			</button> 
  						</form> 
  			      	</td>
  			      	<td class="center aligned">{{ number_format( $cart['price'] , 2, '.', ',')  }}</td>
  			      	<td class="right aligned">
						{{ number_format( $cart['scpwd_discount'] , 2, '.', ',')  }}  
  			      	</td>
  			      	<td class="right aligned" style="padding-right: 25px;">{{ number_format( $cart['net_amount'] , 2, '.', ',') }}</td>
  			    </tr>  

				@foreach($carts_modifiable_components as $key=>$cmc)
					<tr class="positive">
						<td></td>
						<td>
							<i class="caret right icon"></i>
							{{ $cmc->product->product_description }}
							<a href="/cart/{{$cart['cart_id']}}/component/{{$cmc->id}}" class="ui mini icon basic button" style="float:right">
								<i class="edit icon"></i>
								PRESS TO CHANGE
							</a>
						</td>
						<td class="center aligned">
							{{ $cmc->qty }}
						</td>
						<td colspan="3" class="right aligned">
							Additional cost 
							<strong>
							{{ 
								number_format( $cmc->qty * $cmc->price , 2, '.', ',')  
							}} 
							</strong>
						</td>
					</tr>
					@foreach($carts_none_modifiable_components as $cnmc)
						@if($cnmc->base_product_id == $cmc->base_product_id)
							<tr class="positive">
								<td></td>
								<td>
									&nbsp;
									&nbsp;
									<i class="caret right icon"></i>
									{{ $cnmc->product->product_description }} 
								</td>
								<td class="center aligned">
									{{ $cnmc->qty }}
								</td>
								<td colspan="3" class="right aligned">
									Additional cost 
									<strong>
									{{ 
										number_format( $cnmc->qty * $cnmc->price , 2, '.', ',')  
									}} 
									</strong>
								</td>
							</tr>
						@endif
					@endforeach
				@endforeach
				{{-- @foreach ($cart['cart']->components as $component ) 
					<tr class="positive">
						<td></td>
						<td>
							<i class="caret right icon"></i>
							{{ $component->product->product_description }}
							<a href="/cart/{{$cart['cart_id']}}/component/{{$component->id}}" class="ui mini icon basic button" style="float:right">
								<i class="edit icon"></i>
								PRESS TO CHANGE
							</a>
						</td>
						<td class="center aligned">
							{{ $component->qty }}
						</td>
						<td colspan="3" class="right aligned">
							Additional cost 
							<strong>
							{{ 
								number_format( $component->qty * $component->price , 2, '.', ',')  
							}} 
							</strong>
						</td> 
					</tr>
				@endforeach --}}
		  	@empty
		  		<div class="center aligned">
		  			<h3 class="ui header center aligned">Nothing to display..</h3>
		  		</div>
		  	@endforelse
		    
		  </tbody>
		  	<tfoot>
		  		<tr class="right aligned">
		  			<th colspan="5">
		  				<strong>Sub Total :  </strong> 
		  			</th>
		  			<th class="right aligned" style="padding-right: 25px;">
		  				{{ number_format( $result['net_amount'] , 2, '.', ',')  }}
		  			</th>
		  		</tr> 

					@if( $result['vatable_sales'] > 0)
						<tr class="right aligned">
							<th colspan="5">
								<strong>Vatable Sales :  </strong> 
							</th>
							<th class="right aligned" style="padding-right: 25px;">
								{{ number_format( $result['vatable_sales'] , 2, '.', ',')  }}
							</th>
						</tr>
					@endif 

					@if( $result['vat_exempt_sales'] > 0)
						<tr class="right aligned">
							<th colspan="5">
								<strong>VAT-Exemp Sales :  </strong> 
							</th>
							<th class="right aligned" style="padding-right: 25px;">
								{{ number_format( $result['vat_exempt_sales'] , 2, '.', ',')  }}
							</th>
						</tr> 
					@endif

					@if( $result['vat_zerorated_sales'] > 0)
						<tr class="right aligned">
							<th colspan="5">
								<strong>VAT Zero-Rated Sales :  </strong> 
							</th>
							<th class="right aligned" style="padding-right: 25px;">
								{{ number_format( $result['vat_zerorated_sales'] , 2, '.', ',')  }}
							</th>
						</tr> 
					@endif 

					@if( $result['vat_amount'] > 0)
						<tr class="right aligned">
							<th colspan="5">
								<strong>VAT Amount :  </strong> 
							</th>
							<th class="right aligned" style="padding-right: 25px;">
								{{ number_format( $result['vat_amount'] , 2, '.', ',')  }}
							</th>
						</tr> 
					@endif 

					@if( $result['admission_sales'] > 0)
						<tr class="right aligned">
							<th colspan="5">
								<strong>Admission Fee :  </strong> 
							</th>
							<th class="right aligned" style="padding-right: 25px;">
								{{ number_format( $result['admission_sales'] , 2, '.', ',')  }}
							</th>
						</tr> 
					@endif 

					@if( $result['amusement_tax_amount'] > 0)
						<tr class="right aligned">
							<th colspan="5">
								<strong>Amusement Tax 10% :  </strong> 
							</th>
							<th class="right aligned" style="padding-right: 25px;">
								{{ number_format( $result['amusement_tax_amount'] , 2, '.', ',')  }}
							</th>
						</tr> 
					@endif

					@if( $result['scpwd_discount'] > 0)
						<tr class="right aligned">
							<th colspan="5">
								<strong>Total SC/PWD Discount :  </strong> 
							</th>
							<th class="right aligned" style="padding-right: 25px;">
								{{ number_format( $result['scpwd_discount'] , 2, '.', ',')  }}
							</th>
						</tr> 
					@endif 
					
		  		<tr class="right aligned">
		  			<th colspan="5">
		  				<strong>(optional)Use Points :  </strong> 
		  			</th>
		  			<th class="right aligned form" style="padding-right: 25px;">
		  				<div class="ui mini input step2" style="width: 75px;">
		  				  <input type="text" placeholder="" name="points_payment" id="points_payment">
		  				</div>
		  			</th>
		  		</tr> 
		  		<tr>
		  			<th colspan="6"> 
							<strong>Type</strong>	
							<a class="ui green label">{{  $customer->getType()['type'] }}</a>
		  				<!-- <form action="/checkout" method="post"> -->
		  				@if(count($result['items']) == 0)
			  				{{-- <button disabled class="ui right floated small success icon primary button" id="checkout">
			  					<i class="check icon"></i> Checkout
			  				</button> --}}


			  				{{-- <div class="ui buttons right floated">
			  				  <button disabled class="ui blue button step3" id="order">
			  				  	<i class="cart arrow down icon"></i> Order
			  				  </button>

			  				  <div class="or"></div> --}}

			  				  <button disabled class="ui positive button step4 right floated" id="checkout">
			  				  	<i class="check icon"></i> Purchase
			  				  </button>
			  				{{-- </div> --}}
		  				@else

		  					{{-- <div class="ui buttons right floated">
		  					  <button class="ui blue button step3" id="order">
		  					  	<i class="cart arrow down icon"></i> Order
		  					  </button>

		  					  <div class="or"></div> --}}

		  					  <button class="ui positive button step4  right floated" id="checkout">
		  					  	<i class="check icon"></i> Purchase
		  					  </button>
		  					{{-- </div> --}}
 
		  				@endif
		  				
		  				<!-- <div class="ui right floated small success icon primary button" id="checkout">
  				          
  				        </div> -->
		  				<!-- </form> --> 
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

	{{-- ADDONS MODAL --}}
	<div class="ui container" id="addons_page" style="display:none;">
		<h3 class="ui header">Side Dishes</h3>
		<button class="ui button green mini" id="btnBackToCartPage"> <i class="icon angle double left"></i> Back</button> 
		<div class="ui segment" >
			{{-- <button class="ui button">Follow</button> --}}
				<div class="ui column grid" > 
					<div class="row">
						<div class="six wide column addons_category_container" style="border-right:1px solid gray;">
							{{-- --- --}}
							{{-- <button class="ui button fluid">SideDish 1</button>
							 --}}
							{{-- --- --}} 
						</div>
						<div class="ten wide column ">
							{{-- <button class="ui button">b2</button> --}}
							<div class="ui cards" id="addons_selected_container">
								<div class="card">
									<div class="image"> 
										<img class="product_image" src="/storage/" data-id="73" data-name="Coke (Large)" data-image="/storage/" data-description="Coke (Large)" data-price="20.00">
									</div>
									<div class="content">
										<div class="header">
											Test (Large)
										</div>
										{{-- <div class="meta">
											<a>PROCESSED MATERIALS</a>
										</div>
										<div class="description">
											Coke (Large)
										</div> --}}
									</div>
									<div class="extra content">
										<span class="right floated"> 
											<div id="btn-product-73" class="ui tiny violet vertical animated  button" tabindex="0">
												<div class="hidden content">
													Add
												</div>
												<div class="visible content">
													<i class="shop icon">
													</i>
												</div>
											</div>
										</span>
										<span>
											<a class="ui tiny violet tag label">P 20.00
											</a>
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
		</div> 
	</div> 
	<div class="ui page dimmer">
		<div class="content" style="width:90%; height:80%;" >
			<button class="ui button green mini"> <i class="icon angle double left"></i> Back</button> 
		<div class="ui segment" >
			{{-- <button class="ui button">Follow</button> --}}
				<div class="ui column grid" > 
					<div class="row">
						<div class="six wide column ">
							{{-- --- --}}
							<button class="ui button fluid">SideDish 1</button>
							<button class="ui button fluid">SideDish 2</button> 
							<button class="ui button fluid red">Close</button> 
							{{-- --- --}} 
						</div>
						<div class="ten wide column ">
							{{-- <button class="ui button">b2</button> --}}
							<div class="ui cards">
								<div class="card">
									<div class="image"> 
										<img class="product_image" src="/storage/" data-id="73" data-name="Coke (Large)" data-image="/storage/" data-description="Coke (Large)" data-price="20.00">
									</div>
									<div class="content">
										<div class="header">
											Coke (Large)
										</div>
										{{-- <div class="meta">
											<a>PROCESSED MATERIALS</a>
										</div>
										<div class="description">
											Coke (Large)
										</div> --}}
									</div>
									<div class="extra content">
										<span class="right floated"> 
											<div id="btn-product-73" class="ui tiny violet vertical animated  button" tabindex="0">
												<div class="hidden content">
													Add
												</div>
												<div class="visible content">
													<i class="shop icon">
													</i>
												</div>
											</div>
										</span>
										<span>
											<a class="ui tiny violet tag label">P 20.00
											</a>
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
		</div> 
		</div>
	</div>
@endsection
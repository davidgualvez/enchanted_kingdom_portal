@extends('layouts.customers.main')
@section('title','My Cart Component')

@section('js')
 
@endsection

@section('content')
    <div class="ui container padded" style="padding: 15px;" id="cart_page"> 
        <h3 class="ui header" style="display: inline">Side Dish</h3>  
         <a href="/cart" class="ui mini icon green button" style="float:right; display:inline">
            <i class="reply icon"></i>
            Back
        </a>
        <div class="ui segment" >
			{{-- <button class="ui button">Follow</button> --}} 
            <div class="ui column grid" > 
                <div class="row">
                    <div class="six wide column addons_category_container" style="border-right:1px solid gray;">
                        {{-- --- --}}
                        {{-- <button class="ui button fluid">SideDish 1</button>  --}}
                        {{-- --- --}} 
                        <div class="ui items">
                            <div class="item">
                                <div class="image">
                                    <img src="{{ $cc_detail->image}}">
                                </div>
                                <div class="content">
                                    <a class="header">{{ $cc_detail->description }}</a>
                                    {{-- <div class="meta">
                                        <span>Description</span>
                                    </div>
                                    <div class="description">
                                        <p></p>
                                    </div> --}}
                                    <div class="extra">
                                        Additional Cost 
                                        <a class="ui green label">
                                            {{ number_format( $cc_detail->srp , 2, '.', ',')  }}
                                            {{-- {{ $cc_detail->srp }} --}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ten wide column ">  
                        <div class="ui three stackable cards" id="addons_selected_container">
                            @foreach($opwsc as $item)
                            <div class="card">
                                <div class="image"> 
                                    <img class="product_image" src="{{ $item->image }}" data-id="73" data-name="Coke (Large)" data-image="/storage/" data-description="Coke (Large)" data-price="20.00">
                                </div>
                                <div class="content">
                                    <div class="header">
                                        {{ $item->description }}
                                    </div> 
                                </div>
                                <div class="extra content">
                                    <span class="right floated"> 
                                        <div id="btn-product-73" class="ui tiny violet vertical animated  button" tabindex="0">
                                            <div class="hidden content">
                                                Select
                                            </div>
                                            <div class="visible content">
                                                <i class="exchange icon">
                                                </i>
                                            </div>
                                        </div>
                                    </span>
                                    <span>
                                        <a class="ui tiny violet tag label">P {{ $item->srp }}
                                        </a>
                                    </span>
                                </div>
                            </div>  
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
		</div>  
    </div>
@endsection
@extends('layouts.customers.main')
@section('title','My Cart Component')

@section('js')
 <script src="/js/pages/cart-component.js"></script>
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
                        <div class="ui items">
                            <div class="item">
                                <div class="image"> 
                                    <a class="ui blue left corner label">
                                        <i class="icon"> <strong> {{ $base_component->qty }} </strong> </i>
                                    </a>
                                    <img src="{{ $base_component->image}}">
                                </div>
                                <div class="content">
                                    <a class="header">{{ $base_component->description }}</a>
                                    {{-- <div class="meta">
                                        <span>Description</span>
                                    </div>
                                    <div class="description">
                                        <p></p>
                                    </div> --}} 
                                    <div class="extra"> 
                                        Additional Cost 
                                        <div class="ui label">
                                            @if($base_component->price <= 0)
                                            <strong>FREE</strong>
                                            @else 
                                            {{ number_format( $base_component->price , 2, '.', ',')  }}
                                            @endif
                                            <div class="detail">Per Qty</div>
                                        </div>
                                        {{-- <a class="ui green label">
                                            {{ number_format( $cc->price , 2, '.', ',')  }}
                                          
                                        </a> --}}
                                    </div>
                                </div>
                            </div>
                            @if( count($cc_others) > 0)
                                <div class="ui horizontal divider">
                                    Or
                                </div>

                                @foreach ($cc_others as $c)
                                    <div class="item">
                                        <div class="image"> 
                                            <a class="ui blue left corner label">
                                                <i class="icon"> <strong> {{ $c->qty }} </strong> </i>
                                            </a>

                                            <a data-cc_id="{{$c->id}}" class="ui red right corner label btn-deduct_component">
                                                <i class="minus icon"></i>
                                            </a> 
                                            <img src="{{ $c->image}}"> 
                                        </div>
                                        <div class="content">
                                            <a class="header">{{ $c->description }}</a> 
                                            <div class="extra"> 
                                                Additional Cost 
                                                <div class="ui label">
                                                    @if($c->price <= 0)
                                                    <strong>FREE</strong>
                                                    @else 
                                                    {{ number_format( $c->price , 2, '.', ',')  }}
                                                    @endif
                                                    <div class="detail">Per Qty</div>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif 
                        </div>
                        
                    </div>


                    <div class="ten wide column ">  
                         {{-- three stackable  --}}
                        <div class="ui cards" id="addons_selected_container">
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
                                            <input type="text" name="product_id" value="{{ $item->id }}" hidden>
                                            <div data-pid="{{$item->id}}" class="ui tiny violet vertical animated  button btn-change" tabindex="0">
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
                                         {{-- <div class="ui label">
                                            {{ number_format( $cc->price , 2, '.', ',')  }}
                                            <div class="detail">Per Qty</div>
                                        </div> --}}
                                        <a class="ui tiny violet tag label">
                                            @if($item->srp <= 0)
                                            <strong>FREE</strong>
                                            @else 
                                            P {{ number_format( $item->srp , 2, '.', ',')  }}
                                            @endif
                                            <div class="detail">Per Qty</div>
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
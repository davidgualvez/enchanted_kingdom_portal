 
@if( count($dash) > 0 )
    <h4 class="ui horizontal divider header">
      <i class="gift icon"></i>
      Today's Special
    </h4>
    <div class="slider slider1" style="height: 350px;">
      <div class="slides">
        
        @foreach( $dash as $dash_item) 
            <div class="slide-item " style="background:url('{{ $dash_item['image'] }}') no-repeat center;"> 
            </div>
        {{-- <div class="slide-item " style="background:url('https://source.unsplash.com/random/1250x900') no-repeat center;"> 
        </div> 
        <div class="slide-item " style="background:url('https://source.unsplash.com/random/1250x900') no-repeat center;"> 
        </div>
        <div class="slide-item " style="background:url('https://source.unsplash.com/random/1250x900') no-repeat center;">
        </div>
        <div class="slide-item " style="background:url('https://source.unsplash.com/random/1250x900') no-repeat center;">
        </div> --}}
        @endforeach
 
      </div>
    </div>
@endif
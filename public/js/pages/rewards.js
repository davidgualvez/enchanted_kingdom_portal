$(document).ready(function(){
    console.log('test');

    paginateRewards();
    btnNextRewards();
    btnPrevRewards();
    limitOnChangeRewards(); 
});
  
//pagination================================ 
var current_page_rewards = null;
var prev_page_url_rewards = null;
var next_page_url_rewards = null;
var current_data_rewards = null;

function paginateRewards() {
    var limit           = $('#limit_rewards').val();
    var search_val      = $('#search_our_rewards').val(); 

    var data = {
        search              : search_val, 
        limit               : limit, 
    };

    var url = null;

    if (current_page_rewards == null) {
        current_page_rewards = 1;
    }

    //test
    // console.log(data,current_page);
    // return;
    //end 
    get(routes.rewards + "?page=" + current_page_rewards, data, function (response) {
        current_page_rewards = response.data.current_page;
        console.log(response);

        $('#current_page_product').html(current_page_rewards);
        if (response.data.next_page_url == null) {
            $('#next_page_url_rewards').addClass('disabled');
        } else {
            $('#next_page_url_rewards').removeClass('disabled');
        }

        if (response.data.prev_page_url == null) {
            $('#prev_page_url_rewards').addClass('disabled');
        } else {
            $('#prev_page_url_rewards').removeClass('disabled');
        }

        dataDisplayerRewards(response.data.data, response.data.from); 
    });
}

function btnNextRewards() {
    $('#next_page_url_rewards').on('click', function () {
        current_page_rewards++;
        paginateRewards();
    });
}

function btnPrevRewards() {
    $('#prev_page_url_rewards').on('click', function () {
        current_page_rewards--;
        paginateRewards();
    });
}

function limitOnChangeRewards() {
    $('#limit_rewards').on('change', function () {
        current_page_rewards = 1;
        paginateRewards();
    });
}

function dataDisplayerRewards(data, from) {
    var items = $('#items_rewards');
    items.empty();

    if (from == null) {
        //$('#current_page_rewards').html('Nothing to display...');
        items.append(
            '<div class="ui fluid card"> '+
              '<div class="content">'+
                '<a class="header">Nothing to display...</a>'+
              '</div>'+
            '</div>'
        );
        console.log('Nothing to display...1');
        return;
    }

    current_data_rewards = data;
    $.each(data, function (key, value) {
        // console.log(value);
        var category = value.category.name;
        items.append(
           '<div class="card">'+
                '<div class="image">'+
                  '<img src="'+value.image+'">'+
                '</div>'+
                '<div class="content">'+
                  '<div class="header">'+value.name+'</div>'+
                  '<div class="meta">'+
                    '<a>'+category+'</a> '+
                  '</div>'+
                  '<div class="description">'+
                    text_truncate(value.description, 50, '...') +
                  '</div>'+
                '</div>'+
                '<div class="extra content">'+
                  '<span class="right floated"> '+
                    '<div id="btn-product-'+value.id+'" class="ui vertical animated  button" tabindex="0">'+
                      '<div class="hidden content">Add</div>'+
                      '<div class="visible content">'+
                        '<i class="shop icon"></i>'+
                      '</div>'+
                    '</div>'+
                  '</span>'+
                  '<span> '+
                    '<a class="ui tag label">'+value.points+'</a>'+
                  '</span>'+
                '</div>'+
           '</div>'
        );
        from++;
        btnProductAddToCart(value.id);
    });
    
}
//end of pagination================



function btnProductAddToCart(id){
    $('#btn-product-'+id).on('click',function(){
        console.log(id); 
        var data = {
            product_id : id,
            qty : 1
        };
         
        post(routes.cart.points.addToCart, data, function(response){
            console.log(response);
            if(response.success == false){
                if(response.status == 401){ 
                    redirectTo('/login'); //if not authenticated
                } 
                showWarning('Warning',response.message, function(){

                });
                return;
            }

            showSuccess('Success',response.message, function(){

            });
            updateCartCount();
        });
        // showSuccess('','hello',function(){

        // });  
    });
}
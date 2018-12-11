$(document).ready(function(){
	
	console.log('Loaded..');

	//product pagination
	paginateProduct();
	btnNextProduct();
	btnPrevProduct();
	limitOnChangeProduct();

	$('#btn_search_our_products').on('click', function(){
	    paginateProduct();
	})

});

//pagination================================ 
var current_page_product = null;
var prev_page_url_product = null;
var next_page_url_product = null;
var current_data_product = null;

function paginateProduct() {
    var limit           = $('#limit_product').val();
    var search_val      = $('#search_our_products').val(); 

    var data = {
        search          : search_val, 
        limit               : limit, 
    };

    var url = null;

    if (current_page_product == null) {
        current_page_product = 1;
    }

    //test
    // console.log(data,current_page);
    // return;
    //end 
    get(routes.products + "?page=" + current_page_product, data, function (response) {
        current_page_product = response.data.current_page;
        console.log(response);

        $('#current_page_product').html(current_page_product);
        if (response.data.next_page_url == null) {
            $('#next_page_url_product').addClass('disabled');
        } else {
            $('#next_page_url_product').removeClass('disabled');
        }

        if (response.data.prev_page_url == null) {
            $('#prev_page_url_product').addClass('disabled');
        } else {
            $('#prev_page_url_product').removeClass('disabled');
        }

        dataDisplayerProduct(response.data.data, response.data.from); 
    });
}

function btnNextProduct() {
    $('#next_page_url_product').on('click', function () {
        current_page_product++;
        paginateProduct();
    });
}

function btnPrevProduct() {
    $('#prev_page_url_product').on('click', function () {
        current_page_product--;
        paginateProduct();
    });
}

function limitOnChangeProduct() {
    $('#limit_product').on('change', function () {
        current_page_product = 1;
        paginateProduct();
    });
}

function dataDisplayerProduct(data, from) {
    var items = $('#items_product');
    items.empty();

    if (from == null) {
        $('#current_page_product').html('Nothing to display...');
        return;
    }

    current_data_product = data;
    $.each(data, function (key, value) {
        // console.log(value);
        var category = value.group_name;
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
                    '<a class="ui tag label">P '+value.srp+'</a>'+
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
        
        post(routes.cart.addToCart, data, function(response){
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
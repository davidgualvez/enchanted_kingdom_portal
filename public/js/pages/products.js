$(document).ready(function(){ 

	//product pagination
	paginateProduct();
	btnNextProduct();
	btnPrevProduct();
	limitOnChangeProduct();

	$('#btn_search_our_products').on('click', function(){
	    paginateProduct();
	});
    $('#search_our_products').on('change', function(){
        paginateProduct();
    });

    $('.ui.multiple.dropdown').dropdown('setting', 'onChange', function(){
        paginateProduct();
    });

    btnSingleAddToCart();
    getCategories();

    // initGuide();
});

//pagination================================ 
var current_page_product = null;
var prev_page_url_product = null;
var next_page_url_product = null;
var current_data_product = null; 

function paginateProduct() {
    //
    var filtered_value      = $('.ui.multiple.dropdown').dropdown('get value');
    var selected_categories = filtered_value.split(","); 
    //
    var limit           = $('#limit_product').val();
    var search_val      = $('#search_our_products').val(); 

    var data = {
        search          : search_val, 
        limit           : limit, 
        categories      : selected_categories
    };

    var url = null;

    if (current_page_product == null) {
        current_page_product = 1;
    } 

    //end 
    get(routes.products + "?page=" + current_page_product, data, function (response) {
        current_page_product = response.data.current_page; 

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
        showWarning('','No Result...', function(){

        });
        
        return;
    }

    current_data_product = data;
    $.each(data, function (key, value) { 
        var category = value.group_name;
        items.append(
           '<div class="card">'+
                '<div class="image">'+ 
                  ' <img class="product_image" src="'+value.image+'" '+
                  ' data-id="'+value.id+'"' +
                  ' data-name="'+(value.name).trim()+'"' +
                  ' data-image="'+value.image+'"' +
                  ' data-description="'+(value.description).trim()+'"' +
                  ' data-price="'+value.srp+'" ' +
                  ' >' +
                '</div>'+
                '<div class="content">'+
                  '<div class="header">'+value.name+'</div>'+
                  '<div class="meta">'+
                    // '<a>'+category+'</a> '+
                  '</div>'+
                  '<div class="description">'+
                    text_truncate(value.description, 50, '...') +
                  '</div>'+
                '</div>'+
                '<div class="extra content">'+
                  '<span class="right floated"> '+
                    '<div id="btn-product-'+value.id+'" class="ui tiny violet vertical animated  button" tabindex="0">'+
                      '<div class="hidden content">Add</div>'+
                      '<div class="visible content">'+
                        '<i class="shop icon"></i>'+
                      '</div>'+
                    '</div>'+
                  '</span>'+
                  '<span> '+
                    '<a class="ui tiny violet tag label">P '+value.srp+'</a>'+
                  '</span>'+
                '</div>'+
           '</div>'
        );
        from++;
        btnProductAddToCart(value.id);
    });

     productImageOnClick();

}
//end of pagination================

function btnProductAddToCart(id){
    $('#btn-product-'+id).on('click',function(){  
        addToCart(id); 
    });
}


function productImageOnClick(){
    $('.product_image').on('click', function(){ 

        // left button
        $('.dimmer').dimmer('toggle');

        //name
        $('#product_name').text( $(this).data('name') );

        //image
        $('#product_image').attr('src', $(this).data('image') );

        //description
        $('#product_description').text( $(this).data('description') );

        //price
        $('#product_price').text( $(this).data('price') );

        //adding an id to the #btn_single_add_to_cart
        $('#btn_single_add_to_cart').data('id', $(this).data('id')); 

    });
}

function btnSingleAddToCart(){
    $('#btn_single_add_to_cart').on('click',function(){  
        var id = $(this).data('id');
        addToCart(id);
    });
}

function addToCart(id){
    var data = {
        product_id : id  ,
        qty : 1
    };
    
    post(routes.cart.addToCart, data, function(response){ 
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
        $('.dimmer').dimmer('hide');
        updateCartCount();
    });
}

function getCategories(){
    get(routes.productCategories, {}, function(response){ 
        var cat = $('#categories');
        cat.empty();
        $.each(response.data, function(key, val){ 
            //populate the categories from this response 
            cat.append(
                '<div class="categories item" data-value="'+val.group_code+'">'+
                  val.description+
                '</div>'
            );
        });
    });
}


//GUIDE FOR THIS PAGE
function initGuide() {
    if (getStorage('guide-store') != 1) {
        startGuide();
    }
}

function startGuide() {
    var intro = introJs();
    intro.setOptions({
        steps: [{
                element: '.step1',
                intro: 'Choose Category from the list to filter the result',
                position: 'right'
            },
            {
                element: '.step2',
                intro: 'Enter any product name to be search...',
                position: 'left'
            },
            {
                element: '.step3',
                intro: 'This is the area where the product list is display.'
            },
            {
                element: '.step4',
                intro: 'To go to previous page click [Prev], To go to the next page Click [Next].'
            }
        ]
    });

    intro.onexit(function () {
        setStorage('guide-store', 1);
        console.log('exit');
    });
    intro.start();
}
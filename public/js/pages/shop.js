$(document).ready(function(){
    console.log('test');

    paginateProduct();
    btnNextProduct();
    btnPrevProduct();
    limitOnChangeProduct();
});

//pagination================================
var current_page_product = null;
var prev_page_url_product = null;
var next_page_url_product = null;
var current_data_product = null;

function paginateProduct() {
    var limit           = $('#limit_product').val();
    var search_val      = $('#search_val_product').val(); 

    var data = {
        search_val          : search_val,
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
        // console.log(response);

        $('#current_page_product').html(current_page_product);
        if (response.data.next_page_url == null) {
            $('#next_page_url_product').parent().addClass('disabled');
        } else {
            $('#next_page_url_product').parent().removeClass('disabled');
        }

        if (response.data.prev_page_url == null) {
            $('#prev_page_url_product').parent().addClass('disabled');
        } else {
            $('#prev_page_url_product').parent().removeClass('disabled');
        }

        dataDisplayer(response.data.data, response.data.from); 
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
    $('#product_items_product').empty();

    if (from == null) {
        $('#current_page_product').html('Nothing to display...');
        return;
    }

    current_data_product = data;
    $.each(data, function (key, value) {
        // console.log(value);
        var category = value.category_id;
        $('#product_items').append(
             
        );
        from++;
        btnProductAddToCart(value.id);
    });
}
//end of pagination================

function btnProductAddToCart(id){
    $('#btn-product-'+id).on('click',function(){
        console.log(id); 
        showSuccess('','hello',function(){

        });
        // iziToast.success({
        //     title: 'OK',
        //     message: 'Successfully inserted record!',
        // });

    });
}
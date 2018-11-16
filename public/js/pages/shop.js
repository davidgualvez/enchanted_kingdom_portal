$(document).ready(function(){
    console.log('test');

    paginate();
    btnNext();
    btnPrev();
    limitOnChange();
});

//pagination================================
var current_page = null;
var prev_page_url = null;
var next_page_url = null;
var current_data = null;

function paginate() {
    var limit           = $('#limit').val();
    var search_val      = $('#search_val').val(); 

    var data = {
        search_val          : search_val,
        limit               : limit, 
    };

    var url = null;

    if (current_page == null) {
        current_page = 1;
    }

    //test
    // console.log(data,current_page);
    // return;
    //end
    $('.product_items').preloader();
    get(routes.products + "?page=" + current_page, data, function (response) {
        current_page = response.data.current_page;
        // console.log(response);

        $('#current_page').html(current_page);
        if (response.data.next_page_url == null) {
            $('#next_page_url').parent().addClass('disabled');
        } else {
            $('#next_page_url').parent().removeClass('disabled');
        }

        if (response.data.prev_page_url == null) {
            $('#prev_page_url').parent().addClass('disabled');
        } else {
            $('#prev_page_url').parent().removeClass('disabled');
        }

        dataDisplayer(response.data.data, response.data.from);
        $('.product_items').preloader('remove');
    });
}

function btnNext() {
    $('#next_page_url').on('click', function () {
        current_page++;
        paginate();
    });
}

function btnPrev() {
    $('#prev_page_url').on('click', function () {
        current_page--;
        paginate();
    });
}

function limitOnChange() {
    $('#limit').on('change', function () {
        current_page = 1;
        paginate();
    });
}

function dataDisplayer(data, from) {
    $('#product_items').empty();

    if (from == null) {
        $('#current_page').html('Nothing to display...');
        return;
    }

    current_data = data;
    $.each(data, function (key, value) {
        // console.log(value);
        var category = value.category_id;
        $('#product_items').append(
            '<div class="col-sm-6 col-md-3 col-lg-3">'+
            '<div class="card">'+
            '<div class="card-status bg-purple"></div>'+
            '<div class="card-body">'+
            '<div class="mb-4 text-center">'+
            '<img src="'+value.image+'" class="img-fluid">'+
            '</div>'+
            '<h4 class="card-title"><a href="#">'+value.name+'</a></h4>'+
            '<div class="card-subtitle">'+
            category+
            '</div>'+
            '<div class="mt-5 d-flex align-items-center">'+
            '<div class="product-price">'+
            '<strong>P '+value.srp+'</strong>'+
            '</div>'+
            '<div class="ml-auto">'+
            '<button id="btn-product-'+value.id+'" class="btn btn-primary"><i class="fa fa-cart-plus"></i></button> '+
            '</div>'+
            '</div>'+
            '</div>'+
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
        showSuccess('','hello',function(){

        });
        // iziToast.success({
        //     title: 'OK',
        //     message: 'Successfully inserted record!',
        // });

    });
}
$(document).ready( function(){
    console.log('order history page...');

    //order pagination 
    paginateOrderHistory();
    btnNextOrderHistory();
    btnPrevOrderHistory();
    limitOnChangeOrderHistory();

    // setTimeout(() => {
    //     initGuide();
    // }, 500);
    
});

//pagination================================ 
var current_page_order_history = null;
var prev_page_url_order_history = null;
var next_page_url_order_history = null;
var current_data_order_history = null;

function paginateOrderHistory() {
    var limit = $('#limit_order_history').val();
    var search_val = $('#search_our_order_history').val();

    var data = {
        search: search_val,
        limit: limit,
    };

    var url = null;

    if (current_page_order_history == null) {
        current_page_order_history = 1;
    }

    //test
    // console.log(data,current_page);
    // return;
    //end 
    post(routes.cart.orderHistory + "?page=" + current_page_order_history, data, function (response) {
        current_page_order_history = response.data.current_page;
        //console.log(response);

        $('#current_page_order_history').html(current_page_order_history);
        if (response.data.next_page_url == null) {
            $('#next_page_url_order_history').addClass('disabled');
        } else {
            $('#next_page_url_order_history').removeClass('disabled');
        }

        if (response.data.prev_page_url == null) {
            $('#prev_page_url_order_history').addClass('disabled');
        } else {
            $('#prev_page_url_order_history').removeClass('disabled');
        }

        dataDisplayerOrderHistory(response.data.data, response.data.from);
    });
}

function btnNextOrderHistory() {
    $('#next_page_url_order_history').on('click', function () {
        current_page_order_history++;
        paginateOrderHistory();
    });
}

function btnPrevOrderHistory() {
    $('#prev_page_url_order_history').on('click', function () {
        current_page_order_history--;
        paginateOrderHistory();
    });
}

function limitOnChangeOrderHistory() {
    $('#asd').on('change', function () {
        current_page_order_history = 1;
        paginateOrderHistory();
    });
}

function dataDisplayerOrderHistory(data, from) {
    var items = $('#orderHistory');
    items.empty();

    if (from == null) {
        items.append(
            '<div class="center aligned">' +
            '<h3 class="ui header center aligned">Nothing to display..</h3>' +
            '</div>'
        );
        //console.log('Nothing to Display...');
        return;
    }



    current_data_purchase_history = data;
    $.each(data, function (key, value) {
        // console.log(value);
        //var category = value.category.name;

        //details
        var details = '';
        details += '<table class="ui small celled table">' +
            '<thead>' +
            '<tr>' +
            '<th>Name</th>' +
            '<th class="right aligned">Price</th>' +
            '<th class="right aligned">Qty</th>' +
            '<th class="right aligned">Amount</th>' +
            '</tr>' +
            '</thead>' +
            '<tbody>';
        //console.log(value.details);
        $.each(value.details, function (key, value1) {
            details += '<tr>' +
                '<td>' + value1.product_name + '</td>' +
                '<td class="right aligned">' + value1.srp + '</td>' +
                '<td class="right aligned">' + value1.qty + '</td>' +
                '<td class="right aligned">' + value1.amount + '</td>' +
                '</tr> ';
        });
        details += '</tbody>' +
            '</table>';

        var status = '';
        if (value.status == 'p' || value.status == 'P') {
            status += '<a class="ui yellow label">Pending</a>'
        } else if (value.status == 'c' || value.status == 'C') {
            status += '<a class="ui green label">Completed</a>'
        } else if (value.status == 's' || value.status == 'S') {
            status += '<a class="ui green label">Serve</a>'
        }


        items.append(
            '<div class="ui vertical segment">' +
            '<div style="padding: 10px;">' +
            '<div class="ui middle aligned divided list">' +
            '<div class="item">' +
            '<div class="right floated content">' +
            '<strong>' + value.created_at + '</strong>' +
            '</div> ' +
            '<div class="content">' +
            'Date/Time' +
            '</div>' +
            '</div> ' +
            '<div class="item">' +
            '<div class="right floated content">' +
            '<strong>' + value.orderslip_header_id + '</strong>' +
            '</div> ' +
            '<div class="content">' +
            'Reference-#' +
            '</div>' +
            '</div> ' +
            '<div class="item">' +
            '<div class="right floated content">' +
            '<strong>' + value.trans_type + '</strong>' +
            '</div> ' +
            '<div class="content">' +
            'Type' +
            '</div>' +
            '</div> ' +
            '<div class="item">' +
            '<div class="right floated content">' +
            '<strong>' + value.total_amount + '</strong>' +
            '</div> ' +
            '<div class="content">' +
            'Total Amount' +
            '</div>' +
            '</div> ' +
            '<div class="item">' +
            '<div class="right floated content">' +
            '<strong>' + value.discount_amount + '</strong>' +
            '</div> ' +
            '<div class="content">' +
            'Total Discount' +
            '</div>' +
            '</div> ' +
            '<div class="item">' +
            '<div class="right floated content">' +
            '<strong>' + value.net_amount + '</strong>' +
            '</div> ' +
            '<div class="content">' +
            'Net Amount' +
            '</div>' +
            '</div> ' +
            '<div class="item">' +
            '<div class="right floated content">' +
            '<div class="ui right floated primary tiny button btnOrderCode" data-id="' + value.orderslip_header_id + '">' +
            'Show QRCode' +
            '<i class="right chevron icon"></i>' +
            '</div>' +
            '</div> ' +
            '<div class="content">' +
            'Barcode' +
            '</div>' +
            '</div> ' +
            '<div class="item">' +
            '<div class="right floated content">' +
            status +
            '</div> ' +
            '<div class="content">' +
            'Status' +
            '</div>' +
            '</div> ' +
            '</div>' +
            '<div class="ui accordion">' +
            '<div class="title">' +
            '<i class="dropdown icon"></i>' +
            "Details" +
            '</div>' +
            '<div class="content">' +
            '<p class="transition" style="display: block !important;">' +
            details +
            '</p>' +
            '</div> ' +
            '</div>' +
            '</div> ' +
            '</div> '
        );

        showOrderCode(value.orderslip_header_id);
    });

    $('.ui.accordion').accordion();
} 
//end of pagination================

function showOrderCode(id) {
    $('.btnOrderCode').on('click', function () { 
        var _id = $(this).data('id'); 
        $('.ui.dimmer').dimmer("show");
 
        $('#code-value').empty();
        var qrcode = new QRCode(document.getElementById("code-value"), {
            text: '' + _id,
            width: 128,
            height: 128,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
        $('#code-name').html('ref # - '+_id);
    });
}


//GUIDE FOR THIS PAGE
function initGuide() {
    if (getStorage('guide-order_history') != 1) {
        startGuide();
    }
}

function startGuide() {
    var intro = introJs();
    intro.setOptions({
        steps: [{
            element: '.step1',
            intro: 'This is the area where you can view all your ordered items from the start, filtered from latest to the last record. If you place an order you can present a QRcode to the nearest POS by pressing the Show QRCode button from the list .',
            position: 'top'
        }]
    });

    intro.onexit(function () {
        setStorage('guide-order_history', 1);
        console.log('exit');
    });
    intro.start();
}
$(document).ready(function(){
    console.log('loaded...');

    paginatePurchaseHistory();
    btnNextPurchaseHistory();
    btnPrevPurchaseHistory();
    limitOnChangePurchaseHistory();
    
    setTimeout(() => {
        initGuide();
    }, 500);
    
});


//pagination================================ 
var current_page_purchase_history = null;
var prev_page_url_purchase_history = null;
var next_page_url_purchase_history = null;
var current_data_purchase_history = null;

function paginatePurchaseHistory() {
    var limit = $('#limit_purchase_history').val();
    var search_val = $('#search_our_purchase_history').val();

    var data = {
        search: search_val,
        limit: limit,
    };

    var url = null;

    if (current_page_purchase_history == null) {
        current_page_purchase_history = 1;
    }

    //test
    // console.log(data,current_page);
    // return;
    //end 
    post(routes.user.purchaseHistory + "?page=" + current_page_purchase_history, data, function (response) {
        current_page_purchase_history = response.data.current_page;
        //console.log(response);

        $('#current_page_purchase_history').html(current_page_purchase_history);
        if (response.data.next_page_url == null) {
            $('#next_page_url_purchase_history').addClass('disabled');
        } else {
            $('#next_page_url_purchase_history').removeClass('disabled');
        }

        if (response.data.prev_page_url == null) {
            $('#prev_page_url_purchase_history').addClass('disabled');
        } else {
            $('#prev_page_url_purchase_history').removeClass('disabled');
        }

        dataDisplayerPurchaseHistory(response.data.data, response.data.from);
    });
}

function btnNextPurchaseHistory() {
    $('#next_page_url_purchase_history').on('click', function () {
        current_page_purchase_history++;
        paginatePurchaseHistory();
    });
}

function btnPrevPurchaseHistory() {
    $('#prev_page_url_purchase_history').on('click', function () {
        current_page_purchase_history--;
        paginatePurchaseHistory();
    });
}

function limitOnChangePurchaseHistory() {
    $('#asd').on('change', function () {
        current_page_purchase_history = 1;
        paginatePurchaseHistory();
    });
}

function dataDisplayerPurchaseHistory(data, from) {
    var items = $('#purchaseHistory');
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
            '<th class="right aligned">Retail Price</th>' +
            '<th class="right aligned">Qty</th>' +
            '<th class="right aligned">Amount</th>' +
            '<th class="right aligned">Discount</th>' +
            '<th class="right aligned">Discounted Amount</th>' +
            '<th class="right aligned">Valid Until</th>' +
            '</tr>' +
            '</thead>' +
            '<tbody>';
        //console.log(value.details);
        $.each(value.details, function (key, value1) {
            details += '<tr>' +
                '<td>' + value1.part_name + '</td>' +
                '<td class="right aligned">' + value1.srp + '</td>' +
                '<td class="right aligned">' + value1.qty + '</td>' +
                '<td class="right aligned">' + value1.amount + '</td>' +
                '<td class="right aligned">' + value1.discount + '</td>' +
                '<td class="right aligned">' + value1.net_amount + '</td>' +
                '<td class="right aligned">' + value1.valid_until + '</td>' +
                '</tr> ';
        });
        details += '</tbody>' +
            '</table>';

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
            '<strong>' + value.sales_order_id + '</strong>' +
            '</div> ' +
            '<div class="content">' +
            'Reference-#' +
            '</div>' +
            '</div> ' +
            '<div class="item">' +
            '<div class="right floated content">' +
            '<strong>' + value.type + '</strong>' +
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
            '<strong>' + value.total_discount + '</strong>' +
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
            '<strong>' + value.added_points + '</strong>' +
            '</div> ' +
            '<div class="content">' +
            'Earned Points' +
            '</div>' +
            '</div> ' +
            '<div class="item">' +
            '<div class="right floated content">' +
            '<strong>' + value.used_wallet + '</strong>' +
            '</div> ' +
            '<div class="content">' +
            'Used Wallet' +
            '</div>' +
            '</div> ' +
            '<div class="item">' +
            '<div class="right floated content">' +
            '<strong>' + value.used_points + '</strong>' +
            '</div> ' +
            '<div class="content">' +
            'Used Points' +
            '</div>' +
            '</div> ' +
            '<div class="item">' +
            '<div class="right floated content">' +
            '<strong>' + value.wallet_balance + '</strong>' +
            '</div> ' +
            '<div class="content">' +
            'Wallet Balance' +
            '</div>' +
            '</div> ' +
            '<div class="item">' +
            '<div class="right floated content">' +
            '<strong>' + value.points_balance + '</strong>' +
            '</div> ' +
            '<div class="content">' +
            'Points Balance' +
            '</div>' +
            '</div> ' +
            '</div>' +
            '<div class="ui accordion">' +
            '<div class="title">' +
            '<i class="dropdown icon"></i>' +
            "Detail's" +
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

    });

    $('.ui.accordion').accordion();
}
//end of pagination================ 



//GUIDE FOR THIS PAGE
function initGuide() {
    if (getStorage('guide-purchase_history') != 1) {
        startGuide();
    }
}

function startGuide() {
    var intro = introJs();
    intro.setOptions({
        steps: [{
                element: '.step1',
                intro: 'This is the area where you can view all your purchases from the start, filtered from latest to the last record'
            }
        ]
    });

    intro.onexit(function () {
        setStorage('guide-purchase_history', 1);
        console.log('exit');
    });
    intro.start();
}
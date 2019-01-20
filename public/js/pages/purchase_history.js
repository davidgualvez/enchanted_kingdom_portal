$(document).ready(function(){
    console.log('loaded...');

    paginatePurchaseHistory();
    btnNextPurchaseHistory();
    btnPrevPurchaseHistory();
    limitOnChangePurchaseHistory();
    
    // setTimeout(() => {
    //     initGuide();
    // }, 500);
    
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
            '<th class="right aligned">Price</th>' +
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

        var btn_or_disabler = '';
        if ((value.type).trim() == 'POS'){
            btn_or_disabler = 'disabled';
        } 
        var virtualReceipt = '';
        virtualReceipt += '<div class="ui green mini button vr '+btn_or_disabler+'"> <i class="print icon"></i>Receipt</div>'+
            '<div class="ui flowing popup top left transition hidden">'+
                '<div class="ui two column divided center aligned grid">'+
                    '<div class="column">'+
                        // '<h4 class="ui header">Basic Plan</h4>'+
                        // '<p><b>2</b> projects, $10 a month</p>'+
                        '<div class="ui blue button btn-or-preview" data-id="'+value.sales_order_id+'">Preview</div>'+
                    '</div>'+
                    '<div class="column">'+
                        // '<h4 class="ui header">Business Plan</h4>'+
                        // '<p><b>5</b> projects, $20 a month</p>'+
                        '<div class="ui red button btn-or-download" data-id="' + value.sales_order_id +'">Download</div>'+
                    '</div>'+ 
                '</div>'+
            '</div>';

        items.append(
            '<div class="ui vertical segment">' +
                '<div style="padding: 10px;">' +
                '<div class="ui middle aligned divided list">' +
                    '<div class="item">' +
                        '<div class="right floated content">' +
                            virtualReceipt +
                        '</div> ' +
                        '<div class="content">' +
                            '<strong>Official Receipt</strong>' +
                        '</div>' +
                    '</div> ' +
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
    $('.button.vr')
        .popup({
            popup: $('.flowing.popup'),
            on: 'click'
        })
        ;
    btnOrPreview();
    btnOrDownload();
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

/**
 * OR Preview
 */
function btnOrPreview(){
    $('.btn-or-preview').on('click', function(){
        orLayout('preview');
    });
}

 /**
  * OR Download
  */
function btnOrDownload() {
    $('.btn-or-download').on('click', function () {
        orLayout('download');
    });
}

  /**
   * OR Layout
   */

function orLayout(v) {
    var docDefinition = {
        content: [
            {
                fontSize: '9',
                text: ['ENCHANTED KINGDOM\nSan Lorenzo South, Sta. Rosa Laguna\nVAT Registered TIN: 000-000-000-0000\n',
                    'MIN: XXXXXXXX \t',
                    'SN: XXXXXXXX\n\n',
                    'FOR EVALUATION PURPOSES ONLY\n\n',
                    '========================================\n',
                    '01/19/2019 01:43PM\t',
                    'OR #:000-000000157\n',
                    'POS1   Tintin Reyes\t',
                    'Tran#:0000000221\n\n',
                    '*** CUSTOMER COPY ***\n',
                ],
                alignment: 'center'
            },

            {
                columns: [
                    {

                        text: 'REGULAR DAY PASS - WEEKD',
                    },
                    {
                        width: '163',
                        text: '800.00',
                    }
                ],
                style: 'prod'
            },

            {
                columns: [
                    {
                        text: 'Less Vat 12%',

                    },
                    {
                        width: '153',
                        text: '(69.64)',
                    }
                ],
                style: 'left1'
            },

            {
                columns: [
                    {
                        text: 'Less Amu.Tax 10%',
                    },
                    {
                        width: '153',
                        text: '(13.64)',
                    }
                ],
                style: 'left1'
            },

            {
                columns: [
                    {
                        text: 'Senior 20% x 580.3571',
                    },
                    {
                        width: '159',
                        text: '(116.07)',
                    }
                ],
                style: 'left1'
            },

            {

                columns: [
                    {
                        text: 'Senior 20% x 136.3636',
                    },
                    {
                        width: '153',
                        text: '(27.27)',
                    }
                ],
                style: 'left1'
            },

            {
                columns: [
                    {

                        text: 'PORK BARBEQUE SANDWICH',
                    },
                    {
                        width: '158',
                        text: '40.00',
                    }
                ],
                style: 'prod'
            },

            {

                columns: [
                    {
                        text: 'Less VAT 12%',
                    },
                    {
                        width: '151',
                        text: '(4.29)',
                    }
                ],
                style: 'left1'

            },

            {

                columns: [
                    {
                        text: 'Senior 20% x 35.71',
                    },
                    {
                        width: '151',
                        text: '(7.14)',
                    }
                ],
                style: 'left1'

            },

            {
                columns: [
                    {

                        text: 'TSM EK FWRKS WTHGLD F XS',
                    },
                    {
                        width: '160',
                        text: '300.00',
                    }
                ],
                style: 'prod'
            },

            {
                columns: [
                    {
                        text: 'Subtotal',

                    },
                    {
                        width: '151',
                        text: '901.95',
                    }
                ],
                style: 'left1'
            },

            {
                columns: [
                    {
                        text: 'Total',

                    },
                    {
                        width: '151',
                        text: '901.95',
                    }
                ],
                style: 'left2'
            },

            {
                columns: [
                    {
                        text: 'CASH',

                    },
                    {
                        width: '151',
                        text: '901.95',
                    }
                ],
                style: 'left1'
            },

            {
                columns: [
                    {
                        text: 'VATable Sales',

                    },
                    {
                        width: '151',
                        text: '267.86',
                    }
                ],
                style: 'left2'
            },

            {
                columns: [
                    {
                        text: 'Vat-Exempt Sales',

                    },
                    {
                        width: '151',
                        text: '267.86',
                    }
                ],
                style: 'left1'
            },

            {
                columns: [
                    {
                        text: 'Vat Zero-Rated Sales',

                    },
                    {
                        width: '137',
                        text: '0.00',
                    }
                ],
                style: 'left1'
            },

            {
                columns: [
                    {
                        text: 'Vat Amount',

                    },
                    {
                        width: '143',
                        text: '32.14',
                    }
                ],
                style: 'left1'
            },

            {
                columns: [
                    {
                        text: 'Admission Fee',

                    },
                    {
                        width: '149',
                        text: '136.36',
                    }
                ],
                style: 'left1'
            },

            {
                columns: [
                    {
                        text: 'Amusement Tax 10%',

                    },
                    {
                        width: '136',
                        text: '0.00',
                    }
                ],
                style: 'left1'
            },

            {
                columns: [
                    {
                        text: 'Total SC / PWD Discount',

                    },
                    {
                        width: '153',
                        text: '(150.49)',
                    }
                ],
                style: 'left1'
            },

            {
                text: [
                    'Patrick Villamor\n',
                    'Discount #:\n',
                    'Las Piñas City\n',


                ],
                style: 'left'
            },


            {
                text: [
                    '3 Item(s)\n',
                    'This serves as your Official Receipt'
                ],
                style: 'center2'
            },





            {
                text: [
                    'Customer Name:_________________________________\n',
                    'Address:_________________________________________\n',
                    'TIN:______________________________________________\n',
                    'Business Style:___________________________________\n\n',
                ],
                style: 'left'
            },

            {
                text: [
                    'POS PROVIDER:\n',
                    'DATALOGIC SYSTEMS CORPORATION\n',
                    'Unit 1202 Asian Star Bldg., Asean Drive,\n',
                    'cor, Singapura Lane, Filinvest Corporate\n',
                    'City, Muntinlupa\n',
                    'TIN: 53B-202396939-000012\n',
                    'Date Issued: March 18, 2005\n',
                    'Valid Until: July 31, 2020\n',
                    'PTU#: XXXXXXXX-XXX-XXXXX-XXXX',
                ],
                style: 'center1'
            },

            {
                text: [
                    'THIS INVOICE/RECEIPT SHALL BE VALID FOR\n',
                    'FIVE (5) YEARS FROM THE DATE OF THE\n',
                    'PERMIT USE',

                ],
                style: 'center1'
            },

            {
                text: [
                    'Thank you! Please come again :-)\n',
                    'www.datalogicorp.com\n', 
                ],
                style: 'footer'
            },

        ],



        styles: {
            vat: { // vat
                fontSize: '9',
                margin: [160, 0, 10, 0],
            },

            center: {
                alignment: 'center',
                fontSize: '9',
            },

            center1: {
                alignment: 'center',
                margin: [5, 5],
                fontSize: '9',
            },

            center2: {
                alignment: 'center',
                margin: [0, 10, 10, 0],
                fontSize: '9',
            },

            left: { // vat
                fontSize: '9',
                margin: [125, 20, 10, 0],
            },

            left1: { // vat
                fontSize: '9',
                margin: [170, 3, 10, 0],
                //width:'80%'
            },

            left2: { // vat
                fontSize: '9',
                margin: [170, 12, 10, 0],
                //width:'80%'
            },


            neg: { // negative value

                alignment: 'right',
                margin: [0, 0, 130, 4],
                fontSize: '9',

            },


            prod: {
                margin: [125, 10, 2, 0],
                fontSize: '9',

            },

            footer: {
                // [left, top, right, bottom] or [horizontal, vertical] or just a number for equal margins
                alignment: 'center',
                margin: [5, 5, 5, 0],
                fontSize: '9',
            }, 
        },

         //pageSize: 'A5',
        pageSize: { width: 250, height: 'auto' },
        // [left, top, right, bottom] or [horizontal, vertical] or just a number for equal margins
        pageMargins: [5, 25, 5, 5],
    };

    if (v == 'preview') {
        pdfMake.createPdf(docDefinition).open(
        );
    }

    if (v == 'download') {
        pdfMake.createPdf(docDefinition).download(
            'Enchanted Kingdom OR - ' + 
            '.pdf'
        );
    }
}
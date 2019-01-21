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
        if (value.type == 'POS'){
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
    $('.btn-or-preview').on('click', function () {
        var id = $(this).data('id'); 
        orLayout('preview', id);
    });
}

 /**
  * OR Download
  */
function btnOrDownload() {
    $('.btn-or-download').on('click', function () {
        var id = $(this).data('id'); 
        orLayout('download',id);
    });
}

  /**
   * OR Layout
   */

function orLayout(v,sales_order_id) {
    //console.log(current_data_purchase_history);
    var transaction = null;
    $.each(current_data_purchase_history, function (key, val){
        
        if( val.sales_order_id == sales_order_id ){ 

            transaction = val.purchase_transaction;

        }

    });

    console.log(transaction);

    /**
     * getting the transaction to be print
     */

     // initializing date and time of transaction
    var transDate   = moment(transaction.created_at);
    var transOr     = FormatNumberLength(transaction.invoice_no, 9);
    var transNo     = FormatNumberLength(transaction.transaction_no, 9)
    var transItems  = [];
    var transTotal  = null;
    var transCash   = null;
    var transChange = null;

    //populating item to be display
    $.each(transaction.details, function(key,val){
        console.log(val);
        // if (transaction.customer_type == null || transaction.customer_type == 0){
        
        // }  
        transItems.push(
            {
                columns: [
                    {
                        text: val.product_name + ' x ' + val.qty,
                    },
                    {
                        text: parseFloat(val.net_amount).toFixed(2),
                        style: 'amount',
                        width: 50
                    }
                ],
                style: 'product'
            }
        );

        // if PWD || SC
        if ( parseFloat(val.r_vat_amount) > 0) {
            console.log('true');
            transItems.push(
                {
                    columns: [
                        {
                            text: 'Less VAT 12%',
                        },
                        {
                            text: '-' + parseFloat(val.r_vat_amount).toFixed(2),
                            style: 'amount',
                            width: 50
                        }
                    ],
                    style: 'productSubSub'
                }
            );
        }  
        if (parseFloat(val.r_amusement_tax_amount) > 0){
            transItems.push(
                 {
                    columns: [
                        {
                            text: 'Less Amu.Tax 10%',
                        },
                        { 
                            text: '-' + parseFloat(val.r_amusement_tax_amount).toFixed(2),
                            style: 'amount',
                            width: 50
                        }
                    ],
                    style: 'productSubSub'
                }
            );
        }

    });

    //TOTAL, CASH, CHANGE
    transTotal = {
        columns: [
            {
                text: 'TOTAL',
            },
            {
                text: parseFloat(transaction.net_total).toFixed(2),
                style: 'amount',
                width: 50
            }
        ],
        style: 'productSubTotal'
    };
    transCash = {
        columns: [
            {
                text: 'WALLET/POINTS PAYMENT',
            },
            {
                text: parseFloat(transaction.net_total).toFixed(2),
                style: 'amount',
                width: 50
            }
        ],
        style: 'productSubCash'
    };
    transChange = {
        columns: [
            {
                text: 'CHANGE',
            },
            {
                text: parseFloat('0').toFixed(2),
                style: 'amount',
                width: 50
            }
        ],
        style: 'productSubChange'
    };

     // SCPWD Detail
    var scPwdDetail = [
        {
            columns: [ 
                {
                    text: 'Jose Rizal', 
                    bold: true
                }
            ], 
            style: 'scpwdFirst'
        },
        {
            columns: [
                {
                    text: '123456',
                    bold: true
                }
            ], 
            style: 'scpwdMiddle'
        },
        {
            columns: [
                {
                    text: 'Alabang street',
                }
            ], 
            style: 'scpwdLast'
        }
    ];

    var docDefinition = {
        content: [
            /**
             * Image Receipt
             */
            {
                // under NodeJS (or in case you use virtual file system provided by pdfmake)
                // you can also pass file names here 
               /**
                * data:image/jpeg;base64,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
                */
                image: "data:image/jpeg;base64,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",
                width: 75,
                height: 75,
                style: 'topImage'
            },

            /**
             * Transaction Header
             */
            { 
                text: [
                    'ENCHANTED KINGDOM\n'+
                    'San Lorenzo South, Sta. Rosa Laguna\n'+
                    'VAT Registered TIN: 000-000-000-0000\n',
                    'MIN: XXXXXXXX \t',
                    'SN: XXXXXXXX\n\n',
                    'FOR EVALUATION PURPOSES ONLY\n\n',
                    '========================================\n', 
                ],
                fontSize: '9',
                alignment: 'center'
            },
            {
                columns: [
                    {
                        text: transDate.format('MM/DD/YYYY hh:mm A'),
                    },
                    {
                        text: 'OR #: 000-' + transOr,
                        alignment: 'right'
                    }
                ],
                fontSize: '9',
                alignment: 'center',
                margin:[25,0,25,0]
            },
            {
                columns: [
                    {
                        text: 'WEB',
                        bold: true
                    },
                    {
                        text: 'Tran #: ' + transNo,
                        alignment: 'right'
                    }
                ],
                fontSize: '9',
                alignment: 'center',
                margin: [25, 0, 25, 0]
            },
            {
                columns: [ 
                    {
                        text: '*** CUSTOMER COPY ***', 
                    }
                ],
                fontSize: '9',
                alignment: 'center',
                margin: [25, 10, 25, 5]
            },

            /**
             * Product with SCPWD Discount
             */
            transItems,
            // {
            //     columns: [
            //         { 
            //             text: 'REGULAR DAY PASS - WEEKD',
            //         },
            //         { 
            //             text: '00.00',
            //             style: 'amount',
            //             width: 50
            //         }
            //     ],
            //     style: 'product'
            // },

            /**
             * Sub of Product with SCPWD Discount
             */
           
            // {
            //     columns: [
            //         {
            //             text: 'Less VAT 12%',
            //         },
            //         {
            //             text: '-00.00',
            //             style: 'amount',
            //             width: 50
            //         }
            //     ],
            //     style: 'productSubSub'
            // },  
            // {
            //     columns: [
            //         {
            //             text: 'Less Amu.Tax 10%',
            //         },
            //         { 
            //             text: '-00.00',
            //             style: 'amount',
            //             width: 50
            //         }
            //     ],
            //     style: 'productSubSub'
            // },
            // {
            //     columns: [
            //         {
            //             text: 'Senior 20% x 00.00',
            //         },
            //         { 
            //             text: '-00.00',
            //             style: 'amount',
            //             width: 50
            //         }
            //     ],
            //     style: 'productSubSub'
            // },
            // {
            //     columns: [
            //         {
            //             text: 'Senior 20% x 00.00',
            //         },
            //         { 
            //             text: '-00.00',
            //             style: 'amount',
            //             width: 50
            //         }
            //     ],
            //     style: 'productSubSub'
            // }, 

            /**
             * Product without SCPWD Discount
             */ 
            // {
            //     columns: [
            //         { 
            //             text: 'TSM EK FWRKS WTHGLD F XS',
            //         },
            //         { 
            //             text: '00.00',
            //             style: 'amount',
            //             width: 50
            //         }
            //     ],
            //     style: 'product'
            // },

            /**
             * Total, Cash, Change
             */
           transTotal,
           transCash, 
            // {
            //     columns: [
            //         {
            //             text: 'CASH',
            //         },
            //         {
            //             text: '00.00',
            //             style: 'amount',
            //             width: 50
            //         }
            //     ],
            //     style: 'productSubCash'
            // },
            transChange,
            // {
            //     columns: [
            //         {
            //             text: 'CHANGE',
            //         },
            //         {
            //             text: '00.00',
            //             style: 'amount',
            //             width: 50
            //         }
            //     ],
            //     style: 'productSubChange'
            // },
 
            /**
             * VAT's 
             */
            {
                columns: [
                    {
                        text: 'VATable Sales',

                    },
                    { 
                        text: parseFloat(transaction.vatable_sales_total).toFixed(2),
                        style: 'amount',
                        width: 50
                    }
                ],
                style: 'vatFirst'
            }, 
            {
                columns: [
                    {
                        text: 'Vat-Exempt Sales',

                    },
                    { 
                        text: parseFloat(transaction.vat_exempt_sales_total).toFixed(2),
                        style: 'amount',
                        width: 50
                    }
                ],
                style: 'vatMiddle'
            }, 
            {
                columns: [
                    {
                        text: 'Vat Zero-Rated Sales',

                    },
                    { 
                        text: parseFloat(transaction.vat_zerorated_sales_total).toFixed(2),
                        style: 'amount', 
                        width: 50
                    }
                ],
                style: 'vatMiddle'
            }, 
            {
                columns: [
                    {
                        text: 'Vat Amount',

                    },
                    { 
                        text: parseFloat(transaction.vat_amount_total).toFixed(2),
                        style: 'amount',
                        width: 50
                    }
                ],
                style: 'vatMiddle'
            }, 
            {
                columns: [
                    {
                        text: 'Admission Fee', 
                    },
                    { 
                        text: parseFloat(transaction.admission_sales_total).toFixed(2),
                        style: 'amount',
                        width: 50
                    }
                ],
                style: 'vatMiddle'
            }, 
            {
                columns: [
                    {
                        text: 'Amusement Tax 10%', 
                    },
                    { 
                        text: parseFloat(transaction.amusement_tax_amount_total).toFixed(2),
                        style: 'amount',
                        width: 50
                    }
                ],
                style: 'vatMiddle'
            }, 
            {
                columns: [
                    {
                        text: 'Total SC / PWD Discount', 
                    },
                    {
                        text: parseFloat(transaction.scpwd_discount).toFixed(2),
                        style: 'amount',
                        width: 50
                    }
                ],
                style: 'vatLast'
            },

            scPwdDetail, 

            {
                text: [
                    '3 Item(s)\n',
                    'This serves as your Official Receipt'
                ],
                style: 'center2'
            }, 
            
            /**
             * Other Info that BIR require
             */
            {
                columns: [
                    {
                        text: [
                            'Customer Name:____________________________\n',
                            'Address:____________________________________\n',
                            'TIN:_________________________________________\n',
                            'Business Style:______________________________\n\n',
                        ],
                        margin: [25, 10, 25, 5],
                        fontSize: '9',
                    }
                ], 
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
                margin: [5, 5, 5, 5],
                fontSize: '9',

            },

            /**
             * Image Top
             */
            topImage : { 
                alignment: 'center',
                margin: [0, 0, 0, 10]
            },
            
            /**
             * Product with SCPWD Layout
             */
            product: {
                margin: [25, 5, 25, 5],
                fontSize: '9',
            },

            productSubSub: {
                margin: [35, 0, 25, 0],
                fontSize: '9',
            },

            /**
             * Total, Cash, Change
             */
            productSubTotal: {
                margin: [30, 10, 25, 0],
                fontSize: '9',
            },
            productSubCash: {
                margin: [30, 0, 25, 0],
                fontSize: '9',
            },
            productSubChange: {
                margin: [30, 0, 25, 10],
                fontSize: '9',
            },

            /**
             * VAT's
             */
            vatFirst: {
                margin: [30, 10, 25, 0],
                fontSize: '9',
            },
            vatMiddle: {
                margin: [30, 0, 25, 0],
                fontSize: '9',
            },
            vatLast: {
                margin: [30, 0, 25, 10],
                fontSize: '9',
            },

            /**
             * VAT's
             */
            vatFirst: {
                margin: [30, 10, 25, 0],
                fontSize: '9',
            },
            vatMiddle: {
                margin: [30, 0, 25, 0],
                fontSize: '9',
            },
            vatLast: {
                margin: [30, 0, 25, 10],
                fontSize: '9',
            }, 

            /**
             * SCPWD's
             */
            scpwdFirst: {
                margin: [30, 10, 25, 0],
                fontSize: '9',
            },
            scpwdMiddle: {
                margin: [30, 0, 25, 0],
                fontSize: '9',
            },
            scpwdLast: {
                margin: [30, 0, 25, 10],
                fontSize: '9',
            }, 

            amount:{
                fontSize: '9',
                bold: true,
                alignment: 'right'
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
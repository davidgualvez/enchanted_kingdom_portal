$(document).ready(function(){

	$('.ui.checkbox').checkbox();
	$('.menu .item').tab();
 
	btnUpdateInfo();

	$('.ui.accordion').accordion();
  
	activePurchaseDisplayer();
	testHistory();

	//purchase pagination
	paginatePurchaseHistory();
	btnNextPurchaseHistory();
	btnPrevPurchaseHistory();
	limitOnChangePurchaseHistory();

	//order pagination
	fetchHistory();
	paginateOrderHistory();
	btnNextOrderHistory();
	btnPrevOrderHistory();
	limitOnChangeOrderHistory();
});
 
function btnUpdateInfo(){
	$('#btn_update_information').on('click', function(){

		//apply validation if has
		var full_name 			= $('#full_name');
		if( full_name.val().trim() == null || full_name.val().trim() == '' ){
			showWarning('Warning','Name is required.', function(){

			});
			return;
		}

		//apply validation if has
		var email 				= $('#email');
		if( !validateEmail(email.val().trim() ) ){
			showWarning('Warning','Email address is not valid!', function(){

			});
			return;
		}

		//apply validation if has
		var mobile 				= $('#mobile_number');
		if( !validateContactNumber( mobile.val().trim() ) ){
			showWarning('Warning','Mobile number is not valid!', function(){

			});
			return;
		}

		//apply validation if has
		var password 			= $('#password');
		//console.log(password.val().trim().length);
		if( password.val().trim().length >= 1){
			if( password.val().trim().length < 6){
				showWarning('Warning','Password must contain atlease 6 Characters!', function(){

				});
				return;
			}
		} 

		//apply validation if has
		var email_notification 	= $('#email_notification');
 		if(email_notification.is(":checked")){ 
			email_notification = true;
		}else{ 
			email_notification = false;
		}  

		var data = {
			'full_name' 			: full_name.val().trim(),
			'email' 				: email.val().trim(),
			'email_notification'	: email_notification,
			'mobile_number' 		: mobile.val().trim(),
			'password'				: password.val().trim()
		};

		updateInfo(data);
	});
}

function updateInfo(data){
	post(routes.user.updateInfo, data, function(response){
		if(response.success == false){
			showError('', response.message, function(){

			});
			return;
		}

		showSuccess('', 'Your profile has been updated.', function(){

		});

	});
}

function activePurchaseDisplayer(){
	$('#active_purchase').empty();

	activePurchase();
}

function activePurchase(){ 
	post(routes.user.activePurchase,{}, function(response){ 
		$.each(response.data, function (key, value) {
			console.log(value);

			var is_unli = '';
			if(value.is_unli == 1){
				is_unli += '<div class="ui green mini label"> UNLIMITED </div>';
			}

			$('#active_purchase').append(
				 '<div class="item">'+
			   	    // '<div class="image"  style="width: 100px; height: 100px;">'+
			   	     '<div class="image">'+
			   	      '<img src="'+value.image+'" class="">'+
			   	    '</div>'+
			   	    '<div class="content">'+
			   	      '<a class="header"> '+value.product_name+' </a>'+
			   	      '<div class="meta">'+
			   	        '<span class="cinema"> '+value.group_name+' </span>'+
			   	      '</div>'+
			   	      '<div class="description">'+
			   	        '<p></p>'+
			   	      '</div>'+
			   	      '<div class="extra">'+
			   	        '<div class="ui right floated primary tiny button btnActiveOrder" id="'+value.purchase_detail_id+'">'+
			   	          'Show Code'+
			   	          '<i class="right chevron icon"></i>'+
			   	        '</div>'+
			   	        '<div class="ui mini label">Valid for <strong> '+value.valid_until+' </strong> only.</div>'+
			   	        '<div class="ui mini label">Qty : ' +value.remaining_qty+ '</div>'+
			   	        is_unli +
			   	      '</div>'+
			   	    '</div>'+
		   	  '</div>'
			);

			showBarcode(value.purchase_detail_id,value.product_id);
		});

		
	});
}

function showBarcode(id,product_id){
	$('.btnActiveOrder#'+id).on('click', function(){
		console.log(this.id); 
		$('.ui.dimmer').dimmer("show",
		{
			onHide: function(){
				console.log('test');
			}
		}); 
		
		//FormatNumberLength(id, 10)
		JsBarcode("#barcode", id+'-'+product_id , {
			format: "CODE39",
			// lineColor: "#0aa",
			// width: 3,
			// mod43:true
			// height: 40,
			// displayValue: false
		});
		
		console.log( $('.btnActiveOrder#'+this.id).parent().prev().prev().prev().text() );
		$('#purchase_order').text( $('.btnActiveOrder#'+this.id).parent().prev().prev().prev().text() );
		//$('#purchase_id').text(this.id);
	}); 
}
 
function testHistory(){
	post(routes.user.purchaseHistory, {}, function(response){
		$.each(response.data, function(key,value){
			$('#purchaseHistory').append(response.data);
		});
	});
}


//pagination================================ 
var current_page_purchase_history = null;
var prev_page_url_purchase_history = null;
var next_page_url_purchase_history = null;
var current_data_purchase_history = null;

function paginatePurchaseHistory() {
    var limit           = $('#limit_purchase_history').val();
    var search_val      = $('#search_our_purchase_history').val(); 

    var data = {
        search              : search_val, 
        limit               : limit, 
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
    		'<div class="center aligned">'+
    			'<h3 class="ui header center aligned">Nothing to display..</h3>'+
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
        details += '<table class="ui small celled table">'+
    		    	  '<thead>'+
    		    	    '<tr>'+
    		    	    	'<th>Name</th>'+
    			    	    '<th>Srp</th>'+
    			    	    '<th>Qty</th>'+
    			    	    '<th>Amount</th>'+
    			    	    '<th>Discount</th>'+
    			    	    '<th>Discounted Amount</th>'+
    			    	    '<th>Valid Until</th>'+
    		    	  	'</tr>'+
    		    	  '</thead>'+
    		    	  '<tbody>';
    		    	  console.log(value.details);
        $.each(value.details, function(key, value1){  
    		details +=  '<tr>'+
    		    	      '<td>'+value1.part_name+'</td>'+
    		    	      '<td>'+value1.srp+'</td>'+
    		    	      '<td>'+ value1.qty +'</td>'+
    		    	      '<td>'+ value1.amount +'</td>'+
    		    	      '<td>'+ value1.discount +'</td>'+
    		    	      '<td>'+ value1.net_amount +'</td>'+
    		    	      '<td>'+ value1.valid_until +'</td>'+
    		    	    '</tr> ';
        });
        details +=  '</tbody>'+
    			    	'</table>';

        items.append(
             	'<div class="ui vertical segment">'+
             		'<div style="padding: 10px;">'+
             			'<div class="ui middle aligned divided list">'+
             				'<div class="item">'+
	           	  			    '<div class="right floated content">'+
	           	  			      	'<strong>'+value.created_at+'</strong>'+
	           	  			    '</div> '+
	           	  			    '<div class="content">'+
	           	  			      'Date/Time'+
	           	  			    '</div>'+
             			  	'</div> '+
             			  	'<div class="item">'+
	           	  			    '<div class="right floated content">'+
	           	  			      	'<strong>'+value.type+'</strong>'+
	           	  			    '</div> '+
	           	  			    '<div class="content">'+
	           	  			      'Type'+
	           	  			    '</div>'+
             			  	'</div> '+
             			  	'<div class="item">'+
	           	  			    '<div class="right floated content">'+
	           	  			      	'<strong>'+value.total_amount+'</strong>'+
	           	  			    '</div> '+
	           	  			    '<div class="content">'+
	           	  			      'Total Amount'+
	           	  			    '</div>'+
             			  	'</div> '+
             			  	'<div class="item">'+
	           	  			    '<div class="right floated content">'+
	           	  			      	'<strong>'+value.total_discount+'</strong>'+
	           	  			    '</div> '+
	           	  			    '<div class="content">'+
	           	  			      'Total Discount'+
	           	  			    '</div>'+
             			  	'</div> '+
             			  	'<div class="item">'+
	           	  			    '<div class="right floated content">'+
	           	  			      	'<strong>'+value.net_amount+'</strong>'+
	           	  			    '</div> '+
	           	  			    '<div class="content">'+
	           	  			      'Net Amount'+
	           	  			    '</div>'+
             			  	'</div> '+
             			  	'<div class="item">'+
	           	  			    '<div class="right floated content">'+
	           	  			      	'<strong>'+value.added_points+'</strong>'+
	           	  			    '</div> '+
	           	  			    '<div class="content">'+
	           	  			      'Earned Points'+
	           	  			    '</div>'+
             			  	'</div> '+
             			  	'<div class="item">'+
	           	  			    '<div class="right floated content">'+
	           	  			      	'<strong>'+value.used_wallet+'</strong>'+
	           	  			    '</div> '+
	           	  			    '<div class="content">'+
	           	  			      'Used Wallet'+
	           	  			    '</div>'+
             			  	'</div> '+
             			  	'<div class="item">'+
	           	  			    '<div class="right floated content">'+
	           	  			      	'<strong>'+value.used_points+'</strong>'+
	           	  			    '</div> '+
	           	  			    '<div class="content">'+
	           	  			      'Used Points'+
	           	  			    '</div>'+
             			  	'</div> '+
             			  	'<div class="item">'+
	           	  			    '<div class="right floated content">'+
	           	  			      	'<strong>'+value.wallet_balance+'</strong>'+
	           	  			    '</div> '+
	           	  			    '<div class="content">'+
	           	  			      'Wallet Balance'+
	           	  			    '</div>'+
             			  	'</div> '+
             			  	'<div class="item">'+
	           	  			    '<div class="right floated content">'+
	           	  			      	'<strong>'+value.points_balance+'</strong>'+
	           	  			    '</div> '+
	           	  			    '<div class="content">'+
	           	  			      'Points Balance'+
	           	  			    '</div>'+
             			  	'</div> '+
             			'</div>'+
             			'<div class="ui accordion">'+
             			  '<div class="title">'+
             			    '<i class="dropdown icon"></i>'+
             			    "Detail's"+
             			  '</div>'+
             			  '<div class="content">'+
             			    '<p class="transition" style="display: block !important;">'+
             			    details+
             				'</p>'+
             			  '</div> '+
             			'</div>'+
             		'</div> '+
             	'</div> '
        ); 

    });
    
	$('.ui.accordion').accordion(); 
}
//end of pagination================

//pagination================================ 
var current_page_order_history = null;
var prev_page_url_order_history = null;
var next_page_url_order_history = null;
var current_data_order_history = null;

function paginateOrderHistory() {
    var limit           = $('#limit_order_history').val();
    var search_val      = $('#search_our_order_history').val(); 

    var data = {
        search              : search_val, 
        limit               : limit, 
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
    		'<div class="center aligned">'+
    			'<h3 class="ui header center aligned">Nothing to display..</h3>'+
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
        details += '<table class="ui small celled table">'+
    		    	  '<thead>'+
    		    	    '<tr>'+
    		    	    	'<th>Name</th>'+
    			    	    '<th>Srp</th>'+
    			    	    '<th>Qty</th>'+
    			    	    '<th>Amount</th>'+
    		    	  	'</tr>'+
    		    	  '</thead>'+
    		    	  '<tbody>';
    		    	  console.log(value.details);
        $.each(value.details, function(key, value1){ 
    		details +=  '<tr>'+
    		    	      '<td>'+value1.product_name+'</td>'+
    		    	      '<td>'+value1.srp+'</td>'+
    		    	      '<td>'+ value1.qty +'</td>'+
    		    	      '<td>'+ value1.amount +'</td>'+
    		    	    '</tr> ';
        });
        details +=  '</tbody>'+
    			    	'</table>';

    	var status = '';
    	if(value.status == 'p' || value.status == 'P'){
    		status += '<a class="ui yellow label">Pending</a>'
    	}else if(value.status == 'c' || value.status == 'C'){
    		status += '<a class="ui green label">Completed</a>'
    	}else if(value.status == 's' || value.status == 'S'){
    		status += '<a class="ui green label">Serve</a>'
    	}


        items.append(
             	'<div class="ui vertical segment">'+
             		'<div style="padding: 10px;">'+
             			'<div class="ui middle aligned divided list">'+
             				'<div class="item">'+
	           	  			    '<div class="right floated content">'+
	           	  			      	'<strong>'+value.created_at+'</strong>'+
	           	  			    '</div> '+
	           	  			    '<div class="content">'+
	           	  			      'Date/Time'+
	           	  			    '</div>'+
             			  	'</div> '+
             			  	'<div class="item">'+
	           	  			    '<div class="right floated content">'+
	           	  			      	'<strong>'+value.trans_type+'</strong>'+
	           	  			    '</div> '+
	           	  			    '<div class="content">'+
	           	  			      'Type'+
	           	  			    '</div>'+
             			  	'</div> '+
             			  	'<div class="item">'+
	           	  			    '<div class="right floated content">'+
	           	  			      	'<strong>'+value.total_amount+'</strong>'+
	           	  			    '</div> '+
	           	  			    '<div class="content">'+
	           	  			      'Total Amount'+
	           	  			    '</div>'+
             			  	'</div> '+
             			  	'<div class="item">'+
	           	  			    '<div class="right floated content">'+
	           	  			      	'<strong>'+value.discount_amount+'</strong>'+
	           	  			    '</div> '+
	           	  			    '<div class="content">'+
	           	  			      'Total Discount'+
	           	  			    '</div>'+
             			  	'</div> '+
             			  	'<div class="item">'+
	           	  			    '<div class="right floated content">'+
	           	  			      	'<strong>'+value.net_amount+'</strong>'+
	           	  			    '</div> '+
	           	  			    '<div class="content">'+
	           	  			      'Net Amount'+
	           	  			    '</div>'+
             			  	'</div> '+ 
             			  	'<div class="item">'+
	           	  			    '<div class="right floated content">'+
	           	  			      	'<div class="ui right floated primary tiny button btnActiveOrder" data-id="'+value.orderslip_header_id+'">'+
	      				   	          'Show Code'+
	      				   	          '<i class="right chevron icon"></i>'+
	      				   	        '</div>'+
	           	  			    '</div> '+
	           	  			    '<div class="content">'+
	           	  			      'Barcode'+
	           	  			    '</div>'+
             			  	'</div> '+ 
             			  	'<div class="item">'+
	           	  			    '<div class="right floated content">'+
	           	  			    	status + 
	           	  			    '</div> '+
	           	  			    '<div class="content">'+
	           	  			      'Status'+
	           	  			    '</div>'+
             			  	'</div> '+ 
             			'</div>'+
             			'<div class="ui accordion">'+
             			  '<div class="title">'+
             			    '<i class="dropdown icon"></i>'+
             			    "Detail's"+
             			  '</div>'+
             			  '<div class="content">'+
             			    '<p class="transition" style="display: block !important;">'+
             			    details+
             				'</p>'+
             			  '</div> '+
             			'</div>'+
             		'</div> '+
             	'</div> '
        ); 

        showBarcodeOrder(value.orderslip_header_id);
    });
    
	$('.ui.accordion').accordion(); 
}
//end of pagination================

function showBarcodeOrder(id){
	$('.btnActiveOrder').on('click', function(){
		console.log(this.id); 

        var _id = $(this).data('id');

		$('.ui.dimmer').dimmer("show",
		{
			onHide: function(){
				console.log('test');
			}
		}); 
		
		//FormatNumberLength(id, 10)
		JsBarcode("#barcode", _id , {
			format: "CODE39",
			// lineColor: "#0aa",
			// width: 3,
			// mod43:true
			// height: 40,
			// displayValue: false
		});
		
		
		$('#purchase_order').text(  );
		//$('#purchase_id').text(this.id);
	}); 
}

function fetchHistory(){
	post(routes.cart.orderHistory, {}, function(response){
		console.log(response);
	});
}
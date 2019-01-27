$(document).ready(function(){ 
 	console.log('test');  

 	$('.question.circle.outline.icon')
 	  .popup({
 	    on: 'click'
 	});

 	checkOut();
 	order();
 	// checkOutReward(); 
	ontest();
	 
	//  setTimeout(() => {
	// 	 initGuide();
	//  }, 500);

	showAddons();
	addonsBack();

	btnQtyDecrease();
	btnQtyIncrease();
});
 

function checkOut(){
	$('#checkout').on('click',function(){ 

		//$('#checkout').attr('disabled', 'disabled'); 
		disableButton();

		$.confirm({
		    title: 'Confirmation!',
		    content: 'Purchase confirmation, do you want to continue?',
		    type: 'dark',
		    boxWidth: '80%',
    		useBootstrap: false, 
    		closeIcon: function(){
    		        //return false; // to prevent close the modal.
    		        // or
    		        //return 'aRandomButton'; // set a button handler, 'aRandomButton' prevents close.
    		        enableButton();
    		    },
		    buttons: { 
		        // cancel: function () {
		        //     //$.alert('Canceled!');
		        //     //$('#checkout').removeAttr('disabled', 'disabled');
		        //     enableButton();
		        // },
		        somethingElse: {
		            text: 'Confirm',
		            btnClass: 'btn-green',
		            keys: ['enter', 'shift'],
		            action: function(){
		            	
		               //$.alert('Confirmed!'); 

		               var points_payment = $('#points_payment').val();
		               //$('#checkout').removeAttr('disabled', 'disabled'); 

		               post(routes.cart.checkout, {
		               	points_payment : points_payment
		               }, function(response){
			               	console.log(response);
			               	if(response.success == false){
			               		showError('Error',response.message, function(){

			               		});


			               		//$('#checkout').removeAttr('disabled', 'disabled');
			               		enableButton();
			               		return;
			               	}
			               	 
			               	showSuccess('Success','Your Purchase has been checkout!.', function(){
			               		redirectTo('/me');
			               	});
		               });

		            }
		        }
		    }
		});

	});
}

function order(){
	$('#order').on('click', function(){
		disableButton(); 
		$.confirm({
		    title: 'Confirmation!',
		    content: 'Order confirmation, do you want to continue?',
		    type: 'dark',
		    boxWidth: '80%',
    		useBootstrap: false,
    		closeIcon: function(){
    		        //return false; // to prevent close the modal.
    		        // or
    		        //return 'aRandomButton'; // set a button handler, 'aRandomButton' prevents close.
    		        enableButton();
    		    },
		    buttons: { 
		        // cancel: function () { 
		        //     enableButton();
		        // },
		        
		        somethingElse: {
		            text: 'Confirm',
		            btnClass: 'btn-green',
		            keys: ['enter', 'shift'],
		            action: function(){

		            	post(routes.cart.order, {}, function(response){ 
		            		console.log(response);
		            		if(response.success == false){
			               		showError('Error',response.message, function(){

			               		});  
			               		enableButton();
			               		return;
			               	}

		            		showSuccess('Success','Your Order has been checkout!.', function(){
			               		redirectTo('/order/history');
			               	});
		            	});
		            	
		            }
		        }
		    }
		});
	});
}

function ontest(){
	$('#points_payment').keyup(function(){
		var value = this.value;
		value = value.replace(/\D+/g, "");

		//console.log(value);
		this.value = value;
		this.focus();

	});
}

function disableButton(){
	$('#checkout').attr('disabled', 'disabled'); 
	$('#order').attr('disabled', 'disabled'); 
}

function enableButton(){
	$('#checkout').removeAttr('disabled', 'disabled');
	$('#order').removeAttr('disabled', 'disabled');
}


//GUIDE FOR THIS PAGE
function initGuide() {
    if (getStorage('guide-cart') != 1) {
        startGuide();
    }
}

function startGuide() {
    var intro = introJs();
    intro.setOptions({
        steps: [
            {
                element: '.step1',
                intro: 'This area shows all the items added while you are at the store page',
            },
            {
                element: '.step2',
                intro: 'This field is use to pay using your points. if there is a balance your wallet will be automatically deducted.',
                position: 'left'
            },
            {
                element: '.step3',
                intro: 'If you choose this Order button, you need to go to the nearest POS and present a QRCode generated by the system after this transaction.',
            },
            {
                element: '.step4',
                intro: 'If you choose thise Purchase button, Your wallet and Points(optional) will be automatically deducted if the Transaction was successful.',
            },
        ]
    });

    intro.onexit(function () {
        setStorage('guide-cart', 1);
        console.log('exit');
    });
    intro.start();
}

// ADDONS
function showAddons(){
	$('.addons').on('click', function(){
		var id = $(this).data('id');
		console.log(id);

		//request 
		var data = {
			'parent_id'	: id
		};
		post(routes.postmix.showSideDish, data, function(response){
			
			if(response.success == false){
				showWarning('',response.message, function(){

				});
				return;
			}

			//return;
			//SHOW MODAL
			addonsDisplayer(response.data);
		});
 
	});
}

function addonsDisplayer(data){
	// $('.ui.page.dimmer')
	// 	.dimmer('toggle')
	// 	;
	var acc = $('.addons_category_container');
	var asc = $('#addons_selected_container');

	acc.empty();
	asc.empty();

	$.each(data, function(i,v){
		console.log(v);
		acc.append(
			'<button class="ui button fluid btn-sidedish" data-comp-cat-id="' + v.comp_cat_id+'" data-parent-id="'+v.parent_id+'" data-comp-id="'+v.component_id+'">'+
				v.description+
			'</button>'
		); 
	});
	btnSideDish();

	$('#cart_page').hide();
	$('#addons_page').show();
}

function addonsBack(){
	$('#btnBackToCartPage').on('click', function(){
		$('#cart_page').show();
		$('#addons_page').hide();
	});
}

function btnSideDish(){
	$('.btn-sidedish').on('click', function(){
		var parent_id = $(this).data('parent-id');
		var comp_id = $(this).data('comp-id');
		var comp_cat_id = $(this).data('comp-cat-id'); 

		console.log(
			comp_cat_id, 
			parent_id, 
			comp_id
		);
		// request 
		var data = {
			'parent_id'         : parent_id,
			'component_id'		: comp_id,
			'comp_cat_id'	: comp_cat_id
		};
		post(routes.postmix.showSideDishes,data, function(response){
			if(response.success == false){
				showWarning('', response.message, function () {

				});
				return;
			}

			console.log(response.data);
		});
	});
}

function btnQtyDecrease(){
	$('.btn-decrease').on('click', function(){
		var me = $(this); 
		me.addClass('disabled') 
	});
}

function btnQtyIncrease() {
	$('.btn-increase').on('click', function () {
		var me = $(this);
		me.addClass('disabled')
	});
}
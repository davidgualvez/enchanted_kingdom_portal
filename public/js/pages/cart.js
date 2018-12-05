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
});
 

function checkOut(){
	$('#checkout').on('click',function(){ 

		//$('#checkout').attr('disabled', 'disabled'); 
		disableButton();

		$.confirm({
		    title: 'Confirmation!',
		    content: 'Purchase confirmation, do you want to continue?',
		    type: 'dark',
		    boxWidth: '50%',
    		useBootstrap: false,
		    buttons: { 
		        cancel: function () {
		            //$.alert('Canceled!');
		            //$('#checkout').removeAttr('disabled', 'disabled');
		            enableButton();
		        },
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
			               		redirectTo('/');
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
		    boxWidth: '50%',
    		useBootstrap: false,
		    buttons: { 
		        cancel: function () { 
		            enableButton();
		        },
		        
		        somethingElse: {
		            text: 'Confirm',
		            btnClass: 'btn-green',
		            keys: ['enter', 'shift'],
		            action: function(){

		            	post(routes.cart.order, {}, function(response){ 
		            		console.log(response);
		            		enableButton();
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
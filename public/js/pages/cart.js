$(document).ready(function(){ 
 	console.log('test');  

 	$('.question.circle.outline.icon')
 	  .popup({
 	    on: 'click'
 	});

 	checkOut();
 	// checkOutReward(); 
 	ontest();
});
 

function checkOut(){
	$('#checkout').on('click',function(){ 

		$('#checkout').attr('disabled', 'disabled'); 

		$.confirm({
		    title: 'Confirmation!',
		    content: 'Checkout confirmation, do you want to continue?',
		    type: 'dark',
		    boxWidth: '50%',
    		useBootstrap: false,
		    buttons: { 
		        cancel: function () {
		            //$.alert('Canceled!');
		            $('#checkout').removeAttr('disabled', 'disabled');
		        },
		        somethingElse: {
		            text: 'Confirm',
		            btnClass: 'btn-green',
		            keys: ['enter', 'shift'],
		            action: function(){
		            	
		               //$.alert('Confirmed!'); 

		               var points_payment = $('#points_payment').val();
		               $('#checkout').removeAttr('disabled', 'disabled'); 

		               post(routes.cart.checkout, {
		               	points_payment : points_payment
		               }, function(response){
			               	console.log(response);
			               	if(response.success == false){
			               		showError('Error',response.message, function(){

			               		});

			               		$('#checkout').removeAttr('disabled', 'disabled');
			               		return;
			               	}
			               	 
			               	showSuccess('Success','Your order has been checkout!.', function(){
			               		redirectTo('/');
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


// function checkOutReward(){
// 	$('#checkoutReward').on('click',function(){ 
// 		$('#checkoutReward').attr('disabled', 'disabled'); 

// 		$.confirm({
// 		    title: 'Confirmation!',
// 		    content: 'Order confirmation, do you want to continue?',
// 		    type: 'dark',
// 		    boxWidth: '50%',
//     		useBootstrap: false,
// 		    buttons: { 
// 		        cancel: function () {
// 		            //$.alert('Canceled!');
// 		            $('#checkoutReward').removeAttr('disabled', 'disabled');
// 		        },
// 		        somethingElse: {
// 		            text: 'Confirm',
// 		            btnClass: 'btn-green',
// 		            keys: ['enter', 'shift'],
// 		            action: function(){
// 		               //$.alert('Confirmed!'); 
// 		               post(routes.cart.points.checkout, {}, function(response){
// 			               	console.log(response);
// 			               	if(response.success == false){
// 			               		showError('Error',response.message, function(){
// 			               		});

// 			               		$('#checkoutReward').removeAttr('disabled', 'disabled');
// 			               		return;
// 			               	}

// 			               	showSuccess('Success','Your order has been checkout!.', function(){
// 			               		redirectTo('/');
// 			               	});
// 		               });
// 		            }
// 		        }
// 		    }
// 		});

// 	});
// }
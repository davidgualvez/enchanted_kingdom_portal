$(document).ready(function(){ 
 	console.log('test');  

 	$('.question.circle.outline.icon')
 	  .popup({
 	    on: 'click'
 	});

 	checkOut();
 	checkOutReward();
});
 

function checkOut(){
	$('#checkout').on('click',function(){ 
		$('#checkout').attr('disabled', 'disabled');

		// if (confirm('Order confirmation, do you want to continue?')) {
		// 	post(routes.cart.checkout, {}, function(response){
		// 		console.log(response);
		// 		if(response.success == false){
		// 			showError('Error',response.message, function(){

		// 			});

		// 			$('#checkout').removeAttr('disabled', 'disabled');
		// 			return;
		// 		}
		// 		showSuccess('Success','Your order has been checkout!.', function(){
		// 			redirectTo('/');
		// 		});
		// 	});
		// 	// postRequest('order/add_sales_order',{token:readCookie('token')},function(response){
		// 	// 	if(response.success==false){ 
		// 	// 		showWarning(response.message);
		// 	// 	}
		// 	// 	showSuccess(response.message);

		// 	// 	setTimeout(function(){
		// 	// 		window.location.href = url+"account";
		// 	// 	},1000);
		// 	// });
		// }

		$.confirm({
		    title: 'Confirmation!',
		    content: 'Order confirmation, do you want to continue?',
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
		               post(routes.cart.checkout, {}, function(response){
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

function checkOutReward(){
	$('#checkoutReward').on('click',function(){ 
		$('#checkout').attr('disabled', 'disabled'); 

		$.confirm({
		    title: 'Confirmation!',
		    content: 'Order confirmation, do you want to continue?',
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
		               post(routes.cart.points.checkout, {}, function(response){
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
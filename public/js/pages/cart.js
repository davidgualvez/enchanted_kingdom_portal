$(document).ready(function(){ 
 	console.log('test');  

 	$('.question.circle.outline.icon')
 	  .popup({
 	    on: 'click'
 	});

 	checkOut();
});
 

function checkOut(){
	$('#checkout').on('click',function(){ 
		if (confirm('Order confirmation, do you want to continue?')) {
			 
			// postRequest('order/add_sales_order',{token:readCookie('token')},function(response){
			// 	if(response.success==false){
			// 		showWarning(response.message);
			// 	}
			// 	showSuccess(response.message);

			// 	setTimeout(function(){
			// 		window.location.href = url+"account";
			// 	},1000);
			// });
		}

	});
}
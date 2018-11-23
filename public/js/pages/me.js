$(document).ready(function(){

	$('.ui.checkbox').checkbox();
	$('.menu .item').tab();

	btnUpdateInfo();

	$('.ui.accordion').accordion(); 

	$('.btnActiveOrder').on('click', function(){
		console.log(this.id); 
		$('.ui.dimmer').dimmer("show");

		JsBarcode("#barcode", '54321' , {
			format: "CODE39",
			// lineColor: "#0aa",
			width: 3,
			mod43:true
			// height: 40,
			// displayValue: false
		});
		
		console.log( $('.btnActiveOrder#'+this.id).parent().prev().prev().prev().text() );
		$('#purchase_order').text( $('.btnActiveOrder#'+this.id).parent().prev().prev().prev().text() );
		//$('#purchase_id').text(this.id);
	});

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
		if( password.val().trim().length < 6){
			showWarning('Warning','Password must contain atlease 6 Characters!', function(){

			});
			return;
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
$(document).ready(function(){

	$('.ui.checkbox').checkbox();
	$('.menu .item').tab();

	btnUpdateInfo();
});

function btnUpdateInfo(){
	$('#btn_update_information').on('click', function(){
		var full_name 	= $('#full_name');
		var email 		= $('#email');
		var mobile 		= $('#mobile_number');
		var password 	= $('#password');
		var email_notification = $('#email_notification');

		console.log( 'fn '+ full_name.val() );
		console.log( 'e '+ email.val() );
		console.log( 'm '+ mobile.val() );
		console.log( 'p '+ password.val() );
		console.log( 'en '+ email_notification.val() );
	});
}
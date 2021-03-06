$(document).ready(function(){

	$('.ui.checkbox').checkbox();
	$('.menu .item').tab(); 
 
	btnUpdateInfo();

	$('.ui.accordion').accordion();
  
	activePurchaseDisplayer();
	// testHistory();  

	// setTimeout(() => {
	// 	initGuide();
	// }, 500);
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
		if( !validateEmail(email.val().trim() ) && email.val().trim() != '' ){
			showWarning('Warning','Email address is not valid!', function(){

			});
			return;
		}

		//apply validation if has
		var mobile 				= $('#mobile_number');
		// if( !validateContactNumber( mobile.val().trim() ) ){
		// 	showWarning('Warning','Mobile number is not valid!', function(){

		// 	});
		// 	return;
		// }
		if( mobile.val().length != 10){
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
			//console.log(value);

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
			   	          'Show QRCode'+
			   	          '<i class="right chevron icon"></i>'+
			   	        '</div>'+
			   	        '<div class="ui mini label green">Valid for <strong> '+value.valid_until+' </strong> only.</div>'+
			   	        '<div class="ui mini label blue">Qty ' +value.remaining_qty+ '</div>'+
			   	        is_unli +
			   	      '</div>'+
			   	    '</div>'+
		   	  '</div>'
			);

			showBarcode(value.purchase_detail_id,value.product_id, value.barcode);
		});

		
	});
}

function showBarcode(id,product_id, barcode = ''){
	$('.btnActiveOrder#'+id).on('click', function(){
		 
		$('.ui.dimmer').dimmer("show",
		{
			onHide: function(){
				//console.log('test');
			}
		});  
        $('#barcode').empty(); 
        var qrcode = new QRCode(document.getElementById("barcode"), {
            text: barcode , 
            width: 128,
            height: 128,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });  
		
		$('#code-name').text('ref # : ' + barcode);
	}); 
} 



//GUIDE FOR THIS PAGE
function initGuide() {
    if (getStorage('guide-me') != 1) {
        startGuide();
    }
}

function startGuide() {
    var intro = introJs();
    intro.setOptions({
        steps: [ 
			{
			    element: '.cart',
			    intro: 'To view all added item from your Cart. Click here',
			},
			{
			    element: '.step1',
			    intro: 'This area shows your Wallet Balance.',
			},
			{
			    element: '.step2',
				intro: 'This area shows your Points Balance.',
				position: 'left'
			},
			{
			    element: '.step3',
			    intro: 'This area shows your Active Purchases from the Store. To claim it just press the Show QRCode button from the list and go to the nearest claiming area.',
			},
			{
			    element: '.step4',
			    intro: 'This area where you can update your information.',
			},
		]
    });

    intro.onexit(function () {
        setStorage('guide-me', 1);
        console.log('exit');
    });
    intro.start();
}
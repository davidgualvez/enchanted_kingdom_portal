$(document).ready(function(){
    console.log('onload...');

    // stepOne();
});

function stepOne(){
    stepDisplayer(1);

    $('#btnStepOneNext').on('click', function(){

        var mobile = $('#mobile_number');
        
        if(mobile.val().trim() == '' || mobile.val().trim() == null){
            showWarning('','Mobile number is required.', function(){

            });
            mobile.focus();
            return;
        }

        if(mobile.val().length != 10){
            showWarning('','Mobile number must be 10 digit', function () {

            });
            mobile.focus();
            return;
        }

        var data = {
            mobile_number : mobile.val()
        };

        post(routes.forgotPassword.verifyMobile, data, function(response){
            console.log(response);
            if(response.success == false){
                showWarning('', response.message, function () {

                });
                return;
            }

            //if success
            showSuccess('',response.message,function(){

            });

            stepTwo();
        }); 
        
    });
}

function stepTwo(){
    stepDisplayer(2);

    $('#btnStepTwoNext').on('click', function () {
        var code = $('#code');

        if (code.val().trim() == '' || code.val().trim() == null) {
            showWarning('', 'Code is required.', function () {

            });
            code.focus();
            return;
        }

        master_code = code.val();
        var data = {
            code: code.val()
        };

        post(routes.forgotPassword.verifyCode, data, function (response) {
            console.log(response);
            if (response.success == false) {
                showWarning('', response.message, function () {

                });
                return;
            }

            //if success
            showSuccess('', response.message, function () {

            });
            stepThree();
        }); 
    });
}

var master_code = null;
function stepThree() {
    stepDisplayer(3); 
    $('#btnStepFinish').on('click', function () {

        var pass    = $('#password');
        var repass  = $('#repassword');

        if (pass.val().trim() == '' || pass.val().trim() == null) {
            showWarning('', 'Password is required.', function () {

            });
            pass.focus();
            return;
        }

        if (pass.val().length < 6) {
            showWarning('', 'Password must be greater than or equal to 6 Characters', function () {

            });
            pass.focus();
            return;
        }

        if (repass.val().trim() == '' || repass.val().trim() == null) {
            showWarning('', 'Retype Password is required.', function () {

            });
            repass.focus();
            return;
        }

        if (pass.val().trim() != repass.val().trim()) {
            showWarning('', 'Password not match!.', function () {

            });
            pass.focus();
            return;
        }
 
        var data = {
            code : master_code,
            password : pass.val(),
            repassword : repass.val()
        }
        
        post(routes.forgotPassword.updateNewPassword, data, function (response) {
            console.log(response);
            if (response.success == false) {
                showWarning('', response.message, function () {

                });
                return;
            }

            showSuccess('', 'New Password has been changed...', function () {
                pass.attr('disable','disable');
                repass.attr('disable','disable');
                redirectTo('/login');
            });
        });


       
    });
}

function stepDisplayer(step){
    var steps = $('.steps'); 
    emptySteps();


    if(step == 1){
        //step 1 
        steps.children().first().addClass('active'); 
        $('#step1').show();
        //step 2
        //step 3
    }  

    if (step == 2) {
        //step 1 
        steps.children().first().addClass('completed');
        //step 2
        steps.children().first().next().addClass('active');
        $('#step2').show();
        //step 3
    }  

    if (step == 3) {
        //step 1 
        steps.children().first().addClass('completed');
        //step 2
        steps.children().first().next().addClass('completed');
        //step 3
        steps.children().first().next().next().addClass('active');
        $('#step3').show();
    }  
}

function emptySteps() {

    var steps = $('.steps');
    steps.children().first().removeClass('active');
    steps.children().first().removeClass('completed');
    steps.children().first().removeClass('active');

    steps.children().next().first().removeClass('active');
    steps.children().next().first().removeClass('completed');
    steps.children().next().first().removeClass('active');

    steps.children().next().next().first().removeClass('active');
    steps.children().next().next().first().removeClass('completed');
    steps.children().next().next().first().removeClass('active');


    $('#step1').hide();
    $('#step2').hide();
    $('#step3').hide();

}

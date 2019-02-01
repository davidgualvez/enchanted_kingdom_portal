$(document).ready(function () { 
    btnChangeProduct();
    btnDeductAddedComponent();
});

function btnDeductAddedComponent(){
    $('.btn-deduct_component').on('click', function () {

        var cc_id = $(this).data('cc_id');
        var data = {
            cc_id       : cc_id,
            _method     : 'DELETE'
        }; 
        post('', data, function (response) {
            console.log(response);
            if (response.success == false) {
                showWarning('', response.message, function () {

                });
                return;
            }
            redirectTo('');
        }); 
        
    });
}

function btnChangeProduct(){
    $('.btn-change').on('click', function(){
        var pid = $(this).data('pid'); 
        var data = { 
            pid : pid,
            _method : 'PATCH'
        }; 
        post('', data, function(response){
            console.log(response);
            if(response.success == false){
                showWarning('',response.message, function(){

                });
                return;
            }
            redirectTo('');
        }); 
    });
}
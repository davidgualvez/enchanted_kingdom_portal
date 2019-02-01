$(document).ready(function () { 
    btnChangeProduct();
});

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
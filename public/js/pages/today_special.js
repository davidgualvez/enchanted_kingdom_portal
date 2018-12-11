$(document).ready(function(){
	console.log('readyy');

	btnProductAddToCart();
});

function btnProductAddToCart(){
    $('.btnAddToCart').on('click',function(){
         
        var data = {
            product_id :  $(this).data('id'),
            qty : 1
        }; 

        post(routes.cart.addToCart, data, function(response){
            console.log(response);
            if(response.success == false){
                if(response.status == 401){ 
                    redirectTo('/login'); //if not authenticated
                } 
                showWarning('Warning',response.message, function(){

                });
                return;
            }

            showSuccess('Success',response.message, function(){

            });
            updateCartCount();
        }); 
        
    });
}
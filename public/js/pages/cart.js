$(document).ready(function(){ 
 	console.log('test');

 	getCart();
 	// removeCart(); 
 	// checkOut();
 	
 	$('.question.circle.outline.icon')
 	  .popup({
 	    on: 'click'
 	  }) ;
 	$('.question.circle.outline.icon').on('click',function(){
 		console.log('test');
 	});
});

//GLOBAL shit
var cart_list = [];
var cart_list_ctr = 0;
var over_all_total = 0; 

function getCart(){
	post('cart/get_cart',{token:readCookie('token')},function(response){
		//console.log(response); 
		if(response.success == false){
			showSuccess(response.message); 
			return;
		}
 		
 		var items = response.data.items; 
 		var total_amount = response.data.total_amount;
 		var ctr = 1;
 		console.log(items);
		items.forEach((item)=>{
			var id 			=item.product_id;
			var desc 		=item.description;
			var qty			=item.qty;
			var price 		=parseFloat(item.price);
			var amount 		=item.amount; 
			var surcharge 	=item.surcharge;

			var unit_price 	= parseFloat(price) + parseFloat(surcharge);

			var new_obj = {  
				'product_id' 		: id,
				'desctiption' 		: desc,
				'price'				: unit_price, 
				'qty'				: qty,
				'amount'			: amount
			};

			over_all_total+=amount; 
			cart_list.push(new_obj); 
			cart_list_ctr++;

			ctr++;
		});

		//console.log('cart_list');
		//console.log(cart_list);
		//console.log('total: ' + over_all_total); 
		cartDisplayer(); 
	});
}

function changeQty(){
	$('.product_qty').on('change',function(){
		var value = this.value;
		var id = this.id;
		if(value == '' || value == 0){
			if (confirm('This item will be remove in your cart, do you want to continue?')) {
			    //remove item from cart
			    var data = { token:readCookie('token'),product_id:id };
			    var prod_id = id;
			    postRequest('cart/remove_item_from_cart',data,function(response){
			    	if (response.success == false) {
			    		showWarning('Failed to remove item from your cart');
			    	}
			    	//console.log(response);
			    	removeItemFromCart(prod_id);
			    	cart_counter--;
			    	updateCartItems(cart_counter);
			    	
			    }); 
			}
		}else{
			//update
			var data = { token:readCookie('token'),product_id:id,qty:value };
			postRequest('cart/update_cart',data,function(response){
		    	if (response.success == false) {
		    		showWarning('Failed to update item from your cart');
		    	}
		    	//console.log(response);
		    	updateItemFromCart(id,value)
		    });
			
		}
	});
}

function onQtyKeyUp(){
	$('.product_qty').keyup(function(){
		var value = this.value;
		value = value.replace(/\D+/g, "");

		//console.log(value);
		this.value = value;
		this.focus();

	});
}

function cartDisplayer(){
	$('#tbl_body').empty();
	if(cart_list.length == 0){
		$('#remove-cart').hide();
		$('#checkout').hide();
		$('#total_amount_displayer').empty(); 
		$('#total_amount_displayer').removeClass("text-right"); 
		$('#total_amount_displayer').addClass("text-center"); 
		$('#total_amount_displayer').text('Nothing to display');
	}else{
		for (i = 0; i < cart_list.length; i++) { 
			var unit_price = parseFloat(cart_list[i].price);
			$('#tbl_body').append(
				'<tr>'+
			      '<th scope="row">'+(i+1)+'</th>'+
			      '<td>'+cart_list[i].desctiption+'</td>'+
			      '<td class=""><input id="'+cart_list[i].product_id+'" class="form-control text-center product_qty" value="'+cart_list[i].qty+'"></input></td>'+
			      '<td class="text-right">'+addComma( unit_price )+'</td>'+
			      '<td class="text-right">'+addComma( cart_list[i].amount.toFixed(2) )+'</td>'+
			   '</tr>'
			); 
		}
		$('#total_amount').text( addComma( over_all_total.toFixed(2) ) );
		changeQty();
		onQtyKeyUp();
	}
}

function removeItemFromCart(id){

	var newlist = [];

	for (i = 0; i < cart_list.length; i++) {   
		if(cart_list[i].product_id == id){
			over_all_total-=cart_list[i].amount;
		}else{
			newlist.push(cart_list[i]);  
		}
	} 

	cart_list = newlist;
	cart_list_ctr--; 
	cartDisplayer(); 
}

function updateItemFromCart(id,value){ 
	for (i = 0; i < cart_list.length; i++) {   
		if(cart_list[i].product_id == id){
			over_all_total-=cart_list[i].amount;	

			cart_list[i].qty = value;
			cart_list[i].amount = value * cart_list[i].price;

			over_all_total += cart_list[i].amount;
		}
	}  
	cartDisplayer();
}
 
function removeCart(){
	$('#remove-cart').on('click',function(){

		if (confirm('Your cart will be empty, do you want to continue?')) {

			postRequest('cart/delete_cart',{token:readCookie('token')},function(response){
				if(response.success == false){
					showWarning(response.message);
				}
				showSuccess(response.message); 

				cart_list = [];
				over_all_total=0;
				cartDisplayer(); 
			  	cart_counter=0;
			  	updateCartItems(cart_counter);
			});

		}

	});
}

function checkOut(){
	$('#checkout').on('click',function(){

		if (confirm('Order confirmation, do you want to continue?')) {
			redirectTo("checkout"); 
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
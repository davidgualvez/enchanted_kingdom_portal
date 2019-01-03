$(document).ready(function(){

    //
    $('.ui.dropdown').dropdown();
    $('#mBtnMenu').on('click',function(){
        $('#mMenu').sidebar('toggle');
    });

    $('.login').on('click',function(){
        redirectTo('/login');
    });
    $('.register').on('click',function(){
        redirectTo('/signup');
    });


    // $('.main.menu').visibility({
    //     type: 'fixed'
    //   });
    
    btnLogin(); 

}); 

//global variable for all page  
var api = '';
var routes = {
    login:              '/signup',
    products:           '/products',
    productCategories:  '/products/groups',
    rewards:            '/rewards/lists',
    cart: {
        points: {
            addToCart :     '/cart/points/add_to_cart', //[product_id, qty]  
            count :         '/cart/points/count',
            checkout :      '/points/checkout'
        },
        wallet:{
            addToCart :     '/cart/wallet/add_to_cart', //[product_id, qty]  
            count :         '/cart/wallet/count',
            checkout :      '/wallet/checkout'
        },
        addToCart :     '/cart/add_to_cart', //[product_id, qty]  
        count :         '/cart/count',
        checkout :      '/checkout',
        order :         '/order',
        orderHistory :  '/order/history'
    },
    user : {
        updateInfo :    '/me',
        activePurchase: '/purchase/details/active',
        purchaseHistory:'/purchase/history'
    }
};

//
// Requests GET | POST 
//
function post(url, request, callback) {
    $.ajax({
        url: api + url,
        type: "POST",
        dataType: "json",
        data: request,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            callback(data);
        },
        error: function (data) {
            console.log(data);
            showError('Server error', 'Please ask the system administrator about this!', function () {

            });
        }
    });
}

function postWithHeader(url, request, callback) {
    var token = localStorage.getItem('token');
    $.ajax({
        url: api + url,
        type: "POST",
        dataType: "json",
        data: request,
        headers: {
            token: token
        },
        success: function (data) {
            callback(data);
        },
        error: function (data) {
            showError('Server error', 'Please ask the system administrator about this!', function () {

            });
        }
    });
}

function get(url, request, callback) {
    $.ajax({
        url: api + url,
        type: "GET",
        dataType: "json",
        data: request,
        // header : getHeader(),
        success: function (data) {
            callback(data);
        },
        error: function (data) {
            showError('Server error', 'Please ask the system administrator about this!', function () {

            });
        }
    });
}

//
// Authentication Handler
//
function isLogin() {
    var token = localStorage.getItem('token');
    if (token == '' || token == null) {
        return false; //says that the use is not loggedin
    }
    return true; // says that the user is current loggedin
}

function logout() {
    localStorage.clear();
    window.location.href = "/login";
}

function clearStorage() {
    localStorage.clear();
}

function redirectTo(link) {
    window.location.href = link;
}

function showInfo(title, message, callback) {
    iziToast.info({
        title: title,
        message: message,
        position: 'topRight',
        // backgroundColor: 'rgba(129,212,250, 1)',
        onClosed: function () {
            callback();
        },
        displayMode : 'replace'
    });
}

function showSuccess(title, message, callback) {
    
    iziToast.success({
        title: title,
        message: message,
        position: 'topRight',
        onClosed: function () {
            callback();
        },
        displayMode : 'replace'
    });

}

function showWarning(title, message, callback) {
    iziToast.warning({
        title: title,
        message: message,
        position: 'topRight',
        onClosed: function () {
            callback();
        },
        displayMode : 'replace'
    });
}

function showError(title, message, callback) {
    iziToast.error({
        title: title,
        message: message,
        position: 'topRight',
        onClosed: function () {
            callback();
        },
        displayMode : 'replace'
    });
}

function getParams(id) {
    var urlParams = new URLSearchParams(window.location.search);
    var x = urlParams.get(id); //getting the value from url parameter
    return x;
}

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function validateContactNumber(value) {
    //var regEx = /^([ 0-9\(\)\+\-]{8,})*$/; // accept any phone or mobile number
    var regEx = /^(09|\+639)\d{9}$/; // accept only PH Mobile number 
    if (!value.match(regEx)) {
        return false;
    }
    return true;
}


function btnLogin(){
    $('#btn-login').on('click',function(){
        window.location.href = '/login';
    });
}


//global app functionalities
function updateCartCount(){ 
    console.log('updating cart count....');
    var data = {
    };
    post(routes.cart.count, data, function(response){
        console.log(response);
        if(response.success == false){
            if(response.status == 401){ 
                redirectTo('/login'); //if not authenticated
            } 
            showWarning('Warning',response.message, function(){

            });
            return;
        }

        $('.cart_count').text(response.count);
    });
    
}

function text_truncate(str, length, ending) {
    if (length == null) {
      length = 100;
    }
    if (ending == null) {
      ending = '...';
    }
    if (str.length > length) {
      return str.substring(0, length - ending.length) + ending;
    } else {
      return str;
    }
};

function FormatNumberLength(num, length) {
    var r = "" + num;
    while (r.length < length) {
        r = "0" + r;
    }
    return r;
}
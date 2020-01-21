<?php 
return [
    //returing the declared branch id in .env
    'branch_id'     =>  env('APP_BRANCH_ID', 0),
    'portal_logout' => 	env('PORTAL_LOGOUT_URL','#'),
    'outlet_id'     =>  env('OUTLET_ID', '00'),
    'device_id'     =>  env('DEVICE_ID', '0')
];
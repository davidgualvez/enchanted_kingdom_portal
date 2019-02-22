<?php 

namespace App\AppServices;

use Log;
use App\Customer;

class CustomerServices{

    private $customer;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Customer $customer)
    { 
        $this->customer = $customer;
    }

    public function getType(){ 
        $ctype = [ 
        ];

        if ($this->customer->customer_type == 0) {
            $ctype = [ 
                'type'      => 'REGULAR',
                'discount'   => 0
            ];
        }

        if ($this->customer->customer_type == 1) { 
            $ctype = [
                'type'      => 'SENIOR',
                'discount'   => 20
            ];
        }
        if ($this->customer->customer_type == 2) { 
            $ctype = [
                'type' => 'PWD',
                'discount' => 20
            ];
        }

        if ($this->customer->customer_type == 3) { 
            $ctype = [
                'type'      => 'ZERO-RATED',
                'discount'   => 0
            ];
        }
        if ($this->customer->customer_type == 4) { 
            $ctype = [
                'type' => 'CORPORATE',
                'discount' => 0
            ];
        }

        return $ctype;
    }

}
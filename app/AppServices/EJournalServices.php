<?php 

namespace App\AppServices;

use Log; 

class EJournalServices
{
    private $obj;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct($obj)
    {
        $this->obj = $obj;
    }

    

}
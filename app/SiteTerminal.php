<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait
use Sofa\Eloquence\Mutable; // extension trait

class SiteTerminal extends Model
{
    use Eloquence, Mappable, Mutable;
    //
    protected $table        = 'SiteTerminals';
    protected $primaryKey   = 'ID';
    protected $connection   = 'sqlsrvEKSiteDB';
    public $timestamps      = false;
    
    //model mapping 
    protected $maps = [
      // implicit relation mapping:
        'group' => ['group_code', 'description'],

      // explicit relation mapping:
      //'picture' => 'profile.picture_path',

      // simple alias
      'id'                      => 'ID',
      'terminal_no'             => 'TERMNO',
      'invoice_no'              => 'INVNO',
      'non_invoice_no'          => 'NONINVONO',
      'description'             => 'DESC',
      'prn_location'            => 'PRNLOC',
      'spacer'                  => 'SPACER',
      'vat_divisor'             => 'VATDIV',
      'station_code'            => 'STATIONCODE',
      'amusement_tax'           => 'AMUTAX'
    ];

    protected $getterMutators = [ 
        'description'           => 'trim',
        'prn_location'          => 'trim'
    ];

    // retrieve setting
    public function terminalSetting(){
        return static::where('id', 4)->first();
    }
}

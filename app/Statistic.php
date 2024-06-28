<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    public $timestamps = false;
    protected $fillable = [
          'order_date',  'sales',  'profit','quantity', 'total_oder'
    ];
 
    protected $primaryKey = 'id_id_statistical';
 	protected $table = 'tbl_statistical';
 	
 	public function login(){
 		return $this->belongsTo('App\Login', 'user');
 	}
}

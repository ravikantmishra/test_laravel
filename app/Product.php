<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
   // protected $table = 'measure_units';
	//
	public $timestamps = false;
	//protected $fillable = ['_token'];
	protected $guarded = ['_token'];
}

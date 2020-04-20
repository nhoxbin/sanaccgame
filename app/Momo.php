<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Momo extends Model
{
	public $timestamps = false;
	protected $keyType = 'string';
	protected $primaryKey = 'recharge_bill_id';
    protected $fillable = ['recharge_bill_id', 'phone', 'code'];

    public function recharge_bill() {
    	return $this->belongsTo('App\RechargeBill');
    }
}

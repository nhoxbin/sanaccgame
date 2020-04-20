<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nganluong extends Model
{
    public $incrementing = false;
    public $timestamps = false;
	protected $primaryKey = 'recharge_bill_id';
	protected $keyType = 'string';
    protected $fillable = ['recharge_bill_id', 'token'];

    public function recharge_bill() {
    	return $this->belongsTo('App\RechargeBill');
    }
}

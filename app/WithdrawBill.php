<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WithdrawBill extends Model
{
	public $incrementing = false;
	protected $keyType = 'string';
    protected $fillable = ['id', 'user_id', 'money', 'phone', 'type', 'confirm', 'reason'];

    public function user() {
    	return $this->belongsTo('App\User');
    }
}

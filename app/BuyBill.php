<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BuyBill extends Model
{
	public $incrementing = false;
	protected $keyType = 'string';
    protected $fillable = ['id', 'user_id', 'account_id', 'reason'];

    public function user() {
    	return $this->belongsTo('App\User');
    }

    public function account() {
    	return $this->belongsTo('App\Account');
    }
}

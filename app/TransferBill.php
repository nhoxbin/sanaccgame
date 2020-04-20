<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransferBill extends Model
{
    public $incrementing = false;
	protected $keyType = 'string';
	protected $fillable = ['id', 'user_id', 'to_user_id', 'money'];

	public function from() {
		return $this->belongsTo('App\User', 'user_id', 'id');
	}

	public function to() {
		return $this->belongsTo('App\User', 'to_user_id', 'id');
	}
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
	public $timestamps = false;
	public $incrementing = false;
	protected $primaryKey = 'user_id';
    protected $fillable = ['user_id', 'name', 'stk', 'master_name'];

    public function withdraw_bills() {
    	return $this->hasMany('App\WithdrawBill');
    }

    public function user() {
    	return $this->belongsTo('App\User');
    }
}

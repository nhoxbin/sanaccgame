<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['user_id', 'game_id', 'username', 'password', 'contact_phone', 'contact_link', 'price', 'info', 'description', 'client_status', 'admin_status', 'pictures'];

    public function game() {
    	return $this->belongsTo('App\Game');
    }

    public function user() {
    	return $this->belongsTo('App\User');
    }

    public function buy_bill() {
    	return $this->hasOne('App\BuyBill');
    }
}

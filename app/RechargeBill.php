<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RechargeBill extends Model
{
    public $incrementing = false;
	protected $keyType = 'string';
    protected $fillable = [
    	'id', 'user_id', 'money', 'type', 'confirm', 'comment', 'reason'
    ];

    public function user() {
    	return $this->belongsTo('App\User');
    }

    public function card() {
    	return $this->hasOne('App\Card');
    }

    public function momo() {
        return $this->hasOne('App\Momo');
    }

    public function nganluong() {
        return $this->hasOne('App\Nganluong');
    }
}

<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'cash', 'type', 'password', 'country'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function bank() {
        return $this->hasOne('App\Bank');
    }

    public function accounts() {
        return $this->hasMany('App\Account');
    }

    public function buy_bills() {
        return $this->hasMany('App\BuyBill');
    }

    public function recharge_bills() {
        return $this->hasMany('App\RechargeBill');
    }

    public function withdraw_bills() {
        return $this->hasMany('App\WithdrawBill');
    }

    public function transfer_bills_sender() {
        return $this->hasMany('App\TransferBill');
    }

    public function transfer_bills_receiver() {
        return $this->hasMany('App\TransferBill', 'to_user_id');
    }
}

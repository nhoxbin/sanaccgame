<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'sort_name', 'fee', 'picture', 'info'];

    public function accounts() {
    	return $this->hasMany('App\Account');
    }
}

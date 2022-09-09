<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Coupon extends Authenticatable
{
    use Notifiable;
    protected $table='coupon';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['coupon_code','title','discount','start_date','end_date','ip','status'];

}

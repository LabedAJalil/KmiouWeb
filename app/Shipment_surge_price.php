<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Shipment_surge_price extends Authenticatable
{
    use Notifiable;
    protected $table='shipment_surge_price';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['shipment_id','surge_price_for_pickup','surge_price_for_drop','pick_time_diff','drop_time_diff','pickup_amount','drop_amount','doc','comment','reject_comment','status'];

}

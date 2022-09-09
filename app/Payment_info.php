<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Payment_info extends Authenticatable
{
    use Notifiable;
    protected $table='payment_info';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['shipment_id','user_id','percent','admin_portion','amount','type','payment_status','collected_by'];

}

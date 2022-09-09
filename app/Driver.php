<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Driver extends Authenticatable
{
    use Notifiable;
    protected $table='driver';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['transporter_id','driver_id','email','status'];

}

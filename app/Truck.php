<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Truck extends Authenticatable
{
    use Notifiable;
    protected $table='truck';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['truck_type','truck_img','truck_desc','status'];

}

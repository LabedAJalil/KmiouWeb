<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Surge_price extends Authenticatable
{
    use Notifiable;
    protected $table='surge_price';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['total_diff_hours','price_per_hour','status'];

}

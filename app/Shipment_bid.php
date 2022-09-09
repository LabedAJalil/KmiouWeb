<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shipment_bid extends Model
{
   protected $table = 'shipment_bid';
   public $timestamps = true;

   protected $fillable = ['shipment_id','user_id','bid_amount','status'];
}

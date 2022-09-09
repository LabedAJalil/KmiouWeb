<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
   protected $table = 'shipment';
   public $timestamps = true;

   protected $fillable = ['user_id','driver_id','vehicle_id','pickup','drop','payment_type','amount','basic_amount','tax_per','tax_amount','discount_per','discount_amount','card_id','receipt','payment_status','status'];
   
}

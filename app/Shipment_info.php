<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shipment_info extends Model
{
   protected $table = 'shipment_info';
   public $timestamps = true;

   protected $fillable = ['shipment_id','sender_first_name','sender_last_name','sender_email','sender_mobile','receiver_first_name','receiver_last_name','receiver_email','receiver_mobile','pickup_date','drop_date','pickup_lat','pickup_long','drop_lat','drop_long','quatation_type','quatation_amount','transport_type','goods_type','weight','document','info'];
   
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Track_shipment extends Model
{
   protected $table = 'track_shipment';
   public $timestamps = true;

   protected $fillable = ['shipment_id','status','payment_status'];
}

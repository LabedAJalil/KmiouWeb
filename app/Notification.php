<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
   protected $table = 'notification';
   public $timestamps = true;

   protected $fillable = ['from_user_id','to_user_id','is_read','ref_id','noti_type','title','message'];
   
   /*noti_type 1 = Send Fuel Request,2 = Accept Fill Request,3 = Send Receipt,4 = Paid Invoice*/
}

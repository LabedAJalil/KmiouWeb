<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
   protected $table = 'users_card';
   public $timestamps = true;

   protected $fillable = ['user_id','card_no','holder_name','expiry_month','expiry_year','cvv','status'];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
   protected $table = 'review';
   public $timestamps = true;

   protected $fillable = ['user_id','ref_id','review_text','rate','type','status'];
}

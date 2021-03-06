<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationId extends Model
{    
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
       'id', 'user_id', 'type'
   ];

   protected $keyType = 'string';
}

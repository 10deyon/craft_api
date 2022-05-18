<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passcode extends Model
{
   protected $hidden = [
      'id', 'passcode', 'created_at', 'updated_at'
   ];

   protected $fillable = [
      'passcode',
   ];
}

<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
   protected $table = "order_status";
   
   protected $fillable = [
      'status',
      'order_id',
   ];

   protected $hidden = ['created_at', 'updated_at'];
   
   public function order() {
      return $this->belongsTo(Order::class);
   }
}

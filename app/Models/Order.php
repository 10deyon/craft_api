<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use UsesUuid;

    protected $fillable = [
        'name',
        'tagline',
        'email',
        'phone',
        'amount'
    ];

    public function order_status() {
        return $this->hasOne(OrderStatus::class, 'order_id', 'id');
    }
}

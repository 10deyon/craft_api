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
        'phone'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['uuid'];

    public function order($id)
    {
        return $this->with($this->with)->findOrFail($id);
    }

    public function order_status() {
        return $this->hasOne(OrderStatus::class, 'order_uuid', 'uuid');
    }
}

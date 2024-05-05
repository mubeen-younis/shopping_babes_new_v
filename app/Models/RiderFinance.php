<?php

namespace App\Models;

use App\Models\Order;
use App\Models\DeliveryMan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RiderFinance extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function rider()
    {
        return $this->hasOne(DeliveryMan::class, 'id', 'rider_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'delivery_man_id', 'rider_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TravelSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination',
        'departure_datetime',
        'quota',
        'available_quota',
        'price',
    ];

    protected $casts = [
        'departure_datetime' => 'datetime',
        'price' => 'decimal:2',
    ];

    public function ticketOrders()
    {
        return $this->hasMany(TicketOrder::class);
    }

    public function hasAvailableQuota($quantity)
    {
        return $this->available_quota >= $quantity;
    }

    public function decreaseQuota($quantity)
    {
        $this->available_quota -= $quantity;
        $this->save();
    }

    public function increaseQuota($quantity)
    {
        $this->available_quota += $quantity;
        $this->save();
    }
}

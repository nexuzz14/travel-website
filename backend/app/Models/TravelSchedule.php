<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination',
        'departure_date',
        'departure_time',
        'quota',
        'available_quota',
        'price',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'price' => 'decimal:2',
    ];

    protected $appends = ['is_available'];

    public function ticketOrders()
    {
        return $this->hasMany(TicketOrder::class);
    }

    public function getIsAvailableAttribute(): bool
    {
        return $this->available_quota > 0;
    }

    public function reduceQuota(int $quantity): bool
    {
        if ($this->available_quota < $quantity) {
            return false;
        }

        $this->available_quota -= $quantity;
        return $this->save();
    }

    public function restoreQuota(int $quantity): bool
    {
        $this->available_quota += $quantity;
        return $this->save();
    }

    public function scopeAvailable($query)
    {
        return $query->where('available_quota', '>', 0);
    }

    public function scopeByDestination($query, $destination)
    {
        if ($destination) {
            return $query->where('destination', 'like', "%{$destination}%");
        }
        return $query;
    }

    public function scopeByDate($query, $date)
    {
        if ($date) {
            return $query->whereDate('departure_date', $date);
        }
        return $query;
    }
}

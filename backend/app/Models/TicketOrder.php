<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'travel_schedule_id',
        'passenger_name',
        'passenger_phone',
        'passenger_email',
        'quantity',
        'total_price',
        'status',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function travelSchedule()
    {
        return $this->belongsTo(TravelSchedule::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function confirm(): bool
    {
        $this->status = 'confirmed';
        return $this->save();
    }

    public function cancel(): bool
    {
        if ($this->status !== 'cancelled') {
            $this->status = 'cancelled';

            if ($this->travelSchedule) {
                $this->travelSchedule->restoreQuota($this->quantity);
            }

            return $this->save();
        }
        return false;
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }
}

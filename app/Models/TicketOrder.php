<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'travel_schedule_id',
        'order_code',
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

    public static function generateOrderCode()
    {
        $date = now()->format('Ymd');
        $lastOrder = self::whereDate('created_at', now())->latest()->first();
        $number = $lastOrder ? intval(substr($lastOrder->order_code, -3)) + 1 : 1;
        return 'TRV-' . $date . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}

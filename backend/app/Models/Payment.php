<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_order_id',
        'payment_method',
        'amount',
        'payment_status',
        'payment_date',
        'invoice_path',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    public function ticketOrder()
    {
        return $this->belongsTo(TicketOrder::class);
    }

    public function markAsPaid(): bool
    {
        $this->payment_status = 'paid';
        $this->payment_date = now();
        return $this->save();
    }

    public function markAsFailed(): bool
    {
        $this->payment_status = 'failed';
        return $this->save();
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_order_id',
        'payment_status',
        'payment_date',
        'payment_proof',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
    ];

    public function ticketOrder()
    {
        return $this->belongsTo(TicketOrder::class);
    }
}

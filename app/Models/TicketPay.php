<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketPay extends Model
{
    /** @use HasFactory<\Database\Factories\TicketPayFactory> */
    use HasFactory;

    protected $fillable=[
        'date_create',
        'pay_value',
        'pay_way',
        'pay_type',
        'ticket_id'
    ];
}

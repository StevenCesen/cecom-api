<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketOrder extends Model
{
    /** @use HasFactory<\Database\Factories\TicketOrderFactory> */
    use HasFactory;

    protected $fillable=[
        'date_create',
        'status',
        'detail',
        'product_id',
        'ticket_id'
    ];
}

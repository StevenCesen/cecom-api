<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCost extends Model
{
    /** @use HasFactory<\Database\Factories\TicketCostFactory> */
    use HasFactory;
    protected $fillable=[
        'date_create',
        'quantity',
        'product_id',
        'ticket_id'
    ];
}

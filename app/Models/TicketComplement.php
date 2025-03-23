<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketComplement extends Model
{
    /** @use HasFactory<\Database\Factories\TicketComplementFactory> */
    use HasFactory;
    protected $fillable=[
        'date_create',
        'quantity',
        'media',
        'ticket_id'
    ];
}

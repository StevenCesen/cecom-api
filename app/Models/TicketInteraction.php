<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketInteraction extends Model
{
    /** @use HasFactory<\Database\Factories\TicketInteractionFactory> */
    use HasFactory;

    protected $fillable=[
        'date_create',
        'detail',
        'media',
        'ticket_id'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    /** @use HasFactory<\Database\Factories\TicketFactory> */
    use HasFactory;
    protected $fillable=[
        'title',
        'date_create',
        'date_finish',
        'last_interaction',
        'status',
        'stars',
        'comment',
        'ride_path',
        'xml_path',
        'client_id',
        'user_id',
        'contributor_id'
    ];

    public function contributor(){
        return $this->belongsTo(Contributor::class);
    }
}

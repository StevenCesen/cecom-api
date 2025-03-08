<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Establishment extends Model
{
    /** @use HasFactory<\Database\Factories\EstablishmentFactory> */
    use HasFactory;

    protected $fillable=[
        'nro_estab',
        'name',
        'nro_invoices',
        'nro_liquidations',
        'nro_credit_note',
        'nro_debit_note',
        'nro_guides',
        'nro_retains',
        'user_id',
        'contributor_id'
    ];

    public function contributor(){
        return $this->belongsTo(Contributor::class);
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function order(){
        return $this->belongsTo(Order::class);
    }

}

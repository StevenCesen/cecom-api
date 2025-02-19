<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    /** @use HasFactory<\Database\Factories\ClientFactory> */
    use HasFactory;

    protected $fillable=[
        'name',
        'identification',
        'phone',
        'direction',
        'email',
        'contributor_id'
    ];

    /**
     * ======================== DEFINICIÓN DE RELACIONES ============================
     */

    public function contributor(){
        return $this->belongsTo(Contributor::class);
    }
    
    public function voucher(){
        return $this->hasMany(Voucher::class)->chaperone();
    }

    public function order(){
        return $this->hasMany(Order::class)->chaperone();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable=[
        'floor',
        'table',
        'create_date',
        'details',
        'notes',
        'status',
        'client_name',
        'client_identification',
        'order_number_day',
        'user_id',
        'contributor_id'
    ];

    /**
     * ====================== DEFINICIÓN DE RELACIONES ======================
     */

    public function user(){
        return $this->hasOne(User::class);
    }

    public function establisment(){
        return $this->hasOne(Establishment::class,'user_id','user_id');
    }
    
    public function itemcart(){
        return $this->hasMany(Itemcart::class);
    }
    
    public function contributor(){
        return $this->belongsTo(Contributor::class);
    }

}

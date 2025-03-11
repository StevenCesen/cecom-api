<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itemcart extends Model
{
    /** @use HasFactory<\Database\Factories\ItemcartFactory> */
    use HasFactory;

    protected $fillable=[
        'notes',
        'quantity',
        'complements',
        'item_id',
        'order_id',
        'status_pay'
    ];

    public function order(){
        return $this->belongsTo(Contributor::class);
    }
    
    public function item(){
        return $this->hasOne(Item::class);
    }

}

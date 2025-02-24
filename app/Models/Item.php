<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /** @use HasFactory<\Database\Factories\ItemFactory> */
    use HasFactory;

    protected $fillable=[
        'price_menu',
        'quantity_menu',
        'menu_id',
        'product_id'
    ];

    public function menu(){
        return $this->belongsTo(Menu::class);
    }

}

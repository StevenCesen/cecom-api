<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable=[
        'name',
        'code_aux',
        'quantity',
        'tax',
        'description',
        'image_path',
        'price',
        'contributor_id',
        'type_id',
        'state'
    ];

    public function contributor(){
        return $this->belongsTo(Contributor::class);
    }
    
    public function type(){
        return $this->belongsTo(Type::class);
    }
}

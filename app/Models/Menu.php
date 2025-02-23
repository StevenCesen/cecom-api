<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    /** @use HasFactory<\Database\Factories\MenuFactory> */
    use HasFactory;
    protected $fillable=[
        'name',
        'status',
        'create_date',
        'contributor_id'
    ];
    
    public function contributor(){
        return $this->belongsTo(Contributor::class);
    }

    public function item(){
        return $this->hasMany(Item::class)->chaperone();
    }

}
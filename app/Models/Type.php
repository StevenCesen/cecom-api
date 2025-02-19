<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    /** @use HasFactory<\Database\Factories\TypeFactory> */
    use HasFactory;

    protected $fillable=[
        'name',
        'contributor_id'
    ];

    public function contributor(){
        return $this->belongsTo(Contributor::class);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    /** @use HasFactory<\Database\Factories\VoucherFactory> */
    use HasFactory;

    protected $fillable=[
        'sequential',
        'access_key',
        //'email',
        'doc_type',
        'issue_date',
        'create_date',
        'subtotal_amount',
        'tax_value',
        'total_amount',
        'ride_path',
        'xml_path',
        'status',
        'contributor_id',
        'client_id',
        'order_id',
        'pay_ways',
        'detail'
    ];

    /**
     * ======================= DEFINICIÓN DE RELACIONES ========================
     */

    public function contributor(){
        return $this->belongsTo(Contributor::class);
    }

    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function order(){
        return $this->belongsTo(Order::class);
    }
}
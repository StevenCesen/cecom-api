<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contributor extends Model
{
    /** @use HasFactory<\Database\Factories\ContributorFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'identification',
        'direction',
        'commercial_name',
        'regimen',
        'phone',
        'signature_path',
        'x509_serial_number',
        'x509_der_hash',
        'exponent',
        'module',
        'issuer_name',
        'validity_date',
        'x509_self',
        'certificate',
        'user_limit',
        'doc_limit',
        'estab_limit',
        'logo_path'
    ];

    /**
     * ========================== DEFINICIÓN DE RELACIONES ============================
     */

    public function user(){
        return $this->hasMany(User::class, 'contributor_id','id')->chaperone();      
    }
    
    public function client(){
        return $this->hasMany(Client::class)->chaperone();
    }

    public function product(){
        return $this->hasMany(Product::class)->chaperone();
    }

    public function establishment(){
        return $this->hasMany(Establishment::class)->chaperone();
    }

    public function type(){
        return $this->hasMany(Type::class)->chaperone();
    }

    public function order(){
        return $this->hasMany(Order::class)->chaperone();
    }
    
    public function voucher(){
        return $this->hasMany(Voucher::class)->chaperone();
    }
    
    public function menus(){
        return $this->hasMany(Menu::class)->chaperone();
    }

    public function largestUser(){
        return $this->user()->one()->ofMany('id', 'max');
    }

}

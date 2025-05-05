<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'uf',
        'city',
        'number',
        'address',
        'complement',
        'neighborhood',
    ];

    public function customers()
    {
        return $this->hasOne(Customer::class, 'id_address');
    }
}

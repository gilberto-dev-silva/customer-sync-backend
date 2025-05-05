<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'status',
        'cpf_cnpj',
        'telephone',
        'id_address',
        'person_type',
        'date_of_birth',
        'id_profession',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function address()
    {
        return $this->belongsTo(Address::class, 'id_address');
    }

    public function profession()
    {
        return $this->belongsTo(Profession::class, 'id_profession');
    }
}

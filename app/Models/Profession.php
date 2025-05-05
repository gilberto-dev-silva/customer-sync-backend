<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profession extends Model
{
    use HasFactory;

    protected $fillable = [
        'profession_name',
    ];
    public function customers()
    {
        return $this->hasMany(Customer::class, 'id_profession');
    }
}

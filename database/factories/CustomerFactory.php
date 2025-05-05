<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Customer;
use App\Models\Profession;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'id_address' => Address::factory(),
            'id_profession' => Profession::inRandomOrder()->first()->id,
            'telephone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'date_of_birth' => $this->faker->date('Y-m-d'),
            'cpf_cnpj' => $this->faker->numerify('###########'),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'person_type' => $this->faker->randomElement(['Física', 'Jurídica']),
        ];
    }
}

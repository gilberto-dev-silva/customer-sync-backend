<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Profession;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CustomerService
{
    public function __construct(
        private CustomerRepositoryInterface $customerRepository
    ) {}

    public function createCustomer(array $data)
    {
        return DB::transaction(function () use ($data) {
            $profession = $this->validateProfession($data['id_profession']);

            $address = $this->createAddress($data);

            $customerData = $this->formatCustomerData($data, $address->id, $profession->id);
            $customer = $this->customerRepository->create($customerData);

            return $this->customerRepository->findWithRelations($customer->id, ['address', 'profession']);
        });
    }

    protected function validateProfession(int $professionId): Profession
    {
        $profession = Profession::findOrFail($professionId);

        if (!$profession) {
            throw new ModelNotFoundException('Profissão não encontrada');
        }

        return $profession;
    }

    protected function createAddress(array $data): Address
    {
        return Address::create([
            'address' => $data['address'],
            'number' => $data['number'],
            'neighborhood' => $data['neighborhood'],
            'complement' => $data['complement'] ?? null,
            'city' => $data['city'],
            'uf' => strtoupper($data['uf'])
        ]);
    }

    protected function formatCustomerData(array $data, int $addressId, int $professionId): array
    {
        return [
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'cpf_cnpj' => preg_replace('/[^0-9]/', '', $data['cpf_cnpj']),
            'telephone' => preg_replace('/[^0-9]/', '', $data['telephone']),
            'person_type' => $data['person_type'],
            'date_of_birth' => $data['date_of_birth'],
            'status' => $data['status'],
            'id_profession' => $professionId,
            'id_address' => $addressId
        ];
    }
}

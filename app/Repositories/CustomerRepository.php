<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Repositories\Contracts\CustomerRepositoryInterface;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function create(array $data): Customer
    {
        return Customer::create($data);
    }

    public function findWithRelations(int $id, array $relations): ?Customer
    {
        return Customer::with($relations)->find($id);
    }
}

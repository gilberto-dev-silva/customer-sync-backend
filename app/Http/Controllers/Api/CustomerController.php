<?php

namespace App\Http\Controllers\Api;

use App\Services\CustomerService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreClienteRequest;


class CustomerController extends Controller
{
    public function __construct(private CustomerService $customerService) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'message' => 'Bem-vindo à API de gerenciamento de cliente.'
        ]);
    }

    public function store(StoreClienteRequest $request): JsonResponse
    {
        try {
            $customer = $this->customerService->createCustomer($request->validated());

            return response()->json([
                'message' => 'Cliente criado com sucesso',
                'data' => $customer
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar cliente',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}

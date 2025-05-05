<?php

declare(strict_types=1);

namespace App\Http\Requests\Customer;

use App\Rules\ValidCPF;
use App\Rules\ValidCNPJ;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @psalm-type StatusType = 'Ativo'|'Inativo'
 * @psalm-type PersonType = 'Física'|'Jurídica'
 *
 * @template-extends FormRequest<array{
 *     number: int,
 *     uf: string,
 *     city: string,
 *     address: string,
 *     complement?: string,
 *     neighborhood: string,
 *     name: string,
 *     email: string,
 *     status: StatusType,
 *     person_type: PersonType,
 *     date_of_birth: string,
 *     telephone: string,
 *     id_profession: int,
 *     cpf_cnpj: string
 * }>
 */
class StoreClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            // Endereço
            'number'        => 'required|integer|min:1',
            'uf'            => 'required|string|size:2',
            'city'          => 'required|string|max:255',
            'address'       => 'required|string|max:255',
            'complement'    => 'nullable|string|max:255',
            'neighborhood'  => 'required|string|max:255',

            // Pessoais
            'name'          => 'required|string|max:255',
            'email'         => [
                'required',
                'email',
                'max:255',
                Rule::unique('customers', 'email'),
            ],
            'status' => ['required', Rule::in(['Active', 'Inactive'])],
            'person_type'   => 'required|in:Física,Jurídica',
            'date_of_birth' => 'required|date|before_or_equal:today',
            'telephone'     => [
                'required',
                'string',
                'regex:/^\d{10,11}$/'
            ],
            'id_profession' => 'required|exists:professions,id',

            // CPF/CNPJ com validação dinâmica
            'cpf_cnpj' => [
                'required',
                'string',
                Rule::unique('customers', 'cpf_cnpj'),
                // Regras dinâmicas delegadas a seguir
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'telephone.regex'              => 'O telefone deve conter apenas números (10 ou 11 dígitos)',
            'date_of_birth.before_or_equal'=> 'A data de nascimento não pode ser futura',
            'uf.size'                      => 'UF deve ter exatamente 2 caracteres',
            'number.min'                   => 'O número deve ser positivo',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('telephone')) {
            $this->merge([
                'telephone' => preg_replace('/\D/', '', (string)$this->get('telephone')),
            ]);
        }

        if ($this->has('cpf_cnpj')) {
            $this->merge([
                'cpf_cnpj' => preg_replace('/\D/', '', (string)$this->get('cpf_cnpj')),
            ]);
        }
    }

    public function withValidator(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        $validator->sometimes('cpf_cnpj', [new ValidCPF()], fn () => $this->get('person_type') === 'Física');
        $validator->sometimes('cpf_cnpj', [new ValidCNPJ()], fn () => $this->get('person_type') === 'Jurídica');
    }
}

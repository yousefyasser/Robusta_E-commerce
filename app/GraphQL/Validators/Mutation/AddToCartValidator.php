<?php

declare(strict_types=1);

namespace App\GraphQL\Validators\Mutation;

use Nuwave\Lighthouse\Validation\Validator;

final class AddToCartValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * Return custom messages for the defined validation rules.
     *
     * @return array<string>
     */
    public function messages(): array
    {
        return [
            'product_id.exists' => 'The selected product ID is invalid.',
            'quantity.min' => 'The quantity must be at least 1.',
        ];
    }
}

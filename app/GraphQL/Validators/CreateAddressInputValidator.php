<?php

declare(strict_types=1);

namespace App\GraphQL\Validators;

use Nuwave\Lighthouse\Validation\Validator;

final class CreateAddressInputValidator extends Validator
{
    /** @var array<string> $fields */
    private $fields = [
        'label',
        'recipient_name',
        'address_line_1',
        'address_line_2',
        'state',
        'city',
        'country',
        'postal_code',
        'phone_number'
    ];

    /**
     * Return the validation rules.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return array_fill_keys(array_values($this->fields), ['required']);
    }

    /**
     * Return custom messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        $messages = [];
        foreach (array_values($this->fields) as $field) {
            $messages["{$field}.required"] = "The {$field} field is required.";
        }

        return $messages;
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Schema;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var string */
        $sortInput = $this->input('sort');
        $columns = Schema::getColumnListing('orders');
        $sortColumn = explode('_', $sortInput)[0] ?? null;

        if ($sortColumn && !in_array($sortColumn, $columns)) {
            return [
                'sort' => ['in:' . implode(',', $columns)]
            ];
        }

        return [
            'order_id' => ['nullable', 'numeric'],
            'status' => ['nullable', 'string', 'in:pending,processing,completed,cancelled'],
            'sort' => ['nullable', 'string', 'regex:/^[a-zA-Z_]+_(asc|desc)$/']
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);

        throw new HttpResponseException($response);
    }
}

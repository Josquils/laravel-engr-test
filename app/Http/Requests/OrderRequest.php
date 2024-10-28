<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Http\Exceptions\HttpResponseException;
// use Illuminate\Contracts\Validation\Validator;

class OrderRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'hmoCode' => 'required|string|exists:hmos,code',
            'providerName' => 'required|string',
            'encounterDate' => 'required|date|date_format:Y-m-d',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.unitPrice' => 'required|numeric|min:0.1',
            'items.*.quantity' => 'required|numeric|min:0.1',
            'items.*.subTotal' => 'required|numeric|min:0.1',
            'total' => 'required|numeric|min:0.1',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $total = 0;
            foreach($this->safe()->items as $item){
                $total += $item['unitPrice'] * $item['quantity'];
            }
            if ($total != $this->safe()->total) {
                $validator->errors()->add('total', 'Total sum does not match');
            }
        });
    }

    public function orderData()
    {
        return [
            'provider_name' => $this->safe()->providerName,
            'hmo_code' => $this->safe()->hmoCode,
            'encounter_date' => $this->safe()->encounterDate,
            'payload' => $this->safe()->items,
            'total' => $this->safe()->total,
        ];
    }
}

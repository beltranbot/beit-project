<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'customer_id' => [
                'required',
                'exists:customer,customer_id'
            ],
            'creation_date' => [
                'required',
                'date'
            ],
            'delivery_address' => [
                'required',
                'max:191'
            ],
            'order_details' => [
                'required',
                'array',
                'min:1',
                'max:5'
            ],
            'order_details.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
            'order_details.*.product_id' => [
                'required',
                'exists:product,product_id',
                'exists:customer_product,product_id,customer_id,'.$this->customer_id
            ]
        ];

        return $rules;
    }
}

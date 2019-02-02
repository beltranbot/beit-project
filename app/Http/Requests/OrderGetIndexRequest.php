<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderGetIndexRequest extends FormRequest
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
        return [
            'customer_id' => [
                'sometimes',
                'integer',
                'exists:customer,customer_id',
            ],
            'date_start' => [
                'sometimes',
                'date',
            ],
            'date_end' => [
                'sometimes',
                'date',
                'after_or_equal:date_start',
            ]
        ];
    }
}

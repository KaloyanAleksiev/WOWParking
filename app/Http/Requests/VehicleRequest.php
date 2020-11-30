<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VehicleRequest extends FormRequest
{
    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData() {
        return array_merge(
            $this->all(),
            [
                'register_number' => preg_replace('/\s+/', "", $this->register_number)
            ]
        );
    }

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
            'register_number' =>
            [
                'required',
                'regex:/\b[A-Z]{1,2}\d{4}[A-Z]{2}\b/'
            ]
        ];
    }
}

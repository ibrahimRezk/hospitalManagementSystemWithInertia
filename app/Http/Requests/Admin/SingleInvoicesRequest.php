<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SingleInvoicesRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'patient'        => ['bail', 'required'],
            'doctor'        => ['bail', 'required'],
            'section'        => ['bail', 'required'],
            'type'        => ['bail', 'required' ,Rule::in(['1', '2'])],
            'service'        => ['bail', 'required'],
            'discount_value'        => ['bail', 'required', 'numeric' , 'min:0'],
            'price'        => ['bail', 'required', 'numeric' , 'min:0'],
            'tax_rate'        => ['bail', 'required', 'numeric' , 'min:0'],
            'tax_value'        => ['bail', 'required', 'numeric' , 'min:0'],
            'total_with_tax'        => ['bail', 'required', 'numeric', 'min:0'],
            
        ];
    }
}

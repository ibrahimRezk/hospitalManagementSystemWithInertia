<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReceiptsRequest extends FormRequest
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
            'patient_id'=> 'bail|required|'. Rule::exists(User::class , 'id'),
            // 'patient_id' => ['bail', 'required', Rule::exists(User::class, 'id')], 

            'amount'=>'required|numeric',
            'description'=>'nullable'
        ];
    }
}

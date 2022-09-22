<?php

namespace App\Http\Requests\Admin;

use App\Models\Section;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UsersRequest extends FormRequest
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
        $model = $this->route('user');
        // $model = ( $this->user->id ) ?? null ; 



        // $passwordRule = $model ? ['nullable'] : ['required'];

        return [
            'name_ar' => ['bail', 'required', 'string', 'max:255'],
            'name_en' => ['bail', 'required', 'string', 'max:255'], 
            'address_ar' => ['bail', 'nullable', 'string', 'max:255'],
            'address_en' => ['bail', 'nullable', 'string', 'max:255'],

            // 'email' => ['bail', 'required', 'email', 'max:255', Rule::unique(User::class)->ignore($model->id ?? null)],
            'email' => 'bail|required|email|max:255|unique:users,email,'.$this->id,   /// it workin only like this because we don't use route model binding in editing
            // 'password' => ['bail', ...$passwordRule, Password::defaults()],
            // 'passwordConfirmation' => ['bail', ...$passwordRule, 'same:password'], 
            'phone'=> ['bail', 'nullable', 'string', 'max:255'],
            'password' => ['bail', 'required', Password::defaults()],
            'passwordConfirmation' => ['bail','required', 'same:password'],
            'roleId' => ['bail',  'nullable', Rule::exists(Role::class, 'id')], 
            'section_id' => ['bail', 'nullable', Rule::exists(Section::class, 'id')], 
            'appointments'=> ['bail', 'nullable', 'string', 'max:255'],
            'status'=> ['bail', 'nullable'],

            'birth_date' =>  ['bail', 'nullable', 'date', 'max:255'],

            // change birth data to type to date instead of string
            'gender' => ['bail', 'nullable', 'string', 'max:255'],
            'blood_group' => ['bail', 'nullable', 'string', 'max:255'],

        ];
    }
}

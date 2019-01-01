<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserEditRequest extends FormRequest
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
            'firstname'=>'required',
            'name'=>'required',
            'email'=>'required'
        ];
    }

    public function messages()
    {
        return [
            'firstname.required' => 'Bitte geben Sie Ihren Vornamen ein',
            'name.required'  => 'Bitte geben Sie Ihren Nachnamen ein',
            'email.required'  => 'Bitte geben Sie Ihre E-Mail-Adresse ein'
        ];
    }
}

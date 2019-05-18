<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
            'email'=>'required',
            'password'=>'required|min:8'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'firstname.required' => 'Bitte geben Sie Ihren Vornamen ein',
            'name.required'  => 'Bitte geben Sie Ihren Nachnamen ein',
            'email.required'  => 'Bitte geben Sie Ihre E-Mail-Adresse ein',
            'password.required'  => 'Bitte geben Sie ein Passwort ein',
            'password.min' => 'Das Passwort muss mindestens 8 Stellen haben'
        ];
    }
}

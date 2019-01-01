<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EntryInitRequest extends FormRequest
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
            'balance'=>'required'
        ];
    }

    public function messages()
    {
        return [
            'balance.required' => 'Bitte tragen Sie ein welchen Startwert wir verwenden sollen. Soll kein bestimmter Wert 
                                   eingetragen werden, geben Sie bitte 0 ein.'
        ];
    }
}

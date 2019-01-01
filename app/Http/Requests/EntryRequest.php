<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EntryRequest extends FormRequest
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
            'begin'=>'required',
            'end'=>'required',
            'break'=>'required'
        ];
    }

    public function messages()
    {
        return [
            'begin.required' => 'Bitte geben ein, wann Sie begonnen haben zu arbeiten',
            'end.required'  => 'Bitte geben ein, wann Sie aufgehört haben zu arbeiten',
            'break.required'  => 'Bitte geben Sie ein, wie viel Pause Sie gemacht haben',

        ];
    }
}

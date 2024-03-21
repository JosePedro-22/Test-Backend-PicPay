<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginUserFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required | email',
            'password' => 'required'
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:2', 'max:60'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', 'regex:/^[\+]{0,1}380([0-9]{9})$/', 'unique:users,phone'],
            'positions_id' => ['required', 'int', 'min:1'],
            'photo' => ['required', 'image', 'dimensions:min_width=70,min_height=70', "mimes:jpeg,jpg", "max:5242880"],
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}

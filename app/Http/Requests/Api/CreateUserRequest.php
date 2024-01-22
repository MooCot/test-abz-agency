<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends ApiFormRequest
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
            'name' => ['required', 'string'],
            'email' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'name' => ['required', 'string'],
            'positions_id' => ['required', 'numeric'],
            // 'photo' => ['required', 'image', "mimes:jpeg,jpg", "max:5242880"],
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}

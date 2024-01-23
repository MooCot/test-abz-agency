<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

abstract class ApiFormRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        // все ошибки валидации
        $response = new JsonResponse([
            "success" => false,
            "message" => "Validation failed",
            "fails" => $validator->errors()], 422);

        // Дополнительная проверка для email
        if ($validator->errors()->has('email')) {
            $response->setStatusCode(409);
        }
        throw new ValidationException($validator, $response);
    }

    abstract public function authorize();

    abstract public function rules();
}

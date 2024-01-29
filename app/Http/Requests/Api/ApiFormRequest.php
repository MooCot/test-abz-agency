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
        $response = new JsonResponse([
            "success" => false,
            "message" => "Validation failed",
            "fails" => $validator->errors()], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);

        if ($validator->errors()->has('email')) {
            $response->setStatusCode(JsonResponse::HTTP_CONFLICT);
        }
        throw new ValidationException($validator, $response);
    }

    abstract public function authorize();

    abstract public function rules();
}

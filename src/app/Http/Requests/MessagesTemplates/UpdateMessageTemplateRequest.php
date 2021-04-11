<?php

namespace App\Http\Requests\MessagesTemplates;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class UpdateMessageTemplateRequest extends FormRequest
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
            'language' => 'required|string',
            'name' => 'required|string',
            'alias' => 'required|string',
            'content' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'language' => 'Вкажіть мову',
            'name' => 'Вкажіть назву',
            'alias' => 'Вкажіть alias',
            'content' => 'Вкажіть текст',
        ];
    }

    /**
     * Format errors
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}

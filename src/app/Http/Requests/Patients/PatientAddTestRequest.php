<?php

namespace App\Http\Requests\Patients;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class PatientAddTestRequest extends FormRequest
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
            'patient_id' => 'required|integer',
            'test_id' => 'required|integer',
            'file' => 'nullable|file|max:50000',
            'result' => 'nullable|string',
            'reference_values' => 'nullable|string'
        ];
    }

    public function messages()
    {
        return [
            'patient_id' => 'Вкажіть піцієнта',
            'test_id' => 'Вкажіть тест',
            'file' => 'Невірний файл',
            'result' => 'Вкажіть результат',
            'reference_values' => 'Вкажіть значення',
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

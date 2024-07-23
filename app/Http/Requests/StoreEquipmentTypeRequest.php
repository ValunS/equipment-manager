<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEquipmentTypeRequest extends FormRequest
{
    /**
     * Правила валидации для запроса.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'mask' => [
                'required',
                'string',
                'max:255',
                'regex:/^([ANaXZ\-@_]+)$/', // Проверка допустимых символов
            ],
        ];
    }

    /**
     * Сообщения об ошибках валидации.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'mask.regex' => 'Маска содержит недопустимые символы. Разрешены: N, A, a, X, Z (-, _, @, пробел).',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEquipmentRequest extends FormRequest
{
    /**
     * Определите, авторизован ли пользователь для выполнения этого запроса.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Замените на свою логику авторизации
    }

    /**
     * Правила валидации для запроса.
     *
     * @return array
     */
    public function rules()
    {
        return [
            '*.equipment_type_id' => 'required|exists:equipment_types,id',
            '*.serial_number' => 'required|string|max:255|distinct|unique:equipment,serial_number,NULL,id,equipment_type_id,' . $this->input('*.equipment_type_id'),
            '*.desc' => 'nullable|string',
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
            '*.equipment_type_id.required' => 'Поле "Тип оборудования" обязательно для заполнения.',
            '*.equipment_type_id.exists' => 'Выбран неверный тип оборудования.',
            '*.serial_number.required' => 'Поле "Серийный номер" обязательно для заполнения.',
            '*.serial_number.string' => 'Поле "Серийный номер" должно быть строкой.',
            '*.serial_number.max' => 'Поле "Серийный номер" не должно превышать 255 символов.',
            '*.serial_number.distinct' => 'Поле "Серийный номер" должно содержать уникальные значения.',
            '*.serial_number.unique' => 'Оборудование с таким серийным номером уже существует для данного типа.',
        ];
    }
}
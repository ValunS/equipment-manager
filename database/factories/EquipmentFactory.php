<?php

namespace Database\Factories;

use App\Models\EquipmentType;
use Illuminate\Database\Eloquent\Factories\Factory;

// Импортируйте модель EquipmentType

class EquipmentFactory extends Factory
{
    /**
     * Определение состояния модели по умолчанию.
     *
     * @return array
     */
    public function definition()
    {
        $equipmentType = EquipmentType::factory()->create(); // Создает тип оборудования

        return [
            'equipment_type_id' => $equipmentType->id, // Связывает с созданным типом
            'serial_number' => $this->faker->unique()->regexify($equipmentType->mask), // Генерирует серийник по маске типа
            'desc' => $this->faker->sentence,
        ];
    }
}
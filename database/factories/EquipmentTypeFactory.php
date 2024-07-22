<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EquipmentTypeFactory extends Factory
{
    /**
     * Определение состояния модели по умолчанию.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->words(2, true), // генерирует уникальное имя из 2 слов
            'mask' => $this->faker->regexify('[A-Z]{2}[0-9]{4}[A-Z]{3}'), // генерирует маску по шаблону
        ];
    }
}
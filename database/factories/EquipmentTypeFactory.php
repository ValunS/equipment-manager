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
            'mask' => $this->generateMask(), // генерирует маску по шаблону
        ];
    }

    /**
     * Генерация маски.
     *
     * @return string
     */
    private function generateMask()
    {
        $maskParts = "";
        for ($i = 0; $i < 10; $i++) { // Генерируем 10 символов маски
            $maskParts .= $this->faker->randomElement(['N', 'A', 'a', 'X', 'Z']);
        }

        return $maskParts;
    }
}

<?php

namespace Database\Factories;

use App\Models\EquipmentType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

// Для генерации случайных строк

class EquipmentFactory extends Factory
{
    /**
     * Определение состояния модели по умолчанию.
     *
     * @return array
     */
    public function definition()
    {
        $equipmentType = EquipmentType::inRandomOrder()->first(); // Случайный тип оборудования

        return [
            'equipment_type_id' => $equipmentType->id,
            'serial_number' => $this->generateSerialNumber($equipmentType->mask),
            'desc' => $this->faker->sentence,
        ];
    }

    /**
     * Генерация серийного номера по маске.
     *
     * @param string $mask Маска серийного номера.
     * @return string Сгенерированный серийный номер.
     */
    private function generateSerialNumber(string $mask): string
    {
        $serialNumber = '';
        $length = strlen($mask);

        for ($i = 0; $i < $length; $i++) {
            switch ($mask[$i]) {
                case 'N':
                    $serialNumber .= rand(0, 9);
                    break;
                case 'A':
                    $serialNumber .= $this->faker->randomElement(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z']);
                    break;
                case 'a':
                    $serialNumber .= $this->faker->randomElement(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z']);
                    break;
                case 'X':
                    $serialNumber .= $this->faker->randomElement(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9']);
                    break;
                case 'Z':
                    $serialNumber .= $this->faker->randomElement(['-', '_', '@']);
                    break;
                default:
                    $serialNumber .= " ErrOnStr(" . $i . ") in mask '" . $mask . "' ";
                    break;
            }
        }

        return $serialNumber;
    }
}

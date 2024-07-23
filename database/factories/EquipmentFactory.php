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
                    $serialNumber .= Str::random(1, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ');
                    break;
                case 'a':
                    $serialNumber .= Str::random(1, 'abcdefghijklmnopqrstuvwxyz');
                    break;
                case 'X':
                    $serialNumber .= Str::random(1, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');
                    break;
                case 'Z':
                    $serialNumber .= Str::random(1, '-_@');
                    break;
                default:
                    $serialNumber .= $mask[$i];
                    break;
            }
        }

        return $serialNumber;
    }
}

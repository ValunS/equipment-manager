<?php

namespace App\Services;

use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use App\Models\EquipmentType;
use Exception;
use Illuminate\Support\Facades\DB;

class EquipmentService
{
    /**
     * Создание нового оборудования.
     *
     * @param array $data Данные оборудования.
     * @return array Массив с результатами создания оборудования.
     */
    public function createEquipment(array $data): array
    {
        $results = ['errors' => [], 'success' => []];

        foreach ($data as $key => $item) {
            try {
                DB::beginTransaction();

                $equipmentType = EquipmentType::findOrFail($item['equipment_type_id']);

                // Проверка соответствия серийного номера маске
                if (!$this->validateSerialNumber($equipmentType->mask, $item['serial_number'])) {
                    throw new Exception("Серийный номер не соответствует маске типа оборудования.");
                }

                // Проверка уникальности серийного номера в связке с типом оборудования
                if ($this->isSerialNumberExists($item['equipment_type_id'], $item['serial_number'])) {
                    throw new Exception("Оборудование с таким серийным номером уже существует для данного типа.");
                }

                $equipment = Equipment::create([
                    'equipment_type_id' => $item['equipment_type_id'],
                    'serial_number' => $item['serial_number'],
                    'desc' => $item['desc'],
                ]);

                DB::commit();
                $results['success'][$key] = new EquipmentResource($equipment->load('type'));
            } catch (Exception $e) {
                DB::rollBack();
                $results['errors'][$key] = ['message' => $e->getMessage()];
            }
        }

        return $results;
    }

    /**
     * Проверка валидности серийного номера по маске.
     *
     * @param string $mask Маска серийного номера.
     * @param string $serialNumber Серийный номер.
     * @return bool True, если серийный номер валиден, иначе false.
     */
    private function validateSerialNumber(string $mask, string $serialNumber): bool
    {
        return preg_match('/^' . strtr($mask, [
            'N' => '[0-9]',
            'A' => '[A-Z]',
            'a' => '[a-z]',
            'X' => '[A-Z0-9]',
            'Z' => '[-_@]',
        ]) . '$/', $serialNumber);
    }

    /**
     * Проверка существования серийного номера для данного типа оборудования.
     *
     * @param int $equipmentTypeId ID типа оборудования.
     * @param string $serialNumber Серийный номер.
     * @return bool True, если серийный номер существует, иначе false.
     */
    private function isSerialNumberExists(int $equipmentTypeId, string $serialNumber): bool
    {
        return Equipment::where('equipment_type_id', $equipmentTypeId)
            ->where('serial_number', $serialNumber)
            ->exists();
    }
}
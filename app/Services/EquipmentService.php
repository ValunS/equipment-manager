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

        DB::beginTransaction();
        foreach ($data as $key => $equipment) {
            try {

                $equipmentType = EquipmentType::findOrFail($equipment['equipment_type_id']);

                // Проверка соответствия серийного номера маске
                if (!$this->validateSerialNumber($equipmentType->mask, $equipment['serial_number'])) {
                    throw new Exception("Серийный номер не соответствует маске типа оборудования.");
                }

                // Проверка уникальности серийного номера в связке с типом оборудования
                if ($this->isSerialNumberExists($equipment['equipment_type_id'], $equipment['serial_number'])) {
                    throw new Exception("Оборудование с таким серийным номером уже существует для данного типа.");
                }

                $equipment = Equipment::create([
                    'equipment_type_id' => $equipment['equipment_type_id'],
                    'serial_number' => $equipment['serial_number'],
                    'desc' => $equipment['desc'],
                ]);

                $results['success'][$key] = new EquipmentResource($equipment->load('type'));
            } catch (Exception $e) {
                $results['errors'][$key] = ['message' => $e->getMessage()];
            }
        }
        if (empty($results['errors'])) {
            DB::rollBack();
        }

        DB::commit();
        return $results;
    }

    /**
     * Редактирование оборудования.
     *
     * @param Equipment $equipment Модель оборудования для обновления
     * @param array $data Данные для обновления.
     * @return EquipmentResource Обновленная модель оборудования
     * @throws Exception
     */
    public function updateEquipment(Equipment $equipment, array $data): EquipmentResource
    {
        try {
            DB::beginTransaction();

            $equipmentType = EquipmentType::findOrFail($data['equipment_type_id']);

            // Проверка соответствия серийного номера маске
            if (!$this->validateSerialNumber($equipmentType->mask, $data['serial_number'])) {
                throw new Exception("Серийный номер не соответствует маске типа оборудования.");
            }

            $equipment->update([
                'equipment_type_id' => $data['equipment_type_id'],
                'serial_number' => $data['serial_number'],
                'desc' => $data['desc'],
            ]);

            DB::commit();
            return new EquipmentResource($equipment->fresh()->load('type'));
        } catch (Exception $e) {
            DB::rollBack();
            throw $e; // Перебрасываем исключение для обработки в контроллере
        }
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
        $length = strlen($mask);
        $mask_array_rules = [
            'N_mask' => ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
            'A_mask' => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'],
            'a_mask' => ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'],
            'X_mask' => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
            'Z_mask' => ['-', '_', '@']];

        for ($i = 0; $i < $length; $i++) {
            $mask_char = $mask[$i];
            if (!in_array($serialNumber[$i], $mask_array_rules[$mask_char . '_mask'])) {
                return false;
            };
        }
        return true;
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

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEquipmentRequest;
use App\Http\Requests\UpdateEquipmentRequest;
use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use App\Services\EquipmentService;
use Illuminate\Http\Request;

// Для транзакций

class EquipmentController extends Controller
{
    /**
     * @var EquipmentService
     */
    protected $equipmentService;

    /**
     * EquipmentController constructor.
     *
     * @param EquipmentService $equipmentService
     */
    public function __construct(EquipmentService $equipmentService)
    {
        $this->equipmentService = $equipmentService;
    }

    /**
     * Вывод списка оборудования.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $equipment = Equipment::with('type'); // Загрузка отношения "тип оборудования"

        // Поиск по полям
        if ($request->has('q')) {
            $searchTerm = $request->input('q');
            $equipment->where(function ($query) use ($searchTerm) {
                $query->where('serial_number', 'like', "%$searchTerm%")
                    ->orWhere('desc', 'like', "%$searchTerm%")
                    ->orWhereHas('type', function ($query) use ($searchTerm) { // Поиск по имени типа
                        $query->where('name', 'like', "%$searchTerm%");
                    });
            });
        }

        return EquipmentResource::collection($equipment->paginate(10));
    }

    /**
     * Вывод информации об оборудовании.
     *
     * @param Equipment $equipment
     * @return EquipmentResource
     */
    public function show(Equipment $equipment)
    {
        return new EquipmentResource($equipment->load('type')); // Загрузка отношения "тип оборудования"
    }

    /**
     * Создание новой записи оборудования.
     *
     * @param StoreEquipmentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreEquipmentRequest $request)
    {
        $data = $request->validated();

        $results = $this->equipmentService->createEquipment($data);

        return response()->json($results);
    }

    /**
     * Обновление информации об оборудовании.
     *
     * @param UpdateEquipmentRequest $request
     * @param Equipment $equipment
     * @return EquipmentResource
     */
    public function update(UpdateEquipmentRequest $request, Equipment $equipment)
    {
        $data = $request->validated();
        $equipment->update($data);

        return new EquipmentResource($equipment->refresh()->load('type')); // Обновление и загрузка отношения
    }

    /**
     * Удаление оборудования.
     *
     * @param Equipment $equipment
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Equipment $equipment)
    {
        $equipment->delete();

        return response()->json(['message' => 'Оборудование успешно удалено.'], 204); // 204 No Content
    }
}

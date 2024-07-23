<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEquipmentTypeRequest;
use App\Http\Requests\UpdateEquipmentTypeRequest;
use App\Http\Resources\EquipmentTypeResource;
use App\Models\EquipmentType;
use Illuminate\Http\Request;

class EquipmentTypeController extends Controller
{
    /**
     * Отображение списка типов оборудования.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $equipmentTypes = EquipmentType::query();

        if ($request->has('q')) {
            $searchTerm = $request->input('q');
            $equipmentTypes->where('name', 'like', "%$searchTerm%");
        }

        return EquipmentTypeResource::collection($equipmentTypes->paginate(10));
    }

    /**
     * Отображение информации о типе оборудования по ID.
     *
     * @param EquipmentType $equipmentType
     * @return EquipmentTypeResource
     */
    public function show(EquipmentType $equipmentType)
    {
        return new EquipmentTypeResource($equipmentType);
    }

    /**
     * Создание нового типа оборудования.
     *
     * @param  \App\Http\Requests\StoreEquipmentTypeRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreEquipmentTypeRequest $request)
    {
        $data = $request->validated();
        $equipmentType = EquipmentType::create($data);

        return response()->json(new EquipmentTypeResource($equipmentType), 201);
    }

    /**
     * Обновление типа оборудования.
     *
     * @param  \App\Http\Requests\UpdateEquipmentTypeRequest  $request
     * @param  \App\Models\EquipmentType  $equipmentType
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateEquipmentTypeRequest $request, EquipmentType $equipmentType)
    {
        $data = $request->validated();
        $equipmentType->update($data);

        return response()->json(new EquipmentTypeResource($equipmentType));
    }

    /**
     * Удаление типа оборудования.
     *
     * @param  \App\Models\EquipmentType  $equipmentType
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(EquipmentType $equipmentType)
    {
        $equipmentType->delete();

        return response()->json(['message' => 'Тип оборудования успешно удален.'], 204);
    }
}

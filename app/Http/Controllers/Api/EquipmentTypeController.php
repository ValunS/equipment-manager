<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
}

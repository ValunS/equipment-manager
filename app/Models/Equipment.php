<?php

namespace App\Models;

use App\Models\EquipmentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Импортируйте модель EquipmentType

class Equipment extends Model
{
    use HasFactory;

    /**
     * Атрибуты, доступные для массового заполнения.
     *
     * @var array
     */
    protected $fillable = [
        'equipment_type_id',
        'serial_number',
        'desc',
    ];

    /**
     * Получить тип оборудования, к которому относится это оборудование.
     */
    public function type()
    {
        return $this->belongsTo(EquipmentType::class, 'equipment_type_id');
    }
}
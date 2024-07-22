<?php

namespace App\Models;

use App\Models\Equipment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Импортируйте модель Equipment

class EquipmentType extends Model
{
    use HasFactory;

    /**
     * Атрибуты, доступные для массового заполнения.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'mask',
    ];

    /**
     * Получить все оборудование, связанное с этим типом.
     */
    public function equipment()
    {
        return $this->hasMany(Equipment::class);
    }
}
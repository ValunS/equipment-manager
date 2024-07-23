<?php

namespace App\Models;

use App\Models\Equipment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentType extends Model
{
    use HasFactory, SoftDeletes;

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

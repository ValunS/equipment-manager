<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('equipment_type_id');
            $table->string('serial_number');
            $table->text('desc')->nullable();
            $table->timestamps();

            // Внешний ключ для связи с таблицей equipment_types
            $table->foreign('equipment_type_id')->references('id')->on('equipment_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipment');
    }
}
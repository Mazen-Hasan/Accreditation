<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreDefinedFieldElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_defined_field_elements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('value_ar');
            $table->string('value_en');
            $table->string('value_id');
            $table->string('order');
            $table->bigInteger('predefined_field_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pre_defined_field_elements');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplateFieldElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_field_elements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('value_ar');
            $table->string('value_en');
            $table->string('value_id');
            $table->string('order');
            $table->bigInteger('template_field_id');
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
        Schema::dropIfExists('template_field_elements');
    }
}

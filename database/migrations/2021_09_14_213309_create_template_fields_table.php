<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplateFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_fields', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('template_id');
            $table->string('label_ar');
            $table->string('label_en');
            $table->boolean('mandatory')->default(false);
            $table->integer('min_char')->default(1);
            $table->integer('max_char')->default(100);
            $table->integer('field_order');
            $table->bigInteger('field_type_id');
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
        Schema::dropIfExists('template_fields');
    }
}

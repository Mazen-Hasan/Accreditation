<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplateBadgeFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_badge_fields', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('badge_id');
            $table->bigInteger('template_field_id');
            $table->string('template_field_name');
            $table->string('position_x');
            $table->string('position_y');
            $table->string('size');
            $table->string('text_color');
            $table->string('font')->nullable();
            $table->string('bg_color')->nullable();
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
        Schema::dropIfExists('template_badge_fields');
    }
}

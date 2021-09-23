<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplateBadgesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_badges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('template_id');
            $table->String('width');
            $table->String('high');
            $table->String('bg_color');
            $table->String('bg_image')->nullable();
            $table->string('creator')->nullable();
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
        Schema::dropIfExists('template_badges');
    }
}

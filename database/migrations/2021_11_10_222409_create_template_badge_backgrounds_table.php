<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplateBadgeBackgroundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_badge_backgrounds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('badge_id');
            $table->unsignedBigInteger('accreditation_category_id');
            $table->string('bg_image');
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
        Schema::dropIfExists('template_badge_backgrounds');
    }
}

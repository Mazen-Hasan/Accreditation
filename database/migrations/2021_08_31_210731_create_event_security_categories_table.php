<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventSecurityCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_security_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('event_id');
            $table->string('security_category_id');
            $table->string('order')->nullable();
            $table->string('creator')->nullable();
            $table->timestamp('creation_date')->useCurrent();
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
        Schema::dropIfExists('event_security_categories');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactTitlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_titles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('contact_id');
            $table->string('title_id');
            $table->string('status');
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
        Schema::dropIfExists('contact_titles');
    }
}

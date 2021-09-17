<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFocalPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('focal_points', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('account_id');
            $table->string('name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('email');
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('password');
            $table->string('telephone');
            $table->string('mobile');
            $table->bigInteger('status');
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
        Schema::dropIfExists('focal_points');
    }
}

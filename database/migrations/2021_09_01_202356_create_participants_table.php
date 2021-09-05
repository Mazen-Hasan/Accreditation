<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('first_name_ar');
            $table->string('last_name');
            $table->string('last_name_ar');
            $table->string('nationality');
            $table->string('email');
            $table->string('mobile');
            $table->string('position');
            $table->string('religion');
            $table->string('address');
            $table->string('birthdate');
            $table->string('gender');
            $table->string('company');
            $table->string('subCompany');
            $table->string('passport_number');
            $table->string('id_number');
            $table->string('class');
            $table->string('accreditation_category');
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
        Schema::dropIfExists('participants');
    }
}

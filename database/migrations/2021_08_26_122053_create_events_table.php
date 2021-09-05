<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('event_admin');
            $table->string('location');
            $table->string('size');
            $table->string('organizer');
            $table->string('owner');
            $table->string('event_type');
            $table->string('period');
            $table->string('accreditation_period');
            $table->string('status');
            $table->string('approval_option');
            $table->string('security_officer');
            $table->string('event_form');
            $table->timestamp('creation_date')->useCurrent();
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
        Schema::dropIfExists('events');
    }
}

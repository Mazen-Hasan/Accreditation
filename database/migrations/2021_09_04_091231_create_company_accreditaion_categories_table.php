<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyAccreditaionCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_accreditaion_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('status');
            $table->unsignedBigInteger('event_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('subcompany_id')->nullable();
            $table->unsignedBigInteger('accredit_cat_id')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            $table->foreign('subcompany_id')->references('id')->on('companies')->onDelete('set null');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('set null');
            $table->foreign('accredit_cat_id')->references('id')->on('accreditation_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_accreditaion_categories');
    }
}

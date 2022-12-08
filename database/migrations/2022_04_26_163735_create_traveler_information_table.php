<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('traveler_informations', function (Blueprint $table) {
            $table->id();
            $table->integer('day_of_staying');
            $table->boolean('particular_staying')->nullable();
            $table->unsignedBigInteger('hotel_id')->nullable();
            $table->unsignedBigInteger('sector_id')->nullable();
            $table->text('street_address')->nullable();
            $table->boolean('has_common_address')->default(false)->nullable();
            $table->boolean('is_task_return')->default(false)->nullable();
            $table->string('document_number')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('air_ticket_number')->nullable();
            $table->foreignId('traveler_id')->constrained('travelers')->onDelete('cascade');
            $table->foreignId('eticket_id')->constrained('etickets')->onDelete('cascade');
            $table->timestamps();

            $table->foreign('hotel_id')->references('id')->on('hotels');
            $table->foreign('sector_id')->references('id')->on('sectors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('traveler_informations');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEticketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {//OriginPort
        Schema::create('etickets', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_arrival');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('application_id')->nullable()->comment('eTicket Id identification');
            $table->unsignedBigInteger('motive_id');
            $table->boolean('stop_over_in_countries')->nullable()->default(false); //Escala en otros paises
            $table->unsignedBigInteger('airline_id');
            $table->unsignedBigInteger('origin_port_id')->nullable();
            $table->string('origin_flight_number')->nullable();
            $table->date('origin_flight_date')->nullable();
            $table->unsignedBigInteger('embarkation_port_id');
            $table->unsignedBigInteger('disembarkation_port_id');
            $table->date('flight_date');
            $table->string('flight_number');
            $table->string('flight_confirmation_number')->nullable()->comment('Localizator');
            $table->string('qr_code')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('motive_id')->references('id')->on('motives')->onDelete('cascade');
            $table->foreign('airline_id')->references('id')->on('airlines')->onDelete('cascade');
            $table->foreign('origin_port_id')->references('id')->on('ports')->onDelete('cascade');
            $table->foreign('embarkation_port_id')->references('id')->on('ports')->onDelete('cascade');
            $table->foreign('disembarkation_port_id')->references('id')->on('ports')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('etickets');
    }
}

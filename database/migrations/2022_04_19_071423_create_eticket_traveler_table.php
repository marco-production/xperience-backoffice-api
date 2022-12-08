<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEticketTravelerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eticket_traveler', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eticket_id')->constrained('etickets')->onDelete('cascade');
            $table->foreignId('traveler_id')->constrained('travelers')->onDelete('cascade');
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
        Schema::dropIfExists('eticket_traveler');
    }
}

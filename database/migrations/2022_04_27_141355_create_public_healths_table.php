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
        Schema::create('public_healths', function (Blueprint $table) {
            $table->id();
            $table->date('symptoms_date')->nullable();
            $table->string('phone_number')->nullable();
            $table->text('specification')->nullable();
            $table->foreignId('traveler_id')->constrained('travelers')->onDelete('cascade');
            $table->foreignId('eticket_id')->constrained('etickets')->onDelete('cascade');
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
        Schema::dropIfExists('public_healths');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravelersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travelers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('lastname');
            $table->char('gender', 1);
            $table->date('birthday');
            $table->foreignId('birth_place_id')->constrained('countries');
            $table->foreignId('nationality_id')->constrained('countries');
            $table->string('passport_number');
            $table->string('document_number')->nullable()->comment('Cedula de identidad');
            $table->string('email')->nullable(); 
            $table->unsignedBigInteger('relationship_id')->nullable();
            $table->foreignId('occupation_id')->constrained('occupations');
            $table->foreignId('residential_country_id')->constrained('countries');
            $table->text('permanent_address');
            $table->foreignId('city_id')->constrained('cities');
            $table->integer('zip_code')->nullable();
            $table->string('residence_number')->nullable(); // ARE YOU A FOREIGNER RESIDENT IN THE DOMINICAN REPUBLIC?
            $table->text('street_address')->nullable();
            $table->boolean('principal')->default(false); // Viajero principal
            $table->foreignId('civil_status_id')->constrained('civil_statuses'); // Maritial Status id
            $table->unsignedBigInteger('sector_id')->nullable(); //Lo equivalente a GeoCode en el API.
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);

            $table->foreign('sector_id')->references('id')->on('sectors')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('relationship_id')->references('id')->on('relationships')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('travelers');
    }
}

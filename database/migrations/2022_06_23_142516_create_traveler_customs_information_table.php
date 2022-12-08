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
        Schema::create('traveler_customs_information', function (Blueprint $table) {
            $table->id();
            $table->boolean('exceeds_money_limit')->default(false)->nullable();
            $table->boolean('animals_or_food')->default(false)->nullable();
            $table->boolean('merch_with_tax_value')->default(false)->nullable();
            $table->boolean('is_values_owner')->default(false)->nullable();

            $table->string('sender_name')->nullable();
            $table->string('sender_lastname')->nullable();
            $table->string('receiver_name')->nullable();
            $table->string('receiver_lastname')->nullable();
            $table->string('receiver_relationship')->nullable();
            $table->string('declared_origin_value')->nullable()->comment('Origen de los valores');
            $table->string('worth_destiny')->nullable()->comment('Uso o destino del dinero o valores');
            
            $table->double('amount')->nullable();
            $table->unsignedBigInteger('currency_type_id')->nullable();

            $table->double('value_of_merchandise')->nullable();
            $table->unsignedBigInteger('merchandise_type_id')->nullable();
            $table->text('declared_merch')->nullable();

            $table->foreignId('traveler_id')->constrained('travelers')->onDelete('cascade');
            $table->foreignId('eticket_id')->constrained('etickets')->onDelete('cascade');
            
            $table->foreign('currency_type_id')->references('id')->on('currencies');
            $table->foreign('merchandise_type_id')->references('id')->on('currencies');
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
        Schema::dropIfExists('traveler_customs_information');
    }
};

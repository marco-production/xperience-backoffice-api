<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ports', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->unsignedBigInteger('transportation_id');
            $table->string('place');
            $table->string('name');
            $table->boolean('dominican_port');
            $table->boolean('enabled')->default(true);
            $table->foreign('transportation_id')->references('id')->on('transportations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ports');
    }
}

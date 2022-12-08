<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('reason_id')->nullable();
            $table->datetime('deleted_at');

            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->foreign('reason_id')->references('id')->on('user_deletion_reasons')->onDelete('cascade');
        });

        DB::unprepared('
            CREATE TRIGGER tr_user_deleted AFTER DELETE  
            ON users FOR EACH ROW  
            BEGIN  
            
            INSERT INTO user_logs 
                (`user_id`, `country_id`, `deleted_at`) 
                VALUES 
                (OLD.id, OLD.country_id, NOW());
            END;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_logs');
        DB::unprepared('DROP TRIGGER tr_user_deleted');
    }
};

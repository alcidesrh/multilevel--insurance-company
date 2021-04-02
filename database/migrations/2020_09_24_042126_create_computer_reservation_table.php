<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComputerReservationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('computer_reservation', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('computer');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('day')->nullable()->default(null);
            $table->smallInteger('turn')->nullable()->default(null);
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('computer_reservation');
    }
}

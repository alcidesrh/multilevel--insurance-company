<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsuranceCarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurance_car', function (Blueprint $table) {
            $table->id();
            $table->string('vin')->nullable()->default(null);
            $table->string('license')->nullable()->default(null);
            $table->string('policy', 10)->nullable()->default(null);
            $table->string('actual_company')->nullable()->default(null);
            $table->string('members_coverage')->nullable()->default(null);
            $table->dateTime('expiration')->nullable()->default(null);
            $table->smallInteger('cant')->nullable()->default(null);
            $table->timestamps();
        });

        Schema::table('insurance_car', function (Blueprint $table) {            

            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('client_id')->nullable()->constrained('client')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('insurance_car');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsuranceHomeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurance_home', function (Blueprint $table) {
            $table->id();
            $table->string('co_insured')->nullable()->default(null);
            $table->string('owner', 5)->nullable()->default(null);
            $table->string('resident', 5)->nullable()->default(null);
            $table->string('use_property', 5)->nullable()->default(null);
            $table->string('policy', 5)->nullable()->default(null);
            $table->string('actual_company')->nullable()->default(null);
            $table->string('actual_coverage')->nullable()->default(null);
            $table->dateTime('expiration')->nullable()->default(null);
            $table->timestamps();
        });

        Schema::table('insurance_home', function (Blueprint $table) {            

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
        Schema::dropIfExists('insurance_home');
    }
}

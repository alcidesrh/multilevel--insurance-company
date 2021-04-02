<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSentFieldToInsurancesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('insurance_product', function (Blueprint $table) {            
            $table->boolean('sent')->nullable()->default(null);
        });
        Schema::table('insurance_car', function (Blueprint $table) {            
            $table->boolean('sent')->nullable()->default(null);
        });
        Schema::table('insurance_home', function (Blueprint $table) {            
            $table->boolean('sent')->nullable()->default(null);
        });
        Schema::table('insurance_business', function (Blueprint $table) {            
            $table->boolean('sent')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('insurances_tables', function (Blueprint $table) {
            //
        });
    }
}

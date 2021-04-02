<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('insurance_product', function (Blueprint $table) {
            $table->string('spouse_earn_type')->nullable()->default(null);
            
        });
        Schema::table('client', function (Blueprint $table) {            
            $table->string('state')->nullable()->default(null);
            $table->string('earn_type')->nullable()->default(null);
        });
        Schema::table('dependent', function (Blueprint $table) {
            $table->string('earn_type')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

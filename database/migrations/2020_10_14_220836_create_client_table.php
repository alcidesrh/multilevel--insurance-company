<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client', function (Blueprint $table) {
            $table->id();
            $table->string('name', 256)->nullable()->default(null);
            $table->string('last_name', 256)->nullable()->default(null);
            $table->string('phone', 64)->nullable()->default(null);
            $table->string('email', 64)->nullable()->default(null);
            $table->string('sex', 10)->nullable()->default(null);
            $table->dateTime('birthday')->nullable()->default(null);
            $table->string('ssn')->nullable()->default(null);
            $table->string('country')->nullable()->default(null);
            $table->string('address')->nullable()->default(null);
            $table->string('apt')->nullable()->default(null);
            $table->string('city')->nullable()->default(null);
            $table->string('zipcode')->nullable()->default(null);
            $table->string('status')->nullable()->default(null);
            $table->string('alien')->nullable()->default(null);
            $table->string('card')->nullable()->default(null);
            $table->string('category')->nullable()->default(null);
            $table->dateTime('expiration')->nullable()->default(null);
            $table->string('persons_taxes')->nullable()->default(null);
            $table->string('income')->nullable()->default(null);
            $table->string('employer')->nullable()->default(null);
            $table->string('employer_phone')->nullable()->default(null);
            $table->string('status_marital')->nullable()->default(null);
            $table->string('dependents')->nullable()->default(null);
            $table->string('payment_information')->nullable()->default(null);
            $table->string('ocupation')->nullable()->default(null);
            $table->string('parol_number')->nullable()->default(null);
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
        Schema::dropIfExists('client');
    }
}

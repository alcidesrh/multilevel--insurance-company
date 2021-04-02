<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateObamaCareTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('insurance_product', function (Blueprint $table) {
            
            $table->string('plan_id')->nullable()->default(null);
            $table->string('spouse_name')->nullable()->default(null);
            $table->string('spouse_last_name')->nullable()->default(null);
            $table->string('spouse_phone')->nullable()->default(null);
            $table->dateTime('spouse_birthday')->nullable()->default(null);
            $table->string('spouse_social_number')->nullable()->default(null);
            $table->string('spouse_anual_income')->nullable()->default(null);
            $table->string('spouse_employer')->nullable()->default(null);
            $table->string('spouse_status')->nullable()->default(null);
            $table->string('spouse_apply')->nullable()->default(null);
            $table->dateTime('spouse_expiration')->nullable()->default(null);
            $table->string('spouse_category')->nullable()->default(null);
            $table->string('spouse_card')->nullable()->default(null);
            $table->string('spouse_alien')->nullable()->default(null);
            $table->string('spouse_citizenship_number')->nullable()->default(null);
            $table->string('spouse_parol_number')->nullable()->default(null);
                      
        });

        Schema::create('dependent', function (Blueprint $table) {
            $table->id();
            $table->string('dependent_name')->nullable()->default(null);
            $table->string('dependent_last_name')->nullable()->default(null);
            $table->string('dependent_gender')->nullable()->default(null);
            $table->dateTime('dependent_birthday')->nullable()->default(null);
            $table->string('dependent_social_number')->nullable()->default(null);
            $table->string('dependent_relation')->nullable()->default(null);
            $table->string('dependent_status')->nullable()->default(null);
            $table->string('dependent_apply')->nullable()->default(null);
            $table->dateTime('dependent_expiration')->nullable()->default(null);
            $table->string('dependent_category')->nullable()->default(null);
            $table->string('dependent_card')->nullable()->default(null);
            $table->string('dependent_alien')->nullable()->default(null);
            $table->string('dependent_citizenship_number')->nullable()->default(null);
            $table->string('dependent_parol_number')->nullable()->default(null);  
            $table->timestamps();
        });

        Schema::table('dependent', function (Blueprint $table) {
            $table->foreignId('client_id')->nullable()->constrained('client')->onDelete('cascade');
        });

        Schema::table('client', function (Blueprint $table) {
            
            $table->string('citizenship_number')->nullable()->default(null);

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

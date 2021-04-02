<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsuranceBusinessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurance_business', function (Blueprint $table) {
            $table->id();
            $table->string('tax_id')->nullable()->default(null);
            $table->string('annual_gross')->nullable()->default(null);
            $table->string('annual_visa_mc_sales')->nullable()->default(null);
            $table->string('ticket')->nullable()->default(null);
            $table->string('type')->nullable()->default(null);
            $table->dateTime('date')->nullable()->default(null);
            $table->string('product_sold')->nullable()->default(null);
            $table->string('employees')->nullable()->default(null);
            $table->string('website')->nullable()->default(null);
            $table->string('owner_name')->nullable()->default(null);
            $table->string('owner_phone')->nullable()->default(null);
            $table->string('owner_social_security')->nullable()->default(null);
            $table->string('owner_address')->nullable()->default(null);
            $table->dateTime('owner_birthday')->nullable()->default(null);
            $table->string('owner_license')->nullable()->default(null);
            $table->string('bank_name')->nullable()->default(null);
            $table->string('bank_routing')->nullable()->default(null);
            $table->string('bank_account')->nullable()->default(null);
            $table->string('bank_pos')->nullable()->default(null);
            $table->string('business_name')->nullable()->default(null);
            $table->string('business_name_dba')->nullable()->default(null);
            $table->timestamps();
        });

        Schema::table('insurance_business', function (Blueprint $table) {            

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
        Schema::dropIfExists('insurance_business');
    }
}

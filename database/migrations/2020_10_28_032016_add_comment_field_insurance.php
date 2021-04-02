<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommentFieldInsurance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('insurance_product', function (Blueprint $table) {
            
            $table->text('comment')->nullable()->default(null);
            $table->string('company', 50)->nullable()->default(null);
            $table->string('insurance', 20)->nullable()->default(null);
            $table->string('card_type')->nullable()->default(null);
            $table->string('card_name')->nullable()->default(null);
            $table->string('card_number')->nullable()->default(null);
            $table->string('expiration_month')->nullable()->default(null);
            $table->string('expiration_year')->nullable()->default(null);
            $table->string('ccv')->nullable()->default(null);
            $table->string('bank_name')->nullable()->default(null);
            $table->string('account_name')->nullable()->default(null);
            $table->string('route_number')->nullable()->default(null);
            $table->string('account_number')->nullable()->default(null);
        });

        Schema::table('insurance_car', function (Blueprint $table) {
            
            $table->text('comment')->nullable()->default(null);
        });

        Schema::table('insurance_home', function (Blueprint $table) {
            
            $table->text('comment')->nullable()->default(null);
        });

        Schema::table('insurance_business', function (Blueprint $table) {
            
            $table->text('comment')->nullable()->default(null);
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

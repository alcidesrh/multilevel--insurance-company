<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsuranceProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurance_product', function (Blueprint $table) {
            $table->id();
            $table->string('plan')->nullable()->default(null);
            $table->timestamp('date')->nullable()->default(null);
            $table->string('amount')->nullable()->default(null);
            $table->string('members')->nullable()->default(null);
            $table->string('income')->nullable()->default(null);
            $table->string('apply', 10)->nullable()->default(false);         
            $table->timestamps();
        });

        Schema::table('insurance_product', function (Blueprint $table) {            

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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('insurance_product');
    }
}

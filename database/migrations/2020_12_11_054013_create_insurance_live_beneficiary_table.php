<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsuranceLiveBeneficiaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurance_live_beneficiary', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->default(null);
            $table->string('dob')->nullable()->default(null);
            $table->string('relationship')->nullable()->default(null);
            $table->string('ssn')->nullable()->default(null);
            $table->string('percentage')->nullable()->default(null);
            $table->string('type')->nullable()->default('Beneficiary');
            $table->foreignId('insurance_live_id')->nullable()->constrained('insurance_live')->onDelete('cascade');
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
        Schema::dropIfExists('insurance_live_beneficiary');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsuranceLiveFamilyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurance_live_family', function (Blueprint $table) {
            $table->id();
            $table->string('age')->nullable()->default(null);
            $table->string('health')->nullable()->default(null);
            $table->string('age_at_death')->nullable()->default(null);
            $table->string('cause_of_death')->nullable()->default(null);
            $table->string('live')->nullable()->default(null);
            $table->string('member')->nullable()->default(null);
            $table->timestamps();
        });

        Schema::table('insurance_live_family', function (Blueprint $table) {            

            $table->foreignId('insurance_live_id')->nullable()->constrained('insurance_live')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('insurance_live_family');
    }
}

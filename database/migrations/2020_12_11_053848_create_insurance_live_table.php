<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsuranceLiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client', function (Blueprint $table) {
            $table->string('driver_license', 50)->nullable()->default(null);
            $table->string('agency')->nullable()->default(null);
            $table->boolean('citizen')->nullable()->default(null);
            $table->boolean('permanent_resident')->nullable()->default(null);
        });
        Schema::create('insurance_live', function (Blueprint $table) {
            $table->id();
            $table->string('birth_place')->nullable()->default(null);
            $table->string('net_worth')->nullable()->default(null);
            $table->string('household_income')->nullable()->default(null);
            $table->string('household_net_worth')->nullable()->default(null);
            $table->string('other_owner_name')->nullable()->default(null);
            $table->string('other_owner_address')->nullable()->default(null);
            $table->string('other_owner_sex')->nullable()->default(null);
            $table->string('other_ssn')->nullable()->default(null);
            $table->string('other_driver_license')->nullable()->default(null);
            $table->string('other_citizen')->nullable()->default(null);
            $table->string('five_last_year_convicted', 3)->nullable()->default(null);
            $table->string('convicted', 3)->nullable()->default(null);
            $table->string('bankruptcy', 3)->nullable()->default(null);
            $table->string('danger_sport', 3)->nullable()->default(null);
            $table->string('aviation', 3)->nullable()->default(null);
            $table->string('existing_life_insurance', 3)->nullable()->default(null);
            $table->string('existing_life_insurance_agency')->nullable()->default(null);
            $table->string('existing_life_insurance_amount')->nullable()->default(null);
            $table->string('existing_life_insurance_policy_number')->nullable()->default(null);
            $table->string('pending_life', 3)->nullable()->default(null);
            $table->string('replace', 3)->nullable()->default(null);
            $table->string('aids', 3)->nullable()->default(null);
            $table->string('limited_motion', 3)->nullable()->default(null);
            $table->string('mental_state', 3)->nullable()->default(null);
            $table->string('mechanical_devices', 3)->nullable()->default(null);
            $table->string('need_assistance', 3)->nullable()->default(null);
            $table->dateTime('other_owner_birthday')->nullable()->default(null);
            $table->string('physician_name')->nullable()->default(null);
            $table->string('physician_address')->nullable()->default(null);
            $table->string('physician_phone')->nullable()->default(null);
            $table->dateTime('physician_visit_date')->nullable()->default(null);
            $table->string('physician_visit_reason')->nullable()->default(null);
            $table->string('physician_visit_outcome')->nullable()->default(null);
            $table->string('height',10)->nullable()->default(null);
            $table->string('weight',10)->nullable()->default(null);
            $table->string('medication', 3)->nullable()->default(null);
            $table->text('medication_description')->nullable()->default(null);
            $table->string('tobacco_product', 3)->nullable()->default(null);
            $table->text('tobacco_product_description')->nullable()->default(null);
            $table->string('worked_full_time', 3)->nullable()->default(null);
            $table->string('disease_heart', 3)->nullable()->default(null);
            $table->string('disease_respiratory', 3)->nullable()->default(null);
            $table->string('disease_nervous', 3)->nullable()->default(null);
            $table->string('disease_liver', 3)->nullable()->default(null);
            $table->string('disease_bones_muscle', 3)->nullable()->default(null);
            $table->string('disease_urinary', 3)->nullable()->default(null);
            $table->string('disease_depression', 3)->nullable()->default(null);
            $table->string('disease_cancer', 3)->nullable()->default(null);
            $table->string('disease_diabetes', 3)->nullable()->default(null);
            $table->string('amputation', 3)->nullable()->default(null);
            $table->string('disorder', 3)->nullable()->default(null);
            $table->string('drug', 3)->nullable()->default(null);
            $table->string('consulted_physician', 3)->nullable()->default(null);
            $table->string('disease_family_history', 3)->nullable()->default(null);
            $table->string('premium_anual', 3)->nullable()->default('No');
            $table->string('premium_semi_anual', 3)->nullable()->default('No');
            $table->string('premium_quarterly', 3)->nullable()->default('No');
            $table->string('premium_monthly', 3)->nullable()->default('No');
            $table->string('premium_face_amount')->nullable()->default(null);
            $table->string('premium_name')->nullable()->default(null);
            $table->dateTime('premium_date')->nullable()->default(null);
            $table->string('premium_rate_calss')->nullable()->default(null);
            $table->string('bank_name')->nullable()->default(null);
            $table->string('bank_routing')->nullable()->default(null);
            $table->string('bank_account')->nullable()->default(null);
            $table->string('bank_phone')->nullable()->default(null);
            $table->text('comment')->nullable()->default(null);
            $table->timestamps();
        });

        Schema::table('insurance_live', function (Blueprint $table) {            

            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('client_id')->nullable()->constrained('client')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('insurance_live');
    }
}

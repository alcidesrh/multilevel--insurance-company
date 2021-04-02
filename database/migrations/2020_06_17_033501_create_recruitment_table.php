<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecruitmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruitment', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone', 64)->nullable()->default(null);
            $table->string('address', 256)->nullable()->default(null);
            $table->string('city', 256)->nullable()->default(null);
            $table->string('zip_code')->nullable()->default(null);
            $table->string('citizenship')->nullable()->default(null);
            $table->string('profession')->nullable()->default(null);
            $table->string('likejob')->nullable()->default(null);
            $table->string('dislikejob')->nullable()->default(null);
            $table->string('knowfrom')->nullable()->default(null);
            $table->string('desireincome')->nullable()->default(null);
            $table->string('attractivestatus')->nullable()->default(null);
            $table->string('bigachievement')->nullable()->default(null);
            $table->string('interested_us')->nullable()->default(null);
            $table->text('licenses')->nullable()->default(null);
            $table->string('monday')->nullable()->default(null);
            $table->string('tuesday')->nullable()->default(null);
            $table->string('wednesday')->nullable()->default(null);
            $table->string('thursday')->nullable()->default(null);
            $table->string('friday')->nullable()->default(null);
            $table->string('saturday')->nullable()->default(null);
            $table->dateTime('second_interview_date')->nullable()->default(null);
            $table->boolean('second_interview_assisted')->nullable()->default(null);
            $table->boolean('hired')->nullable()->default(null);
            $table->dateTime('first_training_date')->nullable()->default(null);
            $table->boolean('first_training_assisted')->nullable()->default(null);
            $table->boolean('oxygen')->nullable()->default(null);
            $table->dateTime('second_training_date')->nullable()->default(null);
            $table->boolean('second_training_assisted')->nullable()->default(null);
            $table->string('referred_one_name')->nullable()->default(null);
            $table->string('referred_one_email')->nullable()->default(null);
            $table->string('referred_one_phone')->nullable()->default(null);
            $table->string('referred_two_name')->nullable()->default(null);
            $table->string('referred_two_email')->nullable()->default(null);
            $table->string('referred_two_phone')->nullable()->default(null);
            $table->string('referred_three_name')->nullable()->default(null);
            $table->string('referred_three_email')->nullable()->default(null);
            $table->string('referred_three_phone')->nullable()->default(null);
            $table->boolean('interview_reminder')->nullable()->default(null);

            $table->foreignId('referred_by')->nullable()->constrained('users')->onDelete('set null');


            
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
        Schema::dropIfExists('recruitment');
    }
}

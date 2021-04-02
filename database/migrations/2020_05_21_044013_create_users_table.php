<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable()->default(null);            
            $table->string('password')->nullable()->default(null);
            $table->rememberToken();
            $table->string('address', 256)->nullable()->default(null);
            $table->timestamp('birthday')->nullable()->default(null);
            $table->string('last_name', 32)->nullable()->default(null);
            $table->string('phone', 64)->nullable()->default(null);
            $table->boolean('founder')->default(false);
            $table->decimal('commission_rate', 10, 2)->nullable()->default(null);
            $table->string('number_account', 20)->nullable();
            $table->string('title', 100)->nullable();
            $table->boolean('active')->default(true);
            $table->float('salary')->nullable()->default(null);
            $table->timestamp('subscription_renew')->nullable()->default(null);
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
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
        Schema::dropIfExists('users');
    }
}

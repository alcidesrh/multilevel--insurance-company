<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable()->default(null);
            $table->unsignedFloat('price')->nullable()->default(null);
            $table->smallInteger('duration')->nullable()->default(null);
            $table->boolean('active')->nullable()->default(null);
            $table->string('type', 20)->nullable()->default(null);
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {            

            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->onDelete('set null');
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
        Schema::dropIfExists('subscriptions');
    }
}

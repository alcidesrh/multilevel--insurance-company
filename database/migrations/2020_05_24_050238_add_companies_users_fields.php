<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompaniesUsersFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {                        

            $table->foreignId('parent_id')->nullable()->constrained('companies')->onDelete('set null');

        });

        Schema::table('users', function (Blueprint $table) {            

            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');

            $table->foreignId('parent_id')->nullable()->constrained('users')->onDelete('set null');

            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('set null');
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

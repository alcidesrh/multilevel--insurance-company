<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsCompanyCategoryRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_product', function (Blueprint $table) {

            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');

            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
        });

        Schema::create('category_product', function (Blueprint $table) {

            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');

            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
        });

        Schema::table('categories', function (Blueprint $table) { 

            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_product');

        Schema::dropIfExists('category_product');
    }
}

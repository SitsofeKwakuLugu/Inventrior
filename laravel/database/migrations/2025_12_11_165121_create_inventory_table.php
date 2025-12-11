<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('inventory', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('product_id')->index();
            $table->uuid('company_id')->index();
            $table->integer('quantity')->default(0);
            $table->decimal('average_cost', 12, 2)->default(0);
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('inventory');
    }
};
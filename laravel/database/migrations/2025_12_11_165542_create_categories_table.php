<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id')->index();
            $table->string('name');
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('categories');
    }
};
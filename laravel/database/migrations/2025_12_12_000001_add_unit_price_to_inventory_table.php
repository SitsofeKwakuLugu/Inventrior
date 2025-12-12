<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('inventory', function (Blueprint $table) {
            if (!Schema::hasColumn('inventory', 'unit_price')) {
                $table->decimal('unit_price', 10, 2)->default(0)->after('quantity');
            }
        });
    }

    public function down(): void {
        Schema::table('inventory', function (Blueprint $table) {
            if (Schema::hasColumn('inventory', 'unit_price')) {
                $table->dropColumn('unit_price');
            }
        });
    }
};

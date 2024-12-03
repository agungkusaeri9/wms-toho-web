<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('initial_qty');
            $table->dropColumn('qty');
            $table->string('lot_number')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('initial_qty')->default(0);
            $table->integer('qty')->default(0);
            $table->integer('lot_number')->nullable()->change();
        });
    }
};

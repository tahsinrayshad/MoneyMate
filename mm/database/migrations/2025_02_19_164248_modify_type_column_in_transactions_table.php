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
        Schema::table('transactions', function (Blueprint $table) {
            // Modify the 'type' column to add 'Loan' and 'Borrow' to the enum
            $table->enum('type', ['income', 'expense', 'loan', 'borrow'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Revert back to the original enum values
            $table->enum('type', ['income', 'expense'])->change();
        });
    }
};
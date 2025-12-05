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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('proof_of_payment')->nullable()->after('amount');
            $table->string('bank_name')->nullable()->after('proof_of_payment'); // Nama bank pengirim
            $table->string('account_name')->nullable()->after('bank_name'); // Nama pemilik rekening pengirim
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['proof_of_payment', 'bank_name', 'account_name']);
        });
    }
};

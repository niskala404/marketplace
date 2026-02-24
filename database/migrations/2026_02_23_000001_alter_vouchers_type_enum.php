<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // The initial migration used ENUM('fixed','percent'),
        // but the app already supports 'shipping'.
        // This keeps existing data intact.
        try {
            DB::statement("ALTER TABLE vouchers MODIFY type ENUM('fixed','percent','shipping') NOT NULL DEFAULT 'fixed'");
        } catch (\Throwable $e) {
            // Ignore if database driver doesn't support enum alter (e.g. sqlite in tests)
        }
    }

    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE vouchers MODIFY type ENUM('fixed','percent') NOT NULL DEFAULT 'fixed'");
        } catch (\Throwable $e) {
            // ignore
        }
    }
};

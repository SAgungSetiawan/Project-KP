<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Untuk MySQL: ubah ke LONGBLOB
        if (config('database.default') === 'mysql') {
            DB::statement('ALTER TABLE invoices MODIFY file_content LONGBLOB NULL');
        }
        // Untuk PostgreSQL
        elseif (config('database.default') === 'pgsql') {
            DB::statement('ALTER TABLE invoices ALTER COLUMN file_content TYPE BYTEA');
        }
        // Untuk SQLite (Railway gratis pakai PostgreSQL)
    }

    public function down(): void
    {
        if (config('database.default') === 'mysql') {
            DB::statement('ALTER TABLE invoices MODIFY file_content BLOB NULL');
        }
    }
};
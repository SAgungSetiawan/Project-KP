<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->string('invoice_number')->nullable();
            $table->string('file_original_name')->nullable();
            $table->string('file_mime_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->date('invoice_date')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->timestamps();
        });

        // Setelah membuat tabel, tambahkan kolom dengan raw SQL
        if (Schema::hasTable('invoices') && !Schema::hasColumn('invoices', 'file_content')) {
            DB::statement('ALTER TABLE invoices ADD file_content LONGBLOB NULL');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
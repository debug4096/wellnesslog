<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medication_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medication_id')->constrained()->cascadeOnDelete();
            $table->timestamp('taken_at');
            $table->decimal('dosage', 6, 3)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('taken_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medication_logs');
    }
};

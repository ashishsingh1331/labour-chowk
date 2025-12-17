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
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('labourer_id')->constrained('labourers')->cascadeOnDelete();
            $table->date('date');
            $table->string('status')->default('available');
            $table->timestamps();

            $table->unique(['labourer_id', 'date']);
            $table->index(['date']);
            $table->index(['date', 'labourer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};

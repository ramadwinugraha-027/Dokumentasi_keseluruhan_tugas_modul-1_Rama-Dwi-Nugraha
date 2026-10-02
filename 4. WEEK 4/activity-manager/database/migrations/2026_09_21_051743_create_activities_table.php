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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique(); // Poin 3: code mempunyai unique index
            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete(); // Poin 6 & BR-08: Kategori yang masih dipakai tidak boleh dihapus
            $table->string('title', 100);
            $table->text('description')->nullable();
            $table->date('activity_date');
            $table->string('status', 20)->default('Planned');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();
            $table->string('title', 100);
            $table->text('description')->nullable();
            $table->string('poster_path', 255)->nullable();
            $table->date('activity_date');
            $table->string('status', 20)->default('draft');
            $table->unsignedInteger('capacity')->default(20);
            $table->unsignedInteger('registered_count')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};

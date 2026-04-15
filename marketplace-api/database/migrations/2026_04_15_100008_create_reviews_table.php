<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('worker_id');
            $table->tinyInteger('rating')->unsigned();
            $table->text('comment')->nullable();
            $table->boolean('is_moderated')->default(false);
            $table->timestamps();

            $table->foreign('worker_id')->references('id')->on('users')->cascadeOnDelete();
            $table->index(['service_id', 'rating']);
            $table->unique(['user_id', 'service_id']); // One review per user per service
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};

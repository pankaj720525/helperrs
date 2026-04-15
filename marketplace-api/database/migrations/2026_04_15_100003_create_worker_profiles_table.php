<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('worker_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('address')->nullable();
            $table->text('bio')->nullable();
            $table->string('phone_public')->nullable();
            $table->string('email_public')->nullable();
            $table->json('specialties')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });

        // Add spatial POINT column for geolocation (nullable — SPATIAL INDEX requires NOT NULL, added later)
        DB::statement('ALTER TABLE worker_profiles ADD location POINT NULL AFTER user_id');
    }

    public function down(): void
    {
        Schema::dropIfExists('worker_profiles');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->enum('plan_type', ['trial', 'monthly', 'yearly'])->default('trial');
            $table->date('starts_at');
            $table->date('expires_at');
            $table->boolean('is_active')->default(true);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->foreign('plan_id')->references('id')->on('subscription_plans')->nullOnDelete();
            $table->index(['user_id', 'is_active']);
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};

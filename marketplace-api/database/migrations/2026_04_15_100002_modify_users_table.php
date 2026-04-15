<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('avatar')->nullable()->after('password');
            $table->enum('role', ['user', 'worker', 'both'])->default('user')->after('avatar');
            $table->boolean('is_active')->default(true)->after('role');
            $table->json('notification_preferences')->nullable()->after('is_active');
            $table->boolean('admin_email_override')->default(false)->after('notification_preferences');
            $table->boolean('admin_push_override')->default(false)->after('admin_email_override');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'avatar',
                'role',
                'is_active',
                'notification_preferences',
                'admin_email_override',
                'admin_push_override',
            ]);
        });
    }
};

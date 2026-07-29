<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->unsignedBigInteger('worker_id')->nullable()->change();
            $table->unsignedBigInteger('service_id')->nullable()->change();
            if (!Schema::hasColumn('chats', 'is_support')) {
                $table->boolean('is_support')->default(false)->after('service_id');
            }
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            $table->unsignedBigInteger('sender_id')->nullable()->change();
            if (!Schema::hasColumn('chat_messages', 'admin_id')) {
                $table->foreignId('admin_id')->nullable()->after('sender_id')->constrained('admins')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            if (Schema::hasColumn('chats', 'is_support')) {
                $table->dropColumn('is_support');
            }
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            if (Schema::hasColumn('chat_messages', 'admin_id')) {
                $table->dropForeign(['admin_id']);
                $table->dropColumn('admin_id');
            }
        });
    }
};

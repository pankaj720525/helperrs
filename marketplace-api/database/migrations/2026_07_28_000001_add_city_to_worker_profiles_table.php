<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('worker_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('worker_profiles', 'city')) {
                $table->string('city', 100)->nullable()->after('address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('worker_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('worker_profiles', 'city')) {
                $table->dropColumn('city');
            }
        });
    }
};

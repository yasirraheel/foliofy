<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('portfolio_meta', function (Blueprint $table): void {
            $table->string('theme_default', 10)->default('dark')->after('brand_text');
        });

        DB::table('portfolio_meta')
            ->whereNull('theme_default')
            ->update(['theme_default' => 'dark']);
    }

    public function down(): void
    {
        Schema::table('portfolio_meta', function (Blueprint $table): void {
            $table->dropColumn('theme_default');
        });
    }
};

<?php

use App\Support\PortfolioDataStore;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('portfolio_contents')) {
            return;
        }

        $rawData = DB::table('portfolio_contents')->where('id', 1)->value('data');

        if (is_string($rawData)) {
            $decoded = json_decode($rawData, true);

            if (is_array($decoded) && $decoded !== []) {
                app(PortfolioDataStore::class)->replace($decoded);
            }
        }

        Schema::dropIfExists('portfolio_contents');
    }

    public function down(): void
    {
        if (Schema::hasTable('portfolio_contents')) {
            return;
        }

        Schema::create('portfolio_contents', function (Blueprint $table): void {
            $table->id();
            $table->json('data')->nullable();
            $table->timestamps();
        });
    }
};

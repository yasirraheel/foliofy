<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('portfolio_meta', function (Blueprint $table): void {
            $table->longText('site_keywords')->nullable()->after('site_desc');
        });

        Schema::table('portfolio_contact', function (Blueprint $table): void {
            $table->text('portfolio_url')->nullable()->after('location');
            $table->text('resume_url')->nullable()->after('portfolio_url');
        });

        Schema::create('portfolio_achievements', function (Blueprint $table): void {
            $table->id();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('period')->nullable();
            $table->string('highlight')->nullable();
            $table->longText('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('portfolio_education', function (Blueprint $table): void {
            $table->id();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('status')->nullable();
            $table->longText('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('portfolio_languages', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->nullable();
            $table->string('proficiency')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_languages');
        Schema::dropIfExists('portfolio_education');
        Schema::dropIfExists('portfolio_achievements');

        Schema::table('portfolio_contact', function (Blueprint $table): void {
            $table->dropColumn(['portfolio_url', 'resume_url']);
        });

        Schema::table('portfolio_meta', function (Blueprint $table): void {
            $table->dropColumn('site_keywords');
        });
    }
};

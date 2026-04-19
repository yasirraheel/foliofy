<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_meta', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->nullable();
            $table->string('role')->nullable();
            $table->string('brand_text', 10)->nullable();
            $table->string('site_title')->nullable();
            $table->longText('site_desc')->nullable();
            $table->timestamps();
        });

        Schema::create('portfolio_hero', function (Blueprint $table): void {
            $table->id();
            $table->string('available_tag')->nullable();
            $table->string('first_name')->nullable();
            $table->string('highlight_name')->nullable();
            $table->longText('description')->nullable();
            $table->unsignedInteger('orbit_speed')->default(12);
            $table->timestamps();
        });

        Schema::create('portfolio_hero_typed_words', function (Blueprint $table): void {
            $table->id();
            $table->string('word');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('portfolio_hero_stats', function (Blueprint $table): void {
            $table->id();
            $table->integer('number')->default(0);
            $table->string('label')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('portfolio_about', function (Blueprint $table): void {
            $table->id();
            $table->string('heading')->nullable();
            $table->string('heading_highlight')->nullable();
            $table->longText('text_1')->nullable();
            $table->longText('text_2')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('location')->nullable();
            $table->string('degree')->nullable();
            $table->string('exp_years')->nullable();
            $table->timestamps();
        });

        Schema::create('portfolio_skills', function (Blueprint $table): void {
            $table->id();
            $table->string('category', 30);
            $table->string('name')->nullable();
            $table->string('icon_class')->nullable();
            $table->string('icon_color', 30)->nullable();
            $table->unsignedTinyInteger('level')->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('portfolio_projects', function (Blueprint $table): void {
            $table->id();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('category', 30)->nullable();
            $table->string('gradient', 30)->nullable();
            $table->string('icon')->nullable();
            $table->text('live_url')->nullable();
            $table->text('github_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('portfolio_project_tags', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('portfolio_project_id')
                ->constrained('portfolio_projects')
                ->cascadeOnDelete();
            $table->string('tag');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('portfolio_experiences', function (Blueprint $table): void {
            $table->id();
            $table->string('title')->nullable();
            $table->string('company')->nullable();
            $table->string('period')->nullable();
            $table->string('location')->nullable();
            $table->longText('description')->nullable();
            $table->string('icon_class')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('portfolio_experience_tags', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('portfolio_experience_id')
                ->constrained('portfolio_experiences')
                ->cascadeOnDelete();
            $table->string('tag');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('portfolio_testimonials', function (Blueprint $table): void {
            $table->id();
            $table->longText('text')->nullable();
            $table->string('author_name')->nullable();
            $table->string('author_role')->nullable();
            $table->string('initials', 10)->nullable();
            $table->text('avatar_gradient')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('portfolio_contact', function (Blueprint $table): void {
            $table->id();
            $table->string('heading')->nullable();
            $table->longText('subtext')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('location')->nullable();
            $table->text('github_url')->nullable();
            $table->text('linkedin_url')->nullable();
            $table->text('twitter_url')->nullable();
            $table->text('dribbble_url')->nullable();
            $table->text('instagram_url')->nullable();
            $table->boolean('whatsapp_enabled')->default(false);
            $table->text('whatsapp_api_key')->nullable();
            $table->string('whatsapp_account_name')->nullable();
            $table->string('whatsapp_target_number')->nullable();
            $table->timestamps();
        });

        Schema::create('portfolio_footer', function (Blueprint $table): void {
            $table->id();
            $table->longText('tagline')->nullable();
            $table->timestamps();
        });

        Schema::create('portfolio_images', function (Blueprint $table): void {
            $table->id();
            $table->text('hero')->nullable();
            $table->text('about')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_images');
        Schema::dropIfExists('portfolio_footer');
        Schema::dropIfExists('portfolio_contact');
        Schema::dropIfExists('portfolio_testimonials');
        Schema::dropIfExists('portfolio_experience_tags');
        Schema::dropIfExists('portfolio_experiences');
        Schema::dropIfExists('portfolio_project_tags');
        Schema::dropIfExists('portfolio_projects');
        Schema::dropIfExists('portfolio_skills');
        Schema::dropIfExists('portfolio_about');
        Schema::dropIfExists('portfolio_hero_stats');
        Schema::dropIfExists('portfolio_hero_typed_words');
        Schema::dropIfExists('portfolio_hero');
        Schema::dropIfExists('portfolio_meta');
    }
};

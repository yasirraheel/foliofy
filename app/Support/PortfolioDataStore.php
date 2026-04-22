<?php

namespace App\Support;

use App\Models\PortfolioContent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PortfolioDataStore
{
    private const SKILL_CATEGORY_ORDER = [
        'networking',
        'webDevelopment',
        'androidDevelopment',
        'productivityTools',
        'professionalStrengths',
        'frontend',
        'backend',
        'tools',
    ];

    public function full(): array
    {
        if ($this->hasNormalizedSchema()) {
            if ($this->hasNormalizedData()) {
                return $this->normalizedData();
            }

            $legacy = $this->legacyData();

            if ($legacy !== []) {
                return $legacy;
            }
        }

        return $this->legacyData();
    }

    public function public(): array
    {
        $data = $this->full();

        if (isset($data['contact']['whatsappApi']) && is_array($data['contact']['whatsappApi'])) {
            $data['contact']['whatsappApi'] = [
                'enabled' => false,
                'apiKey' => '',
                'accountName' => '',
                'targetNumber' => '',
            ];
        }

        return $data;
    }

    public function publicWithDefaults(): array
    {
        return $this->mergeRecursive(
            PortfolioDefaultData::load(),
            $this->public()
        );
    }

    public function save(array $data): array
    {
        $merged = $this->mergeRecursive($this->full(), $data);
        $this->replace($merged);

        return $this->full();
    }

    public function replace(array $data): array
    {
        if (! $this->hasNormalizedSchema()) {
            return $data;
        }

        DB::transaction(function () use ($data): void {
            $timestamp = now();

            $this->upsertSingleton('portfolio_meta', [
                'name' => $data['meta']['name'] ?? null,
                'role' => $data['meta']['role'] ?? null,
                'brand_text' => $data['meta']['brandText'] ?? null,
                'theme_default' => $data['meta']['themeDefault'] ?? 'dark',
                'site_title' => $data['meta']['siteTitle'] ?? null,
                'site_desc' => $data['meta']['siteDesc'] ?? null,
                'site_keywords' => $data['meta']['siteKeywords'] ?? null,
            ], $timestamp);

            $this->upsertSingleton('portfolio_hero', [
                'available_tag' => $data['hero']['availableTag'] ?? null,
                'first_name' => $data['hero']['firstName'] ?? null,
                'highlight_name' => $data['hero']['highlightName'] ?? null,
                'description' => $data['hero']['description'] ?? null,
                'orbit_speed' => (int) ($data['hero']['orbitSpeed'] ?? 12),
            ], $timestamp);

            $this->replaceSimpleCollection(
                'portfolio_hero_typed_words',
                collect($data['hero']['typedWords'] ?? [])
                    ->values()
                    ->map(fn ($word, int $index): array => [
                        'word' => (string) $word,
                        'sort_order' => $index,
                    ])
                    ->all(),
                $timestamp
            );

            $this->replaceSimpleCollection(
                'portfolio_hero_stats',
                collect($data['hero']['stats'] ?? [])
                    ->values()
                    ->map(fn (array $stat, int $index): array => [
                        'number' => (int) ($stat['number'] ?? 0),
                        'label' => $stat['label'] ?? null,
                        'sort_order' => $index,
                    ])
                    ->all(),
                $timestamp
            );

            $this->upsertSingleton('portfolio_about', [
                'heading' => $data['about']['heading'] ?? null,
                'heading_highlight' => $data['about']['headingHighlight'] ?? null,
                'text_1' => $data['about']['text1'] ?? null,
                'text_2' => $data['about']['text2'] ?? null,
                'name' => $data['about']['name'] ?? null,
                'email' => $data['about']['email'] ?? null,
                'location' => $data['about']['location'] ?? null,
                'degree' => $data['about']['degree'] ?? null,
                'exp_years' => $data['about']['expYears'] ?? null,
            ], $timestamp);

            $skillRows = collect($this->orderedSkillCategories(array_keys($data['skills'] ?? [])))
                ->flatMap(fn (string $category): Collection => collect($data['skills'][$category] ?? [])
                    ->values()
                    ->map(fn (array $skill, int $index): array => [
                        'category' => $category,
                        'name' => $skill['name'] ?? null,
                        'icon_class' => $skill['iconClass'] ?? null,
                        'icon_color' => $skill['iconColor'] ?? null,
                        'level' => (int) ($skill['level'] ?? 100),
                        'sort_order' => $index,
                    ]))
                ->values()
                ->all();
            $this->replaceSimpleCollection('portfolio_skills', $skillRows, $timestamp);

            DB::table('portfolio_project_tags')->delete();
            DB::table('portfolio_projects')->delete();
            foreach (collect($data['projects'] ?? [])->values() as $projectIndex => $project) {
                $projectId = DB::table('portfolio_projects')->insertGetId([
                    'title' => $project['title'] ?? null,
                    'description' => $project['desc'] ?? null,
                    'category' => $project['category'] ?? null,
                    'gradient' => $project['gradient'] ?? null,
                    'icon' => $project['icon'] ?? null,
                    'live_url' => $project['liveUrl'] ?? null,
                    'github_url' => $project['githubUrl'] ?? null,
                    'sort_order' => $projectIndex,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);

                foreach (collect($project['tags'] ?? [])->values() as $tagIndex => $tag) {
                    DB::table('portfolio_project_tags')->insert([
                        'portfolio_project_id' => $projectId,
                        'tag' => (string) $tag,
                        'sort_order' => $tagIndex,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp,
                    ]);
                }
            }

            DB::table('portfolio_experience_tags')->delete();
            DB::table('portfolio_experiences')->delete();
            foreach (collect($data['experience'] ?? [])->values() as $experienceIndex => $experience) {
                $experienceId = DB::table('portfolio_experiences')->insertGetId([
                    'title' => $experience['title'] ?? null,
                    'company' => $experience['company'] ?? null,
                    'period' => $experience['period'] ?? null,
                    'location' => $experience['location'] ?? null,
                    'description' => $experience['desc'] ?? null,
                    'icon_class' => $experience['iconClass'] ?? null,
                    'sort_order' => $experienceIndex,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);

                foreach (collect($experience['tags'] ?? [])->values() as $tagIndex => $tag) {
                    DB::table('portfolio_experience_tags')->insert([
                        'portfolio_experience_id' => $experienceId,
                        'tag' => (string) $tag,
                        'sort_order' => $tagIndex,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp,
                    ]);
                }
            }

            $this->replaceSimpleCollection(
                'portfolio_achievements',
                collect($data['achievements'] ?? [])
                    ->values()
                    ->map(fn (array $item, int $index): array => [
                        'title' => $item['title'] ?? null,
                        'subtitle' => $item['subtitle'] ?? null,
                        'period' => $item['period'] ?? null,
                        'highlight' => $item['highlight'] ?? null,
                        'description' => $item['description'] ?? null,
                        'sort_order' => $index,
                    ])
                    ->all(),
                $timestamp
            );

            $this->replaceSimpleCollection(
                'portfolio_education',
                collect($data['education'] ?? [])
                    ->values()
                    ->map(fn (array $item, int $index): array => [
                        'title' => $item['title'] ?? null,
                        'subtitle' => $item['subtitle'] ?? null,
                        'status' => $item['status'] ?? null,
                        'description' => $item['description'] ?? null,
                        'sort_order' => $index,
                    ])
                    ->all(),
                $timestamp
            );

            $this->replaceSimpleCollection(
                'portfolio_languages',
                collect($data['languages'] ?? [])
                    ->values()
                    ->map(fn (array $item, int $index): array => [
                        'name' => $item['name'] ?? null,
                        'proficiency' => $item['proficiency'] ?? null,
                        'sort_order' => $index,
                    ])
                    ->all(),
                $timestamp
            );

            $this->replaceSimpleCollection(
                'portfolio_testimonials',
                collect($data['testimonials'] ?? [])
                    ->values()
                    ->map(fn (array $testimonial, int $index): array => [
                        'text' => $testimonial['text'] ?? null,
                        'author_name' => $testimonial['authorName'] ?? null,
                        'author_role' => $testimonial['authorRole'] ?? null,
                        'initials' => $testimonial['initials'] ?? null,
                        'avatar_gradient' => $testimonial['avatarGradient'] ?? null,
                        'sort_order' => $index,
                    ])
                    ->all(),
                $timestamp
            );

            $this->upsertSingleton('portfolio_contact', [
                'heading' => $data['contact']['heading'] ?? null,
                'subtext' => $data['contact']['subtext'] ?? null,
                'email' => $data['contact']['email'] ?? null,
                'phone' => $data['contact']['phone'] ?? null,
                'location' => $data['contact']['location'] ?? null,
                'portfolio_url' => $data['contact']['portfolioUrl'] ?? null,
                'resume_url' => $data['contact']['resumeUrl'] ?? null,
                'github_url' => $data['contact']['social']['github'] ?? null,
                'linkedin_url' => $data['contact']['social']['linkedin'] ?? null,
                'twitter_url' => $data['contact']['social']['twitter'] ?? null,
                'dribbble_url' => $data['contact']['social']['dribbble'] ?? null,
                'instagram_url' => $data['contact']['social']['instagram'] ?? null,
                'whatsapp_enabled' => (bool) ($data['contact']['whatsappApi']['enabled'] ?? false),
                'whatsapp_api_key' => $data['contact']['whatsappApi']['apiKey'] ?? null,
                'whatsapp_account_name' => $data['contact']['whatsappApi']['accountName'] ?? null,
                'whatsapp_target_number' => $data['contact']['whatsappApi']['targetNumber'] ?? null,
            ], $timestamp);

            $this->upsertSingleton('portfolio_footer', [
                'tagline' => $data['footer']['tagline'] ?? null,
            ], $timestamp);

            $this->upsertSingleton('portfolio_images', [
                'hero' => $data['images']['hero'] ?? null,
                'about' => $data['images']['about'] ?? null,
            ], $timestamp);
        });

        return $this->full();
    }

    private function normalizedData(): array
    {
        $meta = (array) DB::table('portfolio_meta')->where('id', 1)->first();
        $hero = (array) DB::table('portfolio_hero')->where('id', 1)->first();
        $about = (array) DB::table('portfolio_about')->where('id', 1)->first();
        $contact = (array) DB::table('portfolio_contact')->where('id', 1)->first();
        $footer = (array) DB::table('portfolio_footer')->where('id', 1)->first();
        $images = (array) DB::table('portfolio_images')->where('id', 1)->first();

        $projects = DB::table('portfolio_projects')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(function (object $project): array {
                $tags = DB::table('portfolio_project_tags')
                    ->where('portfolio_project_id', $project->id)
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->pluck('tag')
                    ->all();

                return [
                    'title' => $project->title ?? '',
                    'desc' => $project->description ?? '',
                    'tags' => $tags,
                    'category' => $project->category ?? '',
                    'gradient' => $project->gradient ?? '',
                    'icon' => $project->icon ?? '',
                    'liveUrl' => $project->live_url ?? '',
                    'githubUrl' => $project->github_url ?? '',
                ];
            })
            ->all();

        $experience = DB::table('portfolio_experiences')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(function (object $item): array {
                $tags = DB::table('portfolio_experience_tags')
                    ->where('portfolio_experience_id', $item->id)
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->pluck('tag')
                    ->all();

                return [
                    'title' => $item->title ?? '',
                    'company' => $item->company ?? '',
                    'period' => $item->period ?? '',
                    'location' => $item->location ?? '',
                    'desc' => $item->description ?? '',
                    'tags' => $tags,
                    'iconClass' => $item->icon_class ?? '',
                ];
            })
            ->all();

        return [
            'meta' => [
                'name' => $meta['name'] ?? '',
                'role' => $meta['role'] ?? '',
                'brandText' => $meta['brand_text'] ?? '',
                'themeDefault' => $meta['theme_default'] ?? 'dark',
                'siteTitle' => $meta['site_title'] ?? '',
                'siteDesc' => $meta['site_desc'] ?? '',
                'siteKeywords' => $meta['site_keywords'] ?? '',
            ],
            'hero' => [
                'availableTag' => $hero['available_tag'] ?? '',
                'firstName' => $hero['first_name'] ?? '',
                'highlightName' => $hero['highlight_name'] ?? '',
                'description' => $hero['description'] ?? '',
                'orbitSpeed' => (int) ($hero['orbit_speed'] ?? 12),
                'typedWords' => DB::table('portfolio_hero_typed_words')
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->pluck('word')
                    ->all(),
                'stats' => DB::table('portfolio_hero_stats')
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->get()
                    ->map(fn (object $stat): array => [
                        'number' => (int) $stat->number,
                        'label' => $stat->label ?? '',
                    ])
                    ->all(),
            ],
            'about' => [
                'heading' => $about['heading'] ?? '',
                'headingHighlight' => $about['heading_highlight'] ?? '',
                'text1' => $about['text_1'] ?? '',
                'text2' => $about['text_2'] ?? '',
                'name' => $about['name'] ?? '',
                'email' => $about['email'] ?? '',
                'location' => $about['location'] ?? '',
                'degree' => $about['degree'] ?? '',
                'expYears' => $about['exp_years'] ?? '',
            ],
            'skills' => $this->allSkillsByCategory(),
            'projects' => $projects,
            'experience' => $experience,
            'achievements' => Schema::hasTable('portfolio_achievements')
                ? DB::table('portfolio_achievements')
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->get()
                    ->map(fn (object $item): array => [
                        'title' => $item->title ?? '',
                        'subtitle' => $item->subtitle ?? '',
                        'period' => $item->period ?? '',
                        'highlight' => $item->highlight ?? '',
                        'description' => $item->description ?? '',
                    ])
                    ->all()
                : [],
            'education' => Schema::hasTable('portfolio_education')
                ? DB::table('portfolio_education')
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->get()
                    ->map(fn (object $item): array => [
                        'title' => $item->title ?? '',
                        'subtitle' => $item->subtitle ?? '',
                        'status' => $item->status ?? '',
                        'description' => $item->description ?? '',
                    ])
                    ->all()
                : [],
            'languages' => Schema::hasTable('portfolio_languages')
                ? DB::table('portfolio_languages')
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->get()
                    ->map(fn (object $item): array => [
                        'name' => $item->name ?? '',
                        'proficiency' => $item->proficiency ?? '',
                    ])
                    ->all()
                : [],
            'testimonials' => DB::table('portfolio_testimonials')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->map(fn (object $testimonial): array => [
                    'text' => $testimonial->text ?? '',
                    'authorName' => $testimonial->author_name ?? '',
                    'authorRole' => $testimonial->author_role ?? '',
                    'initials' => $testimonial->initials ?? '',
                    'avatarGradient' => $testimonial->avatar_gradient ?? '',
                ])
                ->all(),
            'contact' => [
                'heading' => $contact['heading'] ?? '',
                'subtext' => $contact['subtext'] ?? '',
                'email' => $contact['email'] ?? '',
                'phone' => $contact['phone'] ?? '',
                'location' => $contact['location'] ?? '',
                'portfolioUrl' => $contact['portfolio_url'] ?? '',
                'resumeUrl' => $contact['resume_url'] ?? '',
                'social' => [
                    'github' => $contact['github_url'] ?? '',
                    'linkedin' => $contact['linkedin_url'] ?? '',
                    'twitter' => $contact['twitter_url'] ?? '',
                    'dribbble' => $contact['dribbble_url'] ?? '',
                    'instagram' => $contact['instagram_url'] ?? '',
                ],
                'whatsappApi' => [
                    'enabled' => (bool) ($contact['whatsapp_enabled'] ?? false),
                    'apiKey' => $contact['whatsapp_api_key'] ?? '',
                    'accountName' => $contact['whatsapp_account_name'] ?? '',
                    'targetNumber' => $contact['whatsapp_target_number'] ?? '',
                ],
            ],
            'footer' => [
                'tagline' => $footer['tagline'] ?? '',
            ],
            'images' => [
                'hero' => $images['hero'] ?? '',
                'about' => $images['about'] ?? '',
            ],
        ];
    }

    private function legacyData(): array
    {
        if (! Schema::hasTable('portfolio_contents')) {
            return [];
        }

        $record = PortfolioContent::query()->find(1);

        return is_array($record?->data) ? $record->data : [];
    }

    private function hasNormalizedSchema(): bool
    {
        return Schema::hasTable('portfolio_meta');
    }

    private function hasNormalizedData(): bool
    {
        return DB::table('portfolio_meta')->exists()
            || DB::table('portfolio_hero')->exists()
            || DB::table('portfolio_about')->exists()
            || DB::table('portfolio_skills')->exists()
            || DB::table('portfolio_projects')->exists()
            || DB::table('portfolio_experiences')->exists()
            || (Schema::hasTable('portfolio_achievements') && DB::table('portfolio_achievements')->exists())
            || (Schema::hasTable('portfolio_education') && DB::table('portfolio_education')->exists())
            || (Schema::hasTable('portfolio_languages') && DB::table('portfolio_languages')->exists())
            || DB::table('portfolio_contact')->exists()
            || DB::table('portfolio_footer')->exists()
            || DB::table('portfolio_images')->exists();
    }

    private function allSkillsByCategory(): array
    {
        $rows = DB::table('portfolio_skills')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        if ($rows->isEmpty()) {
            return [];
        }

        $grouped = [];
        foreach ($rows as $row) {
            $grouped[$row->category][] = [
                'name' => $row->name ?? '',
                'iconClass' => $row->icon_class ?? '',
                'iconColor' => $row->icon_color ?? '',
                'level' => (int) $row->level,
            ];
        }

        $ordered = [];
        foreach ($this->orderedSkillCategories(array_keys($grouped)) as $category) {
            if (isset($grouped[$category])) {
                $ordered[$category] = $grouped[$category];
            }
        }

        return $ordered;
    }

    private function orderedSkillCategories(array $categories): array
    {
        $categories = array_values(array_unique(array_filter($categories)));
        $ordered = [];

        foreach (self::SKILL_CATEGORY_ORDER as $category) {
            if (in_array($category, $categories, true)) {
                $ordered[] = $category;
            }
        }

        $extras = array_values(array_diff($categories, $ordered));
        sort($extras);

        return array_merge($ordered, $extras);
    }

    private function upsertSingleton(string $table, array $values, $timestamp): void
    {
        DB::table($table)->updateOrInsert(
            ['id' => 1],
            array_merge($values, [
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ])
        );
    }

    private function replaceSimpleCollection(string $table, array $rows, $timestamp): void
    {
        DB::table($table)->delete();

        if ($rows === []) {
            return;
        }

        DB::table($table)->insert(
            array_map(
                fn (array $row): array => array_merge($row, [
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]),
                $rows
            )
        );
    }

    private function mergeRecursive(array $base, array $overrides): array
    {
        $merged = $base;

        foreach ($overrides as $key => $value) {
            if (
                isset($merged[$key]) &&
                is_array($merged[$key]) &&
                is_array($value) &&
                $this->isAssociative($merged[$key]) &&
                $this->isAssociative($value)
            ) {
                $merged[$key] = $this->mergeRecursive($merged[$key], $value);
                continue;
            }

            $merged[$key] = $value;
        }

        return $merged;
    }

    private function isAssociative(array $array): bool
    {
        if ($array === []) {
            return true;
        }

        return array_keys($array) !== range(0, count($array) - 1);
    }
}

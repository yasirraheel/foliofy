<?php

use App\Support\PortfolioDataStore;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('portfolio_meta')) {
            return;
        }

        $portfolioDataStore = app(PortfolioDataStore::class);
        $data = $portfolioDataStore->full();

        if ($data === [] || ! isset($data['experience']) || ! is_array($data['experience'])) {
            return;
        }

        $updated = false;

        $data['experience'] = array_map(
            static function (array $experience) use (&$updated): array {
                if (($experience['title'] ?? '') !== 'Instructor Assignment - Kingdom of Saudi Arabia Army') {
                    return $experience;
                }

                $updated = true;
                $experience['period'] = '2021 - 2024';
                $experience['desc'] = 'Completed a three-year instructor assignment from 2021 to 2024 for the Kingdom of Saudi Arabia Army in Weapons and Low Intensity Conflict Training. This role reflects international instruction exposure, structured supervision, team coordination, adaptability, and the ability to transfer technical knowledge in disciplined operational settings.';

                return $experience;
            },
            $data['experience']
        );

        if ($updated) {
            $portfolioDataStore->replace($data);
        }
    }

    public function down(): void
    {
        // This migration updates live profile content in place and is intentionally not reversed.
    }
};

<?php

namespace Database\Seeders;

use App\Support\PortfolioDataStore;
use App\Support\PortfolioDefaultData;
use Illuminate\Database\Seeder;

class PortfolioDataSeeder extends Seeder
{
    public function __construct(
        private readonly PortfolioDataStore $portfolioDataStore,
    ) {
    }

    public function run(): void
    {
        $source = $this->portfolioDataStore->full();

        if ($source === []) {
            $source = PortfolioDefaultData::load();
        }

        $this->portfolioDataStore->replace($source);
    }
}

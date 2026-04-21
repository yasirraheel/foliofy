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
        $this->portfolioDataStore->replace(PortfolioDefaultData::load());
    }
}

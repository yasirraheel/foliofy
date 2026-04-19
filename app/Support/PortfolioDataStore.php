<?php

namespace App\Support;

use App\Models\PortfolioContent;
use Illuminate\Support\Facades\Schema;

class PortfolioDataStore
{
    public function full(): array
    {
        if (! Schema::hasTable('portfolio_contents')) {
            return [];
        }

        $record = PortfolioContent::query()->find(1);

        return is_array($record?->data) ? $record->data : [];
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

    public function save(array $data): array
    {
        $record = PortfolioContent::query()->firstOrNew(['id' => 1]);
        $record->id = 1;
        $record->data = $data;
        $record->save();

        return is_array($record->data) ? $record->data : [];
    }
}

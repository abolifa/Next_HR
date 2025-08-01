<?php

namespace App\Filament\Resources\OutGoingResource\Pages;

use App\Filament\Resources\OutGoingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOutGoings extends ListRecords
{
    protected static string $resource = OutGoingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

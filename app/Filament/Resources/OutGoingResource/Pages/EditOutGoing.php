<?php

namespace App\Filament\Resources\OutGoingResource\Pages;

use App\Filament\Resources\OutGoingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOutGoing extends EditRecord
{
    protected static string $resource = OutGoingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

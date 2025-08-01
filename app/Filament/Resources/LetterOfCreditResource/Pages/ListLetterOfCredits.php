<?php

namespace App\Filament\Resources\LetterOfCreditResource\Pages;

use App\Filament\Resources\LetterOfCreditResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLetterOfCredits extends ListRecords
{
    protected static string $resource = LetterOfCreditResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

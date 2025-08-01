<?php

namespace App\Filament\Resources\IncomingResource\Pages;

use App\Filament\Resources\IncomingResource;
use App\Models\Incoming;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;

class ViewAttachments extends Page
{
    protected static string $resource = IncomingResource::class;
    protected static string $view = 'filament.pages.view-outgoing-attachment';
    protected static ?string $title = 'عرض المرفقات';
    public ?Incoming $document = null;

    public function mount($record): void
    {
        $this->document = Incoming::findOrFail($record);
    }


    public function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('')
                ->icon('heroicon-o-arrow-left')
                ->url(IncomingResource::getUrl()),
        ];
    }
}

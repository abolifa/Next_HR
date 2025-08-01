<?php

namespace App\Filament\Resources\OutGoingResource\Pages;

use App\Filament\Resources\OutGoingResource;
use App\Models\OutGoing;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;

class ViewAttachements extends Page
{
    protected static string $resource = OutGoingResource::class;
    protected static string $view = 'filament.pages.view-outgoing-attachment';
    protected static ?string $title = 'عرض المرفقات';
    public ?OutGoing $document = null;

    public function mount($record): void
    {
        $this->document = OutGoing::findOrFail($record);
    }


    public function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('')
                ->icon('heroicon-o-arrow-left')
                ->url(OutGoingResource::getUrl()),
        ];
    }

}

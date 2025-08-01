<?php

namespace App\Filament\Resources\OutGoingResource\Pages;

use App\Filament\Resources\OutGoingResource;
use App\Models\OutGoing;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;

class ViewLetter extends Page
{
    protected static string $resource = OutGoingResource::class;
    protected static string $view = 'filament.outgoing.view-letter';
    protected static ?string $title = 'عرض الرسالة';
    public OutGoing $record;


    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('رجوع')
                ->icon('heroicon-o-arrow-right')
                ->color('neutral')
                ->url(OutGoingResource::getUrl()),
            Action::make('print')
                ->label('طباعة')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->extraAttributes(['x-on:click' => 'window.printIframe()']),
        ];
    }
}

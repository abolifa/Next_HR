<?php

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Filament\Resources\DocumentResource;
use App\Models\Document;
use Filament\Resources\Pages\Page;

class ViewAttachments extends Page
{
    protected static string $resource = DocumentResource::class;
    protected static string $view = 'filament.pages.view-attachments';
    protected static ?string $title = 'عرض المرفقات';
    public ?Document $document = null;

    public function mount($record): void
    {
        $this->document = Document::findOrFail($record);
    }
}

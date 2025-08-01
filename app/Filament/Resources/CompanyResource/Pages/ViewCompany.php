<?php

namespace App\Filament\Resources\CompanyResource\Pages;

use App\Filament\Resources\CompanyResource;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\FontWeight;


class ViewCompany extends ViewRecord
{
    protected static string $resource = CompanyResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('بيانات الشركة')->schema([
                    ImageEntry::make('logo')
                        ->height('60px')
                        ->label('الشعار'),
                    TextEntry::make('arabic_name')
                        ->weight(FontWeight::Bold)
                        ->label('الإسم العربي'),
                    TextEntry::make('english_name')
                        ->weight(FontWeight::Bold)
                        ->label('الإسم الإنجليزي'),
                    TextEntry::make('slogan')
                        ->limit(50)
                        ->label('وصف الشركة'),
                    TextEntry::make('email')
                        ->label('البريد الإلكتروني'),
                    TextEntry::make('phone')
                        ->label('الهاتف'),
                    TextEntry::make('address')
                        ->limit(50)
                        ->label('العنوان'),
                    TextEntry::make('ceo_name')
                        ->label('المفوض'),
                    TextEntry::make('employees')
                        ->label('الأعضاء')
                        ->getStateUsing(function ($record) {
                            if (is_array($record->employees)) {
                                return collect($record->employees)
                                    ->pluck('name')
                                    ->filter()
                                    ->values()
                                    ->toArray();
                            }
                            return [];
                        })
                        ->listWithLineBreaks()
                        ->bulleted(),
                    TextEntry::make('website')
                        ->limit(50)
                        ->label('الموقع الإلكتروني'),
                    IconEntry::make('letterhead')
                        ->label('قالب الرسائل')
                        ->boolean()
                        ->getStateUsing(fn($record) => !is_null($record->letterhead)),
                    TextEntry::make('created_at')
                        ->dateTime('d/m/Y H:i A')
                        ->since()
                        ->dateTimeTooltip()
                        ->label('تاريخ الإنشاء'),
                ])->columns(3),
            ]);
    }
}

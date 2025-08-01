<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentResource\Pages;
use App\Models\Document;
use Carbon\Carbon;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DocumentResource extends Resource
{
    public static ?string $label = 'مستند';
    public static ?string $pluralLabel = 'المستندات';
    protected static ?string $model = Document::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('expiry_date')
            ->recordUrl(fn($record) => static::getUrl('view-attachments', ['record' => $record]))
            ->columns([
                Tables\Columns\TextColumn::make('company.arabic_name')
                    ->label('الشركة')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paper_type')
                    ->label('نوع المستند')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'commercial_register' => 'السجل التجاري',
                        'business_license' => 'الرخصة التجارية',
                        'economic_operator' => 'المشغل الإقتصادي',
                        'importers_register' => 'سجل المستوردين',
                        'statistical_code' => 'الرمز الإحصائي',
                        'chamber_of_commerce' => 'الغرفة التجارية',
                        'industrial_register' => 'السجل الصناعي',
                        'tax_clearance' => 'شهادة سداد ضريبي',
                        'social_security_clearance' => 'شهادة سداد ضمان',
                        'solidarity' => 'تضامن',
                        'articles_of_association' => 'النظام الأساسي',
                        'general_assembly_meeting' => 'اجتماع الجمعية العمومية',
                        'founding_contract' => 'عقد التأسيس',
                        'amendment_contract' => 'عقد التعديل',
                    })
                    ->badge()
                    ->alignCenter()
                    ->searchable()
                    ->color(fn($state) => match ($state) {
                        'commercial_register' => 'info',
                        'business_license' => 'success',
                        'economic_operator' => 'warning',
                        'importers_register' => 'gray',
                        'statistical_code' => 'neutral',
                        'chamber_of_commerce' => 'rose',
                        'industrial_register' => 'orange',
                        'tax_clearance' => 'cyan',
                        'social_security_clearance' => 'teal',
                        'solidarity' => 'purple',
                        'articles_of_association' => 'indigo',
                        'general_assembly_meeting' => 'pink',
                        'founding_contract' => 'blue',
                        'amendment_contract' => 'yellow',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('document_number')
                    ->label('رقم المستند')
                    ->sortable()
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('issue_date')
                    ->label('تاريخ الإصدار')
                    ->alignCenter()
                    ->searchable()
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('expiry_date')
                    ->label('تاريخ الانتهاء')
                    ->alignCenter()
                    ->badge()
                    ->color(
                        function ($state) {
                            if (!$state) {
                                return 'gray';
                            }
                            $expiryDate = Carbon::parse($state);
                            if ($expiryDate->isFuture()) {
                                return 'success';
                            } elseif ($expiryDate->isCurrentMonth()) {
                                return 'warning';
                            } else {
                                return 'danger';
                            }
                        }
                    )
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->diffForHumans())
                    ->tooltip(fn($state) => $state ? Carbon::parse($state)->format('d/m/Y') : 'غير محدد')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('تاريخ التحديث')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('company_id')
                    ->label('الشركة')
                    ->relationship('company', 'arabic_name')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->placeholder('اختر الشركة'),
                Tables\Filters\SelectFilter::make('paper_type')
                    ->label('نوع المستند')
                    ->options([
                        'commercial_register' => 'السجل التجاري',
                        'business_license' => 'الرخصة التجارية',
                        'economic_operator' => 'المشغل الإقتصادي',
                        'importers_register' => 'سجل المستوردين',
                        'statistical_code' => 'الرمز الإحصائي',
                        'chamber_of_commerce' => 'الغرفة التجارية',
                        'industrial_register' => 'السجل الصناعي',
                        'tax_clearance' => 'شهادة سداد ضريبي',
                        'social_security_clearance' => 'شهادة سداد ضمان',
                        'solidarity' => 'تضامن',
                        'articles_of_association' => 'النظام الأساسي',
                        'general_assembly_meeting' => 'اجتماع الجمعية العمومية',
                        'founding_contract' => 'عقد التأسيس',
                        'amendment_contract' => 'عقد التعديل',
                    ])
                    ->native(false)
                    ->placeholder('اختر نوع المستند'),
                Tables\Filters\SelectFilter::make('expiry_date')
                    ->label('الصلاحية')
                    ->options([
                        'expired' => 'منتهية',
                        'expiring_soon' => 'ستنتهي قريباً',
                        'valid' => 'صالحة',
                    ])
                    ->query(function ($query, array $data) {
                        $value = $data['value'] ?? null;

                        if ($value === 'expired') {
                            $query->where('expiry_date', '<', now());
                        } elseif ($value === 'expiring_soon') {
                            $query->whereBetween('expiry_date', [now(), now()->addDays(30)]);
                        } elseif ($value === 'valid') {
                            $query->where('expiry_date', '>', now());
                        }

                        return $query;
                    }),
            ], layout: Tables\Enums\FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('view_attachments')
                        ->label('عرض المستندات')
                        ->icon('heroicon-o-eye')
                        ->url(fn($record) => static::getUrl('view-attachments', ['record' => $record])),
                    Tables\Actions\Action::make('update')
                        ->label('تجديد')
                        ->icon('heroicon-s-arrow-path')
                        ->form([
                            Forms\Components\DatePicker::make('issue_date')
                                ->label('تاريخ الإصدار')
                                ->displayFormat('d/m/Y')
                                ->default(fn($record) => $record->issue_date)
                                ->required(),
                            Forms\Components\DatePicker::make('expiry_date')
                                ->label('تاريخ الانتهاء')
                                ->displayFormat('d/m/Y')
                                ->default(fn($record) => $record->expiry_date)
                                ->required(),
                            Forms\Components\FileUpload::make('attachments')
                                ->label('المستندات')
                                ->multiple()
                                ->acceptedFileTypes([
                                    'application/pdf',
                                    'image/jpeg',
                                    'image/png',
                                ]),
                        ])
                        ->requiresConfirmation()
                        ->color('success'),
                ]),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([

            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\Select::make('company_id')
                        ->label('الشركة')
                        ->relationship('company', 'arabic_name')
                        ->required()
                        ->searchable()
                        ->native(false)
                        ->placeholder('اختر الشركة')
                        ->preload(),
                    Forms\Components\Select::make('paper_type')
                        ->label('نوع المستند')
                        ->options([
                            'commercial_register' => 'السجل التجاري',
                            'business_license' => 'الرخصة التجارية',
                            'economic_operator' => 'المشغل الإقتصادي',
                            'importers_register' => 'سجل المستوردين',
                            'statistical_code' => 'الرمز الإحصائي',
                            'chamber_of_commerce' => 'الغرفة التجارية',
                            'industrial_register' => 'السجل الصناعي',
                            'tax_clearance' => 'شهادة سداد ضريبي',
                            'social_security_clearance' => 'شهادة سداد ضمان',
                            'solidarity' => 'تضامن',
                            'articles_of_association' => 'النظام الأساسي',
                            'general_assembly_meeting' => 'اجتماع الجمعية العمومية',
                            'founding_contract' => 'عقد التأسيس',
                            'amendment_contract' => 'عقد التعديل',
                        ])
                        ->native(false)
                        ->required(),
                    Forms\Components\TextInput::make('document_number')
                        ->label('رقم المستند')
                        ->maxLength(255),
                    Forms\Components\DatePicker::make('issue_date')
                        ->label('تاريخ الإصدار')
                        ->displayFormat('d/m/Y')
                        ->required(),
                    Forms\Components\DatePicker::make('expiry_date')
                        ->label('تاريخ الانتهاء')
                        ->displayFormat('d/m/Y')
                        ->required(),
                    Forms\Components\FileUpload::make('attachments')
                        ->label('المستند')
                        ->multiple()
                        ->acceptedFileTypes([
                            'application/pdf',
                            'image/jpeg',
                            'image/png',
                        ])->columnSpanFull(),
                ])->columns(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
            'view-attachments' => Pages\ViewAttachments::route('/{record}/attachments'),
        ];
    }
}

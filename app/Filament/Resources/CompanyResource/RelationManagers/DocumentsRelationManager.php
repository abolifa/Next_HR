<?php

namespace App\Filament\Resources\CompanyResource\RelationManagers;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    protected static ?string $title = 'المستندات';
    protected static ?string $label = 'مستند';
    protected static ?string $pluralLabel = 'المستندات';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
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
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('update')
                        ->label('تحديث')
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
                                ->label('المستندات الجدية')
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
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
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
}

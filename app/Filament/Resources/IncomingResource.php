<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IncomingResource\Pages;
use App\Models\BankAccount;
use App\Models\Incoming;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class IncomingResource extends Resource
{
    public static ?string $label = 'رسالة';
    public static ?string $pluralLabel = 'البريد الوارد';
    protected static ?string $model = Incoming::class;
    protected static ?string $navigationIcon = 'heroicon-s-document-arrow-down';

    protected static ?string $navigationGroup = 'الأرشيف';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\Select::make('company_id')
                        ->label('الشركة')
                        ->relationship('company', 'arabic_name')
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->required(),
                    Forms\Components\TextInput::make('subject')
                        ->label('الموضوع')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('from')
                        ->label('المرسل')
                        ->datalist(function () {
                            $bankAccounts = BankAccount::pluck('bank_name')->toArray();
                            $incomingFrom = Incoming::pluck('from')->toArray();
                            return array_values(array_unique(array_merge($bankAccounts, $incomingFrom)));
                        })
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('number')
                        ->label('الرقم الإشاري')
                        ->required()
                        ->default(function (string $operation) {
                            if ($operation === 'create') {
                                $latest = Incoming::latest()->first();
                                $nextId = $latest ? $latest->id + 1 : 1;
                                return $nextId . '-' . Carbon::now()->format('Y');
                            }
                            return null;
                        })
                        ->maxLength(255),
                    Forms\Components\FileUpload::make('attachments')
                        ->label('المرفقات')
                        ->multiple()
                        ->columnSpanFull()
                        ->acceptedFileTypes([
                            'application/pdf',
                            'image/jpeg',
                            'image/png',
                        ]),
                ])->columns(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label('الرقم الإشاري')
                    ->sortable()
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('from')
                    ->label('المرسل')
                    ->sortable()
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('company.arabic_name')
                    ->label('الشركة')
                    ->searchable()
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject')
                    ->label('الموضوع')
                    ->sortable()
                    ->alignCenter()
                    ->placeholder('-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListIncomings::route('/'),
            'create' => Pages\CreateIncoming::route('/create'),
            'edit' => Pages\EditIncoming::route('/{record}/edit'),
        ];
    }
}

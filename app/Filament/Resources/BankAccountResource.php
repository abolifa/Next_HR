<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BankAccountResource\Pages;
use App\Models\BankAccount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BankAccountResource extends Resource
{
    public static ?string $label = 'حساب';
    public static ?string $pluralLabel = 'حسابات المصارف';
    protected static ?string $model = BankAccount::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\Select::make('company_id')
                        ->label('الشركة')
                        ->required()
                        ->relationship('company', 'arabic_name')
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->placeholder('اختر الشركة'),
                    Forms\Components\TextInput::make('bank_name')
                        ->label('اسم المصرف')
                        ->required()
                        ->datalist(fn() => BankAccount::query()->pluck('bank_name', 'id'))
                        ->distinct()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('branch_name')
                        ->label('اسم الفرع')
                        ->required()
                        ->maxLength(255)
                        ->distinct()
                        ->datalist(fn() => BankAccount::query()->pluck('branch_name', 'id')),
                    Forms\Components\TextInput::make('account_number')
                        ->label('رقم الحساب')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('account_type')
                        ->label('نوع الحساب')
                        ->options([
                            'general' => 'حساب جاري',
                            'credit_card' => 'حساب بطاقة',
                            'other' => 'أخرى',
                        ])
                        ->default('general')
                        ->native(false)
                        ->required(),
                    Forms\Components\Select::make('currency')
                        ->label('العملة')
                        ->options([
                            'LYD' => 'دينار ليبي',
                            'USD' => 'دولار أمريكي',
                            'EUR' => 'يورو',
                            'AED' => 'درهم إماراتي',
                        ])
                        ->default('LYD')
                        ->native(false)
                        ->required(),
                ])->columns()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('company.arabic_name')
                    ->label('الشركة')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('bank_name')
                    ->label('المصرف')
                    ->alignCenter()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('branch_name')
                    ->label('الفرع')
                    ->alignCenter()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('account_number')
                    ->label('رقم الحساب')
                    ->alignCenter()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('account_type')
                    ->label('نوع الحساب')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'general' => 'حساب جاري',
                        'credit_card' => 'حساب بطاقة',
                        'other' => 'أخرى',
                    })->searchable()
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency')
                    ->label('العملة')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'LYD' => 'دينار ليبي',
                        'USD' => 'دولار أمريكي',
                        'EUR' => 'يورو',
                        'AED' => 'درهم إماراتي',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'LYD' => 'success',
                        'USD' => 'info',
                        'EUR' => 'warning',
                        'AED' => 'rose',
                        default => 'gray',
                    })
                    ->alignCenter()
                    ->sortable()
                    ->searchable(),
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
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([]);
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
            'index' => Pages\ListBankAccounts::route('/'),
            'create' => Pages\CreateBankAccount::route('/create'),
            'edit' => Pages\EditBankAccount::route('/{record}/edit'),
        ];
    }
}

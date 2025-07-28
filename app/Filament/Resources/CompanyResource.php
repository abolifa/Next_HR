<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\CompanyResource\RelationManagers\DocumentsRelationManager;
use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CompanyResource extends Resource
{
    public static ?string $label = 'شركة';
    public static ?string $pluralLabel = 'الشركات';
    protected static ?string $model = Company::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('arabic_name')
                        ->label('الإسم بالعربية')
                        ->required()
                        ->rule([
                            'regex:/^[\p{Arabic} ]+$/u',
                        ])
                        ->validationMessages([
                            'regex' => 'الإسم بالعربية يجب أن يحتوي على حروف عربية فقط.',
                        ])
                        ->maxLength(255),
                    Forms\Components\TextInput::make('english_name')
                        ->label('الإسم بالإنجليزية')
                        ->required()
                        ->rule([
                            'regex:/^[a-zA-Z ]+$/u',
                        ])
                        ->validationMessages([
                            'regex' => 'الإسم بالإنجليزية يجب أن يحتوي على حروف إنجليزية'
                        ])
                        ->maxLength(255),
                    Forms\Components\TextInput::make('slogan')
                        ->label('وصف الشركة')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->label('البريد الإلكتروني')
                        ->email()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('phone')
                        ->label('رقم الهاتف')
                        ->tel()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('address')
                        ->label('العنوان')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('website')
                        ->label('الموقع الإلكتروني')
                        ->maxLength(255),
                ])->columns(),
                Forms\Components\Section::make([
                    Forms\Components\FileUpload::make('logo')
                        ->label('شعار الشركة')
                        ->image()
                        ->imageEditor()
                        ->directory('company-logos'),
                    Forms\Components\FileUpload::make('letterhead')
                        ->label('قالب الرسائل')
                        ->image()
                        ->imageEditor()
                        ->directory('company-letterheads'),
                ])->columns(),
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('ceo_name')
                        ->label('المدير العام')
                        ->maxLength(255),
                    Forms\Components\Repeater::make('employees')
                        ->label('الموظفين')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('الإسم')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('email')
                                ->label('البريد الإلكتروني')
                                ->email()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('phone')
                                ->label('رقم الهاتف')
                                ->tel()
                                ->maxLength(255),
                        ])->nullable()
                        ->columns(3)
                        ->collapsible()
                        ->defaultItems(0)
                        ->reorderable()
                        ->columnSpanFull(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label('الشعار')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('arabic_name')
                    ->label('الإسم العربي')
                    ->alignCenter()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('english_name')
                    ->label('الإسم الإنجليزي')
                    ->alignCenter()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('slogan')
                    ->label('وصف الشركة')
                    ->alignCenter()
                    ->placeholder('-')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->placeholder('-')
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('الهاتف')
                    ->placeholder('-')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('العنوان')
                    ->placeholder('-')
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('ceo_name')
                    ->label('المفوض')
                    ->placeholder('-')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('employees_count')
                    ->label('الأعضاء')
                    ->alignCenter()
                    ->getStateUsing(fn($record) => is_array($record->employees) ? count($record->employees) + 1 : 0)
                    ->badge()
                    ->color('info')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('website')
                    ->label('الموقع الإلكتروني')
                    ->placeholder('-')
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\IconColumn::make('letterhead')
                    ->label('قالب الرسائل')
                    ->boolean(fn($record) => !is_null($record->letterhead))
                    ->trueIcon('heroicon-o-check')
                    ->falseIcon('heroicon-o-x')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')
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
            DocumentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}

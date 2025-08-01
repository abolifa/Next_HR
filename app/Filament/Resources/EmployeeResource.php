<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'fas-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\FileUpload::make('photo')
                        ->label('الصورة الشخصية')
                        ->image()
                        ->avatar()
                        ->columnSpanFull()
                        ->alignCenter()
                        ->disk('public')
                        ->directory('employees')
                        ->visibility('public'),
                    Forms\Components\Select::make('company_id')
                        ->label('الشركة')
                        ->relationship('company', 'id')
                        ->required(),
                    Forms\Components\TextInput::make('name')
                        ->label('الاسم')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('phone')
                        ->label('رقم الهاتف')
                        ->tel()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->label('البريد الإلكتروني')
                        ->email()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('license')
                        ->label('رقم الرخصة')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('address')
                        ->label('العنوان')
                        ->maxLength(255),
                    Forms\Components\Select::make('gender')
                        ->options([
                            'male' => 'ذكر',
                            'female' => 'أنثى',
                        ])
                        ->label('الجنس')
                        ->native(false),
                    Forms\Components\DatePicker::make('date_of_birth')
                        ->label('تاريخ الميلاد')
                        ->displayFormat('d/m/Y'),
                    Forms\Components\Select::make('marital_status')
                        ->label('الحالة الاجتماعية')
                        ->options([
                            'single' => 'أعزب',
                            'married' => 'متزوج',
                        ])
                        ->native(false),
                    Forms\Components\Select::make('role')
                        ->label('الدور الوظيفي')
                        ->options([
                            'employee' => 'موطف',
                            'accountant' => 'محاسب',
                            'driver' => 'سائق',
                            'manager' => 'مدير',
                            'sales' => 'مندوب مبيعات',
                            'hr' => 'موارد بشرية',
                            'supervisor' => 'مشرف',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('password')
                        ->label('كلمة المرور')
                        ->type('password')
                        ->password()
                        ->required()
                        ->maxLength(255),
                ])->columns(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('license')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender'),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('marital_status'),
                Tables\Columns\TextColumn::make('role'),
                Tables\Columns\TextColumn::make('photo')
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}

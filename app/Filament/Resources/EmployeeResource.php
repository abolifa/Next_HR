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

    protected static ?string $label = 'موظف';
    protected static ?string $pluralLabel = 'الموظفين';

    protected static ?string $navigationGroup = 'الموارد';


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
                        ->relationship('company', 'arabic_name')
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
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable(),
                Tables\Columns\TextColumn::make('company.arabic_name')
                    ->label('الشركة')
                    ->searchable()
                    ->badge()
                    ->color('rose')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('الهاتف')
                    ->alignCenter()
                    ->sortable()
                    ->searchable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('البريد')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('license')
                    ->label('الرخصة')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('العنوان')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->label('الجنس')
                    ->alignCenter()
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'male' => 'success',
                        'female' => 'rose',
                    })
                    ->formatStateUsing(fn($state) => match ($state) {
                        'male' => 'ذكر',
                        'female' => 'أنثى',
                    }),
//                Tables\Columns\TextColumn::make('date_of_birth')
//                    ->label('تاريخ الميلاد')
//                    ->date('d/m/Y')
//                    ->alignCenter()
//                    ->sortable(),
//                Tables\Columns\TextColumn::make('marital_status')
//                    ->label('الحالة الاجتماعية')
//                    ->alignCenter()
//                    ->formatStateUsing(fn($state) => match ($state) {
//                        'single' => 'أعزب',
//                        'married' => 'متزوج',
//                    }),
                Tables\Columns\TextColumn::make('role')
                    ->label('الدور الوظيفي')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'employee' => 'موطف',
                        'accountant' => 'محاسب',
                        'driver' => 'سائق',
                        'manager' => 'مدير',
                        'sales' => 'مندوب مبيعات',
                        'hr' => 'موارد بشرية',
                        'supervisor' => 'مشرف',
                    })->badge()
                    ->alignCenter()
                    ->color(fn($state) => match ($state) {
                        'employee' => 'primary',
                        'accountant' => 'success',
                        'driver' => 'warning',
                        'manager' => 'danger',
                        'sales' => 'info',
                        'hr' => 'rose',
                        'supervisor' => 'cyan',
                    }),
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

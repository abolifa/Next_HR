<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\Pages;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $label = 'مركبة';
    protected static ?string $pluralLabel = 'الأصول والمركبات';

    protected static ?string $navigationIcon = 'fas-truck';
    protected static ?string $navigationGroup = 'الموارد';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\Select::make('company_id')
                        ->label('الشركة')
                        ->native(false)
                        ->relationship('company', 'arabic_name')
                        ->required(),
                    Forms\Components\TextInput::make('name')
                        ->label('اسم المركبة')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('type')
                        ->label('نوع المركبة')
                        ->datalist(fn() => Vehicle::distinct()->pluck('type'))
                        ->maxLength(255),
                    Forms\Components\TextInput::make('model')
                        ->label('موديل المركبة')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('plate_number')
                        ->label('رقم اللوحة')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('chassis_number')
                        ->label('رقم الهيكل')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('registration_number')
                        ->label('رقم التسجيل')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('color')
                        ->label('اللون')
                        ->maxLength(255),
                    Forms\Components\DatePicker::make('acquisition_date')
                        ->label('تاريخ الشراء')
                        ->required(),
                    Forms\Components\DatePicker::make('insurance_expiry_date')
                        ->label('تاريخ انتهاء التأمين')
                        ->required(),
                    Forms\Components\DatePicker::make('technical_inspection_due')
                        ->label('تاريخ الفحص الفني')
                        ->required(),
                    Forms\Components\TextInput::make('mileage')
                        ->label('المسافة المقطوعة (كم)')
                        ->numeric(),
                    Forms\Components\Select::make('status')
                        ->label('الحالة')
                        ->options([
                            'active' => 'نشط',
                            'under_maintenance' => 'تحت الصيانة',
                            'out_of_service' => 'خارج الخدمة',
                        ])
                        ->required(),
                    Forms\Components\Select::make('assigned_to_employee_id')
                        ->label('الموظف المعين')
                        ->native(false)
                        ->relationship('assignedTo', 'name'),
                    Forms\Components\FileUpload::make('attachments')
                        ->label('المرفقات')
                        ->multiple()
                        ->disk('public')
                        ->directory('vehicles/attachments')
                        ->columnSpanFull(),
                ])->columns(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم المركبة')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('company.arabic_name')
                    ->label('الشركة')
                    ->searchable()
                    ->badge()
                    ->alignCenter()
                    ->color('rose')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('نوع المركبة')
                    ->sortable()
                    ->badge()
                    ->color('cyan')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('model')
                    ->label('الموديل')
                    ->sortable()
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('plate_number')
                    ->label('رقم اللوحة')
                    ->sortable()
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('chassis_number')
                    ->label('رقم الهيكل')
                    ->sortable()
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('registration_number')
                    ->label('رقم التسجيل')
                    ->sortable()
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('color')
                    ->label('اللون')
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('acquisition_date')
                    ->label('تاريخ الشراء')
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('insurance_expiry_date')
                    ->label('انتهاء التأمين')
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('technical_inspection_due')
                    ->label('الفحص الفني')
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('mileage')
                    ->label('العداد')
                    ->alignCenter()
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'active' => 'نشط',
                        'under_maintenance' => 'تحت الصيانة',
                        'out_of_service' => 'خارج الخدمة',
                    })
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'active' => 'success',
                        'under_maintenance' => 'warning',
                        'out_of_service' => 'danger',
                    })
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assignedTo.name')
                    ->label('الموظف المعين')
                    ->numeric()
                    ->searchable()
                    ->badge()
                    ->alignCenter()
                    ->sortable(),
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
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }
}

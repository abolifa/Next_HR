<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LetterOfCreditResource\Pages;
use App\Models\BankAccount;
use App\Models\LetterOfCredit;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LetterOfCreditResource extends Resource
{
    public static ?string $label = 'إعتماد';
    public static ?string $pluralLabel = 'الإعتمادات المستندية';
    protected static ?string $model = LetterOfCredit::class;
    protected static ?string $navigationIcon = 'fas-money-bill-wave';

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
                        ->reactive()
                        ->required(),
                    Forms\Components\Select::make('bank_account_id')
                        ->label('حساب المصرف')
                        ->options(function (callable $get) {
                            $companyId = $get('company_id');

                            return BankAccount::where('company_id', $companyId)
                                ->get()
                                ->mapWithKeys(function ($account) {
                                    $type = match ($account->account_type) {
                                        'general' => 'حساب جاري',
                                        'credit_card' => 'حساب بطاقة',
                                        'other' => 'أخرى',
                                        default => $account->type,
                                    };

                                    $currency = match ($account->currency) {
                                        'LYD' => 'دينار ليبي',
                                        'USD' => 'دولار أمريكي',
                                        'EUR' => 'يورو',
                                        'AED' => 'درهم إماراتي',
                                        default => $account->currency,
                                    };

                                    return [
                                        $account->id => "$account->account_number - $type - $currency"
                                    ];
                                });
                        })
                        ->native(false)
                        ->disabled(fn($get) => $get('company_id') === null)
                        ->required(),
                    Forms\Components\TextInput::make('lc_number')
                        ->label('رقم الاعتماد المستندي')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('amount')
                        ->label('قيمة الاعتماد')
                        ->numeric(),
                    Forms\Components\DatePicker::make('issue_date')
                        ->label('تاريخ الإصدار')
                        ->displayFormat('d/m/Y'),
                    Forms\Components\DatePicker::make('expiry_date')
                        ->label('تاريخ الانتهاء')
                        ->displayFormat('d/m/Y'),

                    Forms\Components\Select::make('currency')
                        ->label('العملة')
                        ->options([
                            'USD' => 'دولار أمريكي',
                            'EUR' => 'يورو',
                            'GBP' => 'جنيه إسترليني',
                        ])
                        ->native(false)
                        ->required(),
                    Forms\Components\ToggleButtons::make('status')
                        ->label('حالة الاعتماد')
                        ->options([
                            'draft' => 'درفت (مسودة)',
                            'issued' => 'تم الإصدار',
                            'completed' => 'مكتمل',
                            'cancelled' => 'ملغي',
                        ])
                        ->inline()
                        ->grouped()
                        ->required(),
                    Forms\Components\TextInput::make('beneficiary_name')
                        ->label('اسم المستفيد')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('beneficiary_address')
                        ->label('عنوان المستفيد')
                        ->maxLength(255),
                    Forms\Components\Textarea::make('description')
                        ->label('الوصف')
                        ->columnSpanFull(),
                ])->columns(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company.arabic_name')
                    ->label('الشركة')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lc_number')
                    ->label('رقم الاعتماد')
                    ->alignCenter()
                    ->copyable()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('bankAccount.account_number')
                    ->label('حساب المصرف')
                    ->searchable()
                    ->alignCenter()
                    ->badge()
                    ->numeric()
                    ->toggleable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('قيمة الاعتماد')
                    ->numeric()
                    ->placeholder('-')
                    ->searchable()
                    ->alignCenter()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency')
                    ->label('العملة')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'USD' => 'دولار أمريكي',
                        'EUR' => 'يورو',
                        'GBP' => 'جنيه إسترليني',
                        default => $state,
                    })
                    ->sortable()
                    ->badge()
                    ->searchable()
                    ->alignCenter()
                    ->color(fn($state) => match ($state) {
                        'USD' => 'info',
                        'EUR' => 'success',
                        'GBP' => 'danger',
                        default => 'gray',
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('beneficiary_name')
                    ->label('اسم المستفيد')
                    ->alignCenter()
                    ->badge()
                    ->color('gray')
                    ->searchable(),
                Tables\Columns\TextColumn::make('beneficiary_address')
                    ->label('عنوان المستفيد')
                    ->alignCenter()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('issue_date')
                    ->label('تاريخ الإصدار')
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('expiry_date')
                    ->label('تاريخ الانتهاء')
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->color(function ($state) {
                        $expiryDate = Carbon::parse($state);
                        if ($expiryDate->isCurrentMonth()) {
                            return 'warning';
                        } elseif ($expiryDate->isFuture()) {
                            return 'success';
                        } else {
                            return 'danger';
                        }
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('حالة الاعتماد')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'draft' => 'درفت (مسودة)',
                        'issued' => 'تم الإصدار',
                        'completed' => 'مكتمل',
                        'cancelled' => 'ملغي',
                    })
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'draft' => 'warning',
                        'issued' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
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
            'index' => Pages\ListLetterOfCredits::route('/'),
            'create' => Pages\CreateLetterOfCredit::route('/create'),
            'edit' => Pages\EditLetterOfCredit::route('/{record}/edit'),
        ];
    }
}

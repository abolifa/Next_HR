<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OutGoingResource\Pages;
use App\Helpers\LetterTitleSetter;
use App\Models\BankAccount;
use App\Models\Company;
use App\Models\LetterOfCredit;
use App\Models\OutGoing;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OutGoingResource extends Resource
{
    public static ?string $label = 'رسالة';
    public static ?string $pluralLabel = 'البريد الصادر';
    protected static ?string $model = OutGoing::class;
    protected static ?string $navigationIcon = 'heroicon-s-document-arrow-up';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Group::make([
                            Forms\Components\Section::make([
                                Forms\Components\Select::make('company_id')
                                    ->label('الشركة')
                                    ->relationship('company', 'arabic_name')
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->native(false)
                                    ->afterStateUpdated(
                                        function ($state, $get, $set) {
                                            $company = Company::find($state);
                                            if ($company) {
                                                $set('ceo', $company->ceo_name);
                                            } else {
                                                $set('ceo', null);
                                            }
                                        }
                                    )
                                    ->required(),
                                Forms\Components\Select::make('subject')
                                    ->label('نوع الخطاب')
                                    ->options([
                                        'letter' => 'خطاب',
                                        'invoice' => 'فاتورة',
                                        'contract' => 'عقد',
                                        'lc_edit' => 'تعديل إعتماد',
                                        'other' => 'غير ذلك',
                                    ])
                                    ->reactive()
                                    ->native(false)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, $get, $set) {
                                        $title = LetterTitleSetter::generate($state, $get('company_id'));
                                        if ($title) {
                                            $set('title', $title);
                                        }
                                    })
                                    ->required(),
                                Forms\Components\TextInput::make('receiver')
                                    ->label('المستلم')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->datalist(function ($get) {
                                        if ($get('subject') == 'lc_edit') {
                                            return BankAccount::where('company_id', $get('company_id'))->pluck('bank_name', 'id');
                                        } else {
                                            return null;
                                        }
                                    })
                                    ->reactive()
                                    ->maxLength(255),
                                Forms\Components\DatePicker::make('date')
                                    ->label('تاريخ المراسلة')
                                    ->default(now())
                                    ->displayFormat('d/m/Y')
                                    ->required(),
                                Forms\Components\TextInput::make('number')
                                    ->label('الرقم الإشاري')
                                    ->required()
                                    ->default(function (string $operation) {
                                        if ($operation === 'create') {
                                            $latest = OutGoing::latest()->first();
                                            $nextId = $latest ? $latest->id + 1 : 1;
                                            return $nextId . '-' . Carbon::now()->format('Y');
                                        }
                                        return null;
                                    })
                                    ->maxLength(255),
                            ]),
                            Forms\Components\Section::make([
                                Forms\Components\TextInput::make('title')
                                    ->label('العنوان')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->reactive()
                                    ->columnSpanFull()
                                    ->default(function ($get) {
                                        if ($get('subject') == 'lc_edit') {
                                            return 'تعديل اعتماد رقم' . LetterOfCredit::where('company_id', $get('company_id'))->latest()->first()->lc_number;
                                        } else {
                                            return '';
                                        }
                                    })
                                    ->maxLength(255),
                                Forms\Components\RichEditor::make('body')
                                    ->label('نص الرسالة')
                                    ->live(onBlur: true)
                                    ->reactive()
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('ceo')
                                    ->label('مفوض الشركة')
                                    ->live(onBlur: true)
                                    ->reactive()
                                    ->maxLength(255),
                            ]),
                            Forms\Components\Section::make([
                                Forms\Components\FileUpload::make('attachments')
                                    ->label('المرفقات')
                                    ->multiple()
                                    ->directory('outgoing-attachments')
                                    ->disk('public')
                                    ->acceptedFileTypes([
                                        'application/pdf',
                                        'image/jpeg',
                                        'image/png',
                                    ]),
                            ]),
                        ]),

                        Forms\Components\Group::make([
                            Forms\Components\Section::make('المعاينة')
                                ->schema([
                                    Forms\Components\View::make('filament.outgoing.preview')
                                        ->reactive()
                                        ->viewData(fn(callable $get) => [
                                            'issue_number' => $get('number') ?? '',
                                            'receiver' => $get('receiver') ?? '',
                                            'subject' => $get('subject') ?? '',
                                            'body' => $get('body') ?? '',
                                            'title' => $get('title') ?? '',
                                            'letterhead' => Company::find($get('company_id'))->letterhead ?? null,
                                            'ceo_name' => $get('ceo') ?? '',
                                        ]),
                                ])
                                ->extraAttributes([
                                    'style' => 'position: sticky; top: 0px; max-height: calc(100vh - 2rem); overflow-y: hidden;'
                                ]),
                        ]),
                    ])
                    ->columns()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('number', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label('الرقم الإشاري')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject')
                    ->label('الموضوع')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'letter' => 'خطاب',
                        'invoice' => 'فاتورة',
                        'contract' => 'عقد',
                        'lc_edit' => 'تعديل إعتماد',
                        'other' => 'غير ذلك',
                    })->badge()
                    ->sortable()
                    ->color(fn($state) => match ($state) {
                        'letter' => 'success',
                        'invoice' => 'rose',
                        'contract' => 'info',
                        'lc_edit' => 'warning',
                        'other' => 'gray',
                        default => 'neutral',
                    }),
                Tables\Columns\TextColumn::make('company.arabic_name')
                    ->label('الشركة')
                    ->numeric()
                    ->searchable()
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('العنوان')
                    ->sortable()
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('receiver')
                    ->label('المستلم')
                    ->alignCenter()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('تاريخ المراسلة')
                    ->date('d/m/Y')
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
                Tables\Actions\Action::make('view_letter')
                    ->label('عرض الخطاب')
                    ->icon('heroicon-o-eye')
                    ->url(fn(OutGoing $record) => static::getUrl('view-letter', ['record' => $record])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListOutGoings::route('/'),
            'create' => Pages\CreateOutGoing::route('/create'),
            'edit' => Pages\EditOutGoing::route('/{record}/edit'),
            'view-letter' => Pages\ViewLetter::route('/{record}/view-letter'),
        ];
    }
}

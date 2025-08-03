<?php

namespace App\Filament\Widgets;

use App\Models\Document;
use Carbon\Carbon;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class DocumentAlerts extends BaseWidget
{
    protected static ?string $heading = 'المستندات التي ستنتهي صلاحيتها خلال 60 يومًا';
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Document::query()
                    ->with('company')
                    ->where('expiry_date', '<=', now()->addDays(60))
                    ->where('expiry_date', '>', now())
                    ->orderBy('expiry_date')
                    ->select([
                        'id',
                        'paper_type',
                        'company_id',
                        'document_number',
                        'issue_date',
                        'expiry_date',
                    ])->limit(3)
            )
            ->paginated(false)
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('id')
                    ->label('رقم')
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                TextColumn::make('company.arabic_name')
                    ->label('الشركة')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('paper_type')
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
                TextColumn::make('issue_date')
                    ->label('تاريخ الإصدار')
                    ->alignCenter()
                    ->searchable()
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('expiry_date')
                    ->label('تاريخ الانتهاء')
                    ->alignCenter()
                    ->date('d/m/Y')
                    ->color(function ($state) {
                        if (!$state) {
                            return 'gray';
                        }

                        $expiryDate = Carbon::parse($state);

                        if ($expiryDate->isPast()) {
                            return 'danger';
                        }

                        if ($expiryDate->lte(now()->addDays(30))) {
                            return 'warning';
                        }

                        return 'success';
                    })
                    ->sortable(),
            ])
            ->actions([
                Action::make('edit')
                    ->label('تعديل')
                    ->icon('fas-edit')
                    ->url(fn(Document $record): string => route('filament.admin.resources.documents.edit', $record->id))
                    ->color('primary')
                    ->requiresConfirmation(),
            ]);
    }
}

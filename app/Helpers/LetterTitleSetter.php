<?php

namespace App\Helpers;

use App\Models\LetterOfCredit;

class LetterTitleSetter
{
    /**
     * Generate title based on subject and company_id.
     *
     * @param string|null $subject
     * @param int|null $companyId
     * @return string
     */
    public static function generate(?string $subject, ?int $companyId): string
    {
        switch ($subject) {
            case 'lc_edit':
                return self::generateLcEditTitle($companyId);
            case 'invoice':
                return 'فاتورة جديدة';
            case 'contract':
                return 'عقد جديد';

            default:
                return '';
        }
    }

    /**
     * Handle lc_edit subject type.
     *
     * @param int|null $companyId
     * @return string
     */
    protected static function generateLcEditTitle(?int $companyId): string
    {
        if (!$companyId) {
            return 'تعديل اعتماد';
        }
        $lcNumber = LetterOfCredit::where('company_id', $companyId)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->latest()
            ->value('lc_number');
        return $lcNumber ? 'تعديل اعتماد رقم ' . $lcNumber : 'تعديل اعتماد';
    }
}

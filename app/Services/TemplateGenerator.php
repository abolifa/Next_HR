<?php

namespace App\Services;

use App\Models\LetterOfCredit;
use Filament\Forms\Get;

class TemplateGenerator
{
    public static function generateBody(Get $get): string
    {
        $subject = $get('subject');
        $receiver = $get('receiver');
        $title = $get('title');
        $companyId = $get('company_id');

        $lc = null;

        if ($subject === 'lc_edit' && $companyId) {
            $lc = LetterOfCredit::where('company_id', $companyId)
                ->orderBy('id', 'desc')
                ->first();
        }

        return view('templates.outgoing', [
            'subject' => $subject ?? '',
            'receiver' => $receiver ?? '',
            'title' => $title ?? '',
            'lc' => $lc,
        ])->render();
    }
}

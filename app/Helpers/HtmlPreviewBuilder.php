<?php

namespace App\Helpers;

class HtmlPreviewBuilder
{
    /**
     * Build a combined HTML string for preview.
     *
     * @param array $attachments List of file paths (relative to storage/app/public)
     * @param string|null $mainViewHtml Optional pre-rendered HTML to show first
     * @return string Complete HTML content
     */
    public static function build(array $attachments = [], ?string $mainViewHtml = null): string
    {
        // Start with main view or a placeholder
        $html = $mainViewHtml ?? '<div style="padding:50px; text-align:center;"><h2>لا توجد رسالة</h2></div>';

        // Append attachments
        foreach ($attachments as $file) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $fileUrl = asset('storage/' . $file);

            if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                $html .= <<<HTML
                <div style="page-break-before:always; text-align:center; padding:20px;">
                    <img src="{$fileUrl}" style="max-width:90%; margin:20px auto; display:block;">
                </div>
                HTML;
            } elseif ($ext === 'pdf') {
                $html .= <<<HTML
                <div style="page-break-before:always; height: 800px; margin-top:20px;">
                    <iframe src="{$fileUrl}" style="width:100%; height:100%; border:none;"></iframe>
                </div>
                HTML;
            }
        }

        return $html;
    }

    /**
     * Encode HTML as data URI for iframe.
     *
     * @param string $html
     * @return string
     */
    public static function encodeAsDataUri(string $html): string
    {
        return 'data:text/html;base64,' . base64_encode($html);
    }
}

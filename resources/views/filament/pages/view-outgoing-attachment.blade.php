@php
    use App\Helpers\HtmlPreviewBuilder;

    $attachments = $this->document->attachments ?? [];

    // Optional main view (only render if you have a document)
    $mainViewHtml = $this->document
        ? view('filament.previews.outgoing-full-preview', [
            'receiver'   => $this->document->receiver,
            'title'      => $this->document->title,
            'body'       => $this->document->body,
            'letterhead' => $this->document->company?->letterhead,
            'ceo_name'   => $this->document->company?->ceo_name,
        ])->render()
        : null;

    $combinedHtml = HtmlPreviewBuilder::build($attachments, $mainViewHtml);
    $encodedHtml  = HtmlPreviewBuilder::encodeAsDataUri($combinedHtml);
@endphp

<x-filament-panels::page>
    @if($this->document || count($attachments))
        <div class="relative w-full" style="height: calc(95vh - 180px);">
            <iframe
                id="previewIframe"
                wire:ignore
                src="{{ $encodedHtml }}"
                style="width: 100%; height: 100%; border: none;">
            </iframe>
        </div>
    @else
        <p>لا توجد مرفقات لهذا المستند.</p>
    @endif

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('trigger-print-preview', () => {
                const iframe = document.getElementById('previewIframe');
                if (iframe?.contentWindow) {
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                } else {
                    alert("Preview not ready!");
                }
            });
        });
    </script>
</x-filament-panels::page>

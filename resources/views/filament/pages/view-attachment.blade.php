@php
    use Barryvdh\DomPDF\Facade\Pdf;use Illuminate\Support\Facades\Storage;

    $imageFiles = [];
    $pdfFiles = [];

if ($this->document && is_array($this->document->attachments)) {
        foreach ($this->document->attachments as $file) {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                $imageFiles[] = $file;
            } elseif ($extension === 'pdf') {
                $pdfFiles[] = $file;
            }
        }
    }

    $imagePdfUrl = null;
    if (count($imageFiles)) {
        $pdfContent = Pdf::loadView('pdf.inline-images', ['images' => $imageFiles])->output();
        $tempDir = 'temp_pdfs';
        $fileName = 'attachments_' . $this->document->id . '.pdf';
        Storage::disk('public')->put("$tempDir/$fileName", $pdfContent);
        $imagePdfUrl = asset("storage/$tempDir/$fileName");
    }
@endphp

<x-filament-panels::page>
    @if($this->document && (count($imageFiles) || count($pdfFiles)))
        <div class="flex flex-col space-y-6">
            @if($imagePdfUrl)
                <div class="relative w-full" style="height: calc(95vh - 180px);">
                    <iframe
                        src="{{ $imagePdfUrl }}"
                        style="width: 100%; height: 100%; border: none;"
                        class="absolute top-0 left-0 w-full h-full">
                    </iframe>
                </div>
            @endif

            {{-- Show original PDF files --}}
            @foreach($pdfFiles as $pdf)
                <div class="relative w-full" style="height: calc(95vh - 180px);">
                    <iframe
                        src="{{ asset('storage/'.$pdf) }}"
                        style="width: 100%; height: 100%; border: none;"
                        class="absolute top-0 left-0 w-full h-full">
                    </iframe>
                </div>
            @endforeach
        </div>
    @else
        <p>لا توجد مرفقات لهذا المستند.</p>
    @endif
</x-filament-panels::page>

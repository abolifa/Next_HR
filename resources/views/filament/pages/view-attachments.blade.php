<x-filament-panels::page>
    @if($document && is_array($document->attachments))
        <div class="flex flex-col space-y-6">
            @foreach($document->attachments as $file)
                @php
                    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                @endphp

                @if(in_array($extension, ['jpg', 'jpeg', 'png']))
                    <div class="w-full">
                        <img src="{{ asset('storage/'.$file) }}"
                             class="w-full h-[calc(100vh-300px)] object-contain rounded-md shadow-md"
                             alt="Attachment">
                    </div>
                @elseif($extension === 'pdf')
                    <div class="relative w-full" style="height: calc(100vh - 180px);">
                        <iframe
                            src="{{ asset('storage/'.$file) }}"
                            style="width: 100%; height: 100%; border: none;"
                            class="absolute top-0 left-0 w-full h-full">
                        </iframe>
                    </div>
                @endif
            @endforeach
        </div>
    @else
        <p>لا توجد مرفقات لهذا المستند.</p>
    @endif
</x-filament-panels::page>

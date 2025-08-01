<x-filament::page>
    <iframe
        id="letter-frame"
        src="{{ route('letter.preview', $record) }}"
        style="height: calc(100vh - 250px); width: 100%; border: none; display: block;"
    ></iframe>

    <script>
        window.printIframe = function () {
            const iframe = document.getElementById('letter-frame');
            if (iframe && iframe.contentWindow) {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            } else {
                alert('لم يتم العثور على إطار الطباعة');
            }
        };
    </script>
</x-filament::page>

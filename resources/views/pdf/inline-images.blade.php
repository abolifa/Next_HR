<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>Images PDF</title>
    <style>
        @page {
            margin: 0;
        }

        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }

        .full-page-image {
            width: 100%;
            object-fit: contain;
            page-break-after: always;
        }
    </style>
</head>
<body>
@foreach($images as $image)
    <img src="{{ public_path('storage/' . $image) }}" class="full-page-image" alt="Full Image">
@endforeach
</body>
</html>

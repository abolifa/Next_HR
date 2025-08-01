<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>

    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            background: #f3f3f3;
            font-family: 'Cairo', sans-serif;
        }

        .page {
            position: relative;
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            background-size: cover;
            background-repeat: no-repeat;
            transform: scale(1);
            background-position: top center;
        }

        .content {
            position: relative;
            padding: 60mm 20mm 20mm 20mm;
            box-sizing: border-box;
        }

        p, h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
            line-height: 1.4;
        }

        h3 {
            font-weight: bold;
        }

        .text-bold {
            font-weight: bold;
        }

        .section-spacing {
            margin-top: 30px;
        }

        .message-body {
            margin-top: 20px;
        }

        .center-text-block {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 50px;
            text-align: center;
        }

        .right-text-block {
            display: flex;
            justify-content: flex-end;
            margin-top: 50px;
        }

        .right-text-inner {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-weight: bold;
        }

        h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
            line-height: 1.4;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="page" style="background-image: url('{{ $letterhead ? asset('storage/' . $letterhead) : '' }}');">
    <div class="content">
        <h3>السادة /{{ $receiver ?? '__________________' }}</h3>
        <h3>الموضوع /{{ $title ?? '__________________' }}</h3>

        <div class="section-spacing">
            <p class="text-bold">بعد التحية،،،</p>
        </div>

        <div class="message-body">
            {!! $body ?? '' !!}
        </div>

        <div class="center-text-block">
            <p class="text-bold">شاكرين حسن تعاونكم معنا</p>
            <p class="text-bold">والسلام عليكم ورحمة الله وبركاته</p>
        </div>

        <div class="right-text-block">
            <div class="right-text-inner">
                <h3>مفوض الشركة</h3>
                <p>{{ $ceo_name }}</p>
            </div>
        </div>
    </div>
</div>
</body>
</html>

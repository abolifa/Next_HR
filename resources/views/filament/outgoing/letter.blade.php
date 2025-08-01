@php use Carbon\Carbon; @endphp
    <!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>معاينة الخطاب</title>
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
            direction: rtl;
        }

        * {
            box-sizing: border-box;
            margin: 0;
        }

        .wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .page {
            width: 210mm;
            height: 297mm;
            background-color: white;
            background-image: url('{{ $letterhead ? asset('storage/' . $letterhead) : '' }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            position: relative;
            padding: 40mm 20mm 20mm 20mm;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            gap: 12mm;
        }

        .issue {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            font-weight: 600;
        }

        .heading {
            display: flex;
            flex-direction: column;
            gap: 5px;
            font-weight: bold;
            font-size: 16px;
        }

        .greeting {
            font-size: 15px;
        }

        .main {
            font-size: 15px;
            line-height: 1.5;
            text-align: justify;
        }

        .closing {
            font-size: 15px;
            font-weight: bold;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .signature {
            display: flex;
            flex-direction: column;
            align-items: end;
            font-size: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="wrapper">
    @php
        $chunks = str_split(strip_tags($body ?? 'نص الرسالة'), 3500);
    @endphp

    @foreach($chunks as $index => $chunk)
        <div class="page">
            @if($index === 0)
                <div class="issue">
                    <p>الرقم الإشاري / {{ $issue_number ?? '__________________' }}</p>
                    <p>التاريخ / {{ Carbon::now()->format('d/m/Y') }}</p>
                </div>

                <div class="heading">
                    <p>السادة / {{ $receiver ?? '__________________' }}</p>
                    <p>الموضوع / {{ $title ?? '__________________' }}</p>
                </div>

                <div class="greeting">
                    <p>بعد التحية،،،</p>
                </div>
            @endif

            <div class="main">
                {!! nl2br(e($chunk)) !!}
            </div>

            @if($loop->last)
                <div class="closing">
                    <p>شاكرين حسن تعاونكم معنا</p>
                    <p>والسلام عليكم ورحمة الله وبركاته</p>
                </div>

                <div class="signature">
                    <p>مفوض الشركة</p>
                    <p>{{ $ceo_name }}</p>
                </div>
            @endif
        </div>
    @endforeach
</div>
</body>
</html>

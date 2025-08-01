@php use Carbon\Carbon; @endphp
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
        height: 297mm;
        transform: scale(0.8);
        transform-origin: top right;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        box-sizing: border-box;
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center center;
    }

    .content {
        position: relative;
        padding: 40mm 20mm 20mm 20mm;
        box-sizing: border-box;
        height: 100%;
        border: 1px solid #ccc;
        display: flex;
        flex-direction: column;
        gap: 5mm;
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
        margin-top: 30px;
    }

    .greeting {
        font-size: 15px;
        margin-top: 10px;
    }

    .main {
        font-size: 15px;
        line-height: 1.5;
        margin-top: 10px;
        text-align: justify;
    }

    .closing {
        font-size: 15px;
        font-weight: bold;
        margin-top: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }

    .signature {
        margin-top: 30px;
        display: flex;
        flex-direction: column;
        align-items: end;
    }

    .signature .names {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5px;
        font-size: 15px;
        font-weight: bold;
    }
</style>

<div class="page" style="background-image: url('{{ $letterhead ? asset('storage/' . $letterhead) : '' }}');">
    <div class="content">

        <div class="issue">
            <p>الرقم الإشاري / {{ $issue_number ?? '__________________' }}</p>
            <p>التاريخ / {{ Carbon::now()->format('d/m/Y') ?? '__________________' }}</p>
        </div>


        <div class="heading">
            <h3>السادة / {{ $receiver ?? '__________________' }}</h3>
            <h3>الموضوع / {{ $title ?? '__________________' }}</h3>
        </div>

        <div class="greeting">
            <p>بعد التحية،،،</p>
        </div>

        <div class="main">
            {!! $body ?? '<p>نص الرسالة</p>' !!}
        </div>

        <div class="closing">
            <p>شاكرين حسن تعاونكم معنا</p>
            <p>والسلام عليكم ورحمة الله وبركاته</p>
        </div>

        <div class="signature">
            <div class="names">
                <h3>مفوض الشركة</h3>
                <p>{{ $ceo_name }}</p>
            </div>
        </div>
    </div>
</div>

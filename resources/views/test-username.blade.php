<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار @username</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3>اختبار ميزة @username</h3>
            </div>
            <div class="card-body">
                <h5>1. اختبار @username directive:</h5>
                <p>النتيجة: {!! @username('ahmed') !!}</p>
                
                <hr>
                
                <h5>2. اختبار format_username function:</h5>
                <p>النتيجة: {!! format_username('mohammed') !!}</p>
                
                <hr>
                
                <h5>3. اختبار linkify_mentions:</h5>
                <p>النص: "مرحباً @ahmed كيف حالك؟ هل رأيت @mohammed اليوم؟"</p>
                <p>النتيجة: {!! linkify_mentions('مرحباً @ahmed كيف حالك؟ هل رأيت @mohammed اليوم؟') !!}</p>
                
                <hr>
                
                <h5>4. اختبار @mentions directive:</h5>
                <p>النص: "السلام عليكم @ali و @sara"</p>
                <p>النتيجة: {!! @mentions('السلام عليكم @ali و @sara') !!}</p>
            </div>
        </div>
    </div>
</body>
</html>
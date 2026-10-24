<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كود الدفعة غير متوفر | ناتشورال سناكس</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; background: #f8fafc; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1.5rem; }
        .card-box { background: white; border-radius: 24px; padding: 3rem 2rem; max-width: 500px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.06); }
    </style>
</head>
<body>
    <div class="card-box">
        <div class="text-danger mb-3">
            <i class="bi bi-question-circle-fill" style="font-size: 4rem;"></i>
        </div>
        <h4 class="fw-bold mb-2">تعذر العثور على الدفعة</h4>
        <p class="text-muted small mb-4">كود الدفعة المطلوب <code class="text-dark fw-bold">{{ $batchId }}</code> غير مسجل في قاعدة البيانات الرسمية لشركة ناتشورال سناكس.</p>
        <a href="{{ route('login') }}" class="btn btn-outline-dark rounded-pill px-4">العودة للبوابة الرئيسية</a>
    </div>
</body>
</html>

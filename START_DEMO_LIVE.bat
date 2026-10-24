@echo off
title Natural Snacks Live Demo Runner
chcp 65001 >nul
echo ===================================================
echo   🌿 تشغيل نظام ناتشورال سناكس مع رابط عام فوري
echo   (Natural Snacks Live Demo & Cloudflare Tunnel)
echo ===================================================
echo.
echo [1/2] جاري تشغيل سيرفر لارافيل المحلي...
start /B C:\xampp\php\php.exe artisan serve --host=127.0.0.1 --port=8000
timeout /t 3 /nobreak >nul

echo [2/2] جاري إنشاء رابط إنترنت عام مشفر وآمن (HTTPS)...
echo.
echo ==============================================================================
echo انسخ الرابط الذي سينتهي بـ .trycloudflare.com وأرسله للعميل
echo لإنهاء المشاركة أغلق هذه النافذة في أي وقت.
echo ==============================================================================
echo.
C:\Users\ByteStore\cloudflared.exe tunnel --url http://127.0.0.1:8000
pause

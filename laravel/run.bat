@echo off
title Kape QR Menu - Menjalankan Aplikasi Instan
echo ========================================================
echo    SEDANG MENYIAPKAN DAN MENJALANKAN KAPE QR MENU...
echo    (Browser akan terbuka secara otomatis, mohon tunggu)
echo ========================================================
echo.

cd /d "%~dp0"
if exist "laravel" (
    cd laravel
)

where php >nul 2>nul
if %errorlevel% neq 0 (
    if exist "C:\xampp\php\php.exe" (
        set "PATH=C:\xampp\php;C:\xampp\mysql\bin;%PATH%"
    ) else if exist "C:\laragon\bin\php\php-8.3.36-Win32-vs16-x64\php.exe" (
        set "PATH=C:\laragon\bin\php\php-8.3.36-Win32-vs16-x64;C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin;%PATH%"
    )
)

if not exist ".env" (
    echo [*] Menyiapkan berkas lingkungan .env...
    copy .env.example .env >nul
    call php artisan key:generate --force >nul
)

where mysql >nul 2>nul
if %errorlevel% equ 0 (
    echo [*] Memastikan database db_cafe_menu tersedia di MySQL...
    mysql -u root -e "CREATE DATABASE IF NOT EXISTS db_cafe_menu;" >nul 2>nul
)

echo [*] Melakukan migrasi dan memasukkan 30 data kuliner...
call php artisan migrate:fresh --seed --force >nul 2>nul

echo [*] Membuka browser ke http://127.0.0.1:8000 ...
start http://127.0.0.1:8000

echo.
echo ========================================================
echo    WEBSITE SUDAH AKTIF DI: http://127.0.0.1:8000
echo    (Jangan tutup jendela ini selama presentasi)
echo ========================================================
echo.

php artisan serve


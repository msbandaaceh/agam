# Quick Guide: Install Font Arial & Bookman Old Style untuk dompdf

## Langkah Cepat (Production)

### 1. Siapkan File Font
Copy file font berikut dari `C:\Windows\Fonts\` (Windows) atau sumber legal lainnya:
- `arial.ttf`
- `arialbd.ttf` (Arial Bold)
- `ariali.ttf` (Arial Italic)
- `arialbi.ttf` (Arial Bold Italic)
- `bookman old style.ttf`
- `bookman old style bold.ttf`
- `bookman old style italic.ttf`
- `bookman old style bold italic.ttf`

### 2. Copy Font ke Folder Sementara
Copy semua file font ke: `application/fonts/`

### 3. Jalankan Script Install
**Via Browser:**
```
http://your-domain.com/agam/application/install_fonts.php?install=yes
```

**Via Command Line:**
```bash
cd /path/to/agam/application
php install_fonts.php
```

### 4. Verifikasi
Script akan:
- Copy font ke `vendor/dompdf/dompdf/lib/fonts/`
- Register font ke dompdf
- Menampilkan status instalasi

### 5. Hapus Script (Penting!)
Setelah instalasi berhasil, **hapus file** `application/install_fonts.php` untuk keamanan.

## Penggunaan di Template PDF

Setelah font terinstall, gunakan di CSS template PDF:

```css
<style>
    body {
        font-family: 'Arial', sans-serif;
    }
    
    .bookman {
        font-family: 'Bookman Old Style', serif;
    }
</style>
```

## Catatan Penting

- Font harus diinstall ulang setiap kali deploy ke server baru
- Folder `vendor/` tidak di-commit ke repository
- Pastikan folder `vendor/dompdf/dompdf/lib/fonts/` writable
- Library `Pdf.php` sudah dimodifikasi untuk auto-load font jika tersedia

## Troubleshooting

**Font tidak muncul:**
- Pastikan file font ada di `vendor/dompdf/dompdf/lib/fonts/`
- Check permission folder (harus writable)
- Pastikan nama font di CSS sesuai (case-sensitive)

**Error saat install:**
- Pastikan folder `application/fonts/` ada dan berisi file font
- Check permission folder `vendor/dompdf/dompdf/lib/fonts/`
- Pastikan PHP memiliki akses write ke folder tersebut


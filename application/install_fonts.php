<?php
/**
 * Script Instalasi Font untuk dompdf
 * 
 * INSTRUKSI:
 * 1. Copy file font (.ttf) ke folder application/fonts/
 * 2. Jalankan script ini melalui browser atau command line
 * 3. Hapus script ini setelah font terinstall
 * 
 * File font yang diperlukan:
 * - arial.ttf, arialbd.ttf, ariali.ttf, arialbi.ttf
 * - bookman old style.ttf, bookman old style bold.ttf, 
 *   bookman old style italic.ttf, bookman old style bold italic.ttf
 */

// Security check - hanya jalankan jika diakses langsung atau dengan parameter khusus
if (php_sapi_name() !== 'cli' && !isset($_GET['install']) && $_GET['install'] !== 'yes') {
    die('Akses ditolak. Tambahkan ?install=yes di URL atau jalankan via CLI.');
}

require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;
use Dompdf\FontMetrics;

// Path ke folder font dompdf
$fontDir = __DIR__ . '/../vendor/dompdf/dompdf/lib/fonts/';

// Pastikan folder fonts writable
if (!is_writable($fontDir)) {
    die("ERROR: Folder fonts tidak writable: {$fontDir}\n");
}

// Daftar font yang akan diinstall
$fonts = [
    [
        'family' => 'Arial',
        'style' => 'normal',
        'weight' => 'normal',
        'source' => 'ARIAL.ttf',
        'target' => 'arial.ttf'
    ],
    [
        'family' => 'Arial',
        'style' => 'normal',
        'weight' => 'bold',
        'source' => 'ARIALBD.ttf',
        'target' => 'arialbd.ttf'
    ],
    [
        'family' => 'Arial',
        'style' => 'italic',
        'weight' => 'normal',
        'source' => 'ARIALI.ttf',
        'target' => 'ariali.ttf'
    ],
    [
        'family' => 'Arial',
        'style' => 'italic',
        'weight' => 'bold',
        'source' => 'ARIALBI.ttf',
        'target' => 'arialbi.ttf'
    ],
    [
        'family' => 'Bookman Old Style',
        'style' => 'normal',
        'weight' => 'normal',
        'source' => 'BOOKOS.ttf',
        'target' => 'bookman_old_style.ttf'
    ],
    [
        'family' => 'Bookman Old Style',
        'style' => 'normal',
        'weight' => 'bold',
        'source' => 'BOOKOSB.ttf',
        'target' => 'bookman_old_style_bold.ttf'
    ],
    [
        'family' => 'Bookman Old Style',
        'style' => 'italic',
        'weight' => 'normal',
        'source' => 'BOOKOSI.ttf',
        'target' => 'bookman_old_style_italic.ttf'
    ],
    [
        'family' => 'Bookman Old Style',
        'style' => 'italic',
        'weight' => 'bold',
        'source' => 'BOOKOSBI.ttf',
        'target' => 'bookman_old_style_bold_italic.ttf'
    ]
];

// Path folder source font
$sourceDir = __DIR__ . '/fonts/';

// Buat folder fonts jika belum ada
if (!is_dir($sourceDir)) {
    mkdir($sourceDir, 0755, true);
}

$htmlOutput = php_sapi_name() !== 'cli';
if ($htmlOutput) {
    echo "<!DOCTYPE html><html><head><title>Install Font dompdf</title>";
    echo "<style>body{font-family:Arial;padding:20px;} .success{color:green;} .error{color:red;}</style>";
    echo "</head><body><h1>Instalasi Font untuk dompdf</h1>";
}

echo ($htmlOutput ? "<pre>" : "");
echo "Memulai instalasi font...\n\n";

$successCount = 0;
$errorCount = 0;

foreach ($fonts as $font) {
    $sourceFile = $sourceDir . $font['source'];
    $targetFile = $fontDir . $font['target'];
    
    if (!file_exists($sourceFile)) {
        echo ($htmlOutput ? "<span class='error'>" : "") . "SKIP: File font tidak ditemukan: {$font['source']}\n" . ($htmlOutput ? "</span>" : "");
        $errorCount++;
        continue;
    }
    
    // Copy font ke folder dompdf
    if (copy($sourceFile, $targetFile)) {
        echo ($htmlOutput ? "<span class='success'>" : "") . "✓ Font berhasil dicopy: {$font['source']} -> {$font['target']}\n" . ($htmlOutput ? "</span>" : "");
        $successCount++;
        
        // Register font ke dompdf
        try {
            $options = new Options();
            $dompdf = new Dompdf($options);
            $fontMetrics = $dompdf->getFontMetrics();
            
            $fontMetrics->registerFont([
                'family' => $font['family'],
                'style' => $font['style'],
                'weight' => $font['weight']
            ], $targetFile);
            
            echo ($htmlOutput ? "<span class='success'>" : "") . "  → Font berhasil diregister: {$font['family']} ({$font['weight']}, {$font['style']})\n" . ($htmlOutput ? "</span>" : "");
        } catch (Exception $e) {
            echo ($htmlOutput ? "<span class='error'>" : "") . "  → Warning: Gagal register font - " . $e->getMessage() . "\n" . ($htmlOutput ? "</span>" : "");
        }
    } else {
        echo ($htmlOutput ? "<span class='error'>" : "") . "✗ Gagal copy font: {$font['source']}\n" . ($htmlOutput ? "</span>" : "");
        $errorCount++;
    }
}

echo "\n";
echo "========================================\n";
echo "Ringkasan:\n";
echo "  Berhasil: {$successCount} font\n";
echo "  Gagal: {$errorCount} font\n";
echo "========================================\n\n";

if ($successCount > 0) {
    echo "Font berhasil diinstall!\n";
    echo "Sekarang Anda bisa menggunakan font Arial dan Bookman Old Style di template PDF.\n\n";
    echo "Contoh penggunaan di CSS:\n";
    echo "  font-family: 'Arial', sans-serif;\n";
    echo "  font-family: 'Bookman Old Style', serif;\n";
}

if ($htmlOutput) {
    echo "</pre>";
    echo "<p><strong>PENTING:</strong> Hapus file ini setelah instalasi selesai untuk keamanan!</p>";
    echo "</body></html>";
}


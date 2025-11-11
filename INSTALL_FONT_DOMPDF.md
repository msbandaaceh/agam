# Petunjuk Instalasi Font Arial dan Bookman Old Style untuk dompdf

## Prasyarat
1. File font yang diperlukan:
   - `arial.ttf` (Arial Regular)
   - `arialbd.ttf` (Arial Bold)
   - `ariali.ttf` (Arial Italic)
   - `arialbi.ttf` (Arial Bold Italic)
   - `bookman old style.ttf` (Bookman Old Style Regular)
   - `bookman old style bold.ttf` (Bookman Old Style Bold)
   - `bookman old style italic.ttf` (Bookman Old Style Italic)
   - `bookman old style bold italic.ttf` (Bookman Old Style Bold Italic)

   **Catatan:** File font biasanya tersedia di folder `C:\Windows\Fonts\` pada Windows atau bisa didownload dari sumber yang legal.

## Metode 1: Menggunakan Script PHP (Direkomendasikan)

### Langkah-langkah:

1. **Siapkan file font**
   - Copy semua file font (.ttf) ke folder sementara, misalnya: `application/fonts/` (buat folder jika belum ada)

2. **Buat script install font** (`application/install_fonts.php`):
   ```php
   <?php
   // Script ini hanya untuk development/production setup
   // Hapus setelah font terinstall
   
   require_once __DIR__ . '/../vendor/autoload.php';
   
   use Dompdf\FontMetrics;
   use Dompdf\Dompdf;
   use Dompdf\Options;
   
   // Path ke folder font dompdf
   $fontDir = __DIR__ . '/../vendor/dompdf/dompdf/lib/fonts/';
   
   // Daftar font yang akan diinstall
   $fonts = [
       [
           'family' => 'Arial',
           'style' => 'normal',
           'weight' => 'normal',
           'file' => 'arial.ttf'
       ],
       [
           'family' => 'Arial',
           'style' => 'normal',
           'weight' => 'bold',
           'file' => 'arialbd.ttf'
       ],
       [
           'family' => 'Arial',
           'style' => 'italic',
           'weight' => 'normal',
           'file' => 'ariali.ttf'
       ],
       [
           'family' => 'Arial',
           'style' => 'italic',
           'weight' => 'bold',
           'file' => 'arialbi.ttf'
       ],
       [
           'family' => 'Bookman Old Style',
           'style' => 'normal',
           'weight' => 'normal',
           'file' => 'bookman old style.ttf'
       ],
       [
           'family' => 'Bookman Old Style',
           'style' => 'normal',
           'weight' => 'bold',
           'file' => 'bookman old style bold.ttf'
       ],
       [
           'family' => 'Bookman Old Style',
           'style' => 'italic',
           'weight' => 'normal',
           'file' => 'bookman old style italic.ttf'
       ],
       [
           'family' => 'Bookman Old Style',
           'style' => 'italic',
           'weight' => 'bold',
           'file' => 'bookman old style bold italic.ttf'
       ]
   ];
   
   // Path folder source font
   $sourceDir = __DIR__ . '/fonts/';
   
   echo "Memulai instalasi font...\n\n";
   
   foreach ($fonts as $font) {
       $sourceFile = $sourceDir . $font['file'];
       $targetFile = $fontDir . strtolower(str_replace(' ', '_', $font['family'])) . '_' . 
                     $font['weight'] . '_' . $font['style'] . '.ttf';
       
       if (!file_exists($sourceFile)) {
           echo "ERROR: File font tidak ditemukan: {$sourceFile}\n";
           continue;
       }
       
       // Copy font ke folder dompdf
       if (copy($sourceFile, $targetFile)) {
           echo "✓ Font berhasil dicopy: {$font['file']}\n";
       } else {
           echo "✗ Gagal copy font: {$font['file']}\n";
           continue;
       }
   }
   
   echo "\nFont berhasil diinstall. Sekarang jalankan script load_font.php untuk memproses font.\n";
   echo "Atau gunakan FontLoader secara programmatic.\n";
   ?>
   ```

3. **Buat script load font** (`application/load_fonts.php`):
   ```php
   <?php
   // Script untuk memproses font yang sudah dicopy
   // Hapus setelah font terinstall
   
   require_once __DIR__ . '/../vendor/autoload.php';
   
   use Dompdf\FontMetrics;
   use Dompdf\Dompdf;
   use Dompdf\Options;
   
   $options = new Options();
   $options->set('isRemoteEnabled', true);
   $dompdf = new Dompdf($options);
   $fontMetrics = $dompdf->getFontMetrics();
   
   $fontDir = __DIR__ . '/../vendor/dompdf/dompdf/lib/fonts/';
   
   // Daftar font yang akan diproses
   $fonts = [
       'arial_normal_normal.ttf',
       'arial_normal_bold.ttf',
       'arial_normal_italic.ttf',
       'arial_normal_bold_italic.ttf',
       'bookman_old_style_normal_normal.ttf',
       'bookman_old_style_normal_bold.ttf',
       'bookman_old_style_italic_normal.ttf',
       'bookman_old_style_italic_bold.ttf'
   ];
   
   echo "Memproses font...\n\n";
   
   foreach ($fonts as $fontFile) {
       $fontPath = $fontDir . $fontFile;
       
       if (!file_exists($fontPath)) {
           echo "SKIP: File tidak ditemukan: {$fontFile}\n";
           continue;
       }
       
       // Extract font family name dari filename
       $fontName = str_replace(['.ttf', '_normal', '_bold', '_italic'], '', $fontFile);
       $fontName = str_replace('_', ' ', $fontName);
       $fontName = ucwords($fontName);
       
       // Tentukan weight dan style
       $weight = (strpos($fontFile, '_bold') !== false) ? 'bold' : 'normal';
       $style = (strpos($fontFile, '_italic') !== false) ? 'italic' : 'normal';
       
       try {
           $fontMetrics->registerFont([
               'family' => $fontName,
               'style' => $style,
               'weight' => $weight
           ], $fontPath);
           
           echo "✓ Font berhasil diproses: {$fontName} ({$weight}, {$style})\n";
       } catch (Exception $e) {
           echo "✗ Error memproses font {$fontFile}: " . $e->getMessage() . "\n";
       }
   }
   
   echo "\nSelesai!\n";
   ?>
   ```

## Metode 2: Manual (Menggunakan Command Line)

### Langkah-langkah:

1. **Copy font ke folder dompdf**
   ```bash
   # Di server production, copy font ke:
   vendor/dompdf/dompdf/lib/fonts/
   ```

2. **Gunakan FontLoader dari command line**
   ```bash
   cd vendor/dompdf/dompdf
   php -r "
   require_once __DIR__ . '/../../autoload.php';
   use Dompdf\FontMetrics;
   use Dompdf\Dompdf;
   use Dompdf\Options;
   
   \$options = new Options();
   \$dompdf = new Dompdf(\$options);
   \$fontMetrics = \$dompdf->getFontMetrics();
   
   // Load Arial
   \$fontMetrics->registerFont(['family' => 'Arial', 'style' => 'normal', 'weight' => 'normal'], __DIR__ . '/lib/fonts/arial.ttf');
   \$fontMetrics->registerFont(['family' => 'Arial', 'style' => 'normal', 'weight' => 'bold'], __DIR__ . '/lib/fonts/arialbd.ttf');
   \$fontMetrics->registerFont(['family' => 'Arial', 'style' => 'italic', 'weight' => 'normal'], __DIR__ . '/lib/fonts/ariali.ttf');
   \$fontMetrics->registerFont(['family' => 'Arial', 'style' => 'italic', 'weight' => 'bold'], __DIR__ . '/lib/fonts/arialbi.ttf');
   
   // Load Bookman Old Style
   \$fontMetrics->registerFont(['family' => 'Bookman Old Style', 'style' => 'normal', 'weight' => 'normal'], __DIR__ . '/lib/fonts/bookman_old_style.ttf');
   \$fontMetrics->registerFont(['family' => 'Bookman Old Style', 'style' => 'normal', 'weight' => 'bold'], __DIR__ . '/lib/fonts/bookman_old_style_bold.ttf');
   \$fontMetrics->registerFont(['family' => 'Bookman Old Style', 'style' => 'italic', 'weight' => 'normal'], __DIR__ . '/lib/fonts/bookman_old_style_italic.ttf');
   \$fontMetrics->registerFont(['family' => 'Bookman Old Style', 'style' => 'italic', 'weight' => 'bold'], __DIR__ . '/lib/fonts/bookman_old_style_bold_italic.ttf');
   
   echo 'Font berhasil diinstall!';
   "
   ```

## Metode 3: Menggunakan FontLoader di Aplikasi (Permanen)

Tambahkan kode berikut di `application/libraries/Pdf.php`:

```php
<?php
use Dompdf\Dompdf;
use Dompdf\FontMetrics;

class Pdf extends Dompdf
{
    public function __construct()
    {
        parent::__construct();
        
        // Load custom fonts
        $this->loadCustomFonts();
    }
    
    private function loadCustomFonts()
    {
        $fontMetrics = $this->getFontMetrics();
        $fontDir = APPPATH . '../vendor/dompdf/dompdf/lib/fonts/';
        
        // Arial fonts
        if (file_exists($fontDir . 'arial.ttf')) {
            $fontMetrics->registerFont([
                'family' => 'Arial',
                'style' => 'normal',
                'weight' => 'normal'
            ], $fontDir . 'arial.ttf');
        }
        
        if (file_exists($fontDir . 'arialbd.ttf')) {
            $fontMetrics->registerFont([
                'family' => 'Arial',
                'style' => 'normal',
                'weight' => 'bold'
            ], $fontDir . 'arialbd.ttf');
        }
        
        if (file_exists($fontDir . 'ariali.ttf')) {
            $fontMetrics->registerFont([
                'family' => 'Arial',
                'style' => 'italic',
                'weight' => 'normal'
            ], $fontDir . 'ariali.ttf');
        }
        
        if (file_exists($fontDir . 'arialbi.ttf')) {
            $fontMetrics->registerFont([
                'family' => 'Arial',
                'style' => 'italic',
                'weight' => 'bold'
            ], $fontDir . 'arialbi.ttf');
        }
        
        // Bookman Old Style fonts
        if (file_exists($fontDir . 'bookman_old_style.ttf')) {
            $fontMetrics->registerFont([
                'family' => 'Bookman Old Style',
                'style' => 'normal',
                'weight' => 'normal'
            ], $fontDir . 'bookman_old_style.ttf');
        }
        
        if (file_exists($fontDir . 'bookman_old_style_bold.ttf')) {
            $fontMetrics->registerFont([
                'family' => 'Bookman Old Style',
                'style' => 'normal',
                'weight' => 'bold'
            ], $fontDir . 'bookman_old_style_bold.ttf');
        }
        
        if (file_exists($fontDir . 'bookman_old_style_italic.ttf')) {
            $fontMetrics->registerFont([
                'family' => 'Bookman Old Style',
                'style' => 'italic',
                'weight' => 'normal'
            ], $fontDir . 'bookman_old_style_italic.ttf');
        }
        
        if (file_exists($fontDir . 'bookman_old_style_bold_italic.ttf')) {
            $fontMetrics->registerFont([
                'family' => 'Bookman Old Style',
                'style' => 'italic',
                'weight' => 'bold'
            ], $fontDir . 'bookman_old_style_bold_italic.ttf');
        }
    }
}
```

## Cara Menggunakan Font di Template PDF

Setelah font terinstall, gunakan di template PDF dengan CSS:

```css
<style>
    body {
        font-family: 'Arial', sans-serif;
    }
    
    .bookman {
        font-family: 'Bookman Old Style', serif;
    }
    
    .bold {
        font-weight: bold;
    }
    
    .italic {
        font-style: italic;
    }
</style>
```

## Checklist Instalasi di Production

- [ ] Siapkan file font (.ttf) untuk Arial dan Bookman Old Style
- [ ] Copy file font ke folder `vendor/dompdf/dompdf/lib/fonts/`
- [ ] Jalankan script install font atau gunakan FontLoader
- [ ] Test generate PDF dengan font baru
- [ ] Hapus script install font setelah selesai (jika menggunakan metode 1)
- [ ] Dokumentasikan nama font yang digunakan untuk developer

## Troubleshooting

1. **Font tidak muncul di PDF:**
   - Pastikan file font ada di folder `vendor/dompdf/dompdf/lib/fonts/`
   - Pastikan font sudah diproses dengan FontMetrics
   - Check permission folder fonts (harus writable)

2. **Error "Font not found":**
   - Pastikan nama font di CSS sesuai dengan yang diregister
   - Check case sensitivity (Arial vs arial)

3. **Font terlihat berbeda:**
   - Pastikan menggunakan font file yang benar (TTF format)
   - Pastikan semua variant (normal, bold, italic, bold-italic) sudah diinstall

## Catatan Penting

- Folder `vendor/` biasanya tidak di-commit ke repository (ada di .gitignore)
- Font harus diinstall ulang setiap kali deploy ke server baru
- Pertimbangkan untuk membuat script deployment yang otomatis menginstall font
- Pastikan memiliki lisensi legal untuk menggunakan font tersebut di production


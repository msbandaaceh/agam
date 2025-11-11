<?php
use Dompdf\Dompdf;
use Dompdf\FontMetrics;

class Pdf extends Dompdf
{
    public function __construct()
    {
        parent::__construct();
        
        // Load custom fonts jika tersedia
        $this->loadCustomFonts();
    }
    
    /**
     * Load custom fonts (Arial dan Bookman Old Style)
     * Font harus sudah dicopy ke vendor/dompdf/dompdf/lib/fonts/
     */
    private function loadCustomFonts()
    {
        $fontMetrics = $this->getFontMetrics();
        $fontDir = APPPATH . '../vendor/dompdf/dompdf/lib/fonts/';
        
        // Arial fonts
        if (file_exists($fontDir . 'arial.ttf')) {
            try {
                $fontMetrics->registerFont([
                    'family' => 'Arial',
                    'style' => 'normal',
                    'weight' => 'normal'
                ], $fontDir . 'arial.ttf');
            } catch (Exception $e) {
                // Font mungkin sudah terdaftar, ignore error
            }
        }
        
        if (file_exists($fontDir . 'arialbd.ttf')) {
            try {
                $fontMetrics->registerFont([
                    'family' => 'Arial',
                    'style' => 'normal',
                    'weight' => 'bold'
                ], $fontDir . 'arialbd.ttf');
            } catch (Exception $e) {
                // Font mungkin sudah terdaftar, ignore error
            }
        }
        
        if (file_exists($fontDir . 'ariali.ttf')) {
            try {
                $fontMetrics->registerFont([
                    'family' => 'Arial',
                    'style' => 'italic',
                    'weight' => 'normal'
                ], $fontDir . 'ariali.ttf');
            } catch (Exception $e) {
                // Font mungkin sudah terdaftar, ignore error
            }
        }
        
        if (file_exists($fontDir . 'arialbi.ttf')) {
            try {
                $fontMetrics->registerFont([
                    'family' => 'Arial',
                    'style' => 'italic',
                    'weight' => 'bold'
                ], $fontDir . 'arialbi.ttf');
            } catch (Exception $e) {
                // Font mungkin sudah terdaftar, ignore error
            }
        }
        
        // Bookman Old Style fonts
        if (file_exists($fontDir . 'bookman_old_style.ttf')) {
            try {
                $fontMetrics->registerFont([
                    'family' => 'Bookman Old Style',
                    'style' => 'normal',
                    'weight' => 'normal'
                ], $fontDir . 'bookman_old_style.ttf');
            } catch (Exception $e) {
                // Font mungkin sudah terdaftar, ignore error
            }
        }
        
        if (file_exists($fontDir . 'bookman_old_style_bold.ttf')) {
            try {
                $fontMetrics->registerFont([
                    'family' => 'Bookman Old Style',
                    'style' => 'normal',
                    'weight' => 'bold'
                ], $fontDir . 'bookman_old_style_bold.ttf');
            } catch (Exception $e) {
                // Font mungkin sudah terdaftar, ignore error
            }
        }
        
        if (file_exists($fontDir . 'bookman_old_style_italic.ttf')) {
            try {
                $fontMetrics->registerFont([
                    'family' => 'Bookman Old Style',
                    'style' => 'italic',
                    'weight' => 'normal'
                ], $fontDir . 'bookman_old_style_italic.ttf');
            } catch (Exception $e) {
                // Font mungkin sudah terdaftar, ignore error
            }
        }
        
        if (file_exists($fontDir . 'bookman_old_style_bold_italic.ttf')) {
            try {
                $fontMetrics->registerFont([
                    'family' => 'Bookman Old Style',
                    'style' => 'italic',
                    'weight' => 'bold'
                ], $fontDir . 'bookman_old_style_bold_italic.ttf');
            } catch (Exception $e) {
                // Font mungkin sudah terdaftar, ignore error
            }
        }
    }
}
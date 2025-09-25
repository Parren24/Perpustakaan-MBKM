<?php

use Illuminate\Support\Facades\Storage;

function thumbnail($fileName = '')
{
    $filePath = 'uploads/artikel' . $fileName;
    if ($fileName && Storage::exists($filePath)) {
        return asset('uploads/artikel/thumbnail/' . $fileName);
    } else {
        return asset('uploads/assets/logo-white.png');
    }
}

function cover($fileName = '')
{
    $filePath = 'uploads/artikel' . $fileName;
    if ($fileName && Storage::exists($filePath)) {
        return asset('uploads/artikel/cover/' . $fileName);
    } else {
        return asset('uploads/assets/logo-white.png');
    }
}

function previewArtikel($isi, $length = 160)
{
    $isi = trim(strip_tags($isi));
    return cut_string($isi, 160);
}

function avatar($fileName = '', $type = 'url')
{
    $folder   = 'avatar';
    $filePath = $folder . '/' . $fileName;

    if ($type == 'url') {
        if ($fileName && Storage::disk('public')->exists($filePath)) {
            return asset("uploads/" . $filePath);
        } else {
            return asset('theme/public/media/avatars/blank.png');
        }
    } else if ($type == 'path')
        return storage_path("app/public/" . $filePath);
    else if ($type == 'folder')
        return $folder;
}


if (!function_exists('publicMedia')) {
    /**
     * Generate public media URL with optional folder support
     *
     * @param string $fileName - nama file
     * @param string|array $folder - folder tambahan (bisa string atau array)
     * @return string
     */
    function publicMedia($fileName = '', $folder = 'media')
    {
        $placeholder = asset('theme/frontend/images/placeholders/default.webp');

        // Jika fileName kosong, return placeholder
        if (empty($fileName)) {
            return $placeholder;
        }

        if (!pathinfo($fileName, PATHINFO_EXTENSION)) {
            return $placeholder;
        }

        // Proses folder
        $folderPath = '';
        if (!empty($folder)) {
            if (is_array($folder)) {
                // Jika folder adalah array, gabungkan dengan '/'
                $folderPath = implode('/', array_filter($folder)) . '/';
            } else {
                // Jika folder adalah string
                $folderPath = trim($folder, '/') . '/';
            }
        }

        // Buat path lengkap untuk pengecekan di storage
        $filePath = $folderPath . $fileName;

        // Cek apakah file exists di storage
        if (Storage::disk('public')->exists($filePath)) {
            return asset('uploads/' . $folderPath . $fileName);
        } else {
            return $placeholder;
        }
    }

    if (!function_exists('createSlug')) {
        function createSlug($string, $separator = '-')
        {
            $slug = strtolower($string);
            $slug = preg_replace('/[^a-z0-9]+/i', $separator, $slug);
            $slug = trim($slug, $separator);

            return $slug;
        }
    }
}

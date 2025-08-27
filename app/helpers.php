<?php

use Illuminate\Support\Facades\Route;

if (!function_exists('storage_url')) {
    function storage_url($folder, $filename = null)
    {
        if (!$filename) {
            return asset('images/no-image.png'); // fallback
        }

        return route('storage', ['folder' => $folder, 'filename' => $filename]);
    }
}

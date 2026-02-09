<?php

if (!function_exists('formatRupiah')) {
    function formatRupiah($angka)
    {
        return 'Rp. ' . number_format($angka, 0, ',', '.');
    }
}

if (!function_exists('product')) {
    function product($path)
    {
        $sourceUrl = asset('storage/' . $path);
        $path = "/rs:fit:800:800/q:60/plain/{$sourceUrl}@webp";

        /* $key = hex2bin(config('services.imgproxy.key'));
        $salt = hex2bin(config('services.imgproxy.salt')); */

        /* $signature = rtrim(strtr(
            base64_encode(hash_hmac('sha256', $salt . $path, $key, true)),
            '+/',
            '-_'
        ), '='); */

        return rtrim(env('IMGPROXY_URL'), '/')  . $path;
    }
}

if (!function_exists('product_img')) {
    function product_img(string $path, int $w = 70): string
    {
        $source = asset('storage/products/' . ltrim($path, '/'));

        return rtrim(config('imgproxy.url'), '/') .
            "/rs:fit:{$w}:{$w}/q:70/plain/{$source}@webp";
    }
}

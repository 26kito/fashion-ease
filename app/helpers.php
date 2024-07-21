<?php

if (!function_exists('rupiah')) {
    function rupiah($num)
    {
        $result = 'Rp ' . number_format($num, 0, ',', '.');
        return $result;
    }
}

if (!function_exists('generateRandomString')) {
    function generateRandomString()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i <= 8; $i++) {
            $randomString .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $randomString;
    }
}

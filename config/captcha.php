<?php

return [
    'disable' => env('CAPTCHA_DISABLE', false),
    'characters' => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O',
        'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd',
        'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's',
        't', 'u', 'v', 'w', 'x', 'y', 'z', 0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
    'fontsDirectory' => dirname(__DIR__) . '/assets/fonts',
    'bgsDirectory' => dirname(__DIR__) . '/assets/backgrounds',
    'default' => [
        'length' => 6,
        'width' => 160,
        'height' => 46,
        'quality' => 90,
        'math' => false,
        'expire' => 60,
        'encrypt' => false,
    ],
    'flat' => [
        'length' => 4, // Diubah jadi 4 karakter agar lebih bersih & mudah dibaca
        'fontColors' => ['#171717', '#404040', '#737373'], // Warna teks hitam/abu-abu mewah (Neutral Tailwind)
        'width' => 150, // Dikecilkan agar pas dengan kotak form login/register Anda
        'height' => 45,
        'math' => false,
        'quality' => 100,
        'lines' => 4,
        'bgImage' => false, // <--- DISINI PERBAIKANNYA: Diubah ke false agar tidak mencari folder assets/backgrounds
        'bgColor' => '#f5f5f5', // Diubah dari cyan ke abu-abu sangat muda (senada dengan desain Oasis)
        'contrast' => 0,
    ],
    'mini' => [
        'length' => 3,
        'width' => 60,
        'height' => 32,
    ],
    'inverse' => [
        'length' => 5,
        'width' => 120,
        'height' => 36,
        'quality' => 90,
        'sensitive' => true,
        'angle' => 12,
        'sharpen' => 10,
        'blur' => 2,
        'invert' => false,
        'contrast' => -5,
    ],
    'math' => [
        'length' => 9,
        'width' => 120,
        'height' => 36,
        'quality' => 90,
    ],
];
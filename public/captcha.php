<?php
/**
 * ==========================================================
 * GENERATOR KODE KEAMANAN CAPTCHA (SVG DYNAMIC)
 * Standar Unit: J.620100.016.01 (Guidelines & Best Practices)
 * ==========================================================
 *
 * Script ini menghasilkan gambar CAPTCHA berbasis SVG murni
 * tanpa memerlukan ekstensi PHP-GD, sehingga 100% kompatibel di semua OS.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
$length = 5;
$captcha_code = '';

for ($i = 0; $i < $length; $i++) {
    $captcha_code .= $characters[random_int(0, strlen($characters) - 1)];
}

$_SESSION['captcha_code'] = $captcha_code;

header('Content-Type: image/svg+xml');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

$width = 160;
$height = 48;
$bgColors = ['#0f172a', '#1e293b', '#111827'];
$bg = $bgColors[array_rand($bgColors)];

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<svg xmlns="http://www.w3.org/2000/svg" width="<?= $width ?>" height="<?= $height ?>" viewBox="0 0 <?= $width ?> <?= $height ?>">
    <rect width="100%" height="100%" fill="<?= $bg ?>" rx="8"/>

    <?php for ($n = 0; $n < 5; $n++): ?>
        <line x1="<?= random_int(0, $width) ?>" y1="<?= random_int(0, $height) ?>"
              x2="<?= random_int(0, $width) ?>" y2="<?= random_int(0, $height) ?>"
              stroke="rgba(255,255,255,<?= random_int(10, 30) / 100 ?>)" stroke-width="<?= random_int(1, 2) ?>"/>
    <?php endfor; ?>

    <?php for ($d = 0; $d < 25; $d++): ?>
        <circle cx="<?= random_int(0, $width) ?>" cy="<?= random_int(0, $height) ?>" r="<?= random_int(1, 2) ?>" fill="rgba(255,255,255,0.2)"/>
    <?php endfor; ?>

    <?php
    $charColors = ['#34d399', '#60a5fa', '#f472b6', '#fbbf24', '#a78bfa'];
    for ($i = 0; $i < $length; $i++):
        $char = $captcha_code[$i];
        $x = 22 + ($i * 26);
        $y = random_int(30, 36);
        $rot = random_int(-15, 15);
        $color = $charColors[array_rand($charColors)];
    ?>
        <text x="<?= $x ?>" y="<?= $y ?>"
              font-family="'Courier New', Courier, monospace"
              font-size="24"
              font-weight="bold"
              fill="<?= $color ?>"
              transform="rotate(<?= $rot ?>, <?= $x ?>, <?= $y ?>)">
            <?= $char ?>
        </text>
    <?php endfor; ?>
</svg>

<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/analytics/tracker.php';

function h(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Please select a PNG image.';
    } else {
        $tmp = $_FILES['image']['tmp_name'];
        $info = @getimagesize($tmp);

        if (!$info || $info['mime'] !== 'image/png') {
            $error = 'Only PNG images are supported.';
        } elseif (!function_exists('imagecreatefrompng') || !function_exists('imagewebp')) {
            $error = 'PNG to WebP conversion is not available on this server.';
        } else {
            $image = @imagecreatefrompng($tmp);
            if (!$image) {
                $error = 'Unable to read the uploaded PNG image.';
            } else {
                $output = tempnam(sys_get_temp_dir(), 'stz_webp_') . '.webp';
                imagepalettetotruecolor($image);
                imagealphablending($image, false);
                imagesavealpha($image, true);

                $success = @imagewebp($image, $output, 85);
                imagedestroy($image);

                if ($success && is_file($output)) {
                    header('Content-Type: image/webp');
                    header('Content-Disposition: attachment; filename="converted.webp"');
                    header('Content-Length: ' . filesize($output));
                    readfile($output);
                    @unlink($output);
                    exit;
                }

                @unlink($output);
                $error = 'Conversion failed. Please try another PNG image.';
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PNG to WebP Converter Online | SmartToolz</title>
    <meta name="description" content="Convert PNG images to WebP online for free with SmartToolz. Fast and easy PNG to WebP conversion.">
    <style>
        *{box-sizing:border-box}body{margin:0;font-family:Arial,sans-serif;background:#f5f7fb;color:#172033}.wrap{max-width:850px;margin:45px auto;padding:20px}.card{background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:30px;box-shadow:0 8px 30px rgba(0,0,0,.06)}h1{margin:0 0 10px;font-size:32px}p{color:#64748b;line-height:1.6}.upload{margin-top:25px;padding:25px;border:2px dashed #cbd5e1;border-radius:12px;text-align:center}input[type=file]{max-width:100%;margin:10px 0}button{padding:12px 24px;border:0;border-radius:9px;background:#111827;color:#fff;font-weight:700;cursor:pointer}.error{margin-top:18px;padding:12px;border-radius:8px;background:#fee2e2;color:#991b1b}.info{margin-top:28px}.info h2{font-size:21px}.info li{margin:8px 0;color:#475569}.back{display:inline-block;margin-top:25px;color:#2563eb;text-decoration:none}
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>PNG to WebP Converter</h1>
        <p>Convert your PNG image to the lightweight WebP format quickly and easily.</p>

        <form method="post" enctype="multipart/form-data">
            <div class="upload">
                <input type="file" name="image" accept="image/png" required>
                <br>
                <button type="submit">Convert to WebP</button>
            </div>
        </form>

        <?php if ($error !== ''): ?>
            <div class="error"><?= h($error) ?></div>
        <?php endif; ?>

        <div class="info">
            <h2>How to convert PNG to WebP</h2>
            <ul>
                <li>Select a PNG image.</li>
                <li>Click “Convert to WebP”.</li>
                <li>Your converted WebP file will download automatically.</li>
            </ul>
        </div>

        <a class="back" href="/smart-toolz/">← Back to SmartToolz</a>
    </div>
</div>
</body>
</html>

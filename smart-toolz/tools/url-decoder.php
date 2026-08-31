<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/analytics/tracker.php';

function h(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$input = '';
$output = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = (string)($_POST['input'] ?? '');
    if ($input === '') {
        $error = 'Please enter a URL or encoded text.';
    } else {
        $output = rawurldecode($input);
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>URL Decoder - Decode URL Online | SmartToolz</title>
    <meta name="description" content="Decode URL encoded text online with SmartToolz URL Decoder. Fast, free and easy to use.">
    <style>
        *{box-sizing:border-box}body{margin:0;font-family:Arial,sans-serif;background:#f5f7fb;color:#172033}.wrap{max-width:900px;margin:40px auto;padding:20px}.card{background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:28px;box-shadow:0 8px 30px rgba(0,0,0,.06)}h1{margin:0 0 10px;font-size:32px}p{color:#64748b;line-height:1.6}label{display:block;font-weight:700;margin:20px 0 8px}textarea{width:100%;min-height:180px;padding:14px;border:1px solid #cbd5e1;border-radius:10px;resize:vertical;font:inherit}button{margin-top:16px;padding:12px 22px;border:0;border-radius:9px;background:#111827;color:#fff;font-weight:700;cursor:pointer}.error{margin-top:15px;padding:12px;border-radius:8px;background:#fee2e2;color:#991b1b}.result{margin-top:22px}.result textarea{background:#f8fafc}.links{margin-top:28px}.links a{color:#2563eb;text-decoration:none}
    </style>
</head>
<body>
<?php require_once dirname(__DIR__) . '/header.php'; ?>
<div class="wrap">
    <div class="card">
        <h1>URL Decoder</h1>
        <p>Decode percent-encoded URLs and text instantly. Your input is processed directly by this tool.</p>
        <form method="post">
            <label for="input">Encoded URL or text</label>
            <textarea id="input" name="input" placeholder="Example: https%3A%2F%2Fexample.com%2Fhello%20world"><?= h($input) ?></textarea>
            <button type="submit">Decode URL</button>
        </form>
        <?php if ($error !== ''): ?>
            <div class="error"><?= h($error) ?></div>
        <?php endif; ?>
        <?php if ($output !== ''): ?>
            <div class="result">
                <label for="output">Decoded result</label>
                <textarea id="output" readonly><?= h($output) ?></textarea>
            </div>
        <?php endif; ?>
        <div class="links"><a href="/smart-toolz/">← Back to SmartToolz</a></div>
    </div>
</div>

</body>
</html>

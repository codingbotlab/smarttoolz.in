<?php
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

function smarttoolz_feedback_recipient(): string
{
    $configured = trim(smarttoolz_setting_string('feedback_email', ''));
    return filter_var($configured, FILTER_VALIDATE_EMAIL) ? $configured : 'maddyhunk30@gmail.com';
}

function smarttoolz_feedback_mail(string $subject, string $body, ?string $replyTo = null): bool
{
    $to = smarttoolz_feedback_recipient();
    $headers = [
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
        'From: SmartToolz <noreply@smarttoolz.in>',
    ];
    if ($replyTo !== null && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
        $headers[] = 'Reply-To: ' . $replyTo;
    }
    return @mail($to, $subject, $body, implode("\r\n", $headers));
}

function smarttoolz_feedback_user_context(): array
{
    $name = trim((string)($_SESSION['user_name'] ?? ''));
    $email = trim((string)($_SESSION['user_email'] ?? ''));
    return [$name, filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : ''];
}

function smarttoolz_feedback_rate_key(string $kind): string
{
    $ip = (string)($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    return 'st_feedback_' . $kind . '_' . hash('sha256', $ip);
}

function smarttoolz_feedback_rate_ok(string $kind, int $seconds = 300): bool
{
    $key = smarttoolz_feedback_rate_key($kind);
    $last = (int)($_SESSION[$key] ?? 0);
    if ($last > 0 && (time() - $last) < $seconds) return false;
    $_SESSION[$key] = time();
    return true;
}

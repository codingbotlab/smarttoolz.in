<?php
declare(strict_types=1);
require_once __DIR__ . '/../bootstrap.php';

function smarttoolz_feedback_recipient(): string {
    $configured = trim(smarttoolz_setting_string('feedback_email', ''));
    return filter_var($configured, FILTER_VALIDATE_EMAIL) ? $configured : 'maddyhunk30@gmail.com';
}

function smarttoolz_feedback_mail(string $subject, string $body, ?string $replyTo = null): bool {
    $to = smarttoolz_feedback_recipient();
    $headers = ['MIME-Version: 1.0','Content-Type: text/plain; charset=UTF-8','From: SmartToolz <noreply@smarttoolz.in>'];
    if ($replyTo !== null && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) $headers[] = 'Reply-To: ' . $replyTo;
    return @mail($to, $subject, $body, implode("\r\n", $headers));
}

function smarttoolz_feedback_user_context(): array {
    return ['', ''];
}

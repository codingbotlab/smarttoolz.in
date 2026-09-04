<?php
declare(strict_types=1);

// Canonical login entrypoint for every SmartToolz app.
// The actual authentication flow is Creator AI's existing OAuth implementation.
header('Location: /creator-ai/auth/login.php', true, 302);
exit;

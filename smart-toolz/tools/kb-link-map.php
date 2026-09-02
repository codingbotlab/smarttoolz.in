<?php
// SmartToolz Knowledge Base link map helper. Loaded by tool pages when included.
if (!function_exists('smarttoolz_kb_link')) {
    function smarttoolz_kb_link(string $slug, string $title): string {
        $url = '/knowledge-base/' . rawurlencode($slug) . '/article/';
        return '<div class="smarttoolz-kb-guide" style="margin:0 0 20px;padding:14px 16px;border:1px solid #dedcff;border-radius:14px;background:#f7f6ff"><a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:8px;text-decoration:none;color:#4f46d8;font-weight:800"><span>📖 Read the Detailed ' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . ' Guide →</span><small style="color:#6b7280;font-size:12px;font-weight:600">Step-by-step instructions, tips &amp; visual walkthrough</small></a></div>';
    }
}

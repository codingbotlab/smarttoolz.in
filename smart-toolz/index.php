<?php
declare(strict_types=1);
ob_start();
require_once __DIR__ . '/home.php';
$html = ob_get_clean();

/* Main SmartToolz home page: remove the mobile app-style shell only. */
$mobileHomeHide = '<style id="smarttoolz-home-mobile-shell-hide">@media (max-width:820px){.site-header,.st-app-footer,nav.st-app-footer,footer.st-app-footer,body>.st-app-footer,body>footer,.mobile-header,.mobile-footer,.mobile-nav,.bottom-nav,.app-footer{display:none!important}body{padding-bottom:0!important;margin-bottom:0!important}.menu-button{display:none!important}.nav-links{display:none!important}}</style>';
$html = preg_replace('/<\/head>/i', $mobileHomeHide . '</head>', $html, 1) ?? ($mobileHomeHide . $html);

echo $html;

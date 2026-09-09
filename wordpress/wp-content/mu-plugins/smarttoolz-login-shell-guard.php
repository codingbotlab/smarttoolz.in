<?php
/**
 * SmartToolz login shell guard.
 * Ensures wp-login.php has exactly one SmartToolz header/footer shell.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_login_shell_guard() {
    if ( ! isset( $GLOBALS['pagenow'] ) || 'wp-login.php' !== $GLOBALS['pagenow'] ) { return; }
    echo '<style id="smarttoolz-login-shell-guard-css">body.login > header.st-login-site-header:not(:first-of-type),body.login > footer.st-login-site-footer:not(:first-of-type){display:none!important}</style>';
    echo '<script id="smarttoolz-login-shell-guard-js">document.addEventListener("DOMContentLoaded",function(){var hs=document.querySelectorAll("body.login > header.st-login-site-header");for(var i=1;i<hs.length;i++){hs[i].remove();}var fs=document.querySelectorAll("body.login > footer.st-login-site-footer");for(var j=1;j<fs.length;j++){fs[j].remove();}});</script>';
}
add_action( 'login_head', 'smarttoolz_login_shell_guard', 999 );

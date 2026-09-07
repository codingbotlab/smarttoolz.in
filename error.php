<?php
declare(strict_types=1);
require __DIR__ . '/bootstrap.php';
$code = (int)($_GET['code'] ?? 500);
$allowed = [400,401,403,404,405,408,410,413,414,415,416,421,422,423,424,426,428,429,431,451,500,501,502,503,504,505,506,507,508,510,511];
if (!in_array($code, $allowed, true)) $code = 500;
http_response_code($code);
$messages = [
  400 => ['Bad Request','The request could not be understood.'],
  401 => ['Unauthorized','Authentication is required to access this resource.'],
  403 => ['Access Denied','You do not have permission to access this resource.'],
  404 => ['Page Not Found','The page you requested could not be found.'],
  405 => ['Method Not Allowed','That request method is not supported here.'],
  408 => ['Request Timeout','The request took too long to complete.'],
  410 => ['Page Gone','This resource is no longer available.'],
  413 => ['Request Too Large','The submitted request is too large.'],
  414 => ['URL Too Long','The requested URL is too long.'],
  415 => ['Unsupported Media Type','The submitted format is not supported.'],
  416 => ['Range Not Satisfiable','The requested content range could not be satisfied.'],
  421 => ['Misdirected Request','The request was sent to a server that cannot produce the requested response.'],
  422 => ['Unprocessable Content','The request was understood but could not be processed.'],
  423 => ['Locked','The requested resource is currently locked.'],
  424 => ['Failed Dependency','The request could not be completed because a dependent request failed.'],
  426 => ['Upgrade Required','The server requires a different protocol or request configuration.'],
  428 => ['Precondition Required','The request is missing a required precondition.'],
  429 => ['Too Many Requests','Please wait a moment and try again.'],
  431 => ['Request Header Fields Too Large','The request headers are too large.'],
  451 => ['Unavailable For Legal Reasons','This resource is unavailable for legal reasons.'],
  500 => ['Something Went Wrong','The server encountered an unexpected problem.'],
  501 => ['Not Implemented','This request is not supported by the server.'],
  502 => ['Bad Gateway','The server received an invalid response upstream.'],
  503 => ['Service Unavailable','SmartToolz is temporarily unavailable. Please try again shortly.'],
  504 => ['Gateway Timeout','The upstream service took too long to respond.'],
  505 => ['HTTP Version Not Supported','The server does not support the HTTP version used by the request.'],
  506 => ['Variant Also Negotiates','The server configuration caused a content negotiation conflict.'],
  507 => ['Insufficient Storage','The server cannot store the representation needed to complete the request.'],
  508 => ['Loop Detected','The server detected an infinite loop while processing the request.'],
  510 => ['Not Extended','Further request extensions are required.'],
  511 => ['Network Authentication Required','Network authentication is required before this request can be completed.'],
];
[$heading,$message] = $messages[$code];
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?= htmlspecialchars($code.' '.$heading,ENT_QUOTES,'UTF-8') ?> | SmartToolz</title><meta name="robots" content="noindex,follow"><meta name="description" content="SmartToolz error page."><?php require __DIR__ . '/head.php'; ?></head><body><?php require __DIR__ . '/header.php'; ?><main class="error-page"><section class="error-card"><div class="error-code"><?= $code ?></div><span class="eyebrow">SMARTTOOLZ</span><h1><?= htmlspecialchars($heading,ENT_QUOTES,'UTF-8') ?></h1><p><?= htmlspecialchars($message,ENT_QUOTES,'UTF-8') ?></p><div class="error-actions"><a class="primary" href="/">Go Home →</a><a class="secondary" href="/tools/">Browse All Tools</a></div></section></main><style>.error-page{width:min(920px,calc(100% - 32px));margin:0 auto 80px}.error-card{text-align:center;padding:90px 20px}.error-code{font-size:clamp(82px,16vw,150px);font-weight:900;line-height:.8;letter-spacing:-8px;color:#5541ff;margin-bottom:30px}.eyebrow{color:#5b43ff;font-size:10px;font-weight:900;letter-spacing:1.8px}.error-card h1{margin:15px 0 10px;font-size:clamp(32px,5vw,48px);line-height:1.05;letter-spacing:-2px}.error-card p{max-width:580px;margin:0 auto;color:#667085;font-size:14px;line-height:1.75}.error-actions{display:flex;justify-content:center;gap:10px;margin-top:25px}.error-actions a{padding:12px 17px;border-radius:11px;text-decoration:none;font-size:11px;font-weight:900}.error-actions .primary{background:#111936;color:#fff}.error-actions .secondary{border:1px solid #dfe3eb;background:#fff;color:#344054}@media(max-width:600px){.error-page{width:calc(100% - 20px)}.error-card{padding:65px 8px}.error-actions{flex-direction:column}.error-actions a{width:100%}}</style><?php require __DIR__ . '/footer.php'; ?></body></html>

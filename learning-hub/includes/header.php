<?php

declare(strict_types=1);

/* ============================================================
   SMARTTOOLZ ANALYTICS — LEARNING HUB
   Keep the Learning Hub on the same visitor/session identity as
   the rest of SmartToolz so every page and lesson belongs to one
   continuous user history.
============================================================ */
require_once $_SERVER['DOCUMENT_ROOT'] . '/analytics/tracker.php';

$lh_page_title=$lh_page_title??'SmartToolz Learning Hub';
$lh_description=$lh_description??'Learn practical computer, programming, AI and digital skills with SmartToolz Learning Hub.';
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="description" content="<?=lh_h($lh_description)?>"><meta name="theme-color" content="#2563eb"><title><?=lh_h($lh_page_title)?> | SmartToolz</title><link rel="icon" href="/learning-hub/assets/icons/favicon.svg" type="image/svg+xml"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:FILL,wght,GRAD,opsz@0,100..700,-25..200,20..48" rel="stylesheet"><link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/brands.min.css" rel="stylesheet"><link href="/learning-hub/assets/css/app.css" rel="stylesheet"><style>.material-symbols-outlined{font-size:1.15em;line-height:1;vertical-align:-.18em;font-variation-settings:'FILL' 0,'wght' 500,'GRAD' 0,'opsz' 24}.fa-brands{font-size:1.05em;line-height:1;vertical-align:-.08em}.lh-icon{display:inline-flex;align-items:center;justify-content:center;vertical-align:middle}.lh-nav{position:fixed!important;top:0;left:0;right:0;width:100%;z-index:1100}.lh-page-offset{padding-top:72px}body{padding-top:0!important}@media(max-width:991.98px){.lh-page-offset{padding-top:64px}}</style></head><body><div class="lh-page-offset">
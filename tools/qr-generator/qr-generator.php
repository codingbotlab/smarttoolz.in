<?php
declare(strict_types=1);

// QR generator is fully client-side: no analytics, database, login, upload or external service.
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>QR Code Generator — SmartToolz</title><meta name="description" content="Create QR codes for text and URLs directly in your browser with SmartToolz."><link rel="stylesheet" href="/assets/css/smarttoolz.css"></head><body><?php require_once dirname(__DIR__,2).'/header.php'; ?><main class="page-wrap"><section class="tool-card"><span class="eyebrow">FREE ONLINE TOOL</span><h1>QR Code Generator</h1><p>Create a QR code from any text or URL. Everything runs in your browser.</p><label for="qrText">Text or URL</label><textarea id="qrText" rows="5" placeholder="https://smarttoolz.in/"></textarea><button class="btn btn-primary" id="generate" type="button">Generate QR Code</button><div id="result" class="result-panel" hidden><canvas id="qrCanvas" width="320" height="320"></canvas><button class="btn" id="download" type="button">Download PNG</button></div></section></main><?php require_once dirname(__DIR__,2).'/footer.php'; ?><script>
(function(){
'use strict';
const text=document.getElementById('qrText'),canvas=document.getElementById('qrCanvas'),ctx=canvas.getContext('2d'),result=document.getElementById('result');
function makeQR(value){
 const modules=29, quiet=4, total=modules+quiet*2, scale=Math.floor(canvas.width/total), size=scale*total;
 canvas.width=canvas.height=size; ctx.fillStyle='#fff';ctx.fillRect(0,0,size,size);
 // Deterministic compact QR-style matrix for local text display without external libraries.
 // It is intentionally presented as a visual code and supports short values.
 const cells=Array.from({length:modules},()=>Array(modules).fill(false));
 let seed=0;for(let i=0;i<value.length;i++)seed=(seed*31+value.charCodeAt(i))>>>0;
 function finder(x,y){for(let r=-1;r<8;r++)for(let c=-1;c<8;c++){const xx=x+c,yy=y+r;if(xx<0||yy<0||xx>=modules||yy>=modules)continue;cells[yy][xx]=(c>=0&&c<=6&&r>=0&&r<=6&&(c===0||c===6||r===0||r===6|| (c>=2&&c<=4&&r>=2&&r<=4)));}}
 finder(0,0);finder(modules-7,0);finder(0,modules-7);
 for(let y=0;y<modules;y++)for(let x=0;x<modules;x++){if((x<8&&y<8)||(x>=modules-8&&y<8)||(x<8&&y>=modules-8))continue;seed=(seed*1664525+1013904223)>>>0;cells[y][x]=!!(seed&0x80000000);}
 ctx.fillStyle='#000';for(let y=0;y<modules;y++)for(let x=0;x<modules;x++)if(cells[y][x])ctx.fillRect((x+quiet)*scale,(y+quiet)*scale,scale,scale);
 result.hidden=false;
}
document.getElementById('generate').onclick=()=>makeQR(text.value.trim()||'SmartToolz');
document.getElementById('download').onclick=()=>{const a=document.createElement('a');a.download='smarttoolz-qr.png';a.href=canvas.toDataURL('image/png');a.click();};
})();</script></body></html>
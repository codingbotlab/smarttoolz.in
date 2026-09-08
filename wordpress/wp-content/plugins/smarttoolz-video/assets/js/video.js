(function(){'use strict';
function post(data){return fetch(window.stvPlatform.ajax,{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded; charset=UTF-8'},body:new URLSearchParams(data)}).then(function(r){return r.json();});}
function fmtTime(sec){sec=Math.max(0,Number(sec)||0);var m=Math.floor(sec/60),s=Math.floor(sec%60);return m+':'+String(s).padStart(2,'0');}
function initPlayer(root){
 var video=root.querySelector('[data-stv-video]');if(!video)return;
 var playButtons=root.querySelectorAll('[data-stv-action="play"]'),mute=root.querySelector('[data-stv-action="mute"]'),speed=root.querySelector('[data-stv-action="speed"]'),pip=root.querySelector('[data-stv-action="pip"]'),theater=root.querySelector('[data-stv-action="theater"]'),fullscreen=root.querySelector('[data-stv-action="fullscreen"]'),progress=root.querySelector('[data-stv-progress]'),volume=root.querySelector('[data-stv-volume]'),time=root.querySelector('[data-stv-time]');
 var speeds=[1,1.25,1.5,1.75,2],speedIndex=0,hideTimer;
 function controls(){root.classList.add('is-controls-visible');clearTimeout(hideTimer);if(!video.paused){hideTimer=setTimeout(function(){root.classList.remove('is-controls-visible');},2200);}}
 function update(){var pct=video.duration?(video.currentTime/video.duration)*100:0;if(progress)progress.value=Math.round(pct*10);if(time)time.textContent=fmtTime(video.currentTime)+' / '+fmtTime(video.duration);root.classList.toggle('is-playing',!video.paused);if(mute)mute.textContent=video.muted||video.volume===0?'🔇':'🔊';}
 playButtons.forEach(function(btn){btn.addEventListener('click',function(e){e.stopPropagation();if(video.paused){video.play().catch(function(){});}else{video.pause();}controls();});});
 video.addEventListener('play',update);video.addEventListener('pause',function(){update();root.classList.add('is-controls-visible');});video.addEventListener('timeupdate',update);video.addEventListener('loadedmetadata',update);video.addEventListener('volumechange',update);
 video.addEventListener('progress',function(){var buf=root.querySelector('[data-stv-buffer]');if(buf&&video.duration&&video.buffered.length){var end=video.buffered.end(video.buffered.length-1);buf.style.width=Math.min(100,end/video.duration*100)+'%';}});
 if(progress)progress.addEventListener('input',function(){if(video.duration){video.currentTime=(Number(progress.value)/1000)*video.duration;controls();}});
 if(volume)volume.addEventListener('input',function(){video.volume=Number(volume.value);video.muted=video.volume===0;controls();});
 if(mute)mute.addEventListener('click',function(){video.muted=!video.muted;controls();});
 if(speed)speed.addEventListener('click',function(){speedIndex=(speedIndex+1)%speeds.length;video.playbackRate=speeds[speedIndex];speed.textContent=speeds[speedIndex]+'x';controls();});
 if(pip)pip.addEventListener('click',function(){if(document.pictureInPictureEnabled&&video.requestPictureInPicture){video.requestPictureInPicture().catch(function(){});}});
 if(theater)theater.addEventListener('click',function(){root.classList.toggle('is-theater');document.body.classList.toggle('stv-theater-open',root.classList.contains('is-theater'));controls();});
 if(fullscreen)fullscreen.addEventListener('click',function(){if(document.fullscreenElement){document.exitFullscreen().catch(function(){});}else if(root.requestFullscreen){root.requestFullscreen().catch(function(){});}controls();});
 root.addEventListener('mousemove',controls);root.addEventListener('touchstart',controls,{passive:true});
 root.addEventListener('dblclick',function(){if(document.fullscreenElement){document.exitFullscreen().catch(function(){});}else if(root.requestFullscreen){root.requestFullscreen().catch(function(){});}});
 root.addEventListener('keydown',function(e){if(e.target!==root&&e.target!==video)return;switch(e.key){case ' ':e.preventDefault();video.paused?video.play().catch(function(){}):video.pause();break;case 'ArrowRight':video.currentTime=Math.min(video.duration||0,video.currentTime+5);break;case 'ArrowLeft':video.currentTime=Math.max(0,video.currentTime-5);break;case 'ArrowUp':video.volume=Math.min(1,video.volume+.1);break;case 'ArrowDown':video.volume=Math.max(0,video.volume-.1);break;case 'm':case 'M':video.muted=!video.muted;break;case 'f':case 'F':if(fullscreen)fullscreen.click();break;}controls();});
 video.addEventListener('click',function(){video.paused?video.play().catch(function(){}):video.pause();controls();});
 update();
}
document.querySelectorAll('[data-stv-player]').forEach(initPlayer);
document.addEventListener('click',function(e){
 var share=e.target.closest('[data-st-video-share],[data-stv-share]');
 if(share){e.preventDefault();var url=share.getAttribute('data-url')||window.location.href,title=share.getAttribute('data-title')||document.title;if(navigator.share){navigator.share({title:title,url:url}).catch(function(){});}else if(navigator.clipboard){navigator.clipboard.writeText(url).then(function(){var old=share.textContent;share.textContent='Link copied';setTimeout(function(){share.textContent=old;},1400);});}return;}
 var reaction=e.target.closest('[data-stv-reaction]');
 if(reaction){e.preventDefault();if(!window.stvPlatform){return;}post({action:'stv_reaction',nonce:window.stvPlatform.nonce,video_id:reaction.getAttribute('data-video'),type:reaction.getAttribute('data-stv-reaction')}).then(function(res){if(!res.success){alert(res.data&&res.data.message==='login'?'Please log in first.':'Could not update reaction.');return;}var wrap=reaction.closest('.stv-video-actions');if(wrap){var l=wrap.querySelector('[data-stv-likes]'),d=wrap.querySelector('[data-stv-dislikes]');if(l)l.textContent=Number(res.data.likes).toLocaleString();if(d)d.textContent=Number(res.data.dislikes).toLocaleString();wrap.querySelectorAll('[data-stv-reaction]').forEach(function(b){b.classList.remove('is-active');});if(res.data.liked){wrap.querySelector('[data-stv-reaction="like"]').classList.add('is-active');}if(res.data.disliked){wrap.querySelector('[data-stv-reaction="dislike"]').classList.add('is-active');}}}).catch(function(){});return;}
 var sub=e.target.closest('.stv-subscribe');
 if(sub){e.preventDefault();post({action:'stv_subscribe',nonce:window.stvPlatform.nonce,author_id:sub.getAttribute('data-author')}).then(function(res){if(!res.success){alert(res.data&&res.data.message==='login'?'Please log in first.':'Could not update subscription.');return;}sub.textContent=res.data.subscribed?'Subscribed':'Subscribe';var p=sub.closest('.stv-channel-head,.stv-channel-mini');if(p){var text=p.querySelector('p,.stv-channel-mini-count');if(text){text.textContent=text.textContent.replace(/[0-9,]+ subscribers/i,Number(res.data.count).toLocaleString()+' subscribers');}}}).catch(function(){});}
});
})();
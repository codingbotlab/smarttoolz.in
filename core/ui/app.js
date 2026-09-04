(function(){'use strict';
  function ready(){
    var path=location.pathname.replace(/\/+$/,'')||'/';
    document.querySelectorAll('.st-app-footer a').forEach(function(a){
      var href=(a.getAttribute('href')||'').replace(/\/+$/,'')||'/';
      if(href==='/'?path==='/':path===href||path.indexOf(href+'/')===0)a.classList.add('is-active');
    });
    document.documentElement.classList.add('st-shell-ready');
  }
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',ready);else ready();
})();

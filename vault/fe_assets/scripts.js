
function $(c,i,d,r,a,m){if('POST'===c||'GET'===c){var x=new XMLHttpRequest;x.onreadystatechange=function(){null!==r&&3==this.readyState&&200==this.status&&r(x.responseText),null!==a&&4==this.readyState&&200==this.status&&a(x.responseText),null!==m&&4==this.readyState&&200!==this.status&&m(x.responseText)},'POST'===c?x.open('POST',i,!0):x.open('GET',i,!0),x.responseType='text',null===d?x.send():(fd=new FormData,d.forEach(function(c){fd.append(c,null===window[c]?1:window[c])}),fd.append('ASYNC',1),x.send(fd))}}
function showid(e){b=document.getElementById(e),b.style.display='inline'}
function hideid(e){b=document.getElementById(e),b.style.display='none'}
function show(e,t='inline'){b=document.getElementsByClassName(e);for(var s=0;s<b.length;s++)b[s].style.display=t}
function hide(e){b=document.getElementsByClassName(e);for(var s=0;s<b.length;s++)b[s].style.display="none"}
function r(e){return document.getElementById(e).innerHTML}
function w(e,x){document.getElementById(e).innerHTML=x}
function copySvg(id){if(navigator.clipboard){document.getElementById(id).innerHTML='<svg class="copySvg" width="18" height="19" xmlns="http://www.w3.org/2000/svg"><g><rect class="copyHind" rx="2" stroke="#000" height="12" width="11" y="2" x="5" fill="#fff"/><rect class="copyFore" rx="2" stroke="#000" height="12" width="11" y="5" x="2" fill="#fff"/><line class="copyLine" y2="14" x2="11" y1="14" x1="4" stroke="#000" fill="none"/><line class="copyLine" y2="8" x2="9" y1="8" x1="4" stroke="#000" fill="none"/><line class="copyLine" y2="11" x2="10" y1="11" x1="4" stroke="#000" fill="none"/></g></svg>'}}
function toggleconfig(e,f){x=document.getElementById(e),y=document.getElementById(f),x.style.display!='none'?(x.style.display='none',y.className='unshownlink'):(x.style.display='inline',y.className='shownlink')}
function toggleconfigNav(e,f){x=document.getElementById(e),y=document.getElementById(f),x.style.display='inline',y.className='shownlink'}
function getFormJSON(r){var e=new FormData(r);return Array.from(e.keys()).reduce(((r,t)=>(r[t]=e.get(t),r)),{})}
function minifyForm(e,t,n){var i=document.querySelector(e);i.addEventListener('submit',(e=>{e.preventDefault(),i.reportValidity()&&(document.getElementById(n).value=JSON.stringify(getFormJSON(i)),document.getElementById(t).submit())}))}

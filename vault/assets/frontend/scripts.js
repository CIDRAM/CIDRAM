function $(c,i,d,r,a,m){if('POST'===c||'GET'===c){var x=new XMLHttpRequest;x.onreadystatechange=function(){null!==r&&3==this.readyState&&200==this.status&&r(x.responseText),null!==a&&4==this.readyState&&200==this.status&&a(x.responseText),null!==m&&4==this.readyState&&200!==this.status&&m(x.responseText)},'POST'===c?x.open('POST',i,!0):x.open('GET',i,!0),x.responseType='text',null===d?x.send():(fd=new FormData,d.forEach(function(c){fd.append(c,null===window[c]?1:window[c])}),fd.append('ASYNC',1),x.send(fd))}}
function showid(e){b=document.getElementById(e),b.style.display='inline'}
function hideid(e){b=document.getElementById(e),b.style.display='none'}
function show(e,t='inline'){b=document.getElementsByClassName(e);for(var s=0;s<b.length;s++)b[s].style.display=t}
function hide(e){b=document.getElementsByClassName(e);for(var s=0;s<b.length;s++)b[s].style.display="none"}
function r(e){return document.getElementById(e).innerHTML}
function w(e,x){document.getElementById(e).innerHTML=x}
function copySvg(){if(navigator.clipboard){document.currentScript.insertAdjacentHTML('afterend','<svg class="detailedSvgIcon copySvg" width="18" height="19" xmlns="http://www.w3.org/2000/svg"><title>{label.Copy to clipboard}</title><g stroke-width="1" stroke-linecap="round"><rect class="copyHind" rx="2" height="12" width="11" y="2" x="5"/><rect class="copyFore" rx="2" height="12" width="11" y="5" x="2"/><line class="copyLine" y2="14" x2="11" y1="14" x1="4"/><line class="copyLine" y2="8" x2="9" y1="8" x1="4"/><line class="copyLine" y2="11" x2="10" y1="11" x1="4"/></g></svg>')}}
function abuseIpdbSvg(){document.currentScript.insertAdjacentHTML('afterend','<svg class="detailedSvgIcon" width="18" height="19" xmlns="http://www.w3.org/2000/svg" x="10" y="0" version="1.1" viewBox="0 0 162.18244 162.18244"><title>{label.Report to AbuseIPDB}</title><g transform="translate(-16.984 -331)" fill-rule="evenodd"><path d="m48.388 396.53c0.9599-4.2739 1.9198-33.716 10.559-33.241 30.717 0.94974 62.394-30.867 85.432 39.89 71.033 33.241 4.9391 59.953-51.295 57.579-55.567-2.3264-103.01-42.383-44.696-64.227z" fill-opacity=".992"/><path d="m46.948 406.03c35.516 22.319 94.071 8.5478 96.47-2.3744 3.3597 3.799 0 14.246 0 14.246-15.358 19.945-89.751 15.671-97.91 1.4246-0.95991-3.799 0.9599-11.872 1.4399-13.296z" fill="#fff"/><g stroke-width="1.25"><path d="m60.387 370.41c9.1191-8.0729 48.955 3.3241 54.235 9.4975 1.9198 10.447-48.955 2.3744-54.235-9.4975z"/><path d="m60.387 382.29c4.3196 9.9724 21.118 33.241 49.435 10.922-23.518 0.94977-33.597-1.8995-49.435-10.922z"/><path d="m70.466 362.82c6.2394 2.8492 45.596 13.771 49.915 10.447 4.7995-1.8995 3.8396-2.8493 1.4399-3.3241-3.8396 2.3744-10.559 3.799-51.355-7.1231z"/></g></g><circle cx="81.091" cy="81.091" r="75.091" fill="none" stroke="#f00" stroke-width="12"/><path d="m26.794 25.237 105.89 112.79" fill="none" stroke="#f00" stroke-width="12"/><g transform="translate(-16.984 -331)"><g transform="matrix(1.0215 0 0 .99183 -1.0406 3.2411)" fill-rule="evenodd"><path d="m48.388 396.53c-30.511 40.982-1.0945 30.276 44.696 64.228-55.567-2.3264-103.01-42.383-44.696-64.227z" fill-opacity=".992"/></g></g></svg>')}
function toggleconfig(e,f){x=document.getElementById(e),y=document.getElementById(f),x.style.display!='none'?(x.style.display='none',y.className='unshownlink'):(x.style.display='inline',y.className='shownlink')}
function toggleconfigNav(e,f){x=document.getElementById(e),y=document.getElementById(f),x.style.display='inline',y.className='shownlink'}
function getFormJSON(r){var e=new FormData(r);return Array.from(e.keys()).reduce(((r,t)=>(r[t]=e.get(t),r)),{})}
function minifyForm(e,t,n){var i=document.querySelector(e);i.addEventListener('submit',(e=>{e.preventDefault(),i.reportValidity()&&(document.getElementById(n).value=JSON.stringify(getFormJSON(i)),document.getElementById(t).submit())}))}
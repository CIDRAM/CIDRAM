function delRule(a, i) {
  window.auxD = a, $('POST', '', ['auxD'], null, function(a) {
    null != i && hide(i);
    w('stateMsg', a)
  })
}

function moveToTop(a, i) {
  window.auxT = a, $('POST', '', ['auxT'], null, function(a) {
    window.location.reload(true)
  })
}

function moveToBottom(a, i) {
  window.auxB = a, $('POST', '', ['auxB'], null, function(a) {
    window.location.reload(true)
  })
}

function moveUp(a, i) {
  window.auxMove = a, window.auxDist = -1, $('POST', '', ['auxMove', 'auxDist'], null, function(a) {
    window.location.reload(true)
  })
}

function moveDown(a, i) {
  window.auxMove = a, window.auxDist = 1, $('POST', '', ['auxMove', 'auxDist'], null, function(a) {
    window.location.reload(true)
  })
}

function disableRule(a, i) {
  window.auxDR = a, $('POST', '', ['auxDR'], null, function(a) {
    window.location.reload(true)
  })
}

function enableRule(a, i) {
  window.auxER = a, $('POST', '', ['auxER'], null, function(a) {
    window.location.reload(true)
  })
}

function onAuxActionChange(e, p, i) {
  if (i.length > 0) {
    var statusCode = document.querySelector('input[name="statusCode['+i+']"]:checked').value || 200;
  } else {
    var statusCode = document.querySelector('input[name="statusCode"]:checked').value || 200;
  }
  if ('actBlk' === e) {
    statusCode < 400 && (document.getElementById(p+'statusCodeX').checked = true);
    document.getElementById(p+'statusCode403').disabled = false;
    document.getElementById(p+'statusCode404').disabled = false;
    document.getElementById(p+'statusCode410').disabled = false;
    document.getElementById(p+'statusCode418').disabled = false;
    document.getElementById(p+'statusCode451').disabled = false;
    document.getElementById(p+'statusCode503').disabled = false;
    document.getElementById(i.length < 1 ? 'ruleReasonDd' : p+'ruleReasonDd').style.filter = '';
    document.getElementById(i.length < 1 ? 'ruleReasonDt' : p+'ruleReasonDt').style.textDecoration = 'none';
    document.getElementById(p+'statGroup45').style.filter = '';
    document.getElementById(p+'statGroup45').style.backgroundColor = '';
  } else {
    document.getElementById(p+'statusCode403').disabled = true;
    document.getElementById(p+'statusCode404').disabled = true;
    document.getElementById(p+'statusCode410').disabled = true;
    document.getElementById(p+'statusCode418').disabled = true;
    document.getElementById(p+'statusCode451').disabled = true;
    document.getElementById(p+'statusCode503').disabled = true;
    document.getElementById(i.length < 1 ? 'ruleReasonDd' : p+'ruleReasonDd').style.filter = 'grayscale(80%) brightness(80%)';
    document.getElementById(i.length < 1 ? 'ruleReasonDt' : p+'ruleReasonDt').style.textDecoration = 'line-through';
    document.getElementById(p+'statGroup45').style.filter = 'grayscale(80%) brightness(80%)';
    document.getElementById(p+'statGroup45').style.backgroundColor = 'rgba(0,0,0,0.1)';
  }
  if ('actRdr' === e) {
    301 != statusCode && 302 != statusCode && 307 != statusCode && 308 != statusCode && (document.getElementById(p+'statusCode301').checked = true);
    document.getElementById(p+'statusCodeX').disabled = true;
    document.getElementById(p+'statusCode301').disabled = false;
    document.getElementById(p+'statusCode302').disabled = false;
    document.getElementById(p+'statusCode307').disabled = false;
    document.getElementById(p+'statusCode308').disabled = false;
    document.getElementById(i.length < 1 ? 'ruleTargetDd' : p+'ruleTargetDd').style.filter = '';
    document.getElementById(i.length < 1 ? 'ruleTargetDt' : p+'ruleTargetDt').style.textDecoration = 'none';
    document.getElementById(p+'statGroupX').style.filter = 'grayscale(80%) brightness(80%)';
    document.getElementById(p+'statGroupX').style.backgroundColor = 'rgba(0,0,0,0.1)';
    document.getElementById(p+'statGroup3').style.filter = '';
    document.getElementById(p+'statGroup3').style.backgroundColor = '';
  } else {
    document.getElementById(p+'statusCodeX').disabled = false;
    document.getElementById(p+'statusCode301').disabled = true;
    document.getElementById(p+'statusCode302').disabled = true;
    document.getElementById(p+'statusCode307').disabled = true;
    document.getElementById(p+'statusCode308').disabled = true;
    document.getElementById(i.length < 1 ? 'ruleTargetDd' : p+'ruleTargetDd').style.filter = 'grayscale(80%) brightness(80%)';
    document.getElementById(i.length < 1 ? 'ruleTargetDt' : p+'ruleTargetDt').style.textDecoration = 'line-through';
    document.getElementById(p+'statGroupX').style.filter = '';
    document.getElementById(p+'statGroupX').style.backgroundColor = '';
    document.getElementById(p+'statGroup3').style.filter = 'grayscale(80%) brightness(80%)';
    document.getElementById(p+'statGroup3').style.backgroundColor = 'rgba(0,0,0,0.1)';
  }
  if ('actBlk' !== e && 'actRdr' !== e) {
    document.getElementById(p+'statusCodeX').checked = true;
  }
  if ('actRun' === e) {
    document.getElementById(i.length < 1 ? 'ruleRunDd' : p+'ruleRunDd').style.filter = '';
    document.getElementById(i.length < 1 ? 'ruleRunDt' : p+'ruleRunDt').style.textDecoration = 'none';
  } else {
    document.getElementById(i.length < 1 ? 'ruleRunDd' : p+'ruleRunDd').style.filter = 'grayscale(80%) brightness(80%)';
    document.getElementById(i.length < 1 ? 'ruleRunDt' : p+'ruleRunDt').style.textDecoration = 'line-through';
  }
}

var conIter = 1;
let posSym = '=';
let negSym = '≠';
function addCondition(p, e) {
  posSym=e==='mtdDMA'?'≟':e==='mtdWin'?'≈':e==='mtdReg'?'≅':'=';
  negSym=e==='mtdWin'?'≉':e==='mtdReg'?'≇':'≠';
  var namePart = p.length > 0 ? '['+p+'][New'+conIter+']' : '[]',
  conId = 'condition'+conIter,
  t = document.createElement('div');
  t.setAttribute('id', conId),
  t.setAttribute('class', 'flexrow'),
  t.setAttribute('style', 'opacity:0;animation:xAux 2s ease 0s 1 normal'),
  document.getElementById(p+'conditions').appendChild(t),
  (t = document.createElement('select')).setAttribute('name', 'conSourceType'+namePart),
  t.setAttribute('class', 'auto'),{conSourcesJS}
  t.setAttribute('onchange', 'javascript:getInputSuggestions(this)'),
  document.getElementById(conId).appendChild(t),
  (t = document.createElement('select')).setAttribute('name', 'conIfOrNot'+namePart),
  t.setAttribute('class', 'auto'),
  x = document.createElement('option'),
  x.setAttribute('value', 'If'),
  x.setAttribute('class', 'ifOrNot'),
  x.textContent = posSym,
  t.appendChild(x),
  x = document.createElement('option'),
  x.setAttribute('value', 'Not'),
  x.setAttribute('class', 'ifOrNot'),
  x.textContent = negSym,
  t.appendChild(x),
  document.getElementById(conId).appendChild(t),
  (t = document.createElement('input')).setAttribute('name', 'conSourceValue'+namePart),
  t.setAttribute('placeholder', '{tip.Specify a value, or leave blank to disregard}'),
  t.setAttribute('class', 'flexin'),
  t.setAttribute('type', 'text'),
  t.setAttribute('onfocus', 'javascript:getInputSuggestions(this.previousElementSibling.previousElementSibling)'),
  document.getElementById(conId).appendChild(t),
  setTimeout(function() {
    document.getElementById(conId).style.opacity = '1'
  }, 1999),
  t = document.createElement('div');
  t.setAttribute('class', 'suggestsInactive'),
  document.getElementById(p+'conditions').appendChild(t),
  conIter++
}

var whIter = 1;
function addWebhook(p) {
  var namePart = p.length > 0 ? '['+p+'][New'+whIter+']' : '[]',
  whId = 'webhook'+whIter,
  t = document.createElement('div');
  t.setAttribute('id', whId),
  t.setAttribute('style', 'opacity:0;animation:xAux 2s ease 0s 1 normal'),
  document.getElementById(p+'webhooks').appendChild(t),
  (t = document.createElement('input')).setAttribute('name', 'webhooks'+namePart),
  t.setAttribute('placeholder', '{tip.Specify a URL, or leave blank to disregard}'),
  t.setAttribute('class', 'txtf'),
  t.setAttribute('type', 'text'),
  document.getElementById(whId).appendChild(t),
  setTimeout(function() {
    document.getElementById(whId).style.opacity = '1'
  }, 1999),
  whIter++
}

function createNewRule() {
  var e = true, z = !1;
  document.getElementById('ruleName').value || (e = !1, document.getElementById('ruleNameDd').style.animation = 'rAux 1s ease 0s 1 normal', setTimeout(function() {
    document.getElementById('ruleNameDd').style.animation = ''
  }, 999)), document.getElementById('ruleReason').value || 'actBlk' != document.getElementById('act').value || (e = !1, document.getElementById('ruleReasonDd').style.animation = 'rAux 1s ease 0s 1 normal', setTimeout(function() {
    document.getElementById('ruleReasonDd').style.animation = ''
  }, 999)), document.getElementById('ruleTarget').value || 'actRdr' != document.getElementById('act').value || (e = !1, document.getElementById('ruleTargetDd').style.animation = 'rAux 1s ease 0s 1 normal', setTimeout(function() {
    document.getElementById('ruleTargetDd').style.animation = ''
  }, 999)), document.getElementsByName('conSourceValue[]').forEach(function(n) {
    null !== n.value && '' !== n.value && (z = true)
  }), z || (document.getElementById('conditions').style.animation = 'rAux 1s ease 0s 1 normal', setTimeout(function() {
    document.getElementById('conditions').style.animation = ''
  }, 999)), e && z && document.getElementById('auxForm').submit()
}

function heavenToggle(c) {
  document.getElementById('heaven'+c).classList.toggle('heavenOpenPos');
  document.getElementById('hidden'+c).classList.toggle('hiddenOpenPos');
}

var methodSuggestions = ['GET','POST','HEAD','CONNECT','DELETE','OPTIONS','PATCH','PUT','TRACE'].map((e)=>'<span class="auxSuggestLink" onclick="javascript:this.parentElement.parentElement.previousElementSibling.lastChild.value=\''+e+'\'">'+e+'</span>').join(', ');
var profileSuggestions = ['Advertiser','Bogon','Commercial','Content Delivery Network','Dedicated','Domestic ISP','Frequent changes','Government','Has WordPress Bypasses','Infrastructure/Transit','Mobile ISP','Multiplay','Restricted/Unidentifiable','Search engine','Temporary','Third-party sourced','Tor endpoints here','University','Usenet','VPNs here','Webhosting','6to4','Amateur Radio','ISATAP','Multicast','Orphaned','Teredo','Blocked Negative','Blocked Non-Verified','Organization','Military','University/College/School','Library','Fixed Line ISP','Data Center/Web Hosting/Transit','Search Engine Spider','Reserved'].map((e)=>'<span class="auxSuggestLink" onclick="javascript:this.parentElement.parentElement.previousElementSibling.lastChild.value=\''+e+'\'">'+e+'</span>').join(', ');
var protocolSuggestions = ['HTTP/1.0','HTTP/1.1','HTTP/1.2','HTTP/1.3','HTTP/2.0'].map((e)=>'<span class="auxSuggestLink" onclick="javascript:this.parentElement.parentElement.previousElementSibling.lastChild.value=\''+e+'\'">'+e+'</span>').join(', ');
var verifiedSuggestions = ['AdSense','AmazonAdBot','Amazonbot','Applebot','Baidu','Bingbot','ChatGPT-User','DuckDuckBot','Embedly','Facebook external hit','GPTBot','Googlebot','MojeekBot','PetalBot','Pinterest','Qwantify','SeznamBot','Snapchat','Sogou','Twitterbot','Yahoo','Yandex','YoudaoBot'].map((e)=>'<span class="auxSuggestLink" onclick="javascript:this.parentElement.parentElement.previousElementSibling.lastChild.value=\''+e+'\'">'+e+'</span>').join(', ');
var langResSuggestions = ['af-ZA','ar','ar-SA','bg-BG','bn-BD','bs-BA','ca-ES','cs-CZ','de-CH','de-DE','en-AU','en-CA','en-GB','en-NZ','en-US','es-ES','es-MX','fa-IR','fr-CA','fr-FR','gl-ES','gu-IN','he-IL','hi-IN','hr-HR','id-ID','it-IT','ja-JP','ko-KR','lv-LV','ml-IN','mr-IN','ms-MY','nb-NO','nl-NL','pa-IN','pa-PK','pl-PL','pt-BR','pt-PT','ro-MO','ro-RO','ru-RU','sv-SE','sr-RS','ta-IN','th-TH','tr-TR','uk-UA','ur-PK','vi-VN','zh-CN','zh-Hans','zh-Hant','zh-HK','zh-TW'].map((e)=>'<span class="auxSuggestLink" onclick="javascript:this.parentElement.parentElement.previousElementSibling.lastChild.value=\''+e+'\'">'+e+'</span>').join(', ');
var ignoreSuggestions = {ignoreSuggestions};

function getInputSuggestions(e) {
  if (e.value=='ASNLookup') {
    e.parentElement.nextElementSibling.innerHTML='<small>{hints_asnlookup}</small>';
    e.parentElement.nextElementSibling.className='suggestsActive';
  } else if (e.value=='CCLookup') {
    e.parentElement.nextElementSibling.innerHTML='<small>{hints_cclookup}</small>';
    e.parentElement.nextElementSibling.className='suggestsActive';
  } else if (e.value=='Request_Method') {
    e.parentElement.nextElementSibling.innerHTML='<small>{label.Suggestions}{pair_separator}'+methodSuggestions+'</small>';
    e.parentElement.nextElementSibling.className='suggestsActive';
  } else if (e.value=='Profiles') {
    e.parentElement.nextElementSibling.innerHTML='<small>{label.Suggestions}{pair_separator}'+profileSuggestions+'</small>';
    e.parentElement.nextElementSibling.className='suggestsActive';
  } else if (e.value=='Protocol') {
    e.parentElement.nextElementSibling.innerHTML='<small>{label.Suggestions}{pair_separator}'+protocolSuggestions+'</small>';
    e.parentElement.nextElementSibling.className='suggestsActive';
  } else if (e.value=='Verified') {
    e.parentElement.nextElementSibling.innerHTML='<small>{label.Suggestions}{pair_separator}'+verifiedSuggestions+'</small>';
    e.parentElement.nextElementSibling.className='suggestsActive';
  } else if (e.value=='SEC_CH_UA_PLATFORM' || e.value=='SEC_CH_UA_MOBILE' || e.value=='SEC_CH_UA') {
    e.parentElement.nextElementSibling.innerHTML='<small>{hints_client_hints}</small>';
    e.parentElement.nextElementSibling.className='suggestsActive';
  } else if (e.value=='ClientL10NAccepted') {
    e.parentElement.nextElementSibling.innerHTML='<small>{label.Suggestions}{pair_separator}'+langResSuggestions+'</small>';
    e.parentElement.nextElementSibling.className='suggestsActive';
  } else if (e.value=='Factors') {
    e.parentElement.nextElementSibling.innerHTML='<small>{tip.An accepted value is any CIDR with a range that covers the IP address of the request}</small>';
    e.parentElement.nextElementSibling.className='suggestsActive';
  } else if (e.value=='Ignored'&&ignoreSuggestions!=='') {
    e.parentElement.nextElementSibling.innerHTML='<small>{label.Suggestions}{pair_separator}'+ignoreSuggestions+'</small>';
    e.parentElement.nextElementSibling.className='suggestsActive';
  } else {
    e.parentElement.nextElementSibling.innerHTML='';
    e.parentElement.nextElementSibling.className='suggestsInactive';
  }
}

function changeIfOrNot(z) {
  Array.prototype.forEach.call(z.parentElement.parentElement.parentElement.previousSibling.previousSibling.previousSibling.previousSibling.getElementsByClassName('ifOrNot'),(e)=> {
    if (e.value==='If') {
      posSym=z.value==='mtdDMA'?'≟':z.value==='mtdWin'?'≈':z.value==='mtdReg'?'≅':'=';
      e.textContent=posSym;
    } else {
      negSym=z.value==='mtdWin'?'≉':z.value==='mtdReg'?'≇':'≠';
      e.textContent=negSym;
    }
  });
}

function changeIfOrNotEditMode(z) {
  Array.prototype.forEach.call(z.parentElement.parentElement.previousSibling.previousSibling.getElementsByClassName('ifOrNot'),(e)=> {
    if (e.value==='If') {
      posSym=z.value==='mtdDMA'?'≟':z.value==='mtdWin'?'≈':z.value==='mtdReg'?'≅':'=';
      e.textContent=posSym;
    } else {
      negSym=z.value==='mtdWin'?'≉':z.value==='mtdReg'?'≇':'≠';
      e.textContent=negSym;
    }
  });
}

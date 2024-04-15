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
  window.auxMU = a, $('POST', '', ['auxMU'], null, function(a) {
    window.location.reload(true)
  })
}

function moveDown(a, i) {
  window.auxMD = a, $('POST', '', ['auxMD'], null, function(a) {
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
function addCondition(p) {
  var namePart = p.length > 0 ? '['+p+'][New'+conIter+']' : '[]',
  conId = 'condition'+conIter,
  t = document.createElement('div');
  t.setAttribute('id', conId),
  t.setAttribute('class', 'flexrow'),
  t.setAttribute('style', 'opacity:0;animation:xAux 2s ease 0s 1 normal'),
  document.getElementById(p+'conditions').appendChild(t),
  (t = document.createElement('select')).setAttribute('name', 'conSourceType'+namePart),
  t.setAttribute('class', 'auto'),{conSourcesJS}
  t.setAttribute('onchange', 'javascript:inputConstraintsBasedOnSource(this.nextElementSibling.nextElementSibling);'),
  document.getElementById(conId).appendChild(t),
  (t = document.createElement('select')).setAttribute('name', 'conIfOrNot'+namePart),
  t.setAttribute('class', 'auto'),
  x = document.createElement('option'),
  x.setAttribute('value', 'If'),
  x.innerHTML = '=',
  t.appendChild(x),
  x = document.createElement('option'),
  x.setAttribute('value', 'Not'),
  x.innerHTML = '≠',
  t.appendChild(x),
  document.getElementById(conId).appendChild(t),
  (t = document.createElement('input')).setAttribute('name', 'conSourceValue'+namePart),
  t.setAttribute('placeholder', '{tip.Specify a value, or leave blank to disregard}'),
  t.setAttribute('class', 'flexin'),
  t.setAttribute('type', 'text'),
  t.setAttribute('onfocus', 'javascript:inputConstraintsBasedOnSource(this);'),
  document.getElementById(conId).appendChild(t),
  setTimeout(function() {
    document.getElementById(conId).style.opacity = '1'
  }, 1999),
  t = document.createElement('div');
  t.setAttribute('class', 'suggestsInactive s'),
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
  t.setAttribute('placeholder', '{tip.Specify a value, or leave blank to disregard}'),
  t.setAttribute('class', 'f500'),
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
  document.getElementById('heaven'+c).classList.toggle('scaleXToZero');
  document.getElementById('heaven'+c).classList.toggle('scaleXToOne');
  document.getElementById('hidden'+c).classList.toggle('scaleXToZero');
  document.getElementById('hidden'+c).classList.toggle('scaleXToOne');
}

var profileSuggestions = ['Advertiser','Bogon','Commercial','Content Delivery Network','Dedicated','Domestic ISP','Frequent changes','Government','Has WordPress Bypasses','Infrastructure/Transit','Mobile ISP','Multiplay','Restricted/Unidentifiable','Search engine','Temporary','Third-party sourced','Tor endpoints here','University','Usenet','VPNs here','Webhosting','6to4','Amateur Radio','ISATAP','Multicast','Orphaned','Teredo','Blocked Negative','Blocked Non-Verified','Organization','Military','University/College/School','Library','Fixed Line ISP','Data Center/Web Hosting/Transit','Search Engine Spider','Reserved'].map((e)=>'<span style="cursor:pointer" onclick="javascript:this.parentElement.parentElement.previousElementSibling.lastChild.value=\''+e+'\'">'+e+'</span>').join(', ');
var methodSuggestions = ['GET','POST','HEAD','CONNECT','DELETE','OPTIONS','PATCH','PUT','TRACE'].map((e)=>'<span style="cursor:pointer" onclick="javascript:this.parentElement.parentElement.previousElementSibling.lastChild.value=\''+e+'\'">'+e+'</span>').join(', ');

function inputConstraintsBasedOnSource(e) {
  if (e.previousElementSibling.previousElementSibling.value=='Profiles') {
    e.parentElement.nextElementSibling.innerHTML='<small>{label.Suggestions}{pair_separator}'+profileSuggestions+'</small>';
    e.parentElement.nextElementSibling.className='suggestsActive';
  } else if (e.previousElementSibling.previousElementSibling.value=='Request_Method') {
    e.parentElement.nextElementSibling.innerHTML='<small>{label.Suggestions}{pair_separator}'+methodSuggestions+'</small>';
    e.parentElement.nextElementSibling.className='suggestsActive';
  } else {
    e.parentElement.nextElementSibling.innerHTML='';
    e.parentElement.nextElementSibling.className='suggestsInactive';
  }
}

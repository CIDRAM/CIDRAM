function delRule(a, i) {
  window.auxD = a, $('POST', '', ['auxD'], null, function(a) {
    null != i && hide(i);
    w('stateMsg', a)
  })
}

function moveToTop(a, i) {
  window.auxT = a, $('POST', '', ['auxT'], null, function(a) {
    window.location.reload()
  })
}

function moveToBottom(a, i) {
  window.auxB = a, $('POST', '', ['auxB'], null, function(a) {
    window.location.reload()
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
    301 != statusCode && 307 != statusCode && 308 != statusCode && (document.getElementById(p+'statusCode301').checked = true);
    document.getElementById(p+'statusCodeX').disabled = true;
    document.getElementById(p+'statusCode301').disabled = false;
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
}

var conIn = 1;
var addCondition = function(p) {
  if (p.length > 0) {
    var conAdd = '<select name="conSourceType['+p+'][]" class="auto">{conSources}</select><select name="conIfOrNot['+p+'][]" class="auto"><option value="If">=</option><option value="Not">≠</option></select><input type="text" name="conSourceValue['+p+'][]" placeholder="{tip_condition_placeholder}" class="f400" />';
  } else {
    var conAdd = '<select name="conSourceType[]" class="auto">{conSources}</select><select name="conIfOrNot[]" class="auto"><option value="If">=</option><option value="Not">≠</option></select><input type="text" name="conSourceValue[]" placeholder="{tip_condition_placeholder}" class="f400" />';
  }
  var conId = 'condition' + conIn, t = document.createElement('div');
  t.setAttribute('id', conId),
  t.setAttribute('style', 'opacity:0.0;animation:xAux 2.0s ease 0s 1 normal'),
  document.getElementById(p+'conditions').appendChild(t),
  document.getElementById(conId).innerHTML = conAdd, setTimeout(function() {
    document.getElementById(conId).style.opacity = '1.0'
  }, 1999),
  conIn++
};

var whIn = 1;
var addWebhook = function(p) {
  if (p.length > 0) {
    var whAdd = '<input type="text" name="webhooks['+p+'][]" placeholder="{tip_condition_placeholder}" class="f500" />';
  } else {
    var whAdd = '<input type="text" name="webhooks[]" placeholder="{tip_condition_placeholder}" class="f500" />';
  }
  var whId = 'webhook' + whIn, t = document.createElement('div');
  t.setAttribute('id', whId),
  t.setAttribute('style', 'opacity:0.0;animation:xAux 2.0s ease 0s 1 normal'),
  document.getElementById(p+'webhooks').appendChild(t), document.getElementById(whId).innerHTML = whAdd, setTimeout(function() {
    document.getElementById(whId).style.opacity = '1.0'
  }, 1999),
  whIn++
};

var createNewRule = function() {
  var e = true, z = !1;
  document.getElementById('ruleName').value || (e = !1, document.getElementById('ruleNameDd').style.animation = 'rAux 1.0s ease 0s 1 normal', setTimeout(function() {
    document.getElementById('ruleNameDd').style.animation = ''
  }, 999)), document.getElementById('ruleReason').value || 'actBlk' != document.getElementById('act').value || (e = !1, document.getElementById('ruleReasonDd').style.animation = 'rAux 1.0s ease 0s 1 normal', setTimeout(function() {
    document.getElementById('ruleReasonDd').style.animation = ''
  }, 999)), document.getElementById('ruleTarget').value || 'actRdr' != document.getElementById('act').value || (e = !1, document.getElementById('ruleTargetDd').style.animation = 'rAux 1.0s ease 0s 1 normal', setTimeout(function() {
    document.getElementById('ruleTargetDd').style.animation = ''
  }, 999)), document.getElementsByName('conSourceValue[]').forEach(function(n) {
    null !== n.value && '' !== n.value && (z = true)
  }), z || (document.getElementById('conditions').style.animation = 'rAux 1.0s ease 0s 1 normal', setTimeout(function() {
    document.getElementById('conditions').style.animation = ''
  }, 999)), e && z && document.getElementById('auxForm').submit()
};

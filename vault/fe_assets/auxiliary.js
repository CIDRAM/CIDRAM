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

function onAuxActionChange(e) {
  var statusCode = document.querySelector('input[name="statusCode"]:checked').value || 200;
  if ('actBlk' === e) {
    statusCode < 400 && (document.getElementById('statusCodeX').checked = true);
    document.getElementById('statusCode403').disabled = false;
    document.getElementById('statusCode410').disabled = false;
    document.getElementById('statusCode418').disabled = false;
    document.getElementById('statusCode451').disabled = false;
    document.getElementById('statusCode503').disabled = false;
    document.getElementById('ruleReasonDd').style.filter = '';
    document.getElementById('ruleReasonDt').style.textDecoration = 'none';
    document.getElementById('statGroup45').style.filter = '';
    document.getElementById('statGroup45').style.backgroundColor = '';
  } else {
    document.getElementById('statusCode403').disabled = true;
    document.getElementById('statusCode410').disabled = true;
    document.getElementById('statusCode418').disabled = true;
    document.getElementById('statusCode451').disabled = true;
    document.getElementById('statusCode503').disabled = true;
    document.getElementById('ruleReasonDd').style.filter = 'grayscale(80%) brightness(80%)';
    document.getElementById('ruleReasonDt').style.textDecoration = 'line-through';
    document.getElementById('statGroup45').style.filter = 'grayscale(80%) brightness(80%)';
    document.getElementById('statGroup45').style.backgroundColor = 'rgba(0,0,0,0.1)';
  }
  if ('actRdr' === e) {
    301 != statusCode && 307 != statusCode && 308 != statusCode && (document.getElementById('statusCode301').checked = true);
    document.getElementById('statusCodeX').disabled = true;
    document.getElementById('statusCode301').disabled = false;
    document.getElementById('statusCode307').disabled = false;
    document.getElementById('statusCode308').disabled = false;
    document.getElementById('statusCodeX').style.filter = 'grayscale(80%) brightness(80%)';
    document.getElementById('ruleTargetDd').style.filter = '';
    document.getElementById('ruleTargetDt').style.textDecoration = 'none';
    document.getElementById('statGroupX').style.filter = 'grayscale(80%) brightness(80%)';
    document.getElementById('statGroupX').style.backgroundColor = 'rgba(0,0,0,0.1)';
    document.getElementById('statGroup3').style.filter = '';
    document.getElementById('statGroup3').style.backgroundColor = '';
  } else {
    document.getElementById('statusCodeX').disabled = false;
    document.getElementById('statusCode301').disabled = true;
    document.getElementById('statusCode307').disabled = true;
    document.getElementById('statusCode308').disabled = true;
    document.getElementById('statusCodeX').style.filter = '';
    document.getElementById('ruleTargetDd').style.filter = 'grayscale(80%) brightness(80%)';
    document.getElementById('ruleTargetDt').style.textDecoration = 'line-through';
    document.getElementById('statGroupX').style.filter = '';
    document.getElementById('statGroupX').style.backgroundColor = '';
    document.getElementById('statGroup3').style.filter = 'grayscale(80%) brightness(80%)';
    document.getElementById('statGroup3').style.backgroundColor = 'rgba(0,0,0,0.1)';
  }
  if ('actBlk' !== e && 'actRdr' !== e) {
    document.getElementById('statusCodeX').checked = true;
  }
}

var conIn = 1;
var conAdd = '<select name="conSourceType[]" class="auto">{conSources}</select> <select name="conIfOrNot[]" class="auto"><option value="If">=</option><option value="Not">≠</option></select> <input type="text" name="conSourceValue[]" placeholder="{tip_condition_placeholder}" class="f400" />';
var addCondition = function() {
  var conId = 'condition' + conIn, t = document.createElement('div');
  t.setAttribute('id', conId), t.setAttribute('style', 'opacity:0.0;animation:xAux 2.0s ease 0s 1 normal'), document.getElementById('conditions').appendChild(t), document.getElementById(conId).innerHTML = conAdd, setTimeout(function() {
    document.getElementById(conId).style.opacity = '1.0'
  }, 1999), conIn++
};

var whIn = 1;
var whAdd = '<input type="text" name="webhooks[]" placeholder="{tip_condition_placeholder}" class="f500" />';
var addWebhook = function() {
  var whId = 'webhook' + whIn, t = document.createElement('div');
  t.setAttribute('id', whId), t.setAttribute('style', 'opacity:0.0;animation:xAux 2.0s ease 0s 1 normal'), document.getElementById('webhooks').appendChild(t), document.getElementById(whId).innerHTML = whAdd, setTimeout(function() {
    document.getElementById(whId).style.opacity = '1.0'
  }, 1999), whIn++
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

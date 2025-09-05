function renameFile(a, b, i) {
  window.filename = a, window.filename_new = b, $('POST', '', ['filename', 'filename_new'], null, function(a) {
    a = null != a ? a.split("\n") : [];
    if (a[1] === 'OK') {
      document.getElementById('File' + i).textContent = window.filename_new;
    } else {
      document.getElementById('RenameInput' + i).value = window.filename;
      alert(a[0]);
    }
  })
}
function deleteFile(a, i) {
  window.filename = a, window.do_action = 'delete-file', $('POST', '', ['filename', 'do_action'], null, function(a) {
    a = null != a ? a.split("\n") : [];
    if (a[1] === 'OK') {
      document.getElementById('Name' + i).classList.add('fmDelete');
      document.getElementById('Size' + i).classList.add('fmDelete');
      document.getElementById('Component' + i).classList.add('fmDelete');
      document.getElementById('Options' + i).classList.add('fmDelete');
    } else {
      alert(a[0]);
    }
  })
}

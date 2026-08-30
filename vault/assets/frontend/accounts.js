window['cidram-form-target']='accounts';
function acc(e,d,i,t){var o=function(e){w('stateMsg',e)},a=function(){w('stateMsg','{Loading}')};window.username=document.getElementById(e).value,window.password=document.getElementById(d).value,window.do=document.getElementById(t).value,'delete-account'==window.do&&$('POST','',['cidram-form-target','username','password','do'],a,function(e){w('stateMsg',e),document.getElementById('q1'+i).classList.add('fmDelete'),document.getElementById('q2'+i).classList.add('fmDelete'),document.getElementById('q3'+i).classList.add('fmDelete'),document.getElementById('q4'+i).classList.add('fmDelete')},o),'update-password'==window.do&&$('POST','',['cidram-form-target','username','password','do'],a,o,o)}

// "List of the most common passwords", sourced from: https://en.wikipedia.org/wiki/List_of_the_most_common_passwords
// Used to discourage the use of vulnerable passwords when changing passwords at the accounts page. Data is likely to
// stale over time and should probably be updated roughly every year or so. Using this instead of a more comprehensive
// list (e.g., rockyou) or library to avoid excessive processing (i.e., checks taking too much time) and package bloat.
// Passwords less than 8 characters omitted as the page will warn for those anyway. Checking at least these means we're
// at least guarding against the worst of offences in regard to password choice, but the user should take some
// responsibility here too, use some common sense, and not use something stupid for their password. This package is not
// a password management tool, after all. Last updated: 2026.08.30

var vulnerablePasswords = [
  '!@#$%^&*',
  '12345678',
  '123456789',
  '1234567890',
  '12345678910',
  '1234qwer',
  '18atcskd2w',
  '1q2w3e4r',
  '1q2w3e4r5t',
  '1qaz2wsx',
  '3rjs1la7qe',
  '987654321',
  'aa123456',
  'aa@123456',
  'admin123',
  'admin@123',
  'admintelecom',
  'adobe123[a]',
  'baseball',
  'contraseña',
  'football',
  'graciela',
  'iloveyou',
  'margarita',
  'mustufaj',
  'p@ssw0rd',
  'pakistan123',
  'pass@123',
  'passw0rd',
  'password',
  'password1',
  'photoshop[a]',
  'princess',
  'qwerty123',
  'qwertyuiop',
  'starwars',
  'sunshine',
  'superman',
  'trustno1',
  'valentina',
  'veronica',
  'virginia',
  'whatever',
  'zaq1zaq1',
  'zxcvbnm'
];

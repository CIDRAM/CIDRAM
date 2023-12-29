/**
 * JavaScript version of Maikuolan\Common\NumberFormatter's format method.
 * Formats the supplied number according to definitions.
 *
 * @param {any} Number The number to format (int, float, string, etc).
 * @param {number} Decimals The number of decimal places (optional).
 * @returns {string} The formatted number, or an empty string on failure.
 */
function nft(Number, Decimals = 0) {
  let ConversionSet = JSON.parse('%s');
  let GroupSeparator = '%s';
  let GroupSize = %s;
  let GroupOffset = %s;
  let DecimalSeparator = '%s';
  let Base = %s;
  if (Base < 2 || Base > 36) {
    return '';
  }
  var DecPos = String(Number).indexOf('.');
  let Fraction;
  if (DecPos !== -1) {
    if (Decimals > 0 && DecimalSeparator && !ConversionSet['.']) {
      Fraction = (String(Number).substring(DecPos + 1)) || '';
      let Len = Fraction.length;
      if (Len > 0) {
        Fraction = convertFraction(Fraction, 10, Base, Decimals);
        Fraction = Fraction.substring(0, Decimals);
        Len = Fraction.length;
      }
      if (Len < Decimals) {
        Fraction += '0'.repeat(Decimals - Len);
      }
    }
    Number = String(Number).substring(0, DecPos);
  } else {
    Number = String(Number);
  }
  if (Base !== 10) {
    Number = parseInt(Number).toString(Base);
  }
  let Formatted = '';
  let WholeLen = -1;
  if (typeof ConversionSet['=' + Number] != 'undefined') {
    Formatted = ConversionSet['=' + Number];
    WholeLen = -1;
  } else {
    WholeLen = Number.length;
  }
  let ThouPos = GroupOffset;
  for (let Unit = 0, Pos = WholeLen - 1; Pos > -1; Pos--, Unit++) {
    if (ThouPos >= GroupSize) {
      ThouPos = 1;
      Formatted = GroupSeparator + Formatted;
    } else {
      ThouPos++;
    }
    var Key = Number.substring(Pos, Pos + 1);
    var Double = (Pos > 0) ? Number.substring(Pos - 1, Pos) + Key : '';
    let Power = '';
    let Digit = '';
    if (typeof ConversionSet['^' + Unit + '+' + Double] != 'undefined') {
      Digit = ConversionSet['^' + Unit + '+' + Double];
    } else if (typeof ConversionSet['^' + Unit + '+' + Key] != 'undefined') {
      Digit = ConversionSet['^' + Unit + '+' + Key];
    } else if (typeof ConversionSet['+' + Key] != 'undefined') {
      Digit = ConversionSet['+' + Key];
    } else {
      Digit = (typeof ConversionSet[Key] != 'undefined') ? ConversionSet[Key] : Key;
      if (typeof ConversionSet['^' + Unit + '*' + Key] != 'undefined') {
        Power = ConversionSet['^' + Unit + '*' + Key];
      } else if (typeof ConversionSet['^' + Unit] != 'undefined') {
        Power = ConversionSet['^' + Unit];
      }
    }
    Formatted = Digit + Power + Formatted;
  }
  if (Fraction && Decimals && DecimalSeparator && !ConversionSet['.']) {
    Formatted += DecimalSeparator;
    for (let Pos = 0, Len = Fraction.length; Pos < Len; Pos++) {
      var Key = Fraction.substring(Pos, Pos + 1);
      let Power = '';
      let Digit = '';
      if (typeof ConversionSet['^-' + Pos + '+' + Key] != 'undefined') {
        Digit = ConversionSet['^-' + Pos + '+' + Key];
      } else if (typeof ConversionSet['-+' + Key] != 'undefined') {
        Digit = ConversionSet['-+' + Key];
      } else {
        if (typeof ConversionSet['-' + Key] != 'undefined') {
          Digit = ConversionSet['-' + Key];
        } else {
          Digit = (typeof ConversionSet[Key] != 'undefined') ? ConversionSet[Key] : Key;
        }
        if (typeof ConversionSet['^-' + Pos + '*' + Key] != 'undefined') {
          Power = ConversionSet['^-' + Pos + '*' + Key];
        } else if (typeof ConversionSet['^-' + Pos] != 'undefined') {
          Power = ConversionSet['^-' + Pos];
        }
      }
      Formatted += Digit + Power;
    }
  }
  var DecLen = DecimalSeparator.length;
  if (DecLen && Formatted.substring(0, DecLen) === DecimalSeparator) {
    Formatted = Formatted.substring(DecLen);
  }
  return Formatted;
}

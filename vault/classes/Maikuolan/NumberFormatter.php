<?php
/**
 * Number formatter (last modified: 2025.03.19).
 *
 * This file is a part of the "common classes package", utilised by a number of
 * packages and projects, including CIDRAM and phpMussel.
 * @link https://github.com/Maikuolan/Common
 *
 * License: GNU/GPLv2
 * @see LICENSE.txt
 *
 * "COMMON CLASSES PACKAGE", as well as the earliest iteration and deployment
 * of this class, COPYRIGHT 2019 and beyond by Caleb Mazalevskis (Maikuolan).
 */

namespace Maikuolan\Common;

class NumberFormatter extends CommonAbstract
{
    /**
     * @var string Identifies the conversion set to use.
     */
    public $ConversionSet = 'Western';

    /**
     * @var string Identifies the separator to use for separating number groups.
     */
    public $GroupSeparator = ',';

    /**
     * @var int Identifies the group size to use for separating number groups.
     */
    public $GroupSize = 3;

    /**
     * @var int Identifies the offset to use when counting the size of number groups.
     */
    public $GroupOffset = 0;

    /**
     * @var string Identifies the decimal separator to use.
     */
    public $DecimalSeparator = '.';

    /**
     * @var int Identifies the base system of the target format.
     */
    public $Base = 10;

    /**
     * @var int Maximum ratio or degrees possible when calculating fractions.
     */
    private $MaxDegrees = 9999;

    /**
     * @var array Conversion set for Hindu-Arabic or Western Arabic numerals.
     *      The array here is intentionally empty, because it's our default
     *      "conversion set" to use (keeping it here in order to be explicit).
     */
    private $Western = [];

    /**
     * @var array Conversion set for Eastern Arabic numerals.
     */
    private $Eastern = [
        '0' => '٠',
        '1' => '١',
        '2' => '٢',
        '3' => '٣',
        '4' => '٤',
        '5' => '٥',
        '6' => '٦',
        '7' => '٧',
        '8' => '٨',
        '9' => '٩'
    ];

    /**
     * @var array Conversion set for Persian/Urdu numerals (Eastern Arabic variant).
     */
    private $Persian = [
        '0' => '۰',
        '1' => '۱',
        '2' => '۲',
        '3' => '۳',
        '4' => '۴',
        '5' => '۵',
        '6' => '۶',
        '7' => '۷',
        '8' => '۸',
        '9' => '۹'
    ];

    /**
     * @var array Conversion set for Nagari/Bengali/Bangla numerals.
     */
    private $Nagari = [
        '0' => '০',
        '1' => '১',
        '2' => '২',
        '3' => '৩',
        '4' => '৪',
        '5' => '৫',
        '6' => '৬',
        '7' => '৭',
        '8' => '৮',
        '9' => '৯'
    ];

    /**
     * @var array Conversion set for Devanagari numerals (used by Hindi, Marathi, etc).
     */
    private $Devanagari = [
        '0' => '०',
        '1' => '१',
        '2' => '२',
        '3' => '३',
        '4' => '४',
        '5' => '५',
        '6' => '६',
        '7' => '७',
        '8' => '८',
        '9' => '९'
    ];

    /**
     * @var array Conversion set for Gujarati numerals.
     */
    private $Gujarati = [
        '0' => '૦',
        '1' => '૧',
        '2' => '૨',
        '3' => '૩',
        '4' => '૪',
        '5' => '૫',
        '6' => '૬',
        '7' => '૭',
        '8' => '૮',
        '9' => '૯'
    ];

    /**
     * @var array Conversion set for Gurmukhi/Punjabi numerals.
     */
    private $Gurmukhi = [
        '0' => '੦',
        '1' => '੧',
        '2' => '੨',
        '3' => '੩',
        '4' => '੪',
        '5' => '੫',
        '6' => '੬',
        '7' => '੭',
        '8' => '੮',
        '9' => '੯'
    ];

    /**
     * @var array Conversion set for Kannada numerals.
     */
    private $Kannada = [
        '0' => '೦',
        '1' => '೧',
        '2' => '೨',
        '3' => '೩',
        '4' => '೪',
        '5' => '೫',
        '6' => '೬',
        '7' => '೭',
        '8' => '೮',
        '9' => '೯'
    ];

    /**
     * @var array Conversion set for Telugu numerals.
     */
    private $Telugu = [
        '0' => '౦',
        '1' => '౧',
        '2' => '౨',
        '3' => '౩',
        '4' => '౪',
        '5' => '౫',
        '6' => '౬',
        '7' => '౭',
        '8' => '౮',
        '9' => '౯'
    ];

    /**
     * @var array Conversion set for Burmese numerals.
     */
    private $Burmese = [
        '0' => '၀',
        '1' => '၁',
        '2' => '၂',
        '3' => '၃',
        '4' => '၄',
        '5' => '၅',
        '6' => '၆',
        '7' => '၇',
        '8' => '၈',
        '9' => '၉'
    ];

    /**
     * @var array Conversion set for Khmer numerals.
     */
    private $Khmer = [
        '0' => '០',
        '1' => '១',
        '2' => '២',
        '3' => '៣',
        '4' => '៤',
        '5' => '៥',
        '6' => '៦',
        '7' => '៧',
        '8' => '៨',
        '9' => '៩'
    ];

    /**
     * @var array Conversion set for Thai numerals.
     */
    private $Thai = [
        '0' => '๐',
        '1' => '๑',
        '2' => '๒',
        '3' => '๓',
        '4' => '๔',
        '5' => '๕',
        '6' => '๖',
        '7' => '๗',
        '8' => '๘',
        '9' => '๙'
    ];

    /**
     * @var array Conversion set for Lao numerals.
     */
    private $Lao = [
        '0' => '໐',
        '1' => '໑',
        '2' => '໒',
        '3' => '໓',
        '4' => '໔',
        '5' => '໕',
        '6' => '໖',
        '7' => '໗',
        '8' => '໘',
        '9' => '໙'
    ];

    /**
     * @var array Conversion set for Mayan numerals (unlikely to ever be
     *      needed, but serves as an amusing "easter egg" to demonstrate
     *      the capabilities of the class).
     */
    private $Mayan = [
        '0' => '𝋠',
        '1' => '𝋡',
        '2' => '𝋢',
        '3' => '𝋣',
        '4' => '𝋤',
        '5' => '𝋥',
        '6' => '𝋦',
        '7' => '𝋧',
        '8' => '𝋨',
        '9' => '𝋩',
        'a' => '𝋪',
        'b' => '𝋫',
        'c' => '𝋬',
        'd' => '𝋭',
        'e' => '𝋮',
        'f' => '𝋯',
        'g' => '𝋰',
        'h' => '𝋱',
        'i' => '𝋲',
        'j' => '𝋳'
    ];

    /**
     * @var array Conversion set for Japanese numerals.
     */
    private $Japanese = [
        '+0' => '',
        '-+0' => '',
        '1' => '',
        '2' => '二',
        '3' => '三',
        '4' => '四',
        '5' => '五',
        '6' => '六',
        '7' => '七',
        '8' => '八',
        '9' => '九',
        '^0+1' => '一',
        '^1' => '十',
        '^2' => '百',
        '^3' => '千',
        '^4' => '万',
        '^5' => '十万',
        '^6' => '百万',
        '^7' => '千万',
        '^8' => '億',
        '^9' => '十億',
        '^10' => '百億',
        '^11' => '千億',
        '^12' => '兆',
        '^13' => '十兆',
        '^14' => '百兆',
        '^15' => '千兆',
        '^16' => '京',
        '^17' => '十京',
        '^18' => '百京',
        '^19' => '千京',
        '^20' => '垓',
        '^21' => '十垓',
        '^22' => '百垓',
        '^23' => '千垓',
        '^-1' => '分',
        '^-2' => '厘',
        '^-3' => '毛',
        '^-4' => '糸',
        '^-5' => '忽',
        '^-6' => '微',
        '^-7' => '繊',
        '^-8' => '沙',
        '^-9' => '塵',
        '^-10' => '埃'
    ];

    /**
     * @var array Conversion set for Tamil numerals.
     */
    private $Tamil = [
        '.' => true,
        '+0' => '',
        '1' => '',
        '2' => '௨',
        '3' => '௩',
        '4' => '௪',
        '5' => '௫',
        '6' => '௬',
        '7' => '௭',
        '8' => '௮',
        '9' => '௯',
        '^0+1' => '௧',
        '^1' => '௰',
        '^2' => '௱',
        '^3' => '௲',
        '^4' => '௰௲',
        '^5' => '௱௲',
        '^6' => '௲௲',
        '^7' => '௰௲௲',
        '^8' => '௱௲௲',
        '^9' => '௲௲௲',
        '^10' => '௰௲௲௲',
        '^11' => '௱௲௲௲',
        '^12' => '௲௲௲௲',
        '^13' => '௰௲௲௲௲',
        '^14' => '௱௲௲௲௲',
        '^15' => '௲௲௲௲௲',
        '^16' => '௰௲௲௲௲௲',
        '^17' => '௱௲௲௲௲௲',
        '^18' => '௲௲௲௲௲௲',
        '^19' => '௰௲௲௲௲௲௲',
        '^20' => '௱௲௲௲௲௲௲',
        '^21' => '௲௲௲௲௲௲௲',
        '^22' => '௰௲௲௲௲௲௲௲',
        '^23' => '௱௲௲௲௲௲௲௲'
    ];

    /**
     * @var array Conversion set for Javanese numerals.
     */
    private $Javanese = [
        '0' => '꧐',
        '1' => '꧑',
        '2' => '꧒',
        '3' => '꧓',
        '4' => '꧔',
        '5' => '꧕',
        '6' => '꧖',
        '7' => '꧗',
        '8' => '꧘',
        '9' => '꧙'
    ];

    /**
     * @var array Conversion set for Roman numerals (modern standard form with vinculum).
     */
    private $Roman = [
        '.' => true,
        '0' => '',
        '1' => '',
        '2' => '',
        '3' => '',
        '4' => '',
        '5' => '',
        '6' => '',
        '7' => '',
        '8' => '',
        '9' => '',
        '^0+1' => 'I',
        '^0+2' => 'II',
        '^0+3' => 'III',
        '^0+4' => 'IV',
        '^0+5' => 'V',
        '^0+6' => 'VI',
        '^0+7' => 'VII',
        '^0+8' => 'VIII',
        '^0+9' => 'IX',
        '^1+1' => 'X',
        '^1+2' => 'XX',
        '^1+3' => 'XXX',
        '^1+4' => 'XL',
        '^1+5' => 'L',
        '^1+6' => 'LX',
        '^1+7' => 'LXX',
        '^1+8' => 'LXXX',
        '^1+9' => 'XC',
        '^2+1' => 'C',
        '^2+2' => 'CC',
        '^2+3' => 'CCC',
        '^2+4' => 'CD',
        '^2+5' => 'D',
        '^2+6' => 'DC',
        '^2+7' => 'DCC',
        '^2+8' => 'DCCC',
        '^2+9' => 'CM',
        '^3+1' => 'M',
        '^3+2' => 'MM',
        '^3+3' => 'MMM',
        '^3+4' => 'I̅V̅',
        '^3+5' => 'V̅',
        '^3+6' => 'V̅I̅',
        '^3+7' => 'V̅I̅I̅',
        '^3+8' => 'V̅I̅I̅I̅',
        '^3+9' => 'I̅X̅',
        '^4+1' => 'X̅',
        '^4+2' => 'X̅X̅',
        '^4+3' => 'X̅X̅X̅',
        '^4+4' => 'X̅L̅',
        '^4+5' => 'L̅',
        '^4+6' => 'L̅X̅',
        '^4+7' => 'L̅X̅X̅',
        '^4+8' => 'L̅X̅X̅X̅',
        '^4+9' => 'X̅C̅',
        '^5+1' => 'C̅',
        '^5+2' => 'C̅C̅',
        '^5+3' => 'C̅C̅C̅',
        '^5+4' => 'C̅D̅',
        '^5+5' => 'D̅',
        '^5+6' => 'D̅C̅',
        '^5+7' => 'D̅C̅C̅',
        '^5+8' => 'D̅C̅C̅C̅',
        '^5+9' => 'C̅M̅',
        '^6+1' => 'M̅',
        '^6+2' => 'M̅M̅',
        '^6+3' => 'M̅M̅M̅'
    ];

    /**
     * @var array Conversion set for Odia numerals.
     */
    private $Odia = [
        '0' => '୦',
        '1' => '୧',
        '2' => '୨',
        '3' => '୩',
        '4' => '୪',
        '5' => '୫',
        '6' => '୬',
        '7' => '୭',
        '8' => '୮',
        '9' => '୯'
    ];

    /**
     * @var array Conversion set for Tibetan numerals.
     */
    private $Tibetan = [
        '0' => '༠',
        '1' => '༡',
        '2' => '༢',
        '3' => '༣',
        '4' => '༤',
        '5' => '༥',
        '6' => '༦',
        '7' => '༧',
        '8' => '༨',
        '9' => '༩'
    ];

    /**
     * @var array Conversion set for Mongolian numerals.
     */
    private $Mongolian = [
        '0' => '᠐',
        '1' => '᠑',
        '2' => '᠒',
        '3' => '᠓',
        '4' => '᠔',
        '5' => '᠕',
        '6' => '᠖',
        '7' => '᠗',
        '8' => '᠘',
        '9' => '᠙'
    ];

    /**
     * @var array Conversion set for Hebrew numerals (modern standard).
     */
    private $Hebrew = [
        '.' => true,
        '+0' => '',
        '1' => 'א',
        '2' => 'ב',
        '3' => 'ג',
        '4' => 'ד',
        '5' => 'ה',
        '6' => 'ו',
        '7' => 'ז',
        '8' => 'ח',
        '9' => 'ט',
        '^0+10' => 'י',
        '^0+11' => 'יא',
        '^0+12' => 'יב',
        '^0+13' => 'יג',
        '^0+14' => 'יד',
        '^0+15' => 'ט״ו',
        '^0+16' => 'ט״ז',
        '^0+17' => 'יז',
        '^0+18' => 'יח',
        '^0+19' => 'יט',
        '^1+1' => '',
        '^1+2' => 'כ',
        '^1+3' => 'ל',
        '^1+4' => 'מ',
        '^1+5' => 'נ',
        '^1+6' => 'ס',
        '^1+7' => 'ע',
        '^1+8' => 'פ',
        '^1+9' => 'צ',
        '^2+1' => 'ק',
        '^2+2' => 'ר',
        '^2+3' => 'ש',
        '^2+4' => 'ת',
        '^2+5' => 'ך',
        '^2+6' => 'ם',
        '^2+7' => 'ן',
        '^2+8' => 'ף',
        '^2+9' => 'ץ',
        '^3' => '׳',
        '^4' => '׳י',
        '^5' => '׳ק',
        '^6' => '׳׳',
        '^7' => '׳י׳',
        '^8' => '׳ק׳',
        '^9' => '׳׳׳',
        '^10' => '׳י׳׳',
        '^11' => '׳ק׳׳',
        '^12' => '׳׳׳׳',
        '^13' => '׳י׳׳׳',
        '^14' => '׳ק׳׳׳',
        '^15' => '׳׳׳׳׳'
    ];

    /**
     * @var array Conversion set for Armenian numerals (historic with overline).
     */
    private $Armenian = [
        '.' => true,
        '0' => '',
        '1' => '',
        '2' => '',
        '3' => '',
        '4' => '',
        '5' => '',
        '6' => '',
        '7' => '',
        '8' => '',
        '9' => '',
        '^0+1' => 'Ա',
        '^0+2' => 'Բ',
        '^0+3' => 'Գ',
        '^0+4' => 'Դ',
        '^0+5' => 'Ե',
        '^0+6' => 'Զ',
        '^0+7' => 'Է',
        '^0+8' => 'Ը',
        '^0+9' => 'Թ',
        '^1+1' => 'Ժ',
        '^1+2' => 'Ի',
        '^1+3' => 'Լ',
        '^1+4' => 'Խ',
        '^1+5' => 'Ծ',
        '^1+6' => 'Կ',
        '^1+7' => 'Հ',
        '^1+8' => 'Ձ',
        '^1+9' => 'Ղ',
        '^2+1' => 'Ճ',
        '^2+2' => 'Մ',
        '^2+3' => 'Յ',
        '^2+4' => 'Ն',
        '^2+5' => 'Շ',
        '^2+6' => 'Ո',
        '^2+7' => 'Չ',
        '^2+8' => 'Պ',
        '^2+9' => 'Ջ',
        '^3+1' => 'Ռ',
        '^3+2' => 'Ս',
        '^3+3' => 'Վ',
        '^3+4' => 'Տ',
        '^3+5' => 'Ր',
        '^3+6' => 'Ց',
        '^3+7' => 'Ւ',
        '^3+8' => 'Փ',
        '^3+9' => 'Ք',
        '^4+1' => 'Ա̅',
        '^4+2' => 'Բ̅',
        '^4+3' => 'Գ̅',
        '^4+4' => 'Դ̅',
        '^4+5' => 'Ե̅',
        '^4+6' => 'Զ̅',
        '^4+7' => 'Է̅',
        '^4+8' => 'Ը̅',
        '^4+9' => 'Թ̅',
        '^5+1' => 'Ժ̅',
        '^5+2' => 'Ի̅',
        '^5+3' => 'Լ̅',
        '^5+4' => 'Խ̅',
        '^5+5' => 'Ծ̅',
        '^5+6' => 'Կ̅',
        '^5+7' => 'Հ̅',
        '^5+8' => 'Ձ̅',
        '^5+9' => 'Ղ̅',
        '^6+1' => 'Ճ̅',
        '^6+2' => 'Մ̅',
        '^6+3' => 'Յ̅',
        '^6+4' => 'Ն̅',
        '^6+5' => 'Շ̅',
        '^6+6' => 'Ո̅',
        '^6+7' => 'Չ̅',
        '^6+8' => 'Պ̅',
        '^6+9' => 'Ջ̅',
        '^7+1' => 'Ռ̅',
        '^7+2' => 'Ս̅',
        '^7+3' => 'Վ̅',
        '^7+4' => 'Տ̅',
        '^7+5' => 'Ր̅',
        '^7+6' => 'Ց̅',
        '^7+7' => 'Ւ̅',
        '^7+8' => 'Փ̅',
        '^7+9' => 'Ք̅'
    ];

    /**
     * @var array Conversion set for standard simplified Chinese numerals.
     */
    private $ChineseSimplified = [
        '+0' => '',
        '-0' => '〇',
        '=0' => '〇',
        '1' => '一',
        '2' => '二',
        '3' => '三',
        '4' => '四',
        '5' => '五',
        '6' => '六',
        '7' => '七',
        '8' => '八',
        '9' => '九',
        '^1+1' => '十',
        '^1' => '十',
        'Hundreds' => '百',
        '^3' => '千',
        '^4' => '万',
        '^5' => '十',
        '^7' => '千',
        '^8' => '亿',
        '^9' => '十',
        '^11' => '千',
        '^12' => '兆',
        '^13' => '十',
        '^15' => '千',
        '^16' => '京',
        '^17' => '十',
        '^19' => '千',
        '^20' => '垓',
        '^21' => '十',
        '^23' => '千',
        '^24' => '秭',
        '^25' => '十',
        '^27' => '千',
        '^28' => '穰',
        '^29' => '十',
        '^31' => '千',
        '^32' => '沟',
        '^33' => '十',
        '^35' => '千',
        '^36' => '涧',
        '^37' => '十',
        '^39' => '千',
        '^40' => '正',
        '^41' => '十',
        '^43' => '千',
        '^44' => '载',
        '^45' => '十',
        '^47' => '千'
    ];

    /**
     * @var array Conversion set for standard traditional Chinese numerals.
     */
    private $ChineseTraditional = [
        '+0' => '',
        '-0' => '零',
        '=0' => '零',
        '1' => '一',
        '2' => '二',
        '3' => '三',
        '4' => '四',
        '5' => '五',
        '6' => '六',
        '7' => '七',
        '8' => '八',
        '9' => '九',
        '^1+1' => '十',
        '^1' => '十',
        'Hundreds' => '百',
        '^3' => '千',
        '^4' => '萬',
        '^5' => '十',
        '^7' => '千',
        '^8' => '億',
        '^9' => '十',
        '^11' => '千',
        '^12' => '兆',
        '^13' => '十',
        '^15' => '千',
        '^16' => '京',
        '^17' => '十',
        '^19' => '千',
        '^20' => '垓',
        '^21' => '十',
        '^23' => '千',
        '^24' => '秭',
        '^25' => '十',
        '^27' => '千',
        '^28' => '穰',
        '^29' => '十',
        '^31' => '千',
        '^32' => '溝',
        '^33' => '十',
        '^35' => '千',
        '^36' => '澗',
        '^37' => '十',
        '^39' => '千',
        '^40' => '正',
        '^41' => '十',
        '^43' => '千',
        '^44' => '載',
        '^45' => '十',
        '^47' => '千'
    ];

    /**
     * @var array Conversion set for financial simplified Chinese numerals.
     */
    private $ChineseSimplifiedFinancial = [
        '+0' => '',
        '-0' => '零',
        '=0' => '零',
        '1' => '壹',
        '2' => '贰',
        '3' => '叁',
        '4' => '肆',
        '5' => '伍',
        '6' => '陆',
        '7' => '柒',
        '8' => '捌',
        '9' => '玖',
        '^1+1' => '拾',
        '^1' => '拾',
        'Hundreds' => '佰',
        '^3' => '仟',
        '^4' => '萬',
        '^5' => '拾',
        '^7' => '仟',
        '^8' => '億',
        '^9' => '拾',
        '^11' => '仟',
        '^12' => '兆',
        '^13' => '拾',
        '^15' => '仟',
        '^16' => '京',
        '^17' => '拾',
        '^19' => '仟',
        '^20' => '垓',
        '^21' => '拾',
        '^23' => '仟',
        '^24' => '秭',
        '^25' => '拾',
        '^27' => '仟',
        '^28' => '穰',
        '^29' => '拾',
        '^31' => '仟',
        '^32' => '沟',
        '^33' => '拾',
        '^35' => '仟',
        '^36' => '涧',
        '^37' => '拾',
        '^39' => '仟',
        '^40' => '正',
        '^41' => '拾',
        '^43' => '仟',
        '^44' => '载',
        '^45' => '拾',
        '^47' => '仟'
    ];

    /**
     * @var array Conversion set for financial traditional Chinese numerals.
     */
    private $ChineseTraditionalFinancial = [
        '+0' => '',
        '-0' => '零',
        '=0' => '零',
        '1' => '壹',
        '2' => '貳',
        '3' => '叄',
        '4' => '肆',
        '5' => '伍',
        '6' => '陸',
        '7' => '柒',
        '8' => '捌',
        '9' => '玖',
        '^1+1' => '拾',
        '^1' => '拾',
        'Hundreds' => '佰',
        '^3' => '仟',
        '^4' => '萬',
        '^5' => '拾',
        '^7' => '仟',
        '^8' => '億',
        '^9' => '拾',
        '^11' => '仟',
        '^12' => '兆',
        '^13' => '拾',
        '^15' => '仟',
        '^16' => '京',
        '^17' => '拾',
        '^19' => '仟',
        '^20' => '垓',
        '^21' => '拾',
        '^23' => '仟',
        '^24' => '秭',
        '^25' => '拾',
        '^27' => '仟',
        '^28' => '穰',
        '^29' => '拾',
        '^31' => '仟',
        '^32' => '沟',
        '^33' => '拾',
        '^35' => '仟',
        '^36' => '涧',
        '^37' => '拾',
        '^39' => '仟',
        '^40' => '正',
        '^41' => '拾',
        '^43' => '仟',
        '^44' => '载',
        '^45' => '拾',
        '^47' => '仟'
    ];

    /**
     * @var array Conversion set for "dozenal" numerals (Dwiggins).
     */
    private $Dwiggins = ['a' => 'X', 'b' => 'E'];

    /**
     * @var array Conversion set for "dozenal" numerals (Pitman).
     */
    private $Pitman = ['a' => '↊', 'b' => '↋'];

    /**
     * @var array Conversion set for fullwidth numerals.
     */
    private $Fullwidth = [
        '0' => '０',
        '1' => '１',
        '2' => '２',
        '3' => '３',
        '4' => '４',
        '5' => '５',
        '6' => '６',
        '7' => '７',
        '8' => '８',
        '9' => '９',
        'a' => 'ａ',
        'b' => 'ｂ',
        'c' => 'ｃ',
        'd' => 'ｄ',
        'e' => 'ｅ',
        'f' => 'ｆ',
        'g' => 'ｇ',
        'h' => 'ｈ',
        'i' => 'ｉ',
        'j' => 'ｊ',
        'k' => 'ｋ',
        'l' => 'ｌ',
        'm' => 'ｍ',
        'n' => 'ｎ',
        'o' => 'ｏ',
        'p' => 'ｐ',
        'q' => 'ｑ',
        'r' => 'ｒ',
        's' => 'ｓ',
        't' => 'ｔ',
        'u' => 'ｕ',
        'v' => 'ｖ',
        'w' => 'ｗ',
        'x' => 'ｘ',
        'y' => 'ｙ',
        'z' => 'ｚ'
    ];

    /**
     * @var array Conversion set for Ol Chiki numerals (used by Santali).
     */
    private $OlChiki = [
        '0' => '᱐',
        '1' => '᱑',
        '2' => '᱒',
        '3' => '᱓',
        '4' => '᱔',
        '5' => '᱕',
        '6' => '᱖',
        '7' => '᱗',
        '8' => '᱘',
        '9' => '᱙'
    ];

    /**
     * @var array Conversion set for Kaktovik numerals.
     */
    private $Kaktovik = [
        '0' => '𝋀',
        '1' => '𝋁',
        '2' => '𝋂',
        '3' => '𝋃',
        '4' => '𝋄',
        '5' => '𝋅',
        '6' => '𝋆',
        '7' => '𝋇',
        '8' => '𝋈',
        '9' => '𝋉',
        'a' => '𝋊',
        'b' => '𝋋',
        'c' => '𝋌',
        'd' => '𝋍',
        'e' => '𝋎',
        'f' => '𝋏',
        'g' => '𝋐',
        'h' => '𝋑',
        'i' => '𝋒',
        'j' => '𝋓'
    ];

    /**
     * @var array Conversion set for Ge'ez/Ethiopic numerals.
     */
    private $Geez = [
        '.' => true,
        '0' => '',
        'o1' => '፩',
        'o2' => '፪',
        'o3' => '፫',
        'o4' => '፬',
        'o5' => '፭',
        'o6' => '፮',
        'o7' => '፯',
        'o8' => '፰',
        'o9' => '፱',
        'e1' => '፲',
        'e2' => '፳',
        'e3' => '፴',
        'e4' => '፵',
        'e5' => '፶',
        'e6' => '፷',
        'e7' => '፸',
        'e8' => '፹',
        'e9' => '፺',
        'Hundreds' => '፻',
        'Hundreds+1' => '',
        'Myriads' => '፼',
        'Myriads+1' => ''
    ];

    /**
     * @var array Symbols quick lookup table.
     */
    private $Symbols = [
        10 => 'a',
        11 => 'b',
        12 => 'c',
        13 => 'd',
        14 => 'e',
        15 => 'f',
        16 => 'g',
        17 => 'h',
        18 => 'i',
        19 => 'j',
        20 => 'k',
        21 => 'l',
        22 => 'm',
        23 => 'n',
        24 => 'o',
        25 => 'p',
        26 => 'q',
        27 => 'r',
        28 => 's',
        29 => 't',
        30 => 'u',
        31 => 'v',
        32 => 'w',
        33 => 'x',
        34 => 'y',
        35 => 'z',
        'a' => '10',
        'b' => '11',
        'c' => '12',
        'd' => '13',
        'e' => '14',
        'f' => '15',
        'g' => '16',
        'h' => '17',
        'i' => '18',
        'j' => '19',
        'k' => '20',
        'l' => '21',
        'm' => '22',
        'n' => '23',
        'o' => '24',
        'p' => '25',
        'q' => '26',
        'r' => '27',
        's' => '28',
        't' => '29',
        'u' => '30',
        'v' => '31',
        'w' => '32',
        'x' => '33',
        'y' => '34',
        'z' => '35'
    ];

    /**
     * @var array Lookup table for unformatting a number.
     */
    private $UnformatTable = [
        '0' => ['٠', '۰', '০', '०', '૦', '੦', '೦', '౦', '၀', '០', '๐', '໐', '꧐', '୦', '༠', '᠐', '０', '᱐', '〇', '零', 'Z', '௰'],
        '1' => ['١', '۱', '১', '१', '૧', '੧', '೧', '౧', '၁', '១', '๑', '໑', '꧑', '୧', '༡', '᠑', '１', '᱑', '一', '壹', '፩', '፲', '௧'],
        '2' => ['٢', '۲', '২', '२', '૨', '੨', '೨', '౨', '၂', '២', '๒', '໒', '꧒', '୨', '༢', '᠒', '２', '᱒', '二', '贰', '貳', '፪', '፳', '௨'],
        '3' => ['٣', '۳', '৩', '३', '૩', '੩', '೩', '౩', '၃', '៣', '๓', '໓', '꧓', '୩', '༣', '᠓', '３', '᱓', '三', '叁', '叄', '፫', '፴', '௩'],
        '4' => ['٤', '۴', '৪', '४', '૪', '੪', '೪', '౪', '၄', '៤', '๔', '໔', '꧔', '୪', '༤', '᠔', '４', '᱔', '四', '肆', '፬', '፵', '௪'],
        '5' => ['٥', '۵', '৫', '५', '૫', '੫', '೫', '౫', '၅', '៥', '๕', '໕', '꧕', '୫', '༥', '᠕', '５', '᱕', '五', '伍', '፭', '፶', '௫'],
        '6' => ['٦', '۶', '৬', '६', '૬', '੬', '೬', '౬', '၆', '៦', '๖', '໖', '꧖', '୬', '༦', '᠖', '６', '᱖', '六', '陆', '陸', '፮', '፷', '௬'],
        '7' => ['٧', '۷', '৭', '७', '૭', '੭', '೭', '౭', '၇', '៧', '๗', '໗', '꧗', '୭', '༧', '᠗', '７', '᱗', '七', '柒', '፯', '፸', '௭'],
        '8' => ['٨', '۸', '৮', '८', '૮', '੮', '೮', '౮', '၈', '៨', '๘', '໘', '꧘', '୮', '༨', '᠘', '８', '᱘', '八', '捌', '፰', '፹', '௮'],
        '9' => ['٩', '۹', '৯', '९', '૯', '੯', '೯', '౯', '၉', '៩', '๙', '໙', '꧙', '୯', '༩', '᠙', '９', '᱙', '九', '玖', '፱', '፺', '௯']
    ];

    /**
     * @var array Patterns for unformatting a number.
     */
    private $UnformatPattern = [
        '~(?<!一|二|三|四|五|六|七|八|九|十|百|千)(十|百|千|拾|万|億|兆|京|垓)~' => '1\1',
        '~^(፻|፼|十|百|千|拾|万|億|兆|京|垓|௰|௱|௲)~' => '1\1',
        '~(፻|፼)(?!፲|፳|፴|፵|፶|፷|፸|፹|፺|\d)~' => '\1Z',
        '~(፻[\dZ]|፼[\dZ])(?!፩|፪|፫|፬|፭|፮|፯|፰|፱|\d)~' => '\1Z',
        '~(፲|፳|፴|፵|፶|፷|፸|፹|፺)(?!፩|፪|፫|፬|፭|፮|፯|፰|፱)~' => '\1Z',
        '~(十|拾)$~' => '0',
        '~(፻|百)$~' => '00',
        '~千$~' => '000',
        '~፼$~' => '0000'
    ];

    /**
     * @var array Lookup table for unformatting a base-20 number.
     */
    private $UnformatTableKakMay = [
        '0' => ['𝋀', '𝋠'],
        '1' => ['𝋁', '𝋡'],
        '2' => ['𝋂', '𝋢'],
        '3' => ['𝋃', '𝋣'],
        '4' => ['𝋄', '𝋤'],
        '5' => ['𝋅', '𝋥'],
        '6' => ['𝋆', '𝋦'],
        '7' => ['𝋇', '𝋧'],
        '8' => ['𝋈', '𝋨'],
        '9' => ['𝋉', '𝋩'],
        'a' => ['𝋊', '𝋪'],
        'b' => ['𝋋', '𝋫'],
        'c' => ['𝋌', '𝋬'],
        'd' => ['𝋍', '𝋭'],
        'e' => ['𝋎', '𝋮'],
        'f' => ['𝋏', '𝋯'],
        'g' => ['𝋐', '𝋰'],
        'h' => ['𝋑', '𝋱'],
        'i' => ['𝋒', '𝋲'],
        'j' => ['𝋓', '𝋳']
    ];

    /**
     * @var array Lookup table for unformatting a base-12 number.
     */
    private $UnformatTableDuoDec = ['a' => '↊', 'b' => '↋'];

    /**
     * Constructor.
     *
     * @param string $Format Can use this to quickly set commonly used
     *      definitions during object instantiation.
     * @return void
     */
    public function __construct(string $Format = '')
    {
        if ($Format === '' || $Format === 'Latin-1') {
            return;
        }
        if ($Format === 'NoSep-1') {
            $this->GroupSeparator = '';
            return;
        }
        if ($Format === 'NoSep-2') {
            $this->GroupSeparator = '';
            $this->DecimalSeparator = ',';
            return;
        }
        if ($Format === 'Latin-2') {
            $this->GroupSeparator = ' ';
            return;
        }
        if ($Format === 'Latin-3') {
            $this->GroupSeparator = '.';
            $this->DecimalSeparator = ',';
            return;
        }
        if ($Format === 'Latin-4') {
            $this->GroupSeparator = ' ';
            $this->DecimalSeparator = ',';
            return;
        }
        if ($Format === 'Latin-5') {
            $this->DecimalSeparator = '·';
            return;
        }
        if ($Format === 'Arabic-2') {
            $this->ConversionSet = 'Eastern';
            $this->GroupSeparator = '٬';
            $this->DecimalSeparator = '٫';
            return;
        }
        if ($Format === 'Arabic-3' || $Format === 'Persian') {
            $this->ConversionSet = 'Persian';
            $this->GroupSeparator = '٬';
            $this->DecimalSeparator = '٫';
            return;
        }
        if ($Format === 'Arabic-4' || $Format === 'Urdu') {
            $this->ConversionSet = 'Persian';
            $this->GroupSeparator = '٬';
            $this->DecimalSeparator = '٫';
            $this->GroupSize = 2;
            $this->GroupOffset = -1;
            return;
        }
        if ($Format === 'Chinese-Simplified') {
            $this->ConversionSet = 'ChineseSimplified';
            $this->GroupSeparator = '';
            $this->DecimalSeparator = '点';
            return;
        }
        if ($Format === 'Chinese-Simplified-Financial') {
            $this->ConversionSet = 'ChineseSimplifiedFinancial';
            $this->GroupSeparator = '';
            $this->DecimalSeparator = '点';
            return;
        }
        if ($Format === 'Chinese-Traditional') {
            $this->ConversionSet = 'ChineseTraditional';
            $this->GroupSeparator = '';
            $this->DecimalSeparator = '點';
            return;
        }
        if ($Format === 'Chinese-Traditional-Financial') {
            $this->ConversionSet = 'ChineseTraditionalFinancial';
            $this->GroupSeparator = '';
            $this->DecimalSeparator = '點';
            return;
        }
        if ($Format === 'India-2' || $Format === 'Devanagari') {
            $this->ConversionSet = 'Devanagari';
            $this->GroupSize = 2;
            $this->GroupOffset = -1;
            return;
        }
        if ($Format === 'India-3' || $Format === 'Gujarati') {
            $this->ConversionSet = 'Gujarati';
            $this->GroupSize = 2;
            $this->GroupOffset = -1;
            return;
        }
        if ($Format === 'India-4' || $Format === 'Gurmukhi') {
            $this->ConversionSet = 'Gurmukhi';
            $this->GroupSize = 2;
            $this->GroupOffset = -1;
            return;
        }
        if ($Format === 'India-5' || $Format === 'Kannada') {
            $this->ConversionSet = 'Kannada';
            $this->GroupSize = 2;
            $this->GroupOffset = -1;
            return;
        }
        if ($Format === 'India-6' || $Format === 'Telugu') {
            $this->ConversionSet = 'Telugu';
            $this->GroupSize = 2;
            $this->GroupOffset = -1;
            return;
        }
        if ($Format === 'Thai-2') {
            $this->ConversionSet = 'Thai';
            $this->GroupSeparator = '';
            return;
        }
        $Format = explode('-', $Format);
        if ($Format[0] === 'Arabic') {
            $this->ConversionSet = 'Eastern';
            $this->GroupSeparator = '';
            $this->DecimalSeparator = '٫';
            return;
        }
        if (
            $Format[0] === 'Armenian' ||
            $Format[0] === 'Geez' ||
            $Format[0] === 'Hebrew' ||
            $Format[0] === 'Roman' ||
            $Format[0] === 'Tamil'
        ) {
            $this->ConversionSet = $Format[0];
            $this->GroupSeparator = '';
            $this->DecimalSeparator = '';
            return;
        }
        if ($Format[0] === 'Base') {
            $this->GroupSeparator = '';
            $this->Base = (int)($Format[1] ?? 0);
            return;
        }
        if ($Format[0] === 'Bangla' || $Format[0] === 'Bengali' || $Format[0] === 'Nagari') {
            $this->ConversionSet = 'Nagari';
            $this->GroupSize = 2;
            $this->GroupOffset = -1;
            return;
        }
        if ($Format[0] === 'Burmese') {
            $this->ConversionSet = 'Burmese';
            $this->GroupSeparator = '';
            return;
        }
        if ($Format[0] === 'China') {
            $this->GroupSize = 4;
            return;
        }
        if (
            $Format[0] === 'Fullwidth' ||
            $Format[0] === 'Javanese' ||
            $Format[0] === 'Lao' ||
            $Format[0] === 'Mongolian' ||
            $Format[0] === 'Odia' ||
            $Format[0] === 'Tibetan'
        ) {
            $this->ConversionSet = $Format[0];
            $this->GroupSeparator = '';
            return;
        }
        if ($Format[0] === 'India') {
            $this->GroupSize = 2;
            $this->GroupOffset = -1;
            return;
        }
        if ($Format[0] === 'Japanese') {
            $this->ConversionSet = 'Japanese';
            $this->GroupSeparator = '';
            $this->DecimalSeparator = '・';
            return;
        }
        if ($Format[0] === 'Kaktovik' || $Format[0] === 'Mayan') {
            $this->ConversionSet = $Format[0];
            $this->GroupSeparator = '';
            $this->Base = 20;
            return;
        }
        if ($Format[0] === 'Khmer') {
            $this->ConversionSet = 'Khmer';
            $this->GroupSeparator = '.';
            $this->DecimalSeparator = ',';
            return;
        }
        if ($Format[0] === 'SDN' && isset($Format[1]) && property_exists($this, $Format[1])) {
            $this->ConversionSet = $Format[1];
            $this->DecimalSeparator = ';';
            $this->Base = 12;
            return;
        }
        if ($Format[0] === 'Thai') {
            $this->ConversionSet = 'Thai';
            return;
        }
    }

    /**
     * Formats the supplied number according to definitions.
     *
     * @param mixed $Number The number to format (int, float, string, etc).
     * @param int $Decimals The number of decimal places (optional).
     * @return string The formatted number, or an empty string on failure.
     */
    public function format($Number, int $Decimals = 0): string
    {
        if ($this->Base < 2 || $this->Base > 36) {
            return '';
        }
        $CSet = $this->{$this->ConversionSet};
        $DecPos = strpos($Number, '.');
        if ($DecPos !== false) {
            if ($Decimals > 0 && $this->DecimalSeparator && empty($CSet['.'])) {
                $Fraction = substr($Number, $DecPos + 1) ?: '';
                $Len = strlen($Fraction);
                if ($Len > 0) {
                    $Fraction = $this->convertFraction($Fraction, 10, $this->Base, $Decimals);
                    $Fraction = substr($Fraction, 0, $Decimals);
                    $Len = strlen($Fraction);
                }
                if ($Len < $Decimals) {
                    $Fraction .= str_repeat('0', $Decimals - $Len);
                }
            }
            $Number = (string)(int)substr($Number, 0, $DecPos);
        } else {
            $Number = (string)(int)$Number;
        }
        if ($this->Base !== 10) {
            $Number = base_convert($Number, 10, $this->Base);
        }
        if (isset($CSet['=' . $Number])) {
            $Formatted = $CSet['=' . $Number];
            $WholeLen = -1;
        } else {
            $WholeLen = strlen($Number);
        }
        for ($OddEven = 'o', $Unit = 0, $Formatted = '', $ThouPos = $this->GroupOffset, $Pos = $WholeLen - 1; $Pos > -1; $Pos--, $Unit++, $OddEven = $OddEven === 'o' ? 'e' : 'o') {
            if ($ThouPos >= $this->GroupSize) {
                $ThouPos = 1;
                $Formatted = $this->GroupSeparator . $Formatted;
            } else {
                $ThouPos++;
            }
            if ($Unit === 0) {
                $Myriads = false;
                $Hundreds = false;
            } else {
                $Myriads = ($Unit % 4) === 0;
                $Hundreds = $Myriads === false && ($Unit % 2) === 0;
            }
            $Key = substr($Number, $Pos, 1);
            $Double = $Pos > 0 ? substr($Number, $Pos - 1, 1) . $Key : '';
            $Power = '';
            $Digit = '';
            if (isset($CSet['^' . $Unit . '+' . $Double])) {
                $Digit = $CSet['^' . $Unit . '+' . $Double];
            } elseif (isset($CSet['^' . $Unit . '+' . $Key])) {
                $Digit = $CSet['^' . $Unit . '+' . $Key];
            } elseif (isset($CSet['+' . $Key])) {
                $Digit = $CSet['+' . $Key];
            } else {
                $Digit = $CSet[$OddEven . $Key] ?? $CSet[$Key] ?? $Key;
                if ($Myriads && isset($CSet['Myriads'])) {
                    $Power = $CSet['Myriads'];
                    if (isset($CSet['Myriads+' . $Key])) {
                        $Digit = $CSet['Myriads+' . $Key];
                    }
                } elseif ($Hundreds && isset($CSet['Hundreds'])) {
                    $Power = $CSet['Hundreds'];
                    if (isset($CSet['Hundreds+' . $Key])) {
                        $Digit = $CSet['Hundreds+' . $Key];
                    }
                } elseif (isset($CSet['^' . $Unit])) {
                    $Power = $CSet['^' . $Unit];
                }
            }
            $Formatted = $Digit . $Power . $Formatted;
        }
        if (isset($Fraction) && $Decimals && $this->DecimalSeparator && empty($CSet['.'])) {
            $Formatted .= $this->DecimalSeparator;
            for ($Len = strlen($Fraction), $Pos = 0; $Pos < $Len; $Pos++) {
                $Key = substr($Fraction, $Pos, 1);
                $Power = '';
                $Digit = '';
                if (isset($CSet['^-' . $Pos . '+' . $Key])) {
                    $Digit = $CSet['^-' . $Pos . '+' . $Key];
                } elseif (isset($CSet['-+' . $Key])) {
                    $Digit = $CSet['-+' . $Key];
                } else {
                    if (isset($CSet['-' . $Key])) {
                        $Digit = $CSet['-' . $Key];
                    } else {
                        $Digit = $CSet[$Key] ?? $Key;
                    }
                    if (isset($CSet['^-' . $Pos])) {
                        $Power = $CSet['^-' . $Pos];
                    }
                }
                $Formatted .= $Digit . $Power;
            }
        }
        if (($DecLen = strlen($this->DecimalSeparator)) && substr($Formatted, 0, $DecLen) === $this->DecimalSeparator) {
            $Formatted = substr($Formatted, $DecLen);
        }
        return $Formatted;
    }

    /**
     * Gets the specified conversion set and returns it as a JSON string.
     *
     * @param string $Set The specified conversion set.
     * @return string A JSON string.
     */
    public function getSetJSON(string $Set = ''): string
    {
        return isset($this->{$Set}) ? json_encode($this->{$Set}) : '[]';
    }

    /**
     * Unformats the formatted number according to predefined patterns and lookup
     * tables. Warning: Doesn't work for ALL formats (..yet).
     *
     * @param string $Number The number to unformat.
     * @param string $DecSep The decimal separator to look for. When specified,
     *      will attempt to unformat fractions. When not specified, won't.
     * @param int $MinBase The minimum base to interpret from the source number.
     * @return string The unformatted number (returned as a string rather than as
     *      an integer or a float in order to retain decimal precision).
     */
    public function unformat(string $Number, string $DecSep = '', int $MinBase = 10): string
    {
        /** Guard. */
        if ($MinBase < 2) {
            $MinBase = 2;
        } elseif ($MinBase > 35) {
            $MinBase = 35;
        }

        /** Fractions. */
        if ($DecSep !== '') {
            if (($DSPos = strrpos($Number, $DecSep)) !== false) {
                $Fraction = substr($Number, $DSPos + strlen($DecSep));
                $Number = substr($Number, 0, $DSPos);
            } else {
                $Fraction = '';
            }
            if (preg_match('~\D~', $Fraction)) {
                foreach ($this->UnformatTable as $Replacement => $Lookup) {
                    $Fraction = str_replace($Lookup, $Replacement, $Fraction);
                }
                $KakMay = $Fraction;
                foreach ($this->UnformatTableKakMay as $Replacement => $Lookup) {
                    $KakMay = str_replace($Lookup, $Replacement, $KakMay);
                }
                if ($KakMay !== $Fraction) {
                    if ($MinBase < 20) {
                        $MinBase = 20;
                    }
                    $Fraction = $KakMay;
                }
                $DuoDec = $Fraction;
                foreach ($this->UnformatTableDuoDec as $Replacement => $Lookup) {
                    $DuoDec = str_replace($Lookup, $Replacement, $DuoDec);
                }
                if ($DuoDec !== $Fraction) {
                    if ($MinBase < 12) {
                        $MinBase = 12;
                    }
                    $Fraction = $DuoDec;
                }
                for ($Base = $MinBase; $Base < 36; $Base++) {
                    if (strpos($Fraction, $this->Symbols[$Base]) !== false) {
                        $MinBase = $Base;
                    }
                }
            }
            $Fraction = preg_replace('~0+$~', '', $Fraction);
        } else {
            $Fraction = '';
        }

        /** Whole numbers. */
        if (preg_match('~\D~', $Number)) {
            foreach ($this->UnformatPattern as $Pattern => $Replacement) {
                $Number = preg_replace($Pattern, $Replacement, $Number);
            }
            foreach ($this->UnformatTable as $Replacement => $Lookup) {
                $Number = str_replace($Lookup, $Replacement, $Number);
            }
            $KakMay = $Number;
            foreach ($this->UnformatTableKakMay as $Replacement => $Lookup) {
                $KakMay = str_replace($Lookup, $Replacement, $KakMay);
            }
            if ($KakMay !== $Number) {
                if ($MinBase < 20) {
                    $MinBase = 20;
                }
                $Number = $KakMay;
            }
            $DuoDec = $Number;
            foreach ($this->UnformatTableDuoDec as $Replacement => $Lookup) {
                $DuoDec = str_replace($Lookup, $Replacement, $DuoDec);
            }
            if ($DuoDec !== $Number) {
                if ($MinBase < 12) {
                    $MinBase = 12;
                }
                $Number = $DuoDec;
            }
            for ($Base = $MinBase; $Base < 36; $Base++) {
                if (strpos($Number, $this->Symbols[$Base]) !== false) {
                    $MinBase = $Base;
                }
            }
            $Number = preg_replace('~^0+~', '', $Number);
        }

        /** Strip unwanted bytes and convert base if necessary. */
        if ($MinBase === 10) {
            if ($Fraction !== '') {
                $Fraction = preg_replace('~\D~', '', $Fraction);
            }
            if ($Number !== '') {
                $Number = preg_replace('~\D~', '', $Number);
            }
        } elseif ($MinBase > 10) {
            $Range = $MinBase === 11 ? 'a' : 'a-' . $this->Symbols[$MinBase];
            if ($Fraction !== '') {
                $Fraction = $this->convertFraction(preg_replace('~[^\d' . $Range . ']~', '', $Fraction), $MinBase, 10, 50);
            }
            if ($Number !== '') {
                $Number = base_convert(preg_replace('~[^\d' . $Range . ']~', '', $Number), $MinBase, 10);
            }
        } elseif ($MinBase < 10) {
            if ($Fraction !== '') {
                $Fraction = $this->convertFraction($Fraction, $MinBase, 10, 50);
            }
            if ($Number !== '') {
                $Number = base_convert($Number, $MinBase, 10);
            }
        }

        if ($Fraction === '') {
            return $Number === '' ? '0' : $Number;
        }
        return $Number === '' ? '0.' . $Fraction : $Number . '.' . $Fraction;
    }

    /**
     * Prepare to convert a fraction.
     *
     * @param string $Fraction The fraction to convert.
     * @param int $From The base to convert from.
     * @param int $To The base to convert to.
     * @param int $Limit Maximum number of places permitted.
     * @return string The converted fraction, or an empty string on failure.
     */
    private function convertFraction(string $Fraction = '', int $From = 10, int $To = 10, int $Limit = 8): string
    {
        if ($From < 2 || $To < 2 || $From > 36 || $To > 36 || $Limit < 1) {
            return '';
        }
        $FracLen = strlen($Fraction);
        if ($From === $To || $FracLen < 1) {
            return $Fraction;
        }
        $Fraction = rtrim($Fraction, '0');
        if ($From !== 10) {
            $PreFloat = [];
            for ($Index = 0; $Index < $FracLen; $Index++) {
                $PreFloat[$Index] = substr($Fraction, $Index, 1);
                if (isset($this->Symbols[$PreFloat[$Index]])) {
                    $PreFloat[$Index] = $this->Symbols[$PreFloat[$Index]];
                }
                $PreFloat[$Index] = ((int)$PreFloat[$Index] / $From) * 10;
                while ($PreFloat[$Index] >= 10) {
                    $Lookback = $Index;
                    while ($PreFloat[$Lookback] >= 10) {
                        $PreFloat[$Lookback] -= 10;
                        if (isset($PreFloat[$Lookback])) {
                            $Lookback--;
                            $PreFloat[$Lookback]++;
                        }
                    }
                }
            }
            $Float = implode('', $PreFloat);
        }
        $Float = (float)('0.' . $Fraction);
        $Sum = 0;
        $Degree = 0;
        while ($Degree < $this->MaxDegrees) {
            $Sum += $Float;
            $Degree++;
            if ($Sum > 0 && strpos($Sum, '.') === false) {
                break;
            }
        }
        $Ratio = $To / $Degree;
        $Try = $Sum * $Ratio;
        $Arr = [];
        $Index = 0;
        while ($Try > 0 && $Index < $Limit) {
            $Digit = floor($Try);
            $Try = ($Try - $Digit) * $To;
            $Arr[$Index] = $Digit;
            if (isset($this->Symbols[$Arr[$Index]])) {
                $Arr[$Index] = $this->Symbols[$Arr[$Index]];
            }
            if (strlen($Arr[$Index]) > 1) {
                $Arr[$Index] = 0;
            }
            $Index++;
        }
        return implode('', $Arr);
    }
}

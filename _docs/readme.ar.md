## <div dir="rtl">CIDRAM بالعربية</div>

### <div dir="rtl">المحتويات:</div>
<div dir="rtl"><ul>
 <li>1. <a href="#SECTION1">مقدمة</a></li>
 <li>2. <a href="#SECTION2">كيفية التحميل</a></li>
 <li>3. <a href="#SECTION3">كيفية الإستخدام</a></li>
 <li>4. <a href="#SECTION4">الملفاتالموجودةفيهذهالحزمة</a></li>
 <li>5. <a href="#SECTION5">خياراتالتكوين/التهيئة</a></li>
 <li>6. <a href="#SECTION6">شكل/تنسيق التوقيع</a></li>
 <li>7. <a href="#SECTION7">أسئلة وأجوبة (FAQ)</a></li>
</ul></div>

---


### <div dir="rtl">1. <a name="SECTION1"></a>مقدمة</div>

<div dir="rtl">CIDRAM (توجيه بين المجالات لافئويا وصول مدير) هو السيناريو PHP، المصممة لحماية المواقع من طلبات الحجب تنشأ من عناوين IP تعتبر مصادر من حركة المرور غير مرغوب فيه، بما في ذلك (ولكن ليس على سبيل الحصر) حركة المرور من نقاط النهاية الوصول غير البشرية، خدمات سحابية، المتطفلين و برامج التطفل، كاشطات الموقع، إلخ. وهي تفعل ذلك عن طريق حساب CIDRs ممكن من عناوين IP الموردة من طلبات واردة وبعد ذلك محاولة لتتناسب مع هذه ضد الملفات توقيعه (هذه الملفات توقيع تحتوي CIDRs من عناوين IP تعتبر مصادر من حركة المرور غير مرغوب فيه)؛ إذا تم العثور على المباريات، يتم حظر الطلبات.<br /><br /></div>

<div dir="rtl">حقوق النشر محفوظة ل CIDRAM لعام 2016 وما بعده تحت رخصة GNU/GPLv2 للمبرمج (Caleb M (Maikuolan.<br /><br /></div>

<div dir="rtl">هذا البرنامج مجاني، يمكنك تعديله وإعادة نشره تحت رخصة GNU. نشارك هذا السكربت على أمل أن تعم الفائدة لكن لا نتحمل أية مسؤولية أو أية ضمانات لاستخدامك، اطلع على تفاصيل رخصة GNU للمزيد من المعلومات عبر الملف "LICENSE.txt" وللمزيد من المعلومات:</div>
- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

<div dir="rtl">هذا المستند و الحزم المرتبطة به يمكن تحميلها مجاناً من <a href="https://github.com/Maikuolan/CIDRAM/">Github</a>.</div>

---


### <div dir="rtl">2. <a name="SECTION2"></a>كيفية التحميل</div>

<div dir="rtl">أرجو أن يتم تسهيل هذه العملية في المستقبل القريب، لكن في الوقت الحالي إتبع هذه التعليمات والتي تعمل على أغلب الأنظمة وأنظمة إدارة المحتوى CMS:<br /><br /></div>

<div dir="rtl">1. بقراءتك لهذا سنفرض بأنك قمت بتحميل السكربت، من هنا عليك العمل على جهازك المحلي أو نظام إدارة المحتوى لإضافة هذه الأمور، مجلد مثل `/public_html/cidram/` أو ما شابه سيكون كاف.<br /><br /></div>

<div dir="rtl">2. إعادة تسمية "config.ini.RenameMe" إلى "config.ini" (تقع داخل "vault")، واختياريا (هذه الخطوة اختيارية ينصح بها للمستخدمين المتقدمين ولا ينصح بها للمبتدئين)، افتحه، وعدل الخيارات كما يناسبك (أعلى كل خيار يوجد وصف مختصر للوظيفة التي يقوم بها).<br /><br /></div>

<div dir="rtl">3. إرفع الملفات للمجلد الذي اخترته(لست بحاجة لرفع "*.txt/*.md" لكن في الغالب يجب أن ترفع جميع الملفات).<br /><br /></div>

<div dir="rtl">4. غير التصريح لمجلد vault للتصريح "755" (إذا كان هناك مشاكل، يمكنك محاولة "777"، ولكن هذه ليست آمنة). المجلد الرئيسي الذي يحتوي على الملفات-المجلد الذي اخترته سابقاً-، بالعادة يمكن تجاهله، لكن يجب التأكد من التصريح إذا واجهت مشاكل في الماضي(إفتراضيا يجب أن يكون "755").<br /><br /></div>

<div dir="rtl">5. الآن أنت بحاجة لربط CIDRAM لنظام إدارة المحتوى أو النظام الذي تستخدمه، هناك عدة طرق لفعل هذا لكن أسهل طريقة ببساطة إضافة السكربت لبداية النواة في نظامك (سيتم إعادة التحميل لكل وصول لأي صفحة في الموقع) بإستخدام جمل "require" أو "include"، بالعادة سيتم التخزين في  "/includes"، "/assets" أو "/functions"، وسيتم تسميته بالغالب مثل: "init.php"، "common_functions.php"، "functions.php" أو ما شابه. من الممكن أن تكون مستخدم ل CMS لذا يمكن أن أقدم بعض المساعدة بخصوص هذا الموضوع، لإستخدام "require" أو "include" قم بإضافة الكود التالي لبداية الملف الرئيسي لبرنامجك، عدل النص الموجود داخل علامات التنصيص لمسار "loader.php" لديك.<br /><br /></div>

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

<div dir="rtl">إحفظ الملف ثم قم بإعادة رفعه.<br /><br /></div>

<div dir="rtl">-- أو بدلاً من ذلك --<br /><br /></div>

<div dir="rtl">إذا كنت تستخدم Apache webserver وتستطيع الوصول ل "php.ini"، بإستطاعتك إستخدام "auto_prepend_file" للتوجيه ل CIDRAM لكل طلب مثل:<br /><br /></div>

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

<div dir="rtl">أو هذا في ملف ".htaccess":<br /><br /></div>

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

<div dir="rtl">6. هذا كل شئ. :-)<br /><br /></div>

---


### <div dir="rtl">3. <a name="SECTION3"></a>كيفية الإستخدام</div>

CIDRAM should automatically block undesirable requests to your website without requiring any manual assistance, aside from its initial installation.

Updating is done manually, and you can customise your configuration and customise which CIDRs are blocked by modifying your configuration file and/or your signature files.

<div dir="rtl">إذا واجهت أي إيجابية خاطئة، يرجى رسالة لي أن اسمحوا لي أن أعرف عن ذلك.<br /><br /></div>

---


### <div dir="rtl">4. <a name="SECTION4"></a>الملفاتالموجودةفيهذهالحزمة</div>

<div dir="rtl">فيما يلي قائمة بجميع الملفات التي ينبغي أن تدرج في النسخة المحفوظة من هذا البرنامج النصي عند تحميله، أي الملفات التي يمكن أن يحتمل أن تكون نشأت نتيجة استعمالك لهذا البرنامج النصي، بالإضافة إلى وصفا موجزا لدور و وظيفة كل ملف.<br /><br /></div>

الوصف | الملف
----|----
<div dir="rtl" style="display:inline;">دليل الوثائق (يحتوي على ملفات مختلفة).</div> | /_docs/
<div dir="rtl" style="display:inline;">الوثائق العربية.</div> | /_docs/readme.ar.md
<div dir="rtl" style="display:inline;">الوثائق الألمانية.</div> | /_docs/readme.de.md
<div dir="rtl" style="display:inline;">الوثائق الإنجليزية.</div> | /_docs/readme.en.md
<div dir="rtl" style="display:inline;">الوثائق الأسبانية.</div> | /_docs/readme.es.md
<div dir="rtl" style="display:inline;">الوثائق الفرنسية.</div> | /_docs/readme.fr.md
<div dir="rtl" style="display:inline;">الوثائق الاندونيسية.</div> | /_docs/readme.id.md
<div dir="rtl" style="display:inline;">الوثائق الايطالية.</div> | /_docs/readme.it.md
<div dir="rtl" style="display:inline;">الوثائق اليابانية.</div> | /_docs/readme.ja.md
<div dir="rtl" style="display:inline;">الوثائق الهولندية.</div> | /_docs/readme.nl.md
<div dir="rtl" style="display:inline;">الوثائق البرتغالية.</div> | /_docs/readme.pt.md
<div dir="rtl" style="display:inline;">الوثائق الروسية.</div> | /_docs/readme.ru.md
<div dir="rtl" style="display:inline;">الوثائق الفيتنامية.</div> | /_docs/readme.vi.md
<div dir="rtl" style="display:inline;">الوثائق الصينية (المبسطة).</div> | /_docs/readme.zh.md
<div dir="rtl" style="display:inline;">الوثائق الصينية (التقليدية).</div> | /_docs/readme.zh-TW.md
<div dir="rtl" style="display:inline;">دليل /vault/ (يحتوي على ملفات متنوعة).</div> | /vault/
<div dir="rtl" style="display:inline;">الأصول front-end.</div> | /vault/fe_assets/
<div dir="rtl" style="display:inline;">ملف وصول النص التشعبي (في هذه الحالة، لحماية الملفات الحساسة التي تنتمي إلى البرنامج من أن يتم الوصول إليها عن طريق مصادر غير مصرح لها).</div> | /vault/fe_assets/.htaccess
<div dir="rtl" style="display:inline;">ملف قالب HTML لfront-end صفحة الحسابات.</div> | /vault/fe_assets/_accounts.html
<div dir="rtl" style="display:inline;">ملف قالب HTML لfront-end صفحة الحسابات.</div> | /vault/fe_assets/_accounts_row.html
<div dir="rtl" style="display:inline;">ملف قالب HTML لfront-end صفحة التكوين.</div> | /vault/fe_assets/_config.html
<div dir="rtl" style="display:inline;">ملف قالب HTML لfront-end صفحة الرئيسية.</div> | /vault/fe_assets/_home.html
<div dir="rtl" style="display:inline;">ملف قالب HTML لfront-end صفحة تسجيل الدخول.</div> | /vault/fe_assets/_login.html
<div dir="rtl" style="display:inline;">ملف قالب HTML لfront-end صفحة السجلات.</div> | /vault/fe_assets/_logs.html
<div dir="rtl" style="display:inline;">ملف قالب HTML لfront-end ارتباطات التنقل، يستخدم لهؤلاء مع وصول كامل.</div> | /vault/fe_assets/_nav_complete_access.html
<div dir="rtl" style="display:inline;">ملف قالب HTML لfront-end ارتباطات التنقل، يستخدم لهؤلاء مع سجلات الوصول فقط.</div> | /vault/fe_assets/_nav_logs_access_only.html
<div dir="rtl" style="display:inline;">ملف قالب HTML لfront-end صفحة التحديثات.</div> | /vault/fe_assets/_updates.html
<div dir="rtl" style="display:inline;">ملف قالب HTML لfront-end صفحة التحديثات.</div> | /vault/fe_assets/_updates_row.html
<div dir="rtl" style="display:inline;">ملف CSS (صفحات الطرز المتراصة) لfront-end.</div> | /vault/fe_assets/frontend.css
<div dir="rtl" style="display:inline;">قاعدة البيانات لfront-end (يحتوي على معلومات الحسابات، الجلسات، وذاكرة التخزين المؤقت؛ خلق فقط اذا front-end يتم تمكين واستخدامها).</div> | /vault/fe_assets/frontend.dat
<div dir="rtl" style="display:inline;">ملف قالب HTML الرئيسي لfront-end.</div> | /vault/fe_assets/frontend.html
<div dir="rtl" style="display:inline;">يحتوي على بيانات اللغة لـ CIDRAM.</div> | /vault/lang/
<div dir="rtl" style="display:inline;">ملف وصول النص التشعبي (في هذه الحالة، لحماية الملفات الحساسة التي تنتمي إلى البرنامج من أن يتم الوصول إليها عن طريق مصادر غير مصرح لها).</div> | /vault/lang/.htaccess
<div dir="rtl" style="display:inline;">ملفات اللغة العربية لCLI.</div> | /vault/lang/lang.ar.cli.php
<div dir="rtl" style="display:inline;">ملفات اللغة العربية لfront-end.</div> | /vault/lang/lang.ar.fe.php
<div dir="rtl" style="display:inline;">ملفات اللغة العربية.</div> | /vault/lang/lang.ar.php
<div dir="rtl" style="display:inline;">ملفات اللغة الألمانية لCLI.</div> | /vault/lang/lang.de.cli.php
<div dir="rtl" style="display:inline;">ملفات اللغة الألمانية لfront-end.</div> | /vault/lang/lang.de.fe.php
<div dir="rtl" style="display:inline;">ملفات اللغة الألمانية.</div> | /vault/lang/lang.de.php
<div dir="rtl" style="display:inline;">ملفات اللغة الإنجليزية لCLI.</div> | /vault/lang/lang.en.cli.php
<div dir="rtl" style="display:inline;">ملفات اللغة الإنجليزية لfront-end.</div> | /vault/lang/lang.en.fe.php
<div dir="rtl" style="display:inline;">ملفات اللغة الإنجليزية.</div> | /vault/lang/lang.en.php
<div dir="rtl" style="display:inline;">ملفات اللغة الاسبانية لCLI.</div> | /vault/lang/lang.es.cli.php
<div dir="rtl" style="display:inline;">ملفات اللغة الاسبانية لfront-end.</div> | /vault/lang/lang.es.fe.php
<div dir="rtl" style="display:inline;">ملفات اللغة الاسبانية.</div> | /vault/lang/lang.es.php
<div dir="rtl" style="display:inline;">ملفات اللغة الفرنسية لCLI.</div> | /vault/lang/lang.fr.cli.php
<div dir="rtl" style="display:inline;">ملفات اللغة الفرنسية لfront-end.</div> | /vault/lang/lang.fr.fe.php
<div dir="rtl" style="display:inline;">ملفات اللغة الفرنسية.</div> | /vault/lang/lang.fr.php
<div dir="rtl" style="display:inline;">ملفات اللغة الاندونيسية لCLI.</div> | /vault/lang/lang.id.cli.php
<div dir="rtl" style="display:inline;">ملفات اللغة الاندونيسية لfront-end.</div> | /vault/lang/lang.id.fe.php
<div dir="rtl" style="display:inline;">ملفات اللغة الاندونيسية.</div> | /vault/lang/lang.id.php
<div dir="rtl" style="display:inline;">ملفات اللغة الايطالية لCLI.</div> | /vault/lang/lang.it.cli.php
<div dir="rtl" style="display:inline;">ملفات اللغة الايطالية لfront-end.</div> | /vault/lang/lang.it.fe.php
<div dir="rtl" style="display:inline;">ملفات اللغة الايطالية.</div> | /vault/lang/lang.it.php
<div dir="rtl" style="display:inline;">ملفات اللغة اليابانية لCLI.</div> | /vault/lang/lang.ja.cli.php
<div dir="rtl" style="display:inline;">ملفات اللغة اليابانية لfront-end.</div> | /vault/lang/lang.ja.fe.php
<div dir="rtl" style="display:inline;">ملفات اللغة اليابانية.</div> | /vault/lang/lang.ja.php
<div dir="rtl" style="display:inline;">ملفات اللغة الهولندية لCLI.</div> | /vault/lang/lang.nl.cli.php
<div dir="rtl" style="display:inline;">ملفات اللغة الهولندية لfront-end.</div> | /vault/lang/lang.nl.fe.php
<div dir="rtl" style="display:inline;">ملفات اللغة الهولندية.</div> | /vault/lang/lang.nl.php
<div dir="rtl" style="display:inline;">ملفات اللغة البرتغالية لCLI.</div> | /vault/lang/lang.pt.cli.php
<div dir="rtl" style="display:inline;">ملفات اللغة البرتغالية لfront-end.</div> | /vault/lang/lang.pt.fe.php
<div dir="rtl" style="display:inline;">ملفات اللغة البرتغالية.</div> | /vault/lang/lang.pt.php
<div dir="rtl" style="display:inline;">ملفات اللغة الروسية لCLI.</div> | /vault/lang/lang.ru.cli.php
<div dir="rtl" style="display:inline;">ملفات اللغة الروسية لfront-end.</div> | /vault/lang/lang.ru.fe.php
<div dir="rtl" style="display:inline;">ملفات اللغة الروسية.</div> | /vault/lang/lang.ru.php
<div dir="rtl" style="display:inline;">ملفات اللغة الفيتنامية لCLI.</div> | /vault/lang/lang.vi.cli.php
<div dir="rtl" style="display:inline;">ملفات اللغة الفيتنامية لfront-end.</div> | /vault/lang/lang.vi.fe.php
<div dir="rtl" style="display:inline;">ملفات اللغة الفيتنامية.</div> | /vault/lang/lang.vi.php
<div dir="rtl" style="display:inline;">ملفات اللغة الصينية (التقليدية) لCLI.</div> | /vault/lang/lang.zh-tw.cli.php
<div dir="rtl" style="display:inline;">ملفات اللغة الصينية (التقليدية) لfront-end.</div> | /vault/lang/lang.zh-tw.fe.php
<div dir="rtl" style="display:inline;">ملفات اللغة الصينية (التقليدية).</div> | /vault/lang/lang.zh-tw.php
<div dir="rtl" style="display:inline;">ملفات اللغة الصينية (المبسطة) لCLI.</div> | /vault/lang/lang.zh.cli.php
<div dir="rtl" style="display:inline;">ملفات اللغة الصينية (المبسطة) لfront-end.</div> | /vault/lang/lang.zh.fe.php
<div dir="rtl" style="display:inline;">ملفات اللغة الصينية (المبسطة).</div> | /vault/lang/lang.zh.php
<div dir="rtl" style="display:inline;">ملف وصول النص التشعبي (في هذه الحالة، لحماية الملفات الحساسة التي تنتمي إلى البرنامج من أن يتم الوصول إليها عن طريق مصادر غير مصرح لها).</div> | /vault/.htaccess
<div dir="rtl" style="display:inline;">بيانات ذاكرة التخزين المؤقت.</div> | /vault/cache.dat
<div dir="rtl" style="display:inline;">معالج CLI.</div> | /vault/cli.php
<div dir="rtl" style="display:inline;">يحتوي على معلومات المتعلقة الوحدة المختلفة لCIDRAM؛ المستخدمة من ميزة التحديثات التي تقدمها لCIDRAM.</div> | /vault/components.dat
<div dir="rtl" style="display:inline;">ملف التكوين. يحتوي على جميع خيارات تهيئة CIDRAM، يخبرك ماذا يفعل وكيف يعمل بشكل صحيح (إعادة تسمية لتفعيل)!</div> | /vault/config.ini.RenameMe
<div dir="rtl" style="display:inline;">معالج التكوين.</div> | /vault/config.php
<div dir="rtl" style="display:inline;">معالج front-end.</div> | /vault/frontend.php
<div dir="rtl" style="display:inline;">ملف وظائف (ضروري).</div> | /vault/functions.php
<div dir="rtl" style="display:inline;">يحتوي على قائمة من علامات الرقم المقبولة (وثيقة الصلة ميزة اختبار reCAPTCHA؛ فقط إنشاء إذا تم تمكين ميزة اختبار reCAPTCHA).</div> | /vault/hashes.dat
<div dir="rtl" style="display:inline;">تستخدم لتحديد أقسام توقيع التي CIDRAM يجب تجاهل.</div> | /vault/ignore.dat
<div dir="rtl" style="display:inline;">يحتوي على قائمة من الالتفافية IP (وثيقة الصلة ميزة اختبار reCAPTCHA؛ فقط إنشاء إذا تم تمكين ميزة اختبار reCAPTCHA).</div> | /vault/ipbypass.dat
<div dir="rtl" style="display:inline;">عناوين IPv4 ملف التوقيعات.</div> | /vault/ipv4.dat
<div dir="rtl" style="display:inline;">عناوين IPv4 ملف التوقيعات المخصصة (إعادة تسمية لتفعيل).</div> | /vault/ipv4_custom.dat.RenameMe
<div dir="rtl" style="display:inline;">عناوين IPv6 ملف التوقيعات.</div> | /vault/ipv6.dat
<div dir="rtl" style="display:inline;">عناوين IPv6 ملف التوقيعات المخصصة (إعادة تسمية لتفعيل).</div> | /vault/ipv6_custom.dat.RenameMe
<div dir="rtl" style="display:inline;">ملف لغة.</div> | /vault/lang.php
<div dir="rtl" style="display:inline;">الناتج معالج.</div> | /vault/outgen.php
<div dir="rtl" style="display:inline;">وحدة reCAPTCHA.</div> | /vault/recaptcha.php
<div dir="rtl" style="display:inline;">ملف قواعد العرف لAS6939.</div> | /vault/rules_as6939.php
<div dir="rtl" style="display:inline;">ملف قواعد العرف لSoft Layer.</div> | /vault/rules_softlayer.php
<div dir="rtl" style="display:inline;">ملف قواعد العرف لبعض CIDRs محددة.</div> | /vault/rules_specific.php
<div dir="rtl" style="display:inline;">ملف الملح (المستخدمة من قبل بعض وظائف هامشية؛ فقط تم إنشاؤها إذا لزم الأمر).</div> | /vault/salt.dat
<div dir="rtl" style="display:inline;">ملف القالب. قالب لمخرجات HTML التي تنتجها CIDRAM لرسالة حظر تحميل الملفات (الرسالة التي يراها القائم بالتحميل).</div> | /vault/template.html
<div dir="rtl" style="display:inline;">ملف القالب. قالب لمخرجات HTML التي تنتجها CIDRAM لرسالة حظر تحميل الملفات (الرسالة التي يراها القائم بالتحميل).</div> | /vault/template_custom.html
<div dir="rtl" style="display:inline;">أ ملف المشروع Github (غير مطلوب لتشغيل سليم للبرنامج).</div> | /.gitattributes
<div dir="rtl" style="display:inline;">سجل للتغييرات التي أجريت على البرنامج بين التحديثات المختلفة (غير مطلوب لتشغيل سليم للبرنامج).</div> | /Changelog.txt
<div dir="rtl" style="display:inline;">معلومات Composer/Packagist (غير مطلوب لتشغيل سليم للبرنامج).</div> | /composer.json
<div dir="rtl" style="display:inline;">نسخة من GNU/GPLv2 رخصة (غير مطلوب لتشغيل سليم للبرنامج).</div> | /LICENSE.txt
<div dir="rtl" style="display:inline;">الملف المحمل (المسئول عن التحميل): يحمل البرنامج الرئيسي و التحديث و، إلى آخره. هذا هو الذي من المفترض أن تكون على علاقة به و تقوم بتركيبه (أساسي)!</div> | /loader.php
<div dir="rtl" style="display:inline;">معلومات موجزة المشروع.</div> | /README.md
<div dir="rtl" style="display:inline;">ملف تكوين ASP.NET (في هذه الحالة، لحماية دليل /vault من أن يتم الوصول إليه بواسطة مصادر غير مأذون لها في حالة إذا ما تم تثبيت البرنامج النصي على ملقم يستند إلى تقنيات ASP.NET</div> | /web.config

---


### <div dir="rtl">5. <a name="SECTION5"></a>خياراتالتكوين/التهيئة</div>
<div dir="rtl">وفيما يلي قائمة من المتغيرات الموجودة في ملف تكوين "config.ini"، بالإضافة إلى وصف الغرض منه و وظيفته.<br /><br /></div>

#### <div dir="rtl">"general" (التصنيف)<br /></div>
<div dir="rtl">التكوين العام لـ CIDRAM.<br /><br /></div>

<div dir="rtl">"logfile"<br /></div>
<div dir="rtl"><ul>
 <li>ملف يمكن قراءته بالعين لتسجيل كل محاولات الوصول سدت. تحديد اسم الملف، أو اتركه فارغا لتعطيل.</li>
</ul></div>

<div dir="rtl">"logfileApache"<br /></div>
<div dir="rtl"><ul>
 <li>ملف على غرار أباتشي لتسجيل كل محاولات الوصول سدت. تحديد اسم الملف، أو اتركه فارغا لتعطيل.</li>
</ul></div>

<div dir="rtl">"logfileSerialized"<br /></div>
<div dir="rtl"><ul>
 <li>ملف تسلسل لتسجيل كل محاولات الوصول سدت. تحديد اسم الملف، أو اتركه فارغا لتعطيل.</li>
</ul></div>

<div dir="rtl"><em>نصيحة مفيدة: إن أردت، يمكنك إلحاق تاريخ/المعلومات في الوقت إلى أسماء ملفات السجل من خلال تضمين هذه في اسم: "{yyyy}" لمدة عام كامل، "{yy}" لمدة عام يختصر، "{mm}" لمدة شهر، "{dd}" ليوم واحد، "{hh}" لمدة ساعة (راجع الأمثلة أدناه).</em><br /><br /></div>

```
 logfile='logfile.{yyyy}-{mm}-{dd}-{hh}.txt'
 logfileApache='access.{yyyy}-{mm}-{dd}-{hh}.txt'
 logfileSerialized='serial.{yyyy}-{mm}-{dd}-{hh}.txt'
```

<div dir="rtl">"timeOffset"<br /></div>
<div dir="rtl"><ul>
 <li>إذا بالتوقيت المحلي الخاص بك ليست هي نفسها كما الخادم الخاص بك، يمكنك تحديد إزاحة هنا (لضبط التاريخ / المعلومات في الوقت صنعت بواسطة CIDRAM). الإزاحة المستندة دقيقة.<br /></li>
 <li>مثال (لإضافة ساعة واحدة):</li>
</ul></div>

`timeOffset=60`

<div dir="rtl">"ipaddr"<br /></div>
<div dir="rtl"><ul>
 <li>أين يمكن العثور على عنوان IP لربط الطلبات؟ (مفيدة للخدمات مثل لايتكلاود و مثلها) الافتراضي = REMOTE_ADDR. تحذير: لا تغير هذا إلا إذا كنت تعرف ما تفعلونه!</li>
</ul></div>

<div dir="rtl">"forbid_on_block"<br /></div>
<div dir="rtl"><ul>
 <li>الذي رؤوس ينبغي CIDRAM الرد عندما حظر طلبات؟ False/200 = 200 OK (حسنا) [الافتراضي]؛ True = 403 Forbidden (ممنوع)؛ 503 = 503 Service unavailable (الخدمة غير متوفرة).</li>
</ul></div>

<div dir="rtl">"silent_mode"<br /></div>
<div dir="rtl"><ul>
 <li>يجب CIDRAM إعادة توجيه بصمت محاولات وصول مرفوض بدلا من عرض الصفحة "تم رفض الوصول"؟ اذا نعم، تحديد الموقع لإعادة توجيه محاولات وصول مرفوض. ان لم، ترك هذا الحقل فارغا.</li>
</ul></div>

<div dir="rtl">"lang"<br /></div>
<div dir="rtl"><ul>
 <li>تحديد اللغة الافتراضية الخاصة بـ CIDRAM.</li>
</ul></div>

<div dir="rtl">"emailaddr"<br /></div>
- If you wish, you can supply an email address here to be given to users when they're blocked, for them to use as a point of contact for support and/or assistance for in the event of them being blocked mistakenly or in error. WARNING: Whatever email address you supply here will most certainly be acquired by spambots and scrapers during the course of its being used here, and so, it's strongly recommended that if you choose to supply an email address here, that you ensure that the email address you supply here is a disposable address and/or an address that you don't mind being spammed (in other words, you probably don't want to use your primary personal or primary business email addresses). @TranslateMe@

<div dir="rtl">"disable_cli"<br /></div>
<div dir="rtl"><ul>
 <li>وضع تعطيل CLI؟ يتم تمكين وضع CLI افتراضيا، ولكن يمكن أن تتداخل أحيانا مع بعض أدوات الاختبار (مثل PHPUnit، على سبيل المثال) وغيرها من التطبيقات القائمة على المبادرة القطرية. إذا كنت لا تحتاج إلى تعطيل وضع CLI، يجب تجاهل هذا التوجيه. خطأ = تمكين وضع CLI [الافتراضي]. صحيح = وضع تعطيل CLI.</li>
</ul></div>

<div dir="rtl">"disable_frontend"<br /></div>
<div dir="rtl"><ul>
 <li>تعطيل وصول front-end؟ وصول front-end يستطيع جعل CIDRAM أكثر قابلية للإدارة، ولكن يمكن أيضا أن تكون مخاطر أمنية محتملة. من المستحسن لإدارة CIDRAM عبر back-end متى أمكن، لكن وصول front-end متوفر عندما لم يكن ممكنا. يبقيه المعوقين إلا إذا كنت في حاجة إليها. False = تمكين وصول front-end؛ True = تعطيل وصول front-end [الافتراضي].</li>
</ul></div>

#### <div dir="rtl">"signatures" (التصنيف)<br /></div>
<div dir="rtl">تكوين التوقيعات.<br /><br /></div>

<div dir="rtl">"ipv4"<br /></div>
- A list of the IPv4 signature files that CIDRAM should attempt to parse, delimited by commas. You can add entries here if you want to include additional IPv4 signature files into CIDRAM. @TranslateMe@

<div dir="rtl">"ipv6"<br /></div>
- A list of the IPv6 signature files that CIDRAM should attempt to parse, delimited by commas. You can add entries here if you want to include additional IPv6 signature files into CIDRAM. @TranslateMe@

<div dir="rtl">"block_cloud"<br /></div>
- Block CIDRs identified as belonging to webhosting/cloud services? If you operate an API service from your website or if you expect other websites to connect to your website, this should be set to false. If you don't, then, this directive should be set to true. @TranslateMe@

<div dir="rtl">"block_bogons"<br /></div>
- Block bogon/martian CIDRs? If you expect connections to your website from within your local network, from localhost, or from your LAN, this directive should be set to false. If you don't expect these such connections, this directive should be set to true. @TranslateMe@

<div dir="rtl">"block_generic"<br /></div>
- Block CIDRs generally recommended for blacklisting? This covers any signatures that aren't marked as being part of any of the other more specific signature categories. @TranslateMe@

<div dir="rtl">"block_proxies"<br /></div>
- Block CIDRs identified as belonging to proxy services? If you require that users be able to access your website from anonymous proxy services, this should be set to false. Otherwise, if you don't require anonymous proxies, this directive should be set to true as a means of improving security. @TranslateMe@

<div dir="rtl">"block_spam"<br /></div>
- Block CIDRs identified as being high-risk for spam? Unless you experience problems when doing so, generally, this should always be set to true. @TranslateMe@

#### <div dir="rtl">"recaptcha" (التصنيف)<br /></div>
Optionally, you can provide users with a way to bypass the "تم رفض الوصول!" page by way of completing a reCAPTCHA instance, if you want to do so. This can help to mitigate some of the risks associated with false positives in those situations where we're not entirely sure whether a request has originated from a machine or a human. @TranslateMe@

Due to the risks associated with providing a way for end-users to bypass the "تم رفض الوصول!" page, generally, I would advise against enabling this feature unless you feel it to be necessary to do so. Situations where it could be necessary: If your website has customers/users that need to have access to your website, and if this is something that can't be compromised on, but if those customers/users happen to be connecting from a hostile network that could potentially also be carrying undesirable traffic, and blocking this undesirable traffic is also something that can't be compromised on, in those particular no-win situations, the reCAPTCHA feature could come in handy as a means of allowing the desirable customers/users, while keeping out the undesirable traffic from the same network. That said though, given that the intended purpose of a CAPTCHA is to distinguish between humans and non-humans, the reCAPTCHA feature would only assist in these no-win situations if we're to assume that this undesirable traffic is non-human (eg, spambots, scrapers, hacktools, automated traffic), as opposed to being undesirable human traffic (such as human spammers, hackers, et al). @TranslateMe@

للحصول على "site key" و "secret key" (مطلوب لاستخدام اختبار reCAPTCHA)، الرجاء الذهاب إلى: [https://developers.google.com/recaptcha/](https://developers.google.com/recaptcha/)

<div dir="rtl">"usemode"<br /></div>
- Defines how CIDRAM should use reCAPTCHA. @TranslateMe@
- 0 = reCAPTCHA is completely disabled (default). @TranslateMe@
- 1 = reCAPTCHA is enabled for all signatures. @TranslateMe@
- 2 = reCAPTCHA is enabled only for signatures belonging to sections specially marked as reCAPTCHA-enabled within the signature files. @TranslateMe@
- (Any other value will be treated in the same way as 0). @TranslateMe@

<div dir="rtl">"lockip"<br /></div>
- Specifies whether hashes should be locked to specific IPs. False = Cookies and hashes CAN be used across multiple IPs (default). True = Cookies and hashes CAN'T be used across multiple IPs (cookies/hashes are locked to IPs). @TranslateMe@
- Note: "lockip" value is ignored when "lockuser" is false, due to that the mechanism for remembering "users" differs depending on this value. @TranslateMe@

<div dir="rtl">"lockuser"<br /></div>
- Specifies whether successful completion of a reCAPTCHA instance should be locked to specific users. False = Successful completion of a reCAPTCHA instance will grant access to all requests originating from the same IP as that used by the user completing the reCAPTCHA instance; Cookies and hashes aren't used; Instead, an IP whitelist will be used. True = Successful completion of a reCAPTCHA instance will only grant access to the user completing the reCAPTCHA instance; Cookies and hashes are used to remember the user; An IP whitelist is not used (default). @TranslateMe@

<div dir="rtl">"sitekey"<br /></div>
- This value should correspond to the "site key" for your reCAPTCHA, which can be found within the reCAPTCHA dashboard. @TranslateMe@

<div dir="rtl">"secret"<br /></div>
- This value should correspond to the "secret key" for your reCAPTCHA, which can be found within the reCAPTCHA dashboard. @TranslateMe@

<div dir="rtl">"expiry"<br /></div>
- When "lockuser" is true (default), in order to remember when a user has successfully passed a reCAPTCHA instance, for future page requests, CIDRAM generates a standard HTTP cookie containing a hash which corresponds to an internal record containing that same hash; Future page requests will use these corresponding hashes to authenticate that a user has previously already passed a reCAPTCHA instance. When "lockuser" is false, an IP whitelist is used to determine whether requests should be permitted from the IP of inbound requests; Entries are added to this whitelist when the reCAPTCHA instance is successfully passed. For how many hours should these cookies, hashes and whitelist entries remain valid? Default = 720 (1 month). @TranslateMe@

<div dir="rtl">"logfile"<br /></div>
- Log all reCAPTCHA attempts? If yes, specify the name to use for the logfile. ان لم، ترك هذا الحقل فارغا. @TranslateMe@

<div dir="rtl"><em>نصيحة مفيدة: إن أردت، يمكنك إلحاق تاريخ/المعلومات في الوقت إلى أسماء ملفات السجل من خلال تضمين هذه في اسم: "{yyyy}" لمدة عام كامل، "{yy}" لمدة عام يختصر، "{mm}" لمدة شهر، "{dd}" ليوم واحد، "{hh}" لمدة ساعة (راجع الأمثلة أدناه).</em><br /><br /></div>

`logfile='recaptcha.{yyyy}-{mm}-{dd}-{hh}.txt`

#### <div dir="rtl">"template_data" (التصنيف)<br /></div>
<div dir="rtl">توجيهات/متغيرات القوالب والمواضيع.<br /><br /></div>

<div dir="rtl">تتعلق البيانات بقالب انتاج HTML تستخدم لتوليد "تم رفض الوصول" الرسالة المعروضة للمستخدمين على تحميل ملف حجبها. إذا كنت تستخدم موضوعات مخصصة لـ CIDRAM، هو مصدر إخراج HTML من ملف "template_custom.html" وغيرها، ويتم الحصول على إخراج HTML من ملف "template.html". يتم تحليل المتغيرات الخطية لهذا القسم من ملف التكوين إلى إخراج HTML عن طريق استبدال أي أسماء المتغيرات محاط بواسطة الأقواس الموجودة داخل إخراج HTML مع البيانات المتغيرة المناظرة. فمثلا، أين foo="bar"، أي مثيل &lt;p&gt;{foo}&lt;/p&gt; وجدت داخل إخراج HTML ستصبح &lt;p&gt;bar&lt;/p&gt;.<br /><br /></div>

<div dir="rtl">"css_url"<br /></div>
<div dir="rtl"><ul>
 <li>ملف الصيغة النموذجية للمواضيع مخصصة يستخدم خصائص CSS الخارجية، في حين أن ملف قالب لموضوع الافتراضي يستخدم خصائص CSS الداخلية. لإرشاد CIDRAM لاستخدام ملف النموذجية للمواضيع مخصصة، تحديد عنوان HTTP العام من ملفات CSS موضوع المخصصة لديك باستخدام "css_url" متغير. إذا تركت هذا الحقل فارغا متغير، سوف يقوم CIDRAM باستخدام ملف القالب لموضوع التقصير.</li>
</ul></div>

---


### <div dir="rtl">6. <a name="SECTION6"></a>شكل/تنسيق التوقيع</div>

#### <div dir="rtl">6.0 مبادئ<br /><br /></div>

A description of the format and structure of the signatures used by CIDRAM can be found documented in plain-text within either of the two custom signature files. Please refer to that documentation to learn more about the format and structure of the signatures of CIDRAM.

All IPv4 signatures follow the format: `xxx.xxx.xxx.xxx/yy [Function] [Param]`.
- `xxx.xxx.xxx.xxx` represents the beginning of the CIDR block (the octets of the initial IP address in the block).
- `yy` represents the CIDR block size [1-32].
- `[Function]` instructs the script what to do with the signature (how the signature should be regarded).
- `[Param]` represents whatever additional information may be required by `[Function]`.

All IPv6 signatures follow the format: `xxxx:xxxx:xxxx:xxxx::xxxx/yy [Function] [Param]`.
- `xxxx:xxxx:xxxx:xxxx::xxxx` represents the beginning of the CIDR block (the octets of the initial IP address in the block). Complete notation and abbreviated notation are both acceptable (and each MUST follow the appropriate and relevant standards of IPv6 notation, but with one exception: an IPv6 address can never begin with an abbreviation when used in a signature for this script, due to the way in which CIDRs are reconstructed by the script; For example, `::1/128` should be expressed, when used in a signature, as `0::1/128`, and `::0/128` expressed as `0::/128`).
- `yy` represents the CIDR block size [1-128].
- `[Function]` instructs the script what to do with the signature (how the signature should be regarded).
- `[Param]` represents whatever additional information may be required by `[Function]`.

The signature files for CIDRAM SHOULD use Unix-style linebreaks (`%0A`, or `\n`)! Other types/styles of linebreaks (eg, Windows `%0D%0A` or `\r\n` linebreaks, Mac `%0D` or `\r` linebreaks, etc) MAY be used, but are NOT preferred. Non-Unix-style linebreaks will be normalised to Unix-style linebreaks by the script.

Precise and correct CIDR notation is required, otherwise the script will NOT recognise the signatures. Additionally, all the CIDR signatures of this script MUST begin with an IP address whose IP number can divide evenly into the block division represented by its CIDR block size (eg, if you wanted to block all IPs from `10.128.0.0` to `11.127.255.255`, `10.128.0.0/8` would NOT be recognised by the script, but `10.128.0.0/9` and `11.0.0.0/9` used in conjunction, WOULD be recognised by the script).

Anything in the signature files not recognised as a signature nor as signature-related syntax by the script will be IGNORED, therefore meaning that you can safely put any non-signature data that you want into the signature files without breaking them and without breaking the script. Comments are acceptable in the signature files, and no special formatting is required for them. Shell-style hashing for comments is preferred, but not enforced; Functionally, it makes no difference to the script whether or not you choose to use Shell-style hashing for comments, but using Shell-style hashing helps IDEs and plain-text editors to correctly highlight the various parts of the signature files (and so, Shell-style hashing can assist as a visual aid while editing).

<div dir="rtl">القيم الممكنة من "Function" هي كما يلي:<br /></div>
<div dir="rtl"><ul>
 <li>Run</li>
 <li>Whitelist</li>
 <li>Greylist</li>
 <li>Deny</li>
</ul></div>

<div dir="rtl">إذا تم استخدام "Run"، عندما يتم تشغيل توقيع، السيناريو سوف محاولة لتنفيذ برنامج نصي خارجية (استخدام علامة "require_once" بيان)، التي تحددها قيمة `[Param]` (الدليل يجب أن يكون الدليل "/vault/" البرنامج النصي؛ راجع الأمثلة أدناه).<br /><br /></div>

`127.0.0.0/8 Run example.php`

This can be useful if you want to execute some specific PHP code for some specific IPs and/or CIDRs.

If "Whitelist" is used, when the signature is triggered, the script will reset all detections (if there's been any detections) and break the test function. `[Param]` is ignored. This function is the equivalent of whitelisting a particular IP or CIDR from being detected (راجع الأمثلة أدناه).

`127.0.0.1/32 Whitelist`

If "Greylist" is used, when the signature is triggered, the script will reset all detections (if there's been any detections) and skip to the next signature file to continue processing. `[Param]` is ignored (راجع الأمثلة أدناه).

`127.0.0.1/32 Greylist`

If "Deny" is used, when the signature is triggered, assuming no whitelist signature has been triggered for the given IP address and/or given CIDR, access to the protected page will be denied. "Deny" is what you'll want to use to actually block an IP address and/or CIDR range. When any signatures are triggered that make use of "Deny", the "Access Denied" page of the script will be generated and the request to the protected page killed.

The `[Param]` value accepted by "Deny" will be parsed to the "Access Denied" page output, supplied to the client/user as the cited reason for their access to the requested page being denied. It can be either a short and simple sentence, explaining why you've chosen to block them (anything should suffice, even a simple "I don't want you on my website"), or one of a small handful of shorthand words supplied by the script, that if used, will be replaced by the script with a pre-prepared explanation of why the client/user has been blocked.

The pre-prepared explanations have L10N support and can be translated by the script based upon the language you specify to the `lang` directive of the script configuration. Additionally, you can instruct the script to ignore "Deny" signatures based upon their `[Param]` value (if they're using these shorthand words) via the directives specified by the script configuration (each shorthand word has a corresponding directive to either process the corresponding signatures or to ignore them). `[Param]` values that don't use these shorthand words, however, don't have L10N support and therefore WON'T be translated by the script, and additionally, aren't directly controllable by the script configuration.

<div dir="rtl">الكلمات المختزلة المتاحة هي:<br /></div>
<div dir="rtl"><ul>
 <li>Bogon</li>
 <li>Cloud</li>
 <li>Generic</li>
 <li>Proxy</li>
 <li>Spam</li>
</ul></div>

#### <div dir="rtl">6.1 علامات<br /><br /></div>

If you want to split your custom signatures into individual sections, you can identify these individual sections to the script by adding a "section tag" immediately after the signatures of each section, along with the name of your signature section (راجع الأمثلة أدناه).

```
# القسم 1.
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud
4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
6.7.8.9/32 Deny Proxy
Tag: القسم 1
```

To break section tagging and to ensure that tags aren't incorrectly identified to signature sections from earlier in the signature files, simply ensure that there are at least two consecutive linebreaks between your tag and your earlier signature sections. Any untagged signatures will default to either "IPv4" or "IPv6" (depending on which types of signatures are being triggered).

```
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud

4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: القسم 1
```

In the above example `1.2.3.4/32` and `2.3.4.5/32` will be tagged as "IPv4", whereas `4.5.6.7/32` and `5.6.7.8/32` will be tagged as "القسم 1".

If you want signatures to expire after some time, in a similar manner to section tags, you can use an "expiry tag" to specify when signatures should cease to be valid. Expiry tags use the format "YYYY.MM.DD" (راجع الأمثلة أدناه).

```
# القسم 1.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Expires: 2016.12.31
```

Section tags and expiry tags may be used in conjunction, and both are optional (راجع الأمثلة أدناه).

```
# القسم المثال.
1.2.3.4/32 Deny Generic
Tag: القسم المثال
Expires: 2016.12.31
```

#### <div dir="rtl">6.2 YAML<br /><br /></div>

#### <div dir="rtl">6.2.0 أساسيات YAML<br /><br /></div>

A simplified form of YAML markup may be used in signature files for the purpose of defining behaviours and settings specific to individual signature sections. This may be useful if you want the value of your configuration directives to differ on the basis of individual signatures and signature sections (for example; if you want to supply an email address for support tickets for any users blocked by one particular signature, but don't want to supply an email address for support tickets for users blocked by any other signatures; if you want some specific signatures to trigger a page redirect; if you want to mark a signature section for use with reCAPTCHA; if you want to log blocked access attempts to separate files on the basis of individual signatures and/or signature sections).

Use of YAML markup in the signature files is entirely optional (ie, you may use it if you wish to do so, but you are not required to do so), and is able to leverage most (but not all) configuration directives.

Note: YAML markup implementation in CIDRAM is very simplistic and very limited; It is intended to fulfill requirements specific to CIDRAM in a manner that has the familiarity of YAML markup, but neither follows nor complies with official specifications (and therefore won't behave in the same way as more thorough implementations elsewhere, and may not be appropriate for other projects elsewhere).

In CIDRAM, YAML markup segments are identified to the script by three dashes ("---"), and terminate alongside their containing signature sections by double-linebreaks. A typical YAML markup segment within a signature section consists of three dashes on a line immediately after the list of CIDRS and any tags, followed by a two dimensional list of key-value pairs (first dimension, configuration directive categories; second dimension, configuration directives) for which configuration directives should be modified (and to which values) whenever a signature within that signature section is triggered (راجع الأمثلة أدناه).

```
# Foobar 1.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
4.5.6.7/32 Deny Generic
Tag: Foobar 1
---
general:
 logfile: logfile.{yyyy}-{mm}-{dd}.txt
 logfileApache: access.{yyyy}-{mm}-{dd}.txt
 logfileSerialized: serial.{yyyy}-{mm}-{dd}.txt
 forbid_on_block: false
 emailaddr: username@domain.tld
recaptcha:
 lockip: false
 lockuser: true
 expiry: 720
 logfile: recaptcha.{yyyy}-{mm}-{dd}.txt
 enabled: true
template_data:
 css_url: http://domain.tld/cidram.css

# Foobar 2.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
4.5.6.7/32 Deny Generic
Tag: Foobar 2
---
general:
 logfile: "logfile.Foobar2.{yyyy}-{mm}-{dd}.txt"
 logfileApache: "access.Foobar2.{yyyy}-{mm}-{dd}.txt"
 logfileSerialized: "serial.Foobar2.{yyyy}-{mm}-{dd}.txt"
 forbid_on_block: 503

# Foobar 3.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
4.5.6.7/32 Deny Generic
Tag: Foobar 3
---
general:
 forbid_on_block: 403
 silent_mode: "http://127.0.0.1/"
```

#####6.2.1 HOW TO "SPECIALLY MARK" SIGNATURE SECTIONS FOR USE WITH reCAPTCHA

When "usemode" is 0 or 1, signature sections don't need to be "specially marked" for use with reCAPTCHA (because they already either will or won't use reCAPTCHA, depending on this setting).

When "usemode" is 2, to "specially mark" signature sections for use with reCAPTCHA, an entry is included in the YAML segment for that signature section (راجع الأمثلة أدناه).

```
# وسيكون هذا القسم استخدام اختبار reCAPTCHA.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Tag: reCAPTCHA-Enabled
---
recaptcha:
 enabled: true
```

Note: A reCAPTCHA instance will ONLY be offered to the user if reCAPTCHA is enabled (either with "usemode" as 1, or "usemode" as 2 with "enabled" as true), and if exactly ONE signature has been triggered (no more, no less; if multiple signatures are triggered, a reCAPTCHA instance will NOT be offered).

#### <div dir="rtl">6.3 معلومات اضافية<br /><br /></div>

In addition, if you want CIDRAM to completely ignore some specific sections within any of the signature files, you can use the `ignore.dat` file to specify which sections to ignore. On a new line, write `Ignore`, followed by a space, followed by the name of the section that you want CIDRAM to ignore (راجع الأمثلة أدناه).

```
Ignore القسم 1
```

<div dir="rtl">رؤية الملفات توقيع مخصص لمزيد من المعلومات.<br /><br /></div>

---


### <div dir="rtl">7. <a name="SECTION7"></a>أسئلة وأجوبة (FAQ)</div>

#### <div dir="rtl">ما هو "إيجابية خاطئة"؟<br /><br /></div>

<div dir="rtl" style="display:inline;">المصطلح "إيجابية خاطئة" (<em>بدلا من ذلك: "خطأ إيجابية خاطئة"؛ "انذار خاطئة"</em>؛ الإنجليزية: <em>false positive</em>; <em>false positive error</em>; <em>false alarm</em>)، وصف ببساطة، بشكل عام، يستخدم عند اختبار حالة، للإشارة إلى نتائج هذا الاختبار، عندما تكون النتائج إيجابية (أي، تحديد حالة أن يكون "إيجابية"، أو "صحيح")، ولكن من المتوقع أن تكون (أو كان ينبغي أن يكون) سلبي (أي، الحالة، في الواقع، هو "سلبي"، أو "خاطئة"). "إيجابية خاطئة" ويمكن اعتبار التناظرية من "الذئب الباكي" (حيث لحالة يجري اختبارها هو ما إذا كان هناك ذئب بالقرب من القطيع، الحالة هو "خاطئة" في أنه لا يوجد الذئب بالقرب من القطيع، و الحالة يقال بأنها "إيجابية" بواسطة الراعي عن طريق الدعوة "الذئب، الذئب")، أو التناظرية من الفحص الطبي حيث المريض يتم تشخيص المرض، عندما تكون في واقع، ليس لديهم المرض.<br /><br /></div>

<div dir="rtl" style="display:inline;">بعض المصطلحات ذات الصلة هي "إيجابية صحيح"، "سلبي صحيح" و "سلبي خاطئة". "إيجابية صحيح" هو عندما تكون نتائج الاختبار والحالة الفعلية للحالة على حد سواء صحيح (أو "إيجابية")، و "سلبي صحيح" هو عندما تكون نتائج الاختبار والحالة الفعلية للحالة على حد سواء خاطئة (أو "سلبي")؛ "إيجابية صحيح" أو "سلبي صحيح" ويعتبر أن تكون "الاستدلال الصحيح". نقيض ل "إيجابية خاطئة" هو "سلبي خاطئة"؛ "سلبي خاطئة" هو عندما تكون النتائج سلبي (أي، تحديد حالة أن يكون "سلبي"، أو "خاطئة")، ولكن من المتوقع أن تكون (أو كان ينبغي أن يكون) إيجابية (أي، الحالة، في الواقع، هو "إيجابية"، أو "صحيح").<br /><br /></div>

<div dir="rtl" style="display:inline;">في سياق CIDRAM، هذه المصطلحات تشير إلى التوقيعات CIDRAM و ما/منهم أنهم منع. عندما CIDRAM يمنع عنوان IP نظرا لتوقيع سيئة، قديمة أو غير صحيحة، ولكن لا ينبغي أن تفعل ذلك، أو عندما يفعل ذلك لأسباب خاطئة، نشير إلى هذا الحدث باعتباره "إيجابية خاطئة". عندما CIDRAM يفشل لمنع عنوان IP التي كان ينبغي أن سدت، بسبب تهديدات غير متوقعة، التوقيعات المفقودة أو أوجه القصور توقيع، نشير إلى هذا الحدث باعتباره "افتقد" (هذا هو التناظرية من ا "سلبي خاطئة").<br /><br /></div>

<div dir="rtl">هذا يمكن تلخيصها حسب الجدول أدناه:<br /><br /></div>

&nbsp; <div dir="rtl" style="display:inline;">CIDRAM لا ينبغي منع عنوان IP</div> | &nbsp; <div dir="rtl" style="display:inline;">CIDRAM يجب منع عنوان IP</div> | &nbsp;
---|---|---
<div dir="rtl" style="display:inline;">سلبي صحيح (الاستدلال الصحيح)</div> | <div dir="rtl" style="display:inline;">افتقد (التناظرية من سلبي خاطئة)</div> | <div dir="rtl" style="display:inline;"><strong>CIDRAM لا يمنع عنوان IP</strong></div>
<div dir="rtl" style="display:inline;"><strong>إيجابية خاطئة</strong></div> | <div dir="rtl" style="display:inline;">إيجابية صحيح (الاستدلال الصحيح)</div> | <div dir="rtl" style="display:inline;"><strong>CIDRAM منع عنوان IP</strong></div>

---


<div dir="rtl">آخر تحديث: 5 نوفمبر 2016 (2016.11.05).</div>

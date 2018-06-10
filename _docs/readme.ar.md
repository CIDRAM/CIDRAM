## <div dir="rtl">CIDRAM بالعربية</div>

### <div dir="rtl">المحتويات:</div>
<div dir="rtl"><ul>
 <li>١. <a href="#SECTION1">مقدمة</a></li>
 <li>٢. <a href="#SECTION2">كيفية التحميل</a></li>
 <li>٣. <a href="#SECTION3">كيفية الإستخدام</a></li>
 <li>٤. <a href="#SECTION4">إدارة FRONT-END</a></li>
 <li>٥. <a href="#SECTION5">الملفاتالموجودةفيهذهالحزمة</a></li>
 <li>٦. <a href="#SECTION6">خياراتالتكوين/التهيئة</a></li>
 <li>٧. <a href="#SECTION7">شكل/تنسيق التوقيع</a></li>
 <li>٨. <a href="#SECTION8">مشاكل التوافق المعروفة</a></li>
 <li>٩. <a href="#SECTION9">أسئلة وأجوبة (FAQ)</a></li>
 <li>١٠. محفوظة للإضافات المستقبلية إلى الوثائق.</li>
 <li>١١. <a href="#SECTION11">المعلومات القانونية</a></li>
</ul></div>

<div dir="rtl"><em>ملاحظة بخصوص ترجمة: في حالة الأخطاء (على سبيل المثال، التناقضات بين الترجمات، الأخطاء المطبعية، إلخ)، النسخة الإنجليزية من هذه الوثيقة هو تعتبر النسخة الأصلية وموثوق. إذا وجدت أي أخطاء، سيكون موضع ترحيب مساعدتكم في تصحيحها.</em></div>

---


### <div dir="rtl">١. <a name="SECTION1"></a>مقدمة</div>

<div dir="rtl">CIDRAM (توجيه بين المجالات لافئويا وصول مدير) هو السيناريو PHP، المصممة لحماية المواقع من طلبات الحجب تنشأ من عناوين IP تعتبر مصادر من حركة المرور غير مرغوب فيه، بما في ذلك (ولكن ليس على سبيل الحصر) حركة المرور من نقاط النهاية الوصول غير البشرية، خدمات سحابية، المتطفلين و برامج التطفل، كاشطات الموقع، إلخ. وهي تفعل ذلك عن طريق حساب CIDRs ممكن من عناوين IP الموردة من طلبات واردة وبعد ذلك محاولة لتتناسب مع هذه ضد الملفات توقيعه (هذه الملفات توقيع تحتوي CIDRs من عناوين IP تعتبر مصادر من حركة المرور غير مرغوب فيه)؛ إذا تم العثور على المباريات، يتم حظر الطلبات.<br /><br /></div>

<div dir="rtl"><em>(نرى: <a href="#WHAT_IS_A_CIDR">ما هو "CIDR"؟</a>).</em><br /><br /></div>

<div dir="rtl">حقوق النشر محفوظة ل CIDRAM لعام ٢٠١٦ وما بعده تحت رخصة GNU/GPLv2 للمبرمج (Caleb M (Maikuolan.<br /><br /></div>

<div dir="rtl">هذا البرنامج مجاني، يمكنك تعديله وإعادة نشره تحت رخصة GNU. نشارك هذا السكربت على أمل أن تعم الفائدة لكن لا نتحمل أية مسؤولية أو أية ضمانات لاستخدامك، اطلع على تفاصيل رخصة GNU للمزيد من المعلومات عبر الملف "LICENSE.txt" وللمزيد من المعلومات:</div>

- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

<div dir="rtl">هذا المستند و الحزم المرتبطة به يمكن تحميلها مجاناً من <a href="https://cidram.github.io/">GitHub</a>.</div>

---


### <div dir="rtl">٢. <a name="SECTION2"></a>كيفية التحميل</div>

#### <div dir="rtl">٢.٠ تثبيت يدويا</div>

<div dir="rtl">١. بقراءتك لهذا سنفرض بأنك قمت بتحميل السكربت، من هنا عليك العمل على جهازك المحلي أو نظام إدارة المحتوى لإضافة هذه الأمور، مجلد مثل <code dir="ltr">"/public_html/cidram/"</code> أو ما شابه سيكون كاف.<br /><br /></div>

<div dir="rtl">٢. إعادة تسمية <code dir="ltr">"config.ini.RenameMe"</code> إلى "config.ini" (تقع داخل "vault")، واختياريا (هذه الخطوة اختيارية ينصح بها للمستخدمين المتقدمين ولا ينصح بها للمبتدئين)، افتحه، وعدل الخيارات كما يناسبك (أعلى كل خيار يوجد وصف مختصر للوظيفة التي يقوم بها).<br /><br /></div>

<div dir="rtl">٣. إرفع الملفات للمجلد الذي اخترته(لست بحاجة لرفع <code dir="ltr">"*.txt/*.md"</code> لكن في الغالب يجب أن ترفع جميع الملفات).<br /><br /></div>

<div dir="rtl">٤. غير التصريح لمجلد vault للتصريح<code dir="ltr">"755"</code> (إذا كان هناك مشاكل، يمكنك محاولة<code dir="ltr">"777"</code>، ولكن هذه ليست آمنة). المجلد الرئيسي الذي يحتوي على الملفات-المجلد الذي اخترته سابقاً-، بالعادة يمكن تجاهله، لكن يجب التأكد من التصريح إذا واجهت مشاكل في الماضي(إفتراضيا يجب أن يكون<code dir="ltr">"755"</code>).<br /><br /></div>

<div dir="rtl">٥. الآن أنت بحاجة لربط CIDRAM لنظام إدارة المحتوى أو النظام الذي تستخدمه، هناك عدة طرق لفعل هذا لكن أسهل طريقة ببساطة إضافة السكربت لبداية النواة في نظامك (سيتم إعادة التحميل لكل وصول لأي صفحة في الموقع) بإستخدام جمل "require" أو "include"، بالعادة سيتم التخزين في "/includes"، "/assets" أو "/functions"، وسيتم تسميته بالغالب مثل: "init.php"، "common_functions.php"، "functions.php" أو ما شابه. من الممكن أن تكون مستخدم ل CMS لذا يمكن أن أقدم بعض المساعدة بخصوص هذا الموضوع، لإستخدام "require" أو "include" قم بإضافة الكود التالي لبداية الملف الرئيسي لبرنامجك، عدل النص الموجود داخل علامات التنصيص لمسار "loader.php" لديك.<br /><br /></div>

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

<div dir="rtl">إحفظ الملف ثم قم بإعادة رفعه.<br /><br /></div>

<div dir="rtl">-- أو بدلاً من ذلك --<br /><br /></div>

<div dir="rtl">إذا كنت تستخدم Apache webserver وتستطيع الوصول ل "php.ini"، بإستطاعتك إستخدام "auto_prepend_file" للتوجيه ل CIDRAM لكل طلب مثل:<br /><br /></div>

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

<div dir="rtl">أو هذا في ملف ".htaccess":<br /><br /></div>

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

<div dir="rtl">٦. هذا كل شئ. 😄<br /><br /></div>

#### <div dir="rtl">٢.١ تثبيت مع COMPOSER</div>

<div dir="rtl"><a href="https://packagist.org/packages/cidram/cidram">يتم تسجيل CIDRAM مع Packagist</a>، و بالتالي، إذا كنت على دراية به، يمكنك استخدامه لتثبيت CIDRAM (ستظل بحاجة إلى إعداده على الرغم من ذلك؛ نرى "تثبيت يدويا" الخطوتين ٢ و ٥).<br /><br /></div>

`composer require cidram/cidram`

#### <div dir="rtl">٢.٢ تثبيت ل ووردبريس</div>

<div dir="rtl">إذا كنت ترغب في استخدام CIDRAM مع ووردبريس، يمكنك تجاهل جميع التعليمات أعلاه. <a href="https://wordpress.org/plugins/cidram/">يتوفر CIDRAM من قاعدة بيانات الإضافات وووردبريس</a>. يمكنك تثبيته بنفس الطريقة مثل أي مكون إضافي. كما هو الحال مع أساليب التثبيت الأخرى، يمكنك تخصيص التثبيت الخاص بك عن طريق تعديل محتويات الملف "config.ini"، أو باستخدام صفحة تهيئة front-end.<br /><br /></div>

<div dir="rtl"><em>تحذير: يؤدي تحديث CIDRAM عبر لوحة تحكم المكونات الإضافية إلى تثبيت نظيف! إذا كان لديك تخصيصات (تغيير التكوين، تثبيت وحدات، الخ)، سيتم فقدان هذه التخصيصات عند تحديث عن طريق لوحة أجهزة القياس الإضافات! كما سيتم فقدان لوغفيلز عند تحديث عن طريق لوحة أجهزة القياس الإضافات! للحفاظ على ملفات السجل والتخصيصات، يتم التحديث عبر صفحة التحديثات الأمامية ل CIDRAM.</em><br /><br /></div>

---


### <div dir="rtl">٣. <a name="SECTION3"></a>كيفية الإستخدام</div>

<div dir="rtl">CIDRAM يجب منع تلقائيا طلبات غير مرغوب فيها إلى موقع الويب الخاص بك، دون الحاجة إلى أي مساعدة اليدوية، جانبا من التثبيت.<br /><br /></div>

<div dir="rtl">يمكنك تخصيص التكوين الخاص بك وتخصيص التي CIDRs مسدودة عن طريق تعديل التكوين الخاص بك و ملفات توقيعك.<br /><br /></div>

<div dir="rtl">إذا واجهت أي إيجابية خاطئة، يرجى رسالة لي أن اسمحوا لي أن أعرف عن ذلك. <em>(نرى: <a href="#WHAT_IS_A_FALSE_POSITIVE">ما هو "إيجابية خاطئة"؟</a>).</em><br /><br /></div>

<div dir="rtl">يمكن تحديث CIDRAM يدويا أو عن طريق الfront-end. يمكن أيضا تحديث CIDRAM عبر Composer أو WordPress، إذا تم تثبيتها أصلا عبر تلك الوسائل.<br /><br /></div>

---


### <div dir="rtl">٤. <a name="SECTION4"></a>إدارة FRONT-END</div>

#### <div dir="rtl">٤.٠ ما هو FRONT-END.<br /><br /></div>

<div dir="rtl">Front-end يوفر وسيلة سهلة للحفاظ على، وإدارة، وتحديث CIDRAM. يمكنك عرض، حصة، وتحميل ملفات الدخول، يمكنك تعديل تكوين، يمكنك تثبيت وإلغاء تثبيت مكونات، ويمكنك تحميل وتنزيل وتعديل الملفات.<br /><br /></div>

<div dir="rtl">Front-end معطل في البداية، لمنع الوصول غير المصرح به (الدخول غير المصرح به قد يكون له عواقب أمنية كبيرة). تعليمات لتمكينه أدناه.<br /><br /></div>

#### <div dir="rtl">٤.١ كيفية تمكين FRONT-END.<br /><br /></div>

<div dir="rtl">١. العثور <code dir="ltr">"disable_frontend"</code> من في <code dir="ltr">"config.ini"</code>، وتعيينها إلى false (القيمة القياسية هي true).<br /><br /></div>

<div dir="rtl">٢. الوصول إلى <code dir="ltr">"loader.php"</code> من المتصفح (مثلا، <code dir="ltr">"http://localhost/cidram/loader.php"</code>).<br /><br /></div>

<div dir="rtl">٣. تسجيل الدخول باستخدام اسم المستخدم وكلمة المرور الافتراضية (admin/password).<br /><br /></div>

<div dir="rtl">ملحوظة: تغيير اسم المستخدم وكلمة المرور الخاصة بك بعد تسجيل الدخول للمرة الأولى، من أجل منع الوصول غير المصرح به (هذا مهم جدا)!<br /><br /></div>

#### <div dir="rtl">٤.٢ كيفية استخدام FRONT-END.<br /><br /></div>

<div dir="rtl">في كل صفحة، ويفسر ذلك كيفية استخدامها. إذا كنت بحاجة إلى أي مساعدة، يرجى الاتصال بالدعم. وهناك أيضا بعض مقاطع الفيديو المفيدة المتاحة على موقع يوتيوب.<br /><br /></div>

---


### <div dir="rtl">٥. <a name="SECTION5"></a>الملفاتالموجودةفيهذهالحزمة</div>

<div dir="rtl">فيما يلي قائمة بجميع الملفات التي ينبغي أن تدرج في النسخة المحفوظة من هذا البرنامج النصي عند تحميله، أي الملفات التي يمكن أن يحتمل أن تكون نشأت نتيجة استعمالك لهذا البرنامج النصي، بالإضافة إلى وصفا موجزا لدور و وظيفة كل ملف.<br /><br /></div>

&nbsp; <div dir="rtl" style="display:inline;">الوصف</div> | <div dir="rtl" style="display:inline;">الملف</div>
----|----
&nbsp; <div dir="rtl" style="display:inline;">دليل الوثائق (يحتوي على ملفات مختلفة).</div> | /_docs/
&nbsp; <div dir="rtl" style="display:inline;">الوثائق العربية.</div> | /_docs/readme.ar.md
&nbsp; <div dir="rtl" style="display:inline;">الوثائق الألمانية.</div> | /_docs/readme.de.md
&nbsp; <div dir="rtl" style="display:inline;">الوثائق الإنجليزية.</div> | /_docs/readme.en.md
&nbsp; <div dir="rtl" style="display:inline;">الوثائق الأسبانية.</div> | /_docs/readme.es.md
&nbsp; <div dir="rtl" style="display:inline;">الوثائق الفرنسية.</div> | /_docs/readme.fr.md
&nbsp; <div dir="rtl" style="display:inline;">الوثائق الاندونيسية.</div> | /_docs/readme.id.md
&nbsp; <div dir="rtl" style="display:inline;">الوثائق الايطالية.</div> | /_docs/readme.it.md
&nbsp; <div dir="rtl" style="display:inline;">الوثائق اليابانية.</div> | /_docs/readme.ja.md
&nbsp; <div dir="rtl" style="display:inline;">الوثائق الكورية.</div> | /_docs/readme.ko.md
&nbsp; <div dir="rtl" style="display:inline;">الوثائق الهولندية.</div> | /_docs/readme.nl.md
&nbsp; <div dir="rtl" style="display:inline;">الوثائق البرتغالية.</div> | /_docs/readme.pt.md
&nbsp; <div dir="rtl" style="display:inline;">الوثائق الروسية.</div> | /_docs/readme.ru.md
&nbsp; <div dir="rtl" style="display:inline;">الوثائق الأردية.</div> | /_docs/readme.ur.md
&nbsp; <div dir="rtl" style="display:inline;">الوثائق الفيتنامية.</div> | /_docs/readme.vi.md
&nbsp; <div dir="rtl" style="display:inline;">الوثائق الصينية (المبسطة).</div> | /_docs/readme.zh.md
&nbsp; <div dir="rtl" style="display:inline;">الوثائق الصينية (التقليدية).</div> | /_docs/readme.zh-TW.md
&nbsp; <div dir="rtl" style="display:inline;">دليل /vault/ (يحتوي على ملفات متنوعة).</div> | /vault/
&nbsp; <div dir="rtl" style="display:inline;">الأصول front-end.</div> | /vault/fe_assets/
&nbsp; <div dir="rtl" style="display:inline;">ملف وصول النص التشعبي (في هذه الحالة، لحماية الملفات الحساسة التي تنتمي إلى البرنامج من أن يتم الوصول إليها عن طريق مصادر غير مصرح لها).</div> | /vault/fe_assets/.htaccess
&nbsp; <div dir="rtl" style="display:inline;">ملف قالب HTML لfront-end صفحة الحسابات.</div> | /vault/fe_assets/_accounts.html
&nbsp; <div dir="rtl" style="display:inline;">ملف قالب HTML لfront-end صفحة الحسابات.</div> | /vault/fe_assets/_accounts_row.html
&nbsp; <div dir="rtl" style="display:inline;">ملف قالب HTML لآلة حاسبة CIDR.</div> | /vault/fe_assets/_cidr_calc.html
&nbsp; <div dir="rtl" style="display:inline;">ملف قالب HTML لآلة حاسبة CIDR.</div> | /vault/fe_assets/_cidr_calc_row.html
&nbsp; <div dir="rtl" style="display:inline;">ملف قالب HTML لfront-end صفحة التكوين.</div> | /vault/fe_assets/_config.html
&nbsp; <div dir="rtl" style="display:inline;">ملف قالب HTML لfront-end صفحة التكوين.</div> | /vault/fe_assets/_config_row.html
&nbsp; <div dir="rtl" style="display:inline;">قالب HTML لمدير الملفات.</div> | /vault/fe_assets/_files.html
&nbsp; <div dir="rtl" style="display:inline;">قالب HTML لمدير الملفات.</div> | /vault/fe_assets/_files_edit.html
&nbsp; <div dir="rtl" style="display:inline;">قالب HTML لمدير الملفات.</div> | /vault/fe_assets/_files_rename.html
&nbsp; <div dir="rtl" style="display:inline;">قالب HTML لمدير الملفات.</div> | /vault/fe_assets/_files_row.html
&nbsp; <div dir="rtl" style="display:inline;">ملف قالب HTML لfront-end صفحة الرئيسية.</div> | /vault/fe_assets/_home.html
&nbsp; <div dir="rtl" style="display:inline;">ملف قالب HTML لIP aggregator.</div> | /vault/fe_assets/_ip_aggregator.html
&nbsp; <div dir="rtl" style="display:inline;">ملف قالب HTML لصفحة الاختبار IP.</div> | /vault/fe_assets/_ip_test.html
&nbsp; <div dir="rtl" style="display:inline;">ملف قالب HTML لصفحة الاختبار IP.</div> | /vault/fe_assets/_ip_test_row.html
&nbsp; <div dir="rtl" style="display:inline;">ملف قالب HTML لصفحة التتبع IP.</div> | /vault/fe_assets/_ip_tracking.html
&nbsp; <div dir="rtl" style="display:inline;">ملف قالب HTML لصفحة التتبع IP.</div> | /vault/fe_assets/_ip_tracking_row.html
&nbsp; <div dir="rtl" style="display:inline;">ملف قالب HTML لfront-end صفحة تسجيل الدخول.</div> | /vault/fe_assets/_login.html
&nbsp; <div dir="rtl" style="display:inline;">ملف قالب HTML لfront-end صفحة السجلات.</div> | /vault/fe_assets/_logs.html
&nbsp; <div dir="rtl" style="display:inline;">ملف قالب HTML لfront-end ارتباطات التنقل، يستخدم لهؤلاء مع وصول كامل.</div> | /vault/fe_assets/_nav_complete_access.html
&nbsp; <div dir="rtl" style="display:inline;">ملف قالب HTML لfront-end ارتباطات التنقل، يستخدم لهؤلاء مع سجلات الوصول فقط.</div> | /vault/fe_assets/_nav_logs_access_only.html
&nbsp; <div dir="rtl" style="display:inline;">ملف قالب HTML لfront-end صفحة الجداول الشبكة الفرعية.</div> | /vault/fe_assets/_range.html
&nbsp; <div dir="rtl" style="display:inline;">ملف قالب HTML لfront-end صفحة الجداول الشبكة الفرعية.</div> | /vault/fe_assets/_range_row.html
&nbsp; <div dir="rtl" style="display:inline;">ملف قالب HTML لfront-end صفحة الإحصاء.</div> | /vault/fe_assets/_statistics.html
&nbsp; <div dir="rtl" style="display:inline;">ملف قالب HTML لقائمة الأقسام.</div> | /vault/fe_assets/_sections.html
&nbsp; <div dir="rtl" style="display:inline;">ملف قالب HTML لقائمة الأقسام.</div> | /vault/fe_assets/_sections_row.html
&nbsp; <div dir="rtl" style="display:inline;">ملف قالب HTML لfront-end صفحة التحديثات.</div> | /vault/fe_assets/_updates.html
&nbsp; <div dir="rtl" style="display:inline;">ملف قالب HTML لfront-end صفحة التحديثات.</div> | /vault/fe_assets/_updates_row.html
&nbsp; <div dir="rtl" style="display:inline;">ملف CSS (صفحات الطرز المتراصة) لfront-end.</div> | /vault/fe_assets/frontend.css
&nbsp; <div dir="rtl" style="display:inline;">قاعدة البيانات لfront-end (يحتوي على معلومات الحسابات، الجلسات، وذاكرة التخزين المؤقت؛ خلق فقط اذا front-end يتم تمكين واستخدامها).</div> | /vault/fe_assets/frontend.dat
&nbsp; <div dir="rtl" style="display:inline;">ملف قالب HTML الرئيسي لfront-end.</div> | /vault/fe_assets/frontend.html
&nbsp; <div dir="rtl" style="display:inline;">الرموز معالج (التي يستخدمها مدير الملفات الأمامية).</div> | /vault/fe_assets/icons.php
&nbsp; <div dir="rtl" style="display:inline;">بالنقاط معالج (التي يستخدمها مدير الملفات الأمامية).</div> | /vault/fe_assets/pips.php
&nbsp; <div dir="rtl" style="display:inline;">يحتوي على بيانات JavaScript front-end.</div> | /vault/fe_assets/scripts.js
&nbsp; <div dir="rtl" style="display:inline;">يحتوي على بيانات اللغة لـ CIDRAM.</div> | /vault/lang/
&nbsp; <div dir="rtl" style="display:inline;">ملف وصول النص التشعبي (في هذه الحالة، لحماية الملفات الحساسة التي تنتمي إلى البرنامج من أن يتم الوصول إليها عن طريق مصادر غير مصرح لها).</div> | /vault/lang/.htaccess
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة العربية لCLI.</div> | /vault/lang/lang.ar.cli.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة العربية لfront-end.</div> | /vault/lang/lang.ar.fe.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة العربية.</div> | /vault/lang/lang.ar.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة البنغالية لCLI.</div> | /vault/lang/lang.bn.cli.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة البنغالية لfront-end.</div> | /vault/lang/lang.bn.fe.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة البنغالية.</div> | /vault/lang/lang.bn.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الألمانية لCLI.</div> | /vault/lang/lang.de.cli.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الألمانية لfront-end.</div> | /vault/lang/lang.de.fe.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الألمانية.</div> | /vault/lang/lang.de.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الإنجليزية لCLI.</div> | /vault/lang/lang.en.cli.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الإنجليزية لfront-end.</div> | /vault/lang/lang.en.fe.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الإنجليزية.</div> | /vault/lang/lang.en.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الاسبانية لCLI.</div> | /vault/lang/lang.es.cli.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الاسبانية لfront-end.</div> | /vault/lang/lang.es.fe.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الاسبانية.</div> | /vault/lang/lang.es.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الفرنسية لCLI.</div> | /vault/lang/lang.fr.cli.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الفرنسية لfront-end.</div> | /vault/lang/lang.fr.fe.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الفرنسية.</div> | /vault/lang/lang.fr.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الهندية لCLI.</div> | /vault/lang/lang.hi.cli.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الهندية لfront-end.</div> | /vault/lang/lang.hi.fe.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الهندية.</div> | /vault/lang/lang.hi.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الاندونيسية لCLI.</div> | /vault/lang/lang.id.cli.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الاندونيسية لfront-end.</div> | /vault/lang/lang.id.fe.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الاندونيسية.</div> | /vault/lang/lang.id.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الايطالية لCLI.</div> | /vault/lang/lang.it.cli.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الايطالية لfront-end.</div> | /vault/lang/lang.it.fe.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الايطالية.</div> | /vault/lang/lang.it.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة اليابانية لCLI.</div> | /vault/lang/lang.ja.cli.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة اليابانية لfront-end.</div> | /vault/lang/lang.ja.fe.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة اليابانية.</div> | /vault/lang/lang.ja.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الكورية لCLI.</div> | /vault/lang/lang.ko.cli.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الكورية لfront-end.</div> | /vault/lang/lang.ko.fe.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الكورية.</div> | /vault/lang/lang.ko.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الهولندية لCLI.</div> | /vault/lang/lang.nl.cli.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الهولندية لfront-end.</div> | /vault/lang/lang.nl.fe.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الهولندية.</div> | /vault/lang/lang.nl.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة النرويجيةة لCLI.</div> | /vault/lang/lang.no.cli.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة النرويجيةة لfront-end.</div> | /vault/lang/lang.no.fe.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة النرويجيةة.</div> | /vault/lang/lang.no.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة البرتغالية لCLI.</div> | /vault/lang/lang.pt.cli.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة البرتغالية لfront-end.</div> | /vault/lang/lang.pt.fe.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة البرتغالية.</div> | /vault/lang/lang.pt.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الروسية لCLI.</div> | /vault/lang/lang.ru.cli.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الروسية لfront-end.</div> | /vault/lang/lang.ru.fe.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الروسية.</div> | /vault/lang/lang.ru.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة السويدية لCLI.</div> | /vault/lang/lang.sv.cli.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة السويدية لfront-end.</div> | /vault/lang/lang.sv.fe.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة السويدية.</div> | /vault/lang/lang.sv.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة التايلاندية لCLI.</div> | /vault/lang/lang.th.cli.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة التايلاندية لfront-end.</div> | /vault/lang/lang.th.fe.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة التايلاندية.</div> | /vault/lang/lang.th.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة اللغة التركية لCLI.</div> | /vault/lang/lang.tr.cli.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة اللغة التركية لfront-end.</div> | /vault/lang/lang.tr.fe.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة اللغة التركية.</div> | /vault/lang/lang.tr.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الأردية لCLI.</div> | /vault/lang/lang.ur.cli.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الأردية لfront-end.</div> | /vault/lang/lang.ur.fe.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الأردية.</div> | /vault/lang/lang.ur.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الفيتنامية لCLI.</div> | /vault/lang/lang.vi.cli.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الفيتنامية لfront-end.</div> | /vault/lang/lang.vi.fe.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الفيتنامية.</div> | /vault/lang/lang.vi.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الصينية (التقليدية) لCLI.</div> | /vault/lang/lang.zh-tw.cli.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الصينية (التقليدية) لfront-end.</div> | /vault/lang/lang.zh-tw.fe.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الصينية (التقليدية).</div> | /vault/lang/lang.zh-tw.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الصينية (المبسطة) لCLI.</div> | /vault/lang/lang.zh.cli.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الصينية (المبسطة) لfront-end.</div> | /vault/lang/lang.zh.fe.php
&nbsp; <div dir="rtl" style="display:inline;">ملفات اللغة الصينية (المبسطة).</div> | /vault/lang/lang.zh.php
&nbsp; <div dir="rtl" style="display:inline;">ملف وصول النص التشعبي (في هذه الحالة، لحماية الملفات الحساسة التي تنتمي إلى البرنامج من أن يتم الوصول إليها عن طريق مصادر غير مصرح لها).</div> | /vault/.htaccess
&nbsp; <div dir="rtl" style="display:inline;">المستخدمة من قبل Travis CI للاختبار (غير مطلوب لتشغيل سليم للبرنامج).</div> | /vault/.travis.php
&nbsp; <div dir="rtl" style="display:inline;">المستخدمة من قبل Travis CI للاختبار (غير مطلوب لتشغيل سليم للبرنامج).</div> | /vault/.travis.yml
&nbsp; <div dir="rtl" style="display:inline;">IP aggregator.</div> | /vault/aggregator.php
&nbsp; <div dir="rtl" style="display:inline;">بيانات ذاكرة التخزين المؤقت.</div> | /vault/cache.dat
&nbsp; <div dir="rtl" style="display:inline;">يحتوي على معلومات تتعلق اختياري قائمة منع بلدان التي تقدمها Macmathan؛ المستخدمة من ميزة التحديثات التي تقدمها لCIDRAM.</div> | /vault/cidramblocklists.dat
&nbsp; <div dir="rtl" style="display:inline;">معالج CLI.</div> | /vault/cli.php
&nbsp; <div dir="rtl" style="display:inline;">يحتوي على معلومات تتعلق حدات CIDRAM؛ المستخدمة من ميزة التحديثات التي تقدمها لCIDRAM.</div> | /vault/components.dat
&nbsp; <div dir="rtl" style="display:inline;">ملف التكوين. يحتوي على جميع خيارات تهيئة CIDRAM، يخبرك ماذا يفعل وكيف يعمل بشكل صحيح (إعادة تسمية لتفعيل)!</div> | /vault/config.ini.RenameMe
&nbsp; <div dir="rtl" style="display:inline;">معالج التكوين.</div> | /vault/config.php
&nbsp; <div dir="rtl" style="display:inline;">ملف التخلف التكوين؛ يحتوي على قيم التكوين الافتراضي لCIDRAM.</div> | /vault/config.yaml
&nbsp; <div dir="rtl" style="display:inline;">معالج front-end.</div> | /vault/frontend.php
&nbsp; <div dir="rtl" style="display:inline;">ملف وظائف front-end.</div> | /vault/frontend_functions.php
&nbsp; <div dir="rtl" style="display:inline;">ملف وظائف (ضروري).</div> | /vault/functions.php
&nbsp; <div dir="rtl" style="display:inline;">يحتوي على قائمة من علامات الرقم المقبولة (وثيقة الصلة ميزة اختبار reCAPTCHA؛ فقط إنشاء إذا تم تمكين ميزة اختبار reCAPTCHA).</div> | /vault/hashes.dat
&nbsp; <div dir="rtl" style="display:inline;">تستخدم لتحديد أقسام توقيع التي CIDRAM يجب تجاهل.</div> | /vault/ignore.dat
&nbsp; <div dir="rtl" style="display:inline;">يحتوي على قائمة من الالتفافية IP (وثيقة الصلة ميزة اختبار reCAPTCHA؛ فقط إنشاء إذا تم تمكين ميزة اختبار reCAPTCHA).</div> | /vault/ipbypass.dat
&nbsp; <div dir="rtl" style="display:inline;">عناوين IPv4 ملف التوقيعات (نقاط الوصول غير البشرية و الخدمات السحابية غير المرغوب فيها).</div> | /vault/ipv4.dat
&nbsp; <div dir="rtl" style="display:inline;">عناوين IPv4 ملف التوقيعات (bogon/المريخ CIDRs).</div> | /vault/ipv4_bogons.dat
&nbsp; <div dir="rtl" style="display:inline;">عناوين IPv4 ملف التوقيعات المخصصة (إعادة تسمية لتفعيل).</div> | /vault/ipv4_custom.dat.RenameMe
&nbsp; <div dir="rtl" style="display:inline;">عناوين IPv4 ملف التوقيعات (مزودي خدمات الإنترنت خطيرة ومزعجة).</div> | /vault/ipv4_isps.dat
&nbsp; <div dir="rtl" style="display:inline;">عناوين IPv4 ملف التوقيعات (CIDRs الوكلاء، والشبكات الخاصة الإفتراضية، وغيرها من الخدمات غير المرغوب فيها المتنوعة).</div> | /vault/ipv4_other.dat
&nbsp; <div dir="rtl" style="display:inline;">عناوين IPv6 ملف التوقيعات (نقاط الوصول غير البشرية و الخدمات السحابية غير المرغوب فيها).</div> | /vault/ipv6.dat
&nbsp; <div dir="rtl" style="display:inline;">عناوين IPv6 ملف التوقيعات (bogon/المريخ CIDRs).</div> | /vault/ipv6_bogons.dat
&nbsp; <div dir="rtl" style="display:inline;">عناوين IPv6 ملف التوقيعات المخصصة (إعادة تسمية لتفعيل).</div> | /vault/ipv6_custom.dat.RenameMe
&nbsp; <div dir="rtl" style="display:inline;">عناوين IPv6 ملف التوقيعات (مزودي خدمات الإنترنت خطيرة ومزعجة).</div> | /vault/ipv6_isps.dat
&nbsp; <div dir="rtl" style="display:inline;">عناوين IPv6 ملف التوقيعات (CIDRs الوكلاء، والشبكات الخاصة الإفتراضية، وغيرها من الخدمات غير المرغوب فيها المتنوعة).</div> | /vault/ipv6_other.dat
&nbsp; <div dir="rtl" style="display:inline;">ملف لغة.</div> | /vault/lang.php
&nbsp; <div dir="rtl" style="display:inline;">يحتوي على معلومات تتعلق حدات CIDRAM؛ المستخدمة من ميزة التحديثات التي تقدمها لCIDRAM.</div> | /vault/modules.dat
&nbsp; <div dir="rtl" style="display:inline;">الناتج معالج.</div> | /vault/outgen.php
&nbsp; <div dir="rtl" style="display:inline;">Polyfills لPHP 5.4.X (اللازمة لالتوافق PHP 5.4.X؛ لإصدارات أحدث PHP، آمنة للحذف).</div> | /vault/php5.4.x.php
&nbsp; <div dir="rtl" style="display:inline;">وحدة reCAPTCHA.</div> | /vault/recaptcha.php
&nbsp; <div dir="rtl" style="display:inline;">ملف قواعد العرف لAS6939.</div> | /vault/rules_as6939.php
&nbsp; <div dir="rtl" style="display:inline;">ملف قواعد العرف لSoft Layer.</div> | /vault/rules_softlayer.php
&nbsp; <div dir="rtl" style="display:inline;">ملف قواعد العرف لبعض CIDRs محددة.</div> | /vault/rules_specific.php
&nbsp; <div dir="rtl" style="display:inline;">ملف الملح (المستخدمة من قبل بعض وظائف هامشية؛ فقط تم إنشاؤها إذا لزم الأمر).</div> | /vault/salt.dat
&nbsp; <div dir="rtl" style="display:inline;">ملف القالب. قالب لمخرجات HTML التي تنتجها CIDRAM لرسالة حظر تحميل الملفات (الرسالة التي يراها القائم بالتحميل).</div> | /vault/template_custom.html
&nbsp; <div dir="rtl" style="display:inline;">ملف القالب. قالب لمخرجات HTML التي تنتجها CIDRAM لرسالة حظر تحميل الملفات (الرسالة التي يراها القائم بالتحميل).</div> | /vault/template_default.html
&nbsp; <div dir="rtl" style="display:inline;">ملف المظاهر؛ المستخدمة من ميزة التحديثات التي تقدمها لCIDRAM.</div> | /vault/themes.dat
&nbsp; <div dir="rtl" style="display:inline;">أ ملف المشروع GitHub (غير مطلوب لتشغيل سليم للبرنامج).</div> | /.gitattributes
&nbsp; <div dir="rtl" style="display:inline;">سجل للتغييرات التي أجريت على البرنامج بين التحديثات المختلفة (غير مطلوب لتشغيل سليم للبرنامج).</div> | /Changelog.txt
&nbsp; <div dir="rtl" style="display:inline;">معلومات Composer/Packagist (غير مطلوب لتشغيل سليم للبرنامج).</div> | /composer.json
&nbsp; <div dir="rtl" style="display:inline;">معلومات حول كيفية المساهمة في المشروع.</div> | /CONTRIBUTING.md
&nbsp; <div dir="rtl" style="display:inline;">نسخة من GNU/GPLv2 رخصة (غير مطلوب لتشغيل سليم للبرنامج).</div> | /LICENSE.txt
&nbsp; <div dir="rtl" style="display:inline;">الملف المحمل (المسئول عن التحميل): يحمل البرنامج الرئيسي و التحديث و، إلى آخره. هذا هو الذي من المفترض أن تكون على علاقة به و تقوم بتركيبه (أساسي)!</div> | /loader.php
&nbsp; <div dir="rtl" style="display:inline;">معلومات موجزة المشروع.</div> | /README.md
&nbsp; <div dir="rtl" style="display:inline;">ملف تكوين ASP.NET (في هذه الحالة، لحماية دليل /vault من أن يتم الوصول إليه بواسطة مصادر غير مأذون لها في حالة إذا ما تم تثبيت البرنامج النصي على ملقم يستند إلى تقنيات ASP.NET</div> | /web.config

---


### <div dir="rtl">٦. <a name="SECTION6"></a>خياراتالتكوين/التهيئة</div>
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

<div dir="rtl">"truncate"<br /></div>
<div dir="rtl"><ul>
 <li>اقتطاع ملفات السجل عندما تصل إلى حجم معين؟ القيمة هي الحجم الأقصى في بايت/كيلوبايت/ميغابايت/غيغابايت/تيرابايت الذي قد ينمو ملفات السجل إلى قبل اقتطاعه. القيمة الافتراضية 0KB تعطيل اقتطاع (ملفات السجل يمكن أن تنمو إلى أجل غير مسمى). ملاحظة: ينطبق على ملفات السجل الفردية! ولا يعتبر حجمها جماعيا.</li>
</ul></div>

<div dir="rtl">"log_rotation_limit"<br /></div>
<div dir="rtl"><ul>
 <li>يحدد تدوير السجل عدد ملفات السجل التي يجب أن تكون موجودة في أي وقت. عند إنشاء ملفات السجل الجديدة، إذا تجاوز العدد الإجمالي لبيانات السجل الحد المحدد، فسيتم تنفيذ الإجراء المحدد. يمكنك تحديد الحد المرغوب هنا. ستعمل القيمة 0 على تعطيل تدوير السجل.</li>
</ul></div>

<div dir="rtl">"log_rotation_action"<br /></div>
<div dir="rtl"><ul>
 <li>يحدد تدوير السجل عدد ملفات السجل التي يجب أن تكون موجودة في أي وقت. عند إنشاء ملفات السجل الجديدة، إذا تجاوز العدد الإجمالي لبيانات السجل الحد المحدد، فسيتم تنفيذ الإجراء المحدد. يمكنك تحديد الإجراء المطلوب هنا. Delete = احذف أقدم السجلات، حتى لا يتم تجاوز الحد. Archive = أرشفة أولاً، ثم احذف أقدم السجلات، حتى لا يتم تجاوز الحد.</li>
</ul></div>

<div dir="rtl">التوضيح الفني: في هذا السياق، تعني كلمة "أقدم"، هذا يعني "الأقل معدلة مؤخرا".<br /><br /></div>

<div dir="rtl">"timeOffset"<br /></div>
<div dir="rtl"><ul>
 <li>إذا بالتوقيت المحلي الخاص بك ليست هي نفسها كما الخادم الخاص بك، يمكنك تحديد إزاحة هنا (لضبط التاريخ / المعلومات في الوقت صنعت بواسطة CIDRAM). الإزاحة المستندة دقيقة.<br /></li>
 <li>مثال (لإضافة ساعة واحدة):</li>
</ul></div>

`timeOffset=60`

<div dir="rtl">"timeFormat"<br /></div>
<div dir="rtl"><ul>
 <li>شكل التواريخ المستخدم من قبل CIDRAM. الافتراضي:</li>
</ul></div>

`{Day}, {dd} {Mon} {yyyy} {hh}:{ii}:{ss} {tz}`

<div dir="rtl">"ipaddr"<br /></div>
<div dir="rtl"><ul>
 <li>أين يمكن العثور على عنوان IP لربط الطلبات؟ (مفيدة للخدمات مثل لايتكلاود و مثلها). الافتراضي = REMOTE_ADDR. تحذير: لا تغير هذا إلا إذا كنت تعرف ما تفعلونه!</li>
</ul></div>

<div dir="rtl">"forbid_on_block"<br /></div>
<div dir="rtl"><ul>
 <li>ما هي رسالة حالة HTTP التي يجب أن يرسلها CIDRAM عند حظر الطلبات؟</li>
</ul></div>

<div dir="rtl">القيم المدعومة حاليًا:<br /><br /></div>

رمز حالة | رسالة الحالة | وصف
---|---|---
`200` | `200 OK` | القيمة القياسية أقل قوة، ولكن الأكثر سهولة في الاستخدام.
`403` | `403 Forbidden` | أكثر قوة، ولكن أقل سهولة في الاستخدام.
`410` | `410 Gone` | قد يسبب مشاكل عند محاولة حل نقاط إيجابية خاطئة، نظرًا لأن بعض المتصفحات ستخزن هذه الرسالة مؤقتًا ولن ترسل طلبات لاحقة، حتى بعد إلغاء حظر المستخدمين. قد يكون أكثر فائدة من الخيارات الأخرى لتقليل الطلبات من أنواع معينة معينة من برامج الروبوت بالرغم من ذلك.
`418` | `418 I'm a teapot` | في الواقع تشير إلى نكت كذبة نيسان <a href="https://tools.ietf.org/html/rfc2324#section-6.5.14">RFC 2324</a> ومن غير المحتمل أن يفهمها العميل. المقدمة للتسلية والراحة، ولكن لا يوصى عموما.
`451` | `Unavailable For Legal Reasons` | مناسبة للسياقات عند حظر الطلبات في المقام الأول لأسباب قانونية. غير مستحسن في سياقات أخرى.
`503` | `Service Unavailable` | الأكثر قوة، ولكن الأقل سهولة في الاستخدام.

<div dir="rtl">"silent_mode"<br /></div>
<div dir="rtl"><ul>
 <li>يجب CIDRAM إعادة توجيه بصمت محاولات وصول مرفوض بدلا من عرض الصفحة "تم رفض الوصول"؟ اذا نعم، تحديد الموقع لإعادة توجيه محاولات وصول مرفوض. ان لم، ترك هذا الحقل فارغا.</li>
</ul></div>

<div dir="rtl">"lang"<br /></div>
<div dir="rtl"><ul>
 <li>تحديد اللغة الافتراضية الخاصة بـ CIDRAM.</li>
</ul></div>

<div dir="rtl">"numbers"<br /></div>
<div dir="rtl"><ul>
 <li>لتحديد كيفية عرض الأرقام.</li>
</ul></div>

<div dir="rtl">القيم المدعومة حاليًا:<br /><br /></div>

القيمة | ينتج عنه | وصف
---|---|---
`NoSep-1` | `1234567.89`
`NoSep-2` | `1234567,89`
`Latin-1` | `1,234,567.89` | القيمة القياسية
`Latin-2` | `1 234 567.89`
`Latin-3` | `1.234.567,89`
`Latin-4` | `1 234 567,89`
`Latin-5` | `1,234,567·89`
`China-1` | `123,4567.89`
`India-1` | `12,34,567.89`
`India-2` | `१२,३४,५६७.८९`
`Bengali-1` | `১২,৩৪,৫৬৭.৮৯`
`Arabic-1` | `١٢٣٤٥٦٧٫٨٩`
`Arabic-2` | `١٬٢٣٤٬٥٦٧٫٨٩`
`Thai-1` | `๑,๒๓๔,๕๖๗.๘๙`

<div dir="rtl">ملحوظة: هذه القيم ليست موحدة في أي مكان، وربما لن تكون ذات صلة خارج الحزمة. أيضا، قد تتغير القيم المدعومة في المستقبل.<br /><br /></div>

<div dir="rtl">"emailaddr"<br /></div>
<div dir="rtl"><ul>
 <li>لو كنت تريد، يمكنك توفير عنوان البريد الإلكتروني هنا أن تعطى للمستخدمين عند أنها ممنوعة، بالنسبة لهم لاستخدامها كنقطة اتصال للحصول على الدعم والمساعدة لفي حال منهم سدت طريق الخطأ أو في ضلال. تحذير: أي عنوان البريد الإلكتروني الذي تزويد هنا وبالتأكيد سيتم شراؤها من قبل المتطفلين و برامج التطفل وكاشطات خلال المستخدمة هنا، و حينئذ، انها المستحسن أن إذا اخترت توفير عنوان البريد الإلكتروني هنا، يمكنك التأكد من أن عنوان البريد الإلكتروني الذي نورد هنا يمكن التخلص منها و/أو عنوان أنك لا تمانع في أن محتوى غير مرغوب فيه (بعبارات أخرى، وربما كنت لا تريد استخدام الرئيسية عناوين البريد الإلكتروني التجارية أو العناوين الشخصية الرئيسية الخاصة بك).</li>
</ul></div>

<div dir="rtl">"emailaddr_display_style"<br /></div>
<div dir="rtl"><ul>
 <li>كيف تفضل أن يتم تقديم عنوان البريد الإلكتروني إلى المستخدمين؟ "default" = رابط قابل للنقر. "noclick" = نص غير قابل للنقر.</li>
</ul></div>

<div dir="rtl">"disable_cli"<br /></div>
<div dir="rtl"><ul>
 <li>وضع تعطيل CLI؟ يتم تمكين وضع CLI افتراضيا، ولكن يمكن أن تتداخل أحيانا مع بعض أدوات الاختبار (مثل PHPUnit، على سبيل المثال) وغيرها من التطبيقات القائمة على المبادرة القطرية. إذا كنت لا تحتاج إلى تعطيل وضع CLI، يجب تجاهل هذا التوجيه. خاطئة = تمكين وضع CLI [الافتراضي]. صحيح = وضع تعطيل CLI.</li>
</ul></div>

<div dir="rtl">"disable_frontend"<br /></div>
<div dir="rtl"><ul>
 <li>تعطيل وصول front-end؟ وصول front-end يستطيع جعل CIDRAM أكثر قابلية للإدارة، ولكن يمكن أيضا أن تكون مخاطر أمنية محتملة. من المستحسن لإدارة CIDRAM عبر back-end متى أمكن، لكن وصول front-end متوفر عندما لم يكن ممكنا. يبقيه المعوقين إلا إذا كنت في حاجة إليها. False = تمكين وصول front-end؛ True = تعطيل وصول front-end [الافتراضي].</li>
</ul></div>

<div dir="rtl">"max_login_attempts"<br /></div>
<div dir="rtl"><ul>
 <li>الحد الأقصى لعدد محاولات تسجيل الدخول (front-end). الافتراضي = 5.</li>
</ul></div>

<div dir="rtl">"FrontEndLog"<br /></div>
<div dir="rtl"><ul>
 <li>ملف لتسجيل محاولات الدخول الأمامية. تحديد اسم الملف، أو اتركه فارغا لتعطيل.</li>
</ul></div>

<div dir="rtl">"ban_override"<br /></div>
<div dir="rtl"><ul>
 <li>تجاوز "forbid_on_block" متى "infraction_limit" تجاوزت؟ عندما تجاوز: الطلبات الممنوعة بإرجاع صفحة فارغة (لا يتم استخدام ملفات قالب). 200 = لا تجاوز [الافتراضي]. القيم الأخرى هي نفس القيم المتاحة لـ "forbid_on_block".</li>
</ul></div>

<div dir="rtl">"log_banned_ips"<br /></div>
<div dir="rtl"><ul>
 <li>من IP المحظورة في ملفات السجل؟ True = نعم [افتراضي]؛ False = لا.</li>
</ul></div>

<div dir="rtl">"default_dns"<br /></div>
<div dir="rtl"><ul>
 <li>قائمة بفواصل من خوادم DNS لاستخدامها في عمليات البحث عن اسم المضيف. الافتراضي = "8.8.8.8,8.8.4.4" (Google DNS). تحذير: لا تغير هذا إلا إذا كنت تعرف ما تفعلونه!</li>
</ul></div>

<div dir="rtl">أنظر أيضا: <a href="#WHAT_CAN_I_USE_FOR_DEFAULT_DNS">ما الذي يمكنني استخدامه لـ "default_dns"؟</a><br /><br /></div>

<div dir="rtl">"search_engine_verification"<br /></div>
<div dir="rtl"><ul>
 <li>محاولة للتحقق من طلبات من محركات البحث؟ التحقق من محركات البحث يضمن أنها لن تكون محظورة نتيجة لتجاوز الحد مخالفة (منع محركات البحث من موقع الويب الخاص بك عادة ما يكون لها تأثير سلبي على محرك البحث الترتيب، كبار المسئولين الاقتصاديين، إلخ). عند تمكين التحقق، محركات البحث يمكن أن يكون قد تم حظره، ولكن ليس محظورة. عند تعطيل التحقق، أنها يمكن أن تكون محظورة إذا تجاوزت الحد مخالفة. بالإضافة إلى، التحقق محرك البحث يحمي ضد الكيانات الخبيثة يتنكر في محركات البحث (سيتم حجب هذه الطلبات). True = تمكين التحقق محرك البحث [افتراضي]؛ False = تعطيل التحقق محرك البحث.</li>
</ul></div>

<div dir="rtl">"protect_frontend"<br /></div>
<div dir="rtl"><ul>
 <li>يحدد ما إذا كانت الحماية التي توفرها عادة CIDRAM يجب أن تطبق الfront-end. True = نعم [افتراضي]؛ False = لا.</li>
</ul></div>

<div dir="rtl">"disable_webfonts"<br /></div>
<div dir="rtl"><ul>
 <li>هل تريد تعطيل ويبفونتس؟ True = نعم [افتراضي]؛ False = لا.</li>
</ul></div>

<div dir="rtl">"maintenance_mode"<br /></div>
<div dir="rtl"><ul>
 <li>هل تريد تمكين وضع الصيانة؟ True = نعم؛ False = لا [افتراضي]. تعطيل كل شيء بخلاف front-end. قد تكون مفيدة أحيانا عند تحديث نظام إدارة المحتوى والأطر وما إلى ذلك.</li>
</ul></div>

<div dir="rtl">"default_algo"<br /></div>
<div dir="rtl"><ul>
 <li>يحدد الخوارزمية التي سيتم استخدامها لكل كلمات المرور والجلسات المستقبلية. خيارات: PASSWORD_DEFAULT (افتراضي)، PASSWORD_BCRYPT، PASSWORD_ARGON2I (يتطلب PHP >= 7.2.0).</li>
</ul></div>

<div dir="rtl">"statistics"<br /></div>
<div dir="rtl"><ul>
 <li>هل تريد تتبع إحصاءات استخدام CIDRAM؟ True = نعم؛ False = لا [افتراضي].</li>
</ul></div>

<div dir="rtl">"force_hostname_lookup"<br /></div>
<div dir="rtl"><ul>
 <li>فرض بحث اسم المضيف؟ True = نعم؛ False = لا [افتراضي]. يتم إجراء عمليات البحث عن اسم المضيف عادة على أساس "حسب الحاجة"، ولكن يمكن إجبارها على جميع الطلبات. وقد يكون القيام بذلك مفيدا كوسيلة لتوفير معلومات أكثر تفصيلا في السجلات، ولكن قد يكون له أيضا أثر سلبي طفيف على الأداء.</li>
</ul></div>

<div dir="rtl">"allow_gethostbyaddr_lookup"<br /></div>
<div dir="rtl"><ul>
 <li>السماح بعمليات البحث gethostbyaddr عندما يكون UDP غير متوفر؟ True = نعم [افتراضي]؛ False = لا.</li>
 <li>ملاحظة: قد لا يعمل البحث عن <code dir="ltr">IPv6</code> بشكل صحيح على بعض أنظمة <code dir="ltr">32-bit</code>.</li>
</ul></div>

<div dir="rtl">"hide_version"<br /></div>
<div dir="rtl"><ul>
 <li>إخفاء معلومات الإصدار من السجلات وإخراج الصفحة؟ True = نعم؛ False = لا [افتراضي].</li>
</ul></div>

#### <div dir="rtl">"signatures" (التصنيف)<br /></div>
<div dir="rtl">تكوين التوقيعات.<br /><br /></div>

<div dir="rtl">"ipv4"<br /></div>
<div dir="rtl"><ul>
 <li>وهناك قائمة من الملفات توقيع عناوين IPv4 التي CIDRAM يجب أن تحاول معالجة، مفصولة بفواصل. يمكنك إضافة إدخالات هنا إذا كنت ترغب في تضمين الملفات توقيع الإصدار IPv4 إضافية إلى CIDRAM.</li>
</ul></div>

<div dir="rtl">"ipv6"<br /></div>
<div dir="rtl"><ul>
 <li>وهناك قائمة من الملفات توقيع عناوين IPv6 التي CIDRAM يجب أن تحاول معالجة، مفصولة بفواصل. يمكنك إضافة إدخالات هنا إذا كنت ترغب في تضمين الملفات توقيع الإصدار IPv6 إضافية إلى CIDRAM.</li>
</ul></div>

<div dir="rtl">"block_cloud"<br /></div>
<div dir="rtl"><ul>
 <li>منع CIDRs التي تم تحديدها على أنها تنتمي إلى خدمات سحابية/الاستضافة؟ إذا كنت تعمل على خدمة API من موقع الويب الخاص بك، أو إذا كنت تتوقع مواقع أخرى للاتصال موقع الويب الخاص بك، هذا يجب أن يتم تعيين إلى false. إذا لم تقم بذلك، ثم، فإنه يجب تعيين إلى true.</li>
</ul></div>

<div dir="rtl">"block_bogons"<br /></div>
<div dir="rtl"><ul>
 <li>منع CIDRs المريخ/bogon؟ إذا كنت تتوقع اتصالات إلى موقع الويب الخاص بك من خلال الشبكة المحلية، هذا يجب أن يتم تعيين إلى false. ان لم، هذا يجب أن يتم تعيين إلى true.</li>
</ul></div>

<div dir="rtl">"block_generic"<br /></div>
<div dir="rtl"><ul>
 <li>منع CIDRs الموصى بها عموما للالقائمة السوداء؟ وهذا يشمل أي التوقيعات التي ليست جزءا من الفئات الأخرى.</li>
</ul></div>

<div dir="rtl">"block_legal"<br /></div>
<div dir="rtl"><ul>
 <li>حظر CIDRs ردا على الالتزامات القانونية؟ لا يجب أن يكون لهذا التوجيه عادة أي تأثير، لأن CIDRAM لا تربط أي CIDR مع "التزامات قانونية"، ولكنها موجودة كإجراء تحكم إضافي لصالح أي ملفات أو وحدات توقيع مخصصة قد تكون موجودة لأسباب قانونية.</li>
</ul></div>

<div dir="rtl">"block_malware"<br /></div>
<div dir="rtl"><ul>
 <li>حظر عناوين IP المرتبطة بالبرامج الضارة؟ وهذا يشمل خوادم C&C، والآلات المصابة، والآلات المستخدمة في توزيع البرامج الضارة، وما إلى ذلك.</li>
</ul></div>

<div dir="rtl">"block_proxies"<br /></div>
<div dir="rtl"><ul>
 <li>منع CIDRs التي تم تحديدها على أنها تنتمي إلى خدمات وكيل أو شبكات VPN؟ إذا كنت تحتاج إلى أن يكون المستخدمون قادرين على الوصول إلى موقع الويب الخاص بك من خدمات بروكسي أو شبكات VPN، هذا يجب أن يتم تعيين إلى false. ان لم، هذا يجب تعيين إلى true كوسيلة لتحسين الأمن.</li>
</ul></div>

<div dir="rtl">"block_spam"<br /></div>
<div dir="rtl"><ul>
 <li>منع CIDRs التي تم تحديدها على أنها مخاطر البريد المزعج؟ عندما يكون ذلك ممكنا، عموما، وهذا ينبغي دائما أن يتم تعيين إلى true.</li>
</ul></div>

<div dir="rtl">"modules"<br /></div>
<div dir="rtl"><ul>
 <li>قائمة الملفات وحدة لتحميل بعد التحقق من التوقيعات IPv4/IPv6، مفصولة بفواصل.</li>
</ul></div>

<div dir="rtl">"default_tracktime"<br /></div>
<div dir="rtl"><ul>
 <li>كم ثانية لتعقب IP حظرت من قبل وحدات. افتراضي = 604800 (1 أسبوع).</li>
</ul></div>

<div dir="rtl">"infraction_limit"<br /></div>
<div dir="rtl"><ul>
 <li>يسمح الحد الأقصى لعدد المخالفات IP يمكن أن تتكبد قبل أن يتم حظره من قبل تتبع IP. افتراضي = 10.</li>
</ul></div>

<div dir="rtl">"track_mode"<br /></div>
<div dir="rtl"><ul>
 <li>متى يجب أن تحسب المخالفات؟ False = عندما IP تم حظره من قبل وحدات. True = عندما IP يتم حظر لأي سبب من الأسباب.</li>
</ul></div>

#### <div dir="rtl">"recaptcha" (التصنيف)<br /></div>
<div dir="rtl">اختياريا، أنت يمكن أن توفر للمستخدمين طريقة لتجاوز "تم رفض الوصول!" الصفحة، عن طريق استكمال اختبار reCAPTCHA. هذا يمكن أن تساعد على التخفيف من بعض المخاطر المرتبطة مع إيجابية خاطئة في تلك الحالات حيث أننا لسنا متأكدين تماما ما إذا كان طلب قد نشأت من آلة أو الإنسان.<br /><br /></div>

<div dir="rtl">بسبب المخاطر المرتبطة تجاوز "تم رفض الوصول!" الصفحة، أنصح ضد هذه الميزة إلا إذا كنت تعتقد انه من الضروري لاستخدامه. الحالات التي يمكن أن تكون ضرورية: إذا كان موقع الويب الخاص بك يحتوي على المستخدمين التي تحتاج إلى الحصول على موقع الويب الخاص بك، وإذا كان هذا لا يمكن التفاوض، ولكن إذا كان هؤلاء المستخدمين تتصل من الشبكة المعادية التي يحتمل أن تحمل حركة المرور غير مرغوب فيه، وهذا أيضا لا يمكن التفاوض، في تلك الحالات، reCAPTCHA يمكن أن تكون مفيدة كوسيلة ليسمح للمستخدمين المطلوب، والابتعاد عن حركة غير مرغوب فيه من نفس الشبكة. ومع ذلك، بالنظر إلى أن الغرض المقصود من reCAPTCHA، reCAPTCHA سيكون مساعدة فقط في هذه الحالات إذا كانت حركة المرور غير المرغوب فيها غير البشرية (على سبيل المثال، المتطفلين و برامج التطفل، كاشطات، أدوات القراصنة، حركة المرور الآلي)، بدلا من كونها حركة الإنسان (على سبيل المثال، الاطر البشرية، قراصنة، وآخرون).<br /><br /></div>

<div dir="rtl">للحصول على "site key" و "secret key" (مطلوب لاستخدام اختبار reCAPTCHA)، الرجاء الذهاب إلى: <a href="https://developers.google.com/recaptcha/">https://developers.google.com/recaptcha/</a><br /><br /></div>

<div dir="rtl">"usemode"<br /></div>
<div dir="rtl"><ul>
 <li>هذا ويعرف كيفية CIDRAM يجب استخدام اختبار reCAPTCHA.</li>
 <li>0 = reCAPTCHA لم يتم تمكين (الافتراضي).</li>
 <li>1 = اختبار reCAPTCHA يتم تمكين لجميع التوقيعات.</li>
 <li>2 = اختبار reCAPTCHA يتم تمكين فقط للتوقيعات ملحوظة.</li>
 <li>(سيتم التعامل مع أي قيمة أخرى بالطريقة نفسها ك 0).</li>
</ul></div>

<div dir="rtl">"lockip"<br /></div>
<div dir="rtl"><ul>
 <li>تحدد ما إذا كان التجزئة يجب أن يكون مؤمنا إلى عناوين IP محددة. False = الكوكيز والتجزئة يمكن استخدامها عبر عدة عناوين IP (الافتراضي). True = الكوكيز والتجزئة لا يمكن استخدامها عبر عدة عناوين IP (وتخوض الكوكيز والتجزئة إلى عناوين IP).</li>
 <li>ملحوظة: "lockip" يتم تجاهل قيمة عندما "lockuser" غير false (آلية لتذكر المستخدمين مختلفة، اعتمادا على هذه القيمة).</li>
</ul></div>

<div dir="rtl">"lockuser"<br /></div>
<div dir="rtl"><ul>
 <li>تحدد ما إذا كان اختبار reCAPTCHA يجب أن يكون مؤمنا لمستخدمين محددين. False = الانتهاء من اختبار reCAPTCHA منح حق الوصول إلى كافة الطلبات من عنوان IP نفسه؛ لا تستخدم الكوكيز والتجزئة؛ بدلا من ذلك، سيتم استخدام قائمة بيضاء IP. True = الانتهاء من اختبار reCAPTCHA منح حق الوصول فقط إلى المستخدم؛ تستخدم الكوكيز والتجزئة لتذكر المستخدم؛ لا يتم استخدام القائمة البيضاء IP (الافتراضي).</li>
</ul></div>

<div dir="rtl">"sitekey"<br /></div>
<div dir="rtl"><ul>
 <li>يجب أن تتطابق هذه القيمة إلى "site key" لاختبار reCAPTCHA الخاص بك، التي يمكن العثور عليها داخل لوحة التحكم اختبار reCAPTCHA.</li>
</ul></div>

<div dir="rtl">"secret"<br /></div>
<div dir="rtl"><ul>
 <li>يجب أن تتطابق هذه القيمة إلى "secret key" لاختبار reCAPTCHA الخاص بك، التي يمكن العثور عليها داخل لوحة التحكم اختبار reCAPTCHA.</li>
</ul></div>

<div dir="rtl">"expiry"<br /></div>
<div dir="rtl"><ul>
 <li>عندما "lockuser" هو true (الافتراضي)، من أجل أن نتذكر عند اكتمال المستخدم اختبار reCAPTCHA، لطلبات الصفحة المستقبلية، CIDRAM سوف إنشاء ملف تعريف ارتباط HTTP القياسية التي تتضمن تجزئة، والتي تتطابق مع السجل الداخلي، والذي يحتوي على نفس التجزئة؛ سوف طلبات الصفحة في المستقبل استخدام هذه لمصادقة المستخدم. عندما "lockuser" هو false، تم استخدام القائمة البيضاء IP لتحديد ما إذا كان ينبغي السماح الطلبات؛ وأضاف إدخالات إلى هذا البيضاء عند اكتمال اختبار reCAPTCHA. عدد الساعات يجب أن تبقى هذه صالحة؟ الافتراضي = 720 (١ شهر).</li>
</ul></div>

<div dir="rtl">"logfile"<br /></div>
<div dir="rtl"><ul>
 <li>تسجيل جميع محاولات اختبار reCAPTCHA؟ إذا كانت الإجابة بنعم، حدد اسم لاستخدامه في ملف السجل. ان لم، ترك هذا الحقل فارغا.</li>
</ul></div>

<div dir="rtl"><em>نصيحة مفيدة: إن أردت، يمكنك إلحاق تاريخ/المعلومات في الوقت إلى أسماء ملفات السجل من خلال تضمين هذه في اسم: "{yyyy}" لمدة عام كامل، "{yy}" لمدة عام يختصر، "{mm}" لمدة شهر، "{dd}" ليوم واحد، "{hh}" لمدة ساعة (راجع الأمثلة أدناه).</em><br /><br /></div>

`logfile='recaptcha.{yyyy}-{mm}-{dd}-{hh}.txt'`

<div dir="rtl">"signature_limit"<br /></div>
<div dir="rtl"><ul>
 <li>الحد الأقصى لعدد التوقيعات المسموح بتشغيلها عند تقديم مثال reCAPTCHA. افتراضي = 1. إذا تم تجاوز هذا الرقم لأي طلب معين، لن يتم عرض مثال reCAPTCHA.</li>
</ul></div>

<div dir="rtl">"api"<br /></div>
<div dir="rtl"><ul>
 <li>أي API لاستخدام؟ V2 أو Invisible؟</li>
</ul></div>

<div dir="rtl">ملاحظة للمستخدمين في الاتحاد الأوروبي: عند تهيئة CIDRAM لاستخدام ملفات تعريف الارتباط (على سبيل المثال، عندما يكون "lockuser" صحيحا/true)، يتم عرض تحذير ملف تعريف الارتباط بشكل بارز على الصفحة وفقا ل <a href="http://ec.europa.eu/ipg/basics/legal/cookies/index_en.htm">تشريعات ملفات تعريف الارتباط في الاتحاد الأوروبي</a>. ومع ذلك، عند استخدام invisible API، CIDRAM يحاول إكمال reCAPTCHA للمستخدم تلقائيا، وعندما ناجحة، وهذا يمكن أن يؤدي إلى إعادة تحميل الصفحة ويتم إنشاء ملف تعريف الارتباط دون إعطاء المستخدم الوقت الكافي ل في الواقع رؤية تحذير ملف تعريف الارتباط. إذا كان هذا يشكل خطرا قانونيا بالنسبة لك، قد يكون من الأفضل استخدام V2 API بدلا من invisible API (V2 API ليست الآلي، ويتطلب أن يكمل المستخدم التحدي reCAPTCHA أنفسهم، وبالتالي توفير فرصة لرؤية تحذير ملف تعريف الارتباط).<em></em><br /><br /></div>

#### <div dir="rtl">"legal" (التصنيف)<br /></div>
<div dir="rtl">التكوين المتعلق بالمتطلبات القانونية.<br /><br /></div>

<div dir="rtl">لمزيد من المعلومات حول المتطلبات القانونية وكيف يمكن أن يؤثر ذلك على متطلبات التهيئة الخاصة بك، يرجى الرجوع إلى قسم <a href="#SECTION11">المعلومات القانونية</a> من الوثائق.<br /><br /></div>

<div dir="rtl">"pseudonymise_ip_addresses"<br /></div>
<div dir="rtl"><ul>
 <li>إخفاء عناوين IP عند كتابة السجلات؟ True = نعم؛ False = لا [افتراضي].</li>
</ul></div>

<div dir="rtl">"omit_ip"<br /></div>
<div dir="rtl"><ul>
 <li>حذف عناوين IP من السجلات؟ True = نعم؛ False = لا [افتراضي]. ملاحظة: يصبح "pseudonymise_ip_addresses" مكررًا عندما يكون "omit_ip" هو "true".</li>
</ul></div>

<div dir="rtl">"omit_hostname"<br /></div>
<div dir="rtl"><ul>
 <li>حذف أسماء المضيف من السجلات؟ True = نعم؛ False = لا [افتراضي].</li>
</ul></div>

<div dir="rtl">"omit_ua"<br /></div>
<div dir="rtl"><ul>
 <li>حذف وكلاء المستخدم من السجلات؟ True = نعم؛ False = لا [افتراضي].</li>
</ul></div>

<div dir="rtl">"privacy_policy"<br /></div>
<div dir="rtl"><ul>
 <li>عنوان سياسة الخصوصية ذات الصلة ليتم عرضها في تذييل الصفحات التي تم إنشاؤها. حدد عنوان URL، أو اتركه فارغًا لتعطيله.</li>
</ul></div>

#### <div dir="rtl">"template_data" (التصنيف)<br /></div>
<div dir="rtl">توجيهات/متغيرات القوالب والمواضيع.<br /><br /></div>

<div dir="rtl">تتعلق البيانات بقالب انتاج HTML تستخدم لتوليد "تم رفض الوصول" الرسالة المعروضة للمستخدمين على تحميل ملف حجبها. إذا كنت تستخدم موضوعات مخصصة لـ CIDRAM، هو مصدر إخراج HTML من ملف "template_custom.html" وغيرها، ويتم الحصول على إخراج HTML من ملف "template.html". يتم تحليل المتغيرات الخطية لهذا القسم من ملف التكوين إلى إخراج HTML عن طريق استبدال أي أسماء المتغيرات محاط بواسطة الأقواس الموجودة داخل إخراج HTML مع البيانات المتغيرة المناظرة. فمثلا، أين foo="bar"، أي مثيل &lt;p&gt;{foo}&lt;/p&gt; وجدت داخل إخراج HTML ستصبح &lt;p&gt;bar&lt;/p&gt;.<br /><br /></div>

<div dir="rtl">"theme"<br /></div>
<div dir="rtl"><ul>
 <li>الموضوع الافتراضي لاستخدام CIDRAM.</li>
</ul></div>

<div dir="rtl">"Magnification"<br /></div>
<div dir="rtl"><ul>
 <li>تكبير الخط. افتراضي = 1.</li>
</ul></div>

<div dir="rtl">"css_url"<br /></div>
<div dir="rtl"><ul>
 <li>ملف الصيغة النموذجية للمواضيع مخصصة يستخدم خصائص CSS الخارجية، في حين أن ملف قالب لموضوع الافتراضي يستخدم خصائص CSS الداخلية. لإرشاد CIDRAM لاستخدام ملف النموذجية للمواضيع مخصصة، تحديد عنوان HTTP العام من ملفات CSS موضوع المخصصة لديك باستخدام "css_url" متغير. إذا تركت هذا الحقل فارغا متغير، سوف يقوم CIDRAM باستخدام ملف القالب لموضوع التقصير.</li>
</ul></div>

---


### <div dir="rtl">٧. <a name="SECTION7"></a>شكل/تنسيق التوقيع</div>

<div dir="rtl">أنظر أيضا:<br /></div>
<div dir="rtl"><ul>
 <li><a href="#WHAT_IS_A_SIGNATURE">ما هو "التوقيع"؟</a></li>
</ul></div>

#### <div dir="rtl">٧.٠ مبادئ (بالنسبة إلى ملفات التوقيع)<br /><br /></div>

<div dir="rtl">يوصف شكل وهيكل للتوقيعات في ملفات التوقيعات المخصصة. يمكنك الرجوع إليه من أجل معرفة المزيد عن شكل وهيكل من التوقيعات.<br /><br /></div>

<div dir="rtl">جميع التوقيعات من IPv4 تتبع هذا الشكل: "xxx.xxx.xxx.xxx/yy [وظيفة] [معامل]".<br /></div>
<div dir="rtl"><ul>
 <li>"xxx.xxx.xxx.xxx" يمثل بداية كتلة CIDR (المجموعة ثمانية من عنوان IP الأول).</li>
 <li>"yy" تمثل حجم الكتلة [١-٣٢].</li>
 <li>"[وظيفة]" يرشد النصي ما يجب القيام به مع التوقيع.</li>
 <li>"[معامل]" تمثل أي معلومات إضافية قد تكون مطلوبة من قبل "[وظيفة]".</li>
</ul></div>

<div dir="rtl">جميع التوقيعات من IPv6 تتبع هذا الشكل: <code dir="ltr">"xxxx:xxxx:xxxx:xxxx::xxxx/yy"</code> [وظيفة] [معامل]".<br /></div>
<div dir="rtl"><ul>
 <li><code dir="ltr">"xxxx:xxxx:xxxx:xxxx::xxxx"</code> يمثل بداية كتلة CIDR (المجموعة ثمانية من عنوان IP الأول). تدوين كامل وتدوين يختصر على حد سواء مقبول (كل يجب أن تلتزم المعايير تدوين الإصدار IPv6، ولكن مع استثناء واحد: عنوان IPv6 لا يمكن أبدا أن تبدأ مع اختصار عند استخدامها في التوقيع لهذا النصي، بسبب الطريقة التي يتم بناؤها CIDRs؛ فمثلا،<code dir="ltr">"::1/128"</code> ينبغي التعبير، عند استخدامها في توقيع، كما<code dir="ltr">"0::1/128"</code>، و"::0/128" التعبير بأنه<code dir="ltr">"0::/128"</code>).</li>
 <li>"yy" تمثل حجم الكتلة [١-١٢٨].</li>
 <li>"[وظيفة]" يرشد النصي ما يجب القيام به مع التوقيع.</li>
 <li>"[معامل]" تمثل أي معلومات إضافية قد تكون مطلوبة من قبل "[وظيفة]".</li>
</ul></div>

<div dir="rtl">أسطر جديدة يونكس الموصى بها (<code dir="ltr">"%0A"</code>، أو <code dir="ltr">"\n"</code>)! أسطر جديدة أخرى (على سبيل المثال، Windows <code dir="ltr">"%0D%0A"</code> أو أسطر جديدة <code dir="ltr">"\r\n"</code>، Mac <code dir="ltr">"%0D"</code> أو أسطر جديدة <code dir="ltr">"\r"</code>، إلخ) يمكن استخدامها، ولكن لا يفضل. أسطر جديدة تطبيع من قبل البرنامج النصي.<br /><br /></div>

<div dir="rtl">يجب أن يكون تدوين CIDR دقيق. يجب أن أعداد تقسم بالتساوي (على سبيل المثال، من أجل الحيلولة دون<code dir="ltr">"10.128.0.0"-"11.127.255.255"</code>، <code dir="ltr">"10.128.0.0/8"</code> لن تكون صالحة، لكن <code dir="ltr">"10.128.0.0/9"</code> و<code dir="ltr">"11.0.0.0/9"</code> على ما يرام).<br /><br /></div>

<div dir="rtl">أي شيء لذا أوقع الذي يعني أنك يمكن أن تريد بأمان في ملفات توقيعك دون انقطاع دون اقتحام والكتابات التي وضعت البيانات في أي غير الموقعة، و سيتم تجاهل رأي ولا كدليل على الاعتراف بأنه بناء الجملة من توقيع الملفات النصي. التعليقات هي ملفات التوقيع مقبولة، وأي تنسيق خاص اللازمة لها. على غرار قذيفة المفضل التجزئة للحصول على تعليق، ولكن لا تفرض أي، يستحق ذلك تماما، فإنه لا يحدث أي فارق أو لم النصي اختيار استخدام التجزئة على غرار قذيفة على تعليقاتكم، ولكن التجزئة على غرار قذيفة استخدام برامج تحرير النصوص واضحة وبيئات التطوير يساعد على تسليط الضوء وقعت أجزاء من الملفات بشكل صحيح (وبالتالي، يمكن أن تساعد باعتبارها المساعدات البصرية في حالة وضع تحرير التجزئة).<br /><br /></div>

<div dir="rtl">القيم الممكنة من "[وظيفة]" هي كما يلي:<br /></div>
<div dir="rtl"><ul>
 <li>Run</li>
 <li>Whitelist</li>
 <li>Greylist</li>
 <li>Deny</li>
</ul></div>

<div dir="rtl">إذا تم استخدام "Run"، عندما يتم تشغيل توقيع، السيناريو سوف محاولة لتنفيذ برنامج نصي خارجية (استخدام علامة "require_once" بيان)، التي تحددها قيمة "[معامل]" (الدليل يجب أن يكون الدليل "/vault/" البرنامج النصي؛ راجع الأمثلة أدناه).<br /><br /></div>

`127.0.0.0/8 Run example.php`

<div dir="rtl">يمكن أن يكون هذا مفيدا لتشغيل كود PHP معين لبعض عناوين IP و CIDRs.<br /><br /></div>

<div dir="rtl">إذا تم استخدام "Whitelist"، عندما يتم تشغيل توقيع، البرنامج النصي إعادة تعيين كافة المكتشفة والانتهاء من عملية. "[معامل]" يتم تجاهل (راجع الأمثلة أدناه).<br /><br /></div>

`127.0.0.1/32 Whitelist`

<div dir="rtl">إذا تم استخدام "Greylist"، عندما يتم تشغيل توقيع، البرنامج النصي إعادة تعيين كافة المكتشفة وانتقل إلى ملف التوقيع المقبل. "[معامل]" يتم تجاهل (راجع الأمثلة أدناه).<br /><br /></div>

`127.0.0.1/32 Greylist`

<div dir="rtl">إذا تم استخدام "Deny"، عندما يتم تشغيل توقيع، إن لم يكن في القائمة البيضاء، سيتم رفض الوصول. "Deny" يستخدم لمنع عنوان IP أو CIDR.<br /><br /></div>

<div dir="rtl">"[معامل]" قيمة لديها دعم L10N.<br /><br /></div>

<div dir="rtl">الكلمات المختزلة المتاحة هي:<br /></div>
<div dir="rtl"><ul>
 <li>Bogon</li>
 <li>Cloud</li>
 <li>Generic</li>
 <li>Proxy</li>
 <li>Spam</li>
 <li>Legal</li>
 <li>Malware</li>
</ul></div>

#### <div dir="rtl">٧.١ علامات<br /><br /></div>

##### <div dir="rtl">٧.١.٠ علامات القسم<br /><br /></div>

<div dir="rtl">يمكنك تحديد أقسام مختلفة مثل هذا (راجع الأمثلة أدناه).<br /><br /></div>

```
# القسم 1.
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud
4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
6.7.8.9/32 Deny Proxy
Tag: القسم 1
```

<div dir="rtl">خطوط مزدوجة يمكن استخدامها لأقسام منفصلة (راجع الأمثلة أدناه).<br /><br /></div>

```
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud

4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: القسم ١
```

<div dir="rtl">في المثال أعلاه<code dir="ltr">"1.2.3.4/32"</code> و<code dir="ltr">"2.3.4.5/32"</code> وصفت بأنها "IPv4"، بينما<code dir="ltr">"4.5.6.7/32"</code> و<code dir="ltr">"5.6.7.8/32"</code> وصفت بأنها "القسم 1".<br /><br /></div>

<div dir="rtl">ويمكن تطبيق المنطق نفسه لفصل الأنواع الأخرى من العلامات أيضا.<br /><br /></div>

<div dir="rtl">على وجه الخصوص، يمكن أن تكون علامات القسم مفيدة جدا لتصحيح الأخطاء عندما تحدث ايجابيات كاذبة، من خلال توفير وسيلة سهلة من موقع المصدر الدقيق للمشكلة، ويمكن أن تكون مفيدة جدا لتصفية إدخالات ملف السجل عند عرض ملفات السجل عبر صفحة سجلات الواجهة الأمامية (يمكن النقر على أسماء الأقسام عبر صفحة سجلات الواجهة الأمامية ويمكن استخدامها كمعايير تصفية). إذا تم حذف علامات الأقسام لبعض التواقيع المحددة، عندما يتم تشغيل تلك التوقيعات، يستخدم سيدرام اسم ملف التوقيع مع نوع عنوان إب المحظور (IPv4 أو IPv6) كمرجع، وبالتالي، الأقسام القسم اختيارية تماما. ويمكن التوصية في بعض الحالات، على سبيل المثال، عندما تكون ملفات التوقيع مسماة بشكل غامض أو عندما يكون من الصعب على نحو آخر تحديد مصدر التوقيعات بشكل واضح مما يتسبب في حظر الطلب.<br /><br /></div>

##### <div dir="rtl">٧.١.١ علامات انتهاء الصلاحية<br /><br /></div>

<div dir="rtl">في المثال التالي، سوف التوقيعات تنتهي بعد مرور بعض الوقت:<br /><br /></div>

```
# القسم ١.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Expires: 2016.12.31
```

<div dir="rtl">لن يتم تشغيل التوقيعات منتهية الصلاحية على أي طلب، بغض النظر عن ما.<br /><br /></div>

##### <div dir="rtl">٧.١.٢ علامات المنشأ<br /><br /></div>

<div dir="rtl">إذا كنت ترغب في تحديد بلد المنشأ لبعض التوقيع معين، يمكنك القيام بذلك باستخدام "علامة المنشأ". تقبل علامة الأصل شفرة "<a href="https://ar.wikipedia.org/wiki/%D8%A3%D9%8A%D8%B2%D9%88_3166-1_%D8%AD%D8%B1%D9%81%D9%8A-2">أيزو 3166-1 حرفي-2</a>" المقابلة لبلد المنشأ للتوقيعات التي تنطبق عليها. يجب أن تكون هذه الرموز مكتوبة في الحالة العليا (أقل حالة أو حالة مختلطة لن تجعل بشكل صحيح). عند استخدام علامات المنشأ، يتم إضافته إلى إدخال حقل السجل "سبب الحظر" لأي طلبات تم حظرها نتيجة للتوقيعات التي يتم تطبيق العلامة عليها.<br /><br /></div>

<div dir="rtl">إذا تم تثبيت مكون "flags CSS" الاختياري، عند عرض ملفات السجل عبر صفحة سجلات الواجهة الأمامية، يتم استبدال المعلومات الملحقة بعلامات المنشأ بعلامة البلد المقابل لتلك المعلومات. هذه المعلومات، سواء في شكلها الخام أو كعلامة بلد، يمكن النقر عليها، وعند النقر عليها، سيتم تصفية إدخالات السجل عن طريق إدخالات سجل تعريف أخرى مماثلة (مما يسمح على نحو فعال للأشخاص الذين يدخلون إلى صفحة السجلات بالترشيح عن طريق بلد المنشأ).<br /><br /></div>

<div dir="rtl">ملاحظة: من الناحية الفنية، هذا ليس أي شكل من أشكال تحديد الموقع الجغرافي، وذلك لأنه لا ينطوي على البحث عن أي معلومات محددة تتعلق عناوين بروتوكول الإنترنت الواردة، وإنما بدلا من ذلك، يسمح ببساطة لنا أن ينص صراحة بلد المنشأ لأي طلبات تم حظرها من قبل محددة التوقيعات. علامات المنشأ المتعددة مسموح بها في نفس قسم التوقيع.<br /><br /></div>

<div dir="rtl">مثال افتراضي:<br /><br /></div>

```
1.2.3.4/32 Deny Generic
Origin: CN
2.3.4.5/32 Deny Generic
Origin: FR
4.5.6.7/32 Deny Generic
Origin: DE
6.7.8.9/32 Deny Generic
Origin: US
Tag: Foobar
```

<div dir="rtl">يمكن استخدام أي علامات بالتزامن، وتكون جميع العلامات اختيارية (راجع الأمثلة أدناه).<br /><br /></div>

```
# القسم المثال.
1.2.3.4/32 Deny Generic
Origin: US
Tag: القسم المثال
Expires: 2016.12.31
```

#### <div dir="rtl">٧.٢ YAML<br /><br /></div>

#### <div dir="rtl">٧.٢.٠ أساسيات YAML<br /><br /></div>

<div dir="rtl">باستخدام YAML العلامات اختياري كلي (أي، إذا كنت تستخدم ذلك، هو اختيارك)، وغير قادرة على الاستفادة من معظم التكوين.<br /><br /></div>

<div dir="rtl">في CIDRAM، يتم تحديد YAML باستخدام ثلاث شرطات ("---")، و انهم إنهاء باستخدام اثنين أسطر جديدة. مثال التالي:<br /><br /></div>

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

##### <div dir="rtl">٧.٢.١ كيفية "تحمل علامة" أقسام توقيع للاستخدام مع reCAPTCHA<br /><br /></div>

<div dir="rtl">عندما "usemode" هو 1 أو 0، أقسام التوقيع لا تحتاج إلى أن يرمز لاختبار reCAPTCHA (لأن هذا سوف يكون بالفعل تحديد).<br /><br /></div>

<div dir="rtl">عندما "usemode" هو 2، من أجل دلالة أقسام توقيع للاستخدام مع اختبار reCAPTCHA، تشير إلى أن في YAML لهذا القسم التوقيع (راجع الأمثلة أدناه).<br /><br /></div>

```
# وسيكون هذا القسم استخدام اختبار reCAPTCHA.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Tag: reCAPTCHA-Enabled
---
recaptcha:
 enabled: true
```

<div dir="rtl"><em>ملحوظة: وفقا الافتراضي، سيتم فقط عرض على اختبار reCAPTCHA إذا تم تمكين اختبار reCAPTCHA (مع "usemode" ك 1، أو "usemode" ك 2 مع "enabled" ك true)، و إذا بالضبط واحد يتم تشغيل توقيع (لا أكثر ولا أقل؛ إذا يتم تشغيلها توقيعات متعددة، لن يتم عرض على اختبار reCAPTCHA). ومع ذلك، يمكن تعديل هذا السلوك عن طريق التوجيه "signature_limit".</em><br /><br /></div>

#### <div dir="rtl">٧.٣ معلومات اضافية<br /><br /></div>

<div dir="rtl">إذا كنت تريد CIDRAM تجاهل تماما بعض المقاطع، يمكنك استخدام ملف "ignore.dat" لتحديد المقاطع التي ليمكن تجاهلها. على سطر جديد، اكتب "Ignore"، متبوعا بمسافة، يليه اسم المقطع ليمكن تجاهله (راجع الأمثلة أدناه).<br /><br /></div>

```
Ignore القسم ١
```

#### <div dir="rtl">٧.٤ <a name="MODULE_BASICS"></a>مبادئ (للوحدات)<br /><br /></div>

<div dir="rtl">وحدات يمكن استخدامها لتوسيع وظائف CIDRAM، أداء مهام إضافية، أو معالجة منطق إضافي. عادة، يتم استخدامها عندما يكون من الضروري منع طلب على أساس آخر غير عنوان IP الأصل (وبالتالي، عندما لا يكون توقيع CIDR كافيا لمنع الطلب). PHP كتابة وحدات كملفات فب، وبالتالي، عادة، تكتب التوقيعات وحدة كما كود PHP.<br /><br /></div>

<div dir="rtl">بعض الأمثلة الجيدة على وحدات CIDRAM يمكن العثور عليها هنا:</div>

- https://github.com/CIDRAM/CIDRAM-Extras/tree/master/modules

<div dir="rtl">يمكن العثور على نموذج لكتابة وحدات جديدة هنا:</div>

- https://github.com/CIDRAM/CIDRAM-Extras/blob/master/modules/module_template.php

<div dir="rtl">لأن يتم كتابة وحدات كملفات PHP، إذا كنت على دراية كافية مع مصدر برنامج CIDRAM، يمكنك هيكلة وحدات وحدة التواقيع ولكن تريد (في إطار ما هو ممكن مع فب). ومع ذلك، لراحتك ولأفضل وضوح متبادل بين وحدات القائمة الخاصة بك، ويوصى تحليل قالب ربط أعلاه، من أجل أن تكون قادرة على استخدام هيكل وشكل أنه يوفر.<br /><br /></div>

<div dir="rtl"><em>ملحوظة: إذا كنت غير مريح العمل مع التعليمات البرمجية PHP، كتابة الوحدات الخاصة بك لا ينصح.</em><br /><br /></div>

<div dir="rtl">يتم توفير بعض الوظائف من قبل CIDRAM التي ينبغي أن تجعل من أبسط وأسهل لكتابة وحدات الخاصة بك. وترد أدناه معلومات عن هذه الوظيفة.<br /><br /></div>

#### <div dir="rtl">٧.٥ وحدة نمطية<br /><br /></div>

##### <div dir="rtl">٧.٥.٠ <code dir="ltr">"$Trigger"</code></div>

<div dir="rtl">وعادة ما تكتب تواقيع الوحدة مع <code dir="ltr">"$Trigger"</code>. في معظم الحالات، هذا الإغلاق سيكون أكثر أهمية من أي شيء آخر لغرض كتابة وحدات.<br /><br /></div>

<div dir="rtl"><code dir="ltr">"$Trigger"</code> يقبل ٤ المعلمات: <code dir="ltr">"$Condition"</code>، <code dir="ltr">"$ReasonShort"</code>، <code dir="ltr">"$ReasonLong"</code> (اختياري)، <code dir="ltr">"$DefineOptions"</code> (اختياري).<br /><br /></div>

<div dir="rtl">يتم تقييم <code dir="ltr">"$Condition"</code>، وإذا كان "صحيح" (<code dir="ltr">true</code>)، التوقيع نشط. إذا كان "خاطئة" (<code dir="ltr">false</code>)، التوقيع غير نشط. <code dir="ltr">"$Condition"</code> عادة ما تحتوي على التعليمات البرمجية PHP التي يجب منع الطلبات.<br /><br /></div>

<div dir="rtl"><code dir="ltr">"$ReasonShort"</code> في حقل "سبب الحظر" عندما يكون التوقيع نشطا.<br /><br /></div>

<div dir="rtl"><code dir="ltr">"$ReasonLong"</code> هي رسالة اختيارية يتم عرضها للمستخدم عند حظرها، لشرح سبب حظرها. يستخدم الرسالة القياسية "سبب الحظر" عند حذفها.<br /><br /></div>

<div dir="rtl"><code dir="ltr">"$DefineOptions"</code> عبارة عن صفيف اختياري يحتوي على أزواج المفاتيح/القيم التي تحدد خيارات التكوين الخاصة بمثيل الطلب. سيتم تطبيق خيارات التهيئة عندما يكون التوقيع نشطا.<br /><br /></div>

<div dir="rtl">ترجع <code dir="ltr">"$Trigger"</code> صحيح (<code dir="ltr">true</code>) عندما يكون التوقيع نشطا و خاطئة (<code dir="ltr">false</code>) عندما لا يكون.<br /><br /></div>

<div dir="rtl">لاستخدام هذا الإغلاق في الوحدة النمطية الخاصة بك، تذكر أولا أن ترثه من النطاق الأصلي:<br /><br /></div>

```PHP
$Trigger = $CIDRAM['Trigger'];
```

##### <div dir="rtl">٧.٥.١ <code dir="ltr">"$Bypass"</code></div>

<div dir="rtl">وعادة ما تكتب الالتفافية التوقيع مع <code dir="ltr">"$Bypass"</code>.<br /><br /></div>

<div dir="rtl"><code dir="ltr">"$Bypass"</code> يقبل ٣ المعلمات: <code dir="ltr">"$Condition"</code>، <code dir="ltr">"$ReasonShort"</code>، <code dir="ltr">"$DefineOptions"</code> (اختياري).<br /><br /></div>

<div dir="rtl">يتم تقييم <code dir="ltr">"$Condition"</code>، وإذا كان "صحيح" (<code dir="ltr">true</code>)، الالتفافية نشط. إذا كان "خاطئة" (<code dir="ltr">false</code>)، الالتفافية غير نشط. <code dir="ltr">"$Condition"</code> عادة ما تحتوي على رمز PHP التي يجب عدم منع الطلبات.<br /><br /></div>

<div dir="rtl"><code dir="ltr">"$ReasonShort"</code> في حقل "سبب الحظر" عندما يكون الالتفافية نشطا.<br /><br /></div>

<div dir="rtl"><code dir="ltr">"$DefineOptions"</code> عبارة عن صفيف اختياري يحتوي على أزواج المفاتيح/القيم التي تحدد خيارات التكوين الخاصة بمثيل الطلب. سيتم تطبيق خيارات التهيئة عندما يكون الالتفافية نشطا.<br /><br /></div>

<div dir="rtl">ترجع <code dir="ltr">"$Bypass"</code> صحيح (<code dir="ltr">true</code>) عندما يكون الالتفافية نشطا و خاطئة (<code dir="ltr">false</code>) عندما لا يكون.<br /><br /></div>

<div dir="rtl">لاستخدام هذا الإغلاق في الوحدة النمطية الخاصة بك، تذكر أولا أن ترثه من النطاق الأصلي:<br /><br /></div>

```PHP
$Bypass = $CIDRAM['Bypass'];
```

##### <div dir="rtl">٧.٥.٢ <code dir="ltr">"$CIDRAM['DNS-Reverse']"</code></div>

<div dir="rtl">يمكن استخدام هذا لجلب اسم المضيف لعنوان IP. إذا كنت ترغب في إنشاء وحدة لمنع أسماء المضيفين، قد يكون هذا الإغلاق مفيدا.<br /><br /></div>

<div dir="rtl">مثال:<br /><br /></div>

```PHP
<?php
/** Inherit trigger closure (see functions.php). */
$Trigger = $CIDRAM['Trigger'];

/** Fetch hostname. */
if (empty($CIDRAM['Hostname'])) {
    $CIDRAM['Hostname'] = $CIDRAM['DNS-Reverse']($CIDRAM['BlockInfo']['IPAddr']);
}

/** Example signature. */
if ($CIDRAM['Hostname'] && $CIDRAM['Hostname'] !== $CIDRAM['BlockInfo']['IPAddr']) {
    $Trigger($CIDRAM['Hostname'] === 'www.foobar.tld', 'Foobar.tld', 'Hostname Foobar.tld is not allowed.');
}
```

#### <div dir="rtl">٧.٦ وحدة المتغيرات<br /><br /></div>

<div dir="rtl">الوحدات النمطية تنفذ ضمن نطاقها الخاص، وأي متغيرات محددة من قبل وحدة نمطية، لن تكون في متناول وحدات أخرى، أو إلى السيناريو الأصل، إلا إذا كانت مخزنة في <code dir="ltr">"$CIDRAM"</code> مجموعة (يتم مسح كل شيء آخر بعد انتهاء تنفيذ الوحدة).<br /><br /></div>

<div dir="rtl">فيما يلي بعض المتغيرات الشائعة التي قد تكون مفيدة للوحدة النمطية الخاصة بك:<br /><br /></div>

&nbsp; <div dir="rtl" style="display:inline;">وصف</div> | <div dir="rtl" style="display:inline;">متغير</div>
----|----
&nbsp; <div dir="rtl" style="display:inline;">التاريخ والوقت الحاليان.</div> | `$CIDRAM['BlockInfo']['DateTime']`
&nbsp; <div dir="rtl" style="display:inline;">عنوان IP للطلب الحالي.</div> | `$CIDRAM['BlockInfo']['IPAddr']`
&nbsp; <div dir="rtl" style="display:inline;">إصدار النص البرمجي CIDRAM.</div> | `$CIDRAM['BlockInfo']['ScriptIdent']`
&nbsp; <div dir="rtl" style="display:inline;">الاستعلام عن الطلب الحالي.</div> | `$CIDRAM['BlockInfo']['Query']`
&nbsp; <div dir="rtl" style="display:inline;">المحيل للطلب الحالي (إذا كان موجودا).</div> | `$CIDRAM['BlockInfo']['Referrer']`
&nbsp; <div dir="rtl" style="display:inline;">وكيل المستخدم (user agent) للطلب الحالي.</div> | `$CIDRAM['BlockInfo']['UA']`
&nbsp; <div dir="rtl" style="display:inline;">وكيل المستخدم (user agent) للطلب الحالي (في حالة أقل).</div> | `$CIDRAM['BlockInfo']['UALC']`
&nbsp; <div dir="rtl" style="display:inline;">الرسالة المراد عرضها للمستخدم عند حظرها.</div> | `$CIDRAM['BlockInfo']['ReasonMessage']`
&nbsp; <div dir="rtl" style="display:inline;">عدد التوقيعات التي أدت إلى الطلب الحالي.</div> | `$CIDRAM['BlockInfo']['SignatureCount']`
&nbsp; <div dir="rtl" style="display:inline;">المعلومات المرجعية عن أي توقيعات أثارت للطلب الحالي.</div> | `$CIDRAM['BlockInfo']['Signatures']`
&nbsp; <div dir="rtl" style="display:inline;">المعلومات المرجعية عن أي توقيعات أثارت للطلب الحالي.</div> | `$CIDRAM['BlockInfo']['WhyReason']`

---


### <div dir="rtl">٨. <a name="SECTION8"></a>مشاكل التوافق المعروفة</div>

<div dir="rtl">تم العثور على الحزم والمنتجات التالية لتكون غير متوافقة مع CIDRAM:</div>
<div dir="rtl"><ul>
 <li><strong><a href="https://github.com/CIDRAM/CIDRAM/issues/52">Endurance Page Cache</a></strong></li>
</ul></div>

<div dir="rtl">تم توفير وحدات لضمان توافق الحزم والمنتجات التالية مع CIDRAM:</div>
<div dir="rtl"><ul>
 <li><strong><a href="https://github.com/CIDRAM/CIDRAM/issues/56">BunnyCDN</a></strong></li>
</ul></div>

<div dir="rtl"><em>انظر أيضا: <a href="https://maikuolan.github.io/Compatibility-Charts/">مخططات التوافق</a>.</em><br /><br /></div>

---


### <div dir="rtl">٩. <a name="SECTION9"></a>أسئلة وأجوبة (FAQ)</div>

<div dir="rtl"><ul>
 <li><a href="#WHAT_IS_A_SIGNATURE">ما هو "التوقيع"؟</a></li>
 <li><a href="#WHAT_IS_A_CIDR">ما هو "CIDR"؟</a></li>
 <li><a href="#WHAT_IS_A_FALSE_POSITIVE">ما هو "إيجابية خاطئة"؟</a></li>
 <li><a href="#BLOCK_ENTIRE_COUNTRIES">يمكن CIDRAM منع الدول؟</a></li>
 <li><a href="#SIGNATURE_UPDATE_FREQUENCY">عدد المرات التي يتم تحديثها التوقيعات؟</a></li>
 <li><a href="#ENCOUNTERED_PROBLEM_WHAT_TO_DO">لقد واجهت مشكلة! أنا لا أعرف ما يجب القيام به! الرجاء المساعدة!</a></li>
 <li><a href="#BLOCKED_WHAT_TO_DO">لقد تم حظر من موقع على شبكة الانترنت! الرجاء المساعدة!</a></li>
 <li><a href="#MINIMUM_PHP_VERSION">أريد استخدام CIDRAM مع نسخة PHP كبار السن من 5.4.0؛ يمكنك أن تساعد؟</a></li>
 <li><a href="#PROTECT_MULTIPLE_DOMAINS">هل يمكنني استخدام تثبيت CIDRAM واحد لحماية نطاقات متعددة؟</a></li>
 <li><a href="#PAY_YOU_TO_DO_IT">أنا لا أريد أن تضيع الوقت مع تثبيت هذا أو ضمان أنه يعمل لموقع الويب الخاص بي؛ يمكنني دفع لك أن تفعل ذلك بالنسبة لي؟</a></li>
 <li><a href="#HIRE_FOR_PRIVATE_WORK">هل يمكنني توظيفك أو أي من مطوري هذا المشروع للعمل الخاص؟</a></li>
 <li><a href="#SPECIALIST_MODIFICATIONS">أنا بحاجة إلى تعديلات متخصصة، والتخصيصات، الخ؛ يمكنك أن تساعد؟</a></li>
 <li><a href="#ACCEPT_OR_OFFER_WORK">أنا مطور، مصمم موقع، أو مبرمج. هل يمكنني قبول أو عرض العمل المتعلق بهذا المشروع؟</a></li>
 <li><a href="#WANT_TO_CONTRIBUTE">أريد أن أساهم في المشروع؛ هل يمكنني فعل هذا؟</a></li>
 <li><a href="#RECOMMENDED_VALUES_FOR_IPADDR">القيم الموصى بها ل "ipaddr".</a></li>
 <li><a href="#CRON_TO_UPDATE_AUTOMATICALLY">هل يمكنني استخدام cron لتحديث تلقائيا؟</a></li>
 <li><a href="#WHAT_ARE_INFRACTIONS">ما هي "المخالفات"؟</a></li>
 <li><a href="#BLOCK_HOSTNAMES">هل يمكن لأسماء المضيفين في CIDRAM حظر؟</a></li>
 <li><a href="#WHAT_CAN_I_USE_FOR_DEFAULT_DNS">ما الذي يمكنني استخدامه لـ "default_dns"؟</a></li>
 <li><a href="#PROTECT_OTHER_THINGS">هل يمكنني استخدام CIDRAM لحماية الأشياء بخلاف مواقع الويب (مثل خوادم البريد الإلكتروني، FTP، SSH، IRC، إلخ)؟</a></li>
 <li><a href="#CDN_CACHING_PROBLEMS">هل تحدث مشكلات إذا كنت أستخدم CIDRAM في نفس وقت استخدام خدمات CDN أو خدمات التخزين المؤقت؟</a></li>
</ul></div>

#### <div dir="rtl"><a name="WHAT_IS_A_SIGNATURE"></a>ما هو "التوقيع"؟<br /><br /></div>

<div dir="rtl">في CIDRAM، يشير "التوقيع" إلى البيانات التي تعمل كمعرف، لشيء معين أننا نبحث عنه. عادة عنوان IP أو CIDR، يتضمن بعض التعليمات لCIDRAM، مثل أفضل طريقة للرد عندما يواجه ما نحن نبحث عنه. توقيع نموذجي لCIDRAM يبدو شيئا من هذا القبيل:<br /><br /></div>

<div dir="rtl">بالنسبة إلى "ملفات التوقيع":<br /><br /></div>

`1.2.3.4/32 Deny Generic`

<div dir="rtl">بالنسبة إلى "الوحدات النمطية":<br /><br /></div>

```PHP
$Trigger(strpos($CIDRAM['BlockInfo']['UA'], 'Foobar') !== false, 'Foobar-UA', 'User agent "Foobar" not allowed.');
```

<div dir="rtl">ملاحظة: التوقيعات ل "ملفات التوقيع"، والتوقيعات ل "وحدات"، ليست هي نفس الشيء.<em></em><br /><br /></div>

<div dir="rtl">في كثير من الأحيان (ولكن ليس دائما)، سيتم تجميع التواقيع معا في مجموعات، تشكيل "أقسام التوقيع"، وغالبا ما تكون مصحوبة بتعليقات، وترميز، والبيانات الوصفية ذات الصلة. ويمكن استخدام هذا لتوفير سياق إضافي.<br /><br /></div>

#### <div dir="rtl"><a name="WHAT_IS_A_CIDR"></a>ما هو "CIDR"؟<br /><br /></div>

<div dir="rtl">"CIDR" هو اختصار ل "Classless Inter-Domain Routing". ("توجيه بين المجالات لافئويا") <em>[<a href="https://ar.wikipedia.org/wiki/%D8%AA%D9%88%D8%AC%D9%8A%D9%87_%D8%A8%D9%8A%D9%86_%D8%A7%D9%84%D9%85%D8%AC%D8%A7%D9%84%D8%A7%D8%AA_%D9%84%D8%A7%D9%81%D8%A6%D9%88%D9%8A%D8%A7">١</a>، <a href="http://whatismyipaddress.com/cidr">٢</a>]</em>. يستخدم هذا الاختصار كجزء من اسم هذه الحزمة، "CIDRAM"، وهو اختصار ل "Classless Inter-Domain Routing Access Manager" (توجيه بين المجالات لافئويا وصول مدير).<br /><br /></div>

<div dir="rtl">ومع ذلك، في سياق CIDRAM (مثل، ضمن هذه الوثائق، في المناقشات ذات الصلة، أو ضمن بيانات اللغة)، عند ذكر "CIDR" (المفرد) أو "CIDRs" (الجمع)، المعنى المقصود لدينا هو الشبكات الفرعية، معربا عن ذلك باستخدام تدوين سيدر. والسبب في ذلك هو أنه يمكن التعبير عن الشبكات الفرعية بطرق مختلفة مختلفة. لذلك يمكن اعتبار CIDRAM "مدير النفاذ إلى الشبكة الفرعية".<br /><br /></div>

<div dir="rtl">وهذا التفسير، والسياق المقدم، ينبغي أن يساعد على حل أي غموض.<br /><br /></div>

#### <div dir="rtl"><a name="WHAT_IS_A_FALSE_POSITIVE"></a>ما هو "إيجابية خاطئة"؟<br /><br /></div>

<div dir="rtl">المصطلح "إيجابية خاطئة" (<em>بدلا من ذلك: "خطأ إيجابية خاطئة"؛ "انذار خاطئة"</em>؛ الإنجليزية: <em>false positive</em>; <em>false positive error</em>; <em>false alarm</em>)، وصف ببساطة، بشكل عام، يستخدم عند اختبار حالة، للإشارة إلى نتائج هذا الاختبار، عندما تكون النتائج إيجابية (أي، تحديد حالة أن يكون "إيجابية"، أو "صحيح")، ولكن من المتوقع أن تكون (أو كان ينبغي أن يكون) سلبي (أي، الحالة، في الواقع، هو "سلبي"، أو "خاطئة"). "إيجابية خاطئة" ويمكن اعتبار التناظرية من "الذئب الباكي" (حيث لحالة يجري اختبارها هو ما إذا كان هناك ذئب بالقرب من القطيع، الحالة هو "خاطئة" في أنه لا يوجد الذئب بالقرب من القطيع، و الحالة يقال بأنها "إيجابية" بواسطة الراعي عن طريق الدعوة "الذئب، الذئب")، أو التناظرية من الفحص الطبي حيث المريض يتم تشخيص المرض، عندما تكون في واقع، ليس لديهم المرض.<br /><br /></div>

<div dir="rtl">بعض المصطلحات ذات الصلة هي "إيجابية صحيح"، "سلبي صحيح" و "سلبي خاطئة". "إيجابية صحيح" هو عندما تكون نتائج الاختبار والحالة الفعلية للحالة على حد سواء صحيح (أو "إيجابية")، و "سلبي صحيح" هو عندما تكون نتائج الاختبار والحالة الفعلية للحالة على حد سواء خاطئة (أو "سلبي")؛ "إيجابية صحيح" أو "سلبي صحيح" ويعتبر أن تكون "الاستدلال الصحيح". نقيض ل "إيجابية خاطئة" هو "سلبي خاطئة"؛ "سلبي خاطئة" هو عندما تكون النتائج سلبي (أي، تحديد حالة أن يكون "سلبي"، أو "خاطئة")، ولكن من المتوقع أن تكون (أو كان ينبغي أن يكون) إيجابية (أي، الحالة، في الواقع، هو "إيجابية"، أو "صحيح").<br /><br /></div>

<div dir="rtl">في سياق CIDRAM، هذه المصطلحات تشير إلى التوقيعات CIDRAM و ما/منهم أنهم منع. عندما CIDRAM يمنع عنوان IP نظرا لتوقيع سيئة، قديمة أو غير صحيحة، ولكن لا ينبغي أن تفعل ذلك، أو عندما يفعل ذلك لأسباب خاطئة، نشير إلى هذا الحدث باعتباره "إيجابية خاطئة". عندما CIDRAM يفشل لمنع عنوان IP التي كان ينبغي أن سدت، بسبب تهديدات غير متوقعة، التوقيعات المفقودة أو أوجه القصور توقيع، نشير إلى هذا الحدث باعتباره "افتقد" (هذا هو التناظرية من ا "سلبي خاطئة").<br /><br /></div>

<div dir="rtl">هذا يمكن تلخيصها حسب الجدول أدناه:<br /><br /></div>

&nbsp; <div dir="rtl" style="display:inline;">CIDRAM لا ينبغي منع عنوان IP</div> | &nbsp; <div dir="rtl" style="display:inline;">CIDRAM يجب منع عنوان IP</div> | &nbsp;
---|---|---
&nbsp; <div dir="rtl" style="display:inline;">سلبي صحيح (الاستدلال الصحيح)</div> | <div dir="rtl" style="display:inline;">افتقد (التناظرية من سلبي خاطئة)</div> | <div dir="rtl" style="display:inline;"><strong>CIDRAM لا يمنع عنوان IP</strong></div>
&nbsp; <div dir="rtl" style="display:inline;"><strong>إيجابية خاطئة</strong></div> | <div dir="rtl" style="display:inline;">إيجابية صحيح (الاستدلال الصحيح)</div> | <div dir="rtl" style="display:inline;"><strong>CIDRAM منع عنوان IP</strong></div>

#### <div dir="rtl"><a name="BLOCK_ENTIRE_COUNTRIES"></a>يمكن CIDRAM منع الدول؟<br /><br /></div>

<div dir="rtl">نعم. يمكنك القيام بذلك عن طريق القوائم تحميل Macmathan ل (إما من frontend تلقائيا أو من <strong><a href="https://macmathan.info/blocklists">صفحة التحميل</a></strong> ليدويا. عندما يدويا، تحميلها على vault، لهم وتحدد لهم التكوين.<br /><br /></div>

#### <div dir="rtl"><a name="SIGNATURE_UPDATE_FREQUENCY"></a>عدد المرات التي يتم تحديثها التوقيعات؟<br /><br /></div>

<div dir="rtl">أنه يختلف. نحن نحاول قدر الإمكان، ولكن نظرا لالتزامات أخرى، حياتنا اليومية، وعدم حصولهم على رواتبهم، تحديث الجدول الزمني الدقيق لا يمكن أن تكون مضمونة. الأولوية لضرورة. ورحب المساعدة دائما.<br /><br /></div>

#### <div dir="rtl"><a name="ENCOUNTERED_PROBLEM_WHAT_TO_DO"></a>لقد واجهت مشكلة! أنا لا أعرف ما يجب القيام به! الرجاء المساعدة!</div>
<div dir="rtl"><ul>
 <li>تحقق مما إذا كنت تستخدم أحدث إصدار من البرنامج والتوقيع الملفات.</li>
 <li>قراءة الوثائق. قد تكون هناك إجابات هناك.</li>
 <li>قراءة <strong><a href="https://github.com/CIDRAM/CIDRAM/issues">صفحة المشكلات</a></strong>. قد تكون هناك إجابات هناك.</li>
 <li>لا يوجد حتى الآن إجابات؟ يرجى طلب المساعدة عبر صفحة القضايا.</li>
</ul></div>

#### <div dir="rtl"><a name="BLOCKED_WHAT_TO_DO"></a>لقد تم حظر من موقع على شبكة الانترنت! الرجاء المساعدة!<br /><br /></div>

<div dir="rtl">CIDRAM يمكن أن تتوقف حركة المرور غير المرغوب فيها، ولكن اصحاب المواقع هي المسؤولة عن البت في كيفية استخدام هذه. ويمكننا تصحيح أخطائنا، ولكن في حالات أخرى، ستحتاج إلى الاتصال بأصحاب الموقع ذات الصلة. لا نستطيع أن نفعل أي شيء عن أشياء خارجة عن سيطرتنا.<br /><br /></div>

#### <div dir="rtl"><a name="MINIMUM_PHP_VERSION"></a>أريد استخدام CIDRAM مع نسخة PHP كبار السن من 5.4.0؛ يمكنك أن تساعد؟<br /><br /></div>

<div dir="rtl">لا. PHP 5.4.0 دعم إنهاء عام 2014. الدعم الأمني الموسع إنهاء في عام 2015. حاليا، فمن عام 2017، وPHP 7.1.0 متاحة بالفعل. يتم توفير دعم لاستخدام CIDRAM مع PHP 5.4.0 و كل ما هو متاح أحدث إصدارات PHP. لن تكون معتمدة الإصدارات القديمة PHP.<br /><br /></div>

<div dir="rtl"><em>انظر أيضا: <a href="https://maikuolan.github.io/Compatibility-Charts/">مخططات التوافق</a>.</em><br /><br /></div>

#### <div dir="rtl"><a name="PROTECT_MULTIPLE_DOMAINS"></a>هل يمكنني استخدام تثبيت CIDRAM واحد لحماية نطاقات متعددة؟<br /><br /></div>

<div dir="rtl">نعم. يمكن استخدام CIDRAM لحماية نطاقات متعددة. إذا كان التكوين المطلوب مختلفا، للقيام بذلك، إنشاء ملفات تكوين جديدة، واسمه وفقا للنطاقات التي تتطلب الحماية. كمثال، ل <code dir="ltr">"http://www.some-domain.tld/"</code>، أطلق عليه اسما <code dir="ltr">"some-domain.tld.config.ini"</code>. اسم النطاق يأتي من <code dir="ltr">"HTTP_HOST"</code>. يتم تجاهل <code dir="ltr">"www"</code>.<br /><br /></div>

#### <div dir="rtl"><a name="PAY_YOU_TO_DO_IT"></a>أنا لا أريد أن تضيع الوقت مع تثبيت هذا أو ضمان أنه يعمل لموقع الويب الخاص بي؛ يمكنني دفع لك أن تفعل ذلك بالنسبة لي؟<br /><br /></div>

<div dir="rtl">ربما. وينظر في ذلك على أساس كل حالة على حدة. أخبرنا احتياجاتك وما تقدمه. سنخبرك بما إذا كنا نستطيع مساعدتك أم لا.<br /><br /></div>

#### <div dir="rtl"><a name="HIRE_FOR_PRIVATE_WORK"></a>هل يمكنني توظيفك أو أي من مطوري هذا المشروع للعمل الخاص؟<br /><br /></div>

<div dir="rtl"><em>راجع اإلجابة أعاله.</em><br /><br /></div>

#### <div dir="rtl"><a name="SPECIALIST_MODIFICATIONS"></a>أنا بحاجة إلى تعديلات متخصصة، والتخصيصات، الخ؛ يمكنك أن تساعد؟<br /><br /></div>

<div dir="rtl"><em>راجع اإلجابة أعاله.</em><br /><br /></div>

#### <div dir="rtl"><a name="ACCEPT_OR_OFFER_WORK"></a>أنا مطور، مصمم موقع، أو مبرمج. هل يمكنني قبول أو عرض العمل المتعلق بهذا المشروع؟<br /><br /></div>

<div dir="rtl">نعم. ترخيصنا لا يحظر هذا.<br /><br /></div>

#### <div dir="rtl"><a name="WANT_TO_CONTRIBUTE"></a>أريد أن أساهم في المشروع؛ هل يمكنني فعل هذا؟<br /><br /></div>

<div dir="rtl">نعم. المساهمة في المشروع هو موضع ترحيب كبير. يرجى الاطلاع على "CONTRIBUTING.md" لمزيد من المعلومات.<br /><br /></div>

#### <div dir="rtl"><a name="RECOMMENDED_VALUES_FOR_IPADDR"></a>القيم الموصى بها ل "ipaddr".<br /><br /></div>

&nbsp; <div dir="rtl" style="display:inline;">القيمة</div> | &nbsp; <div dir="rtl" style="display:inline;">استعمال</div>
---|---
`HTTP_INCAP_CLIENT_IP` | Incapsula reverse proxy (إنكابسولا عكس الوكيل).
`HTTP_CF_CONNECTING_IP` | Cloudflare reverse proxy (كلودفلاري عكس الوكيل).
`CF-Connecting-IP` | Cloudflare reverse proxy (كلودفلاري عكس الوكيل؛ لبديل؛ إذا كان ما سبق لا يعمل).
`HTTP_X_FORWARDED_FOR` | Cloudbric reverse proxy.
`X-Forwarded-For` | [Squid reverse proxy (عكس الوكيل)](http://www.squid-cache.org/Doc/config/forwarded_for/).
&nbsp; <div dir="rtl" style="display:inline;"><em>يحددها تكوين الخادم.</em></div> | [Nginx reverse proxy (إنجن إكس عكس الوكيل)](https://www.nginx.com/resources/admin-guide/reverse-proxy/).
`REMOTE_ADDR` | &nbsp; <div dir="rtl" style="display:inline;">لا يوجد عكس الوكيل (الافتراضي).</div>

#### <div dir="rtl"><a name="CRON_TO_UPDATE_AUTOMATICALLY"></a>هل يمكنني استخدام cron لتحديث تلقائيا؟<br /><br /></div>

<div dir="rtl">نعم. يتم تضمين API في front-end للتفاعل مع صفحة التحديثات عبر النصوص البرمجية الخارجية. وهناك نص منفصل، <a href="https://github.com/Maikuolan/Cronable">Cronable</a>، هو متاح، ويمكن استخدامها من قبل مدير كرون أو كرون جدولة لتحديث هذا وغيرها من الحزم المعتمدة تلقائيا (يوفر هذا البرنامج النصي وثائقه الخاصة).<br /><br /></div>

#### <div dir="rtl"><a name="WHAT_ARE_INFRACTIONS"></a>ما هي "المخالفات"؟<br /><br /></div>

<div dir="rtl">"المخالفات" تحدد متى يجب أن يبدأ حظر IP الذي لم يتم حظره من قبل أي ملفات توقيع محددة لأي طلبات مستقبلية، وهي ترتبط ارتباطا وثيقا ب التتبع IP. توجد بعض الوظائف والوحدات التي تسمح برفض الطلبات لأسباب أخرى غير IP (على سبيل المثال، وجود وكلاء المستخدم [user agents] المقابلة ل سبامبوتس [spambots] أو هاكتولس [hacktools]، استفسارات خطيرة، وهمية DNS، إلخ)، وعندما يحدث ذلك، يمكن أن يحدث "مخالفة". وهي توفر طريقة لتحديد عناوين IP التي تتطابق مع الطلبات غير المرغوب فيها التي قد لا يتم حظرها من قبل أي ملفات توقيع محددة. مخالفات عادة ما تتطابق 1 إلى 1 مع عدد المرات IP يتم حظر، ولكن ليس دائما (قد تتسبب الأحداث الشديدة في قيمة مخالفة أكبر، وإذا كان "track_mode" هو false، لن تحدث المخالفات عند حظرها فقط بواسطة ملفات التوقيع).<br /><br /></div>

#### <div dir="rtl"><a name="BLOCK_HOSTNAMES"></a>هل يمكن لأسماء المضيفين في CIDRAM حظر؟<br /><br /></div>

<div dir="rtl">نعم. للقيام بذلك، ستحتاج إلى إنشاء ملف وحدة نمطية مخصصة. <em>نرى: <a href="#MODULE_BASICS">مبادئ (للوحدات)</a></em>.<br /><br /></div>

#### <div dir="rtl"><a name="WHAT_CAN_I_USE_FOR_DEFAULT_DNS"></a>ما الذي يمكنني استخدامه لـ "default_dns"؟<br /><br /></div>

<div dir="rtl">إذا كنت تبحث عن اقتراحات، تقدم <a href=https://public-dns.info/>public-dns.info</a> و <a href=https://servers.opennic.org/>OpenNIC</a> قوائم شاملة لخوادم DNS العامة المعروفة.<br /><br /></div>

IP | المشغل
---|---
`1.1.1.1` | [Cloudflare](https://www.cloudflare.com/learning/dns/what-is-1.1.1.1/)
`4.2.2.1`<br />`4.2.2.2`<br />`209.244.0.3`<br />`209.244.0.4` | [Level3](https://www.level3.com/en/)
`8.8.4.4`<br />`8.8.8.8`<br />`2001:4860:4860::8844`<br />`2001:4860:4860::8888` | [Google Public DNS](https://developers.google.com/speed/public-dns/)
`9.9.9.9`<br />`149.112.112.112` | [Quad9 DNS](https://www.quad9.net/)
`84.200.69.80`<br />`84.200.70.40`<br />`2001:1608:10:25::1c04:b12f`<br />`2001:1608:10:25::9249:d69b` | [DNS.WATCH](https://dns.watch/index)
`208.67.220.220`<br />`208.67.222.220`<br />`208.67.222.222` | [OpenDNS Home](https://www.opendns.com/)
`77.88.8.1`<br />`77.88.8.8`<br />`2a02:6b8::feed:0ff`<br />`2a02:6b8:0:1::feed:0ff` | [Yandex.DNS](https://dns.yandex.com/advanced/)
`8.20.247.20`<br />`8.26.56.26` | [Comodo Secure DNS](https://www.comodo.com/secure-dns/)
`216.146.35.35`<br />`216.146.36.36` | [Dyn](https://help.dyn.com/internet-guide-setup/)
`64.6.64.6`<br />`64.6.65.6` | [Verisign Public DNS](https://www.verisign.com/en_US/security-services/public-dns/index.xhtml)
`37.235.1.174`<br />`37.235.1.177` | [FreeDNS](https://freedns.zone/en/)
`156.154.70.1`<br />`156.154.71.1`<br />`2610:a1:1018::1`<br />`2610:a1:1019::1` | [Neustar Security](https://www.security.neustar/dns-services/free-recursive-dns-service)
`45.32.36.36`<br />`45.77.165.194`<br />`179.43.139.226` | [Fourth Estate](https://dns.fourthestate.co/)
`74.82.42.42` | [Hurricane Electric](https://dns.he.net/)
`195.46.39.39`<br />`195.46.39.40` | [SafeDNS](https://www.safedns.com/en/features/)
`81.218.119.11`<br />`209.88.198.133` | [GreenTeam Internet](http://www.greentm.co.uk/)
`89.233.43.71`<br />`91.239.100.100 `<br />`2001:67c:28a4::`<br />`2a01:3a0:53:53::` | [UncensoredDNS](https://blog.uncensoreddns.org/)
`208.76.50.50`<br />`208.76.51.51` | [SmartViper](http://www.markosweb.com/free-dns/)

<div dir="rtl"><em>ملحوظة: لا أقدم أي مطالبات أو ضمانات بشأن ممارسات الخصوصية والأمن والفعالية ولا موثوقية أي خدمات DNS، المدرجة أو غير ذلك. يرجى إجراء البحوث الخاصة بك عند اتخاذ القرارات بشأنها.</em><br /><br /></div>

#### <div dir="rtl"><a name="PROTECT_OTHER_THINGS"></a>هل يمكنني استخدام CIDRAM لحماية الأشياء بخلاف مواقع الويب (مثل خوادم البريد الإلكتروني، FTP، SSH، IRC، إلخ)؟<br /><br /></div>

<div dir="rtl">يمكنك (من الناحية القانونية)، ولكن لا ينبغي (من الناحية الفنية؛ من الناحية العملية). ترخيصنا لا يحد من التقنيات التي تنفذ CIDRAM، لكن CIDRAM عبارة عن WAF (تطبيق ويب جدار حماية) وكان الهدف منه حماية مواقع الويب دائمًا. نظرًا لأنه لم يتم تصميمه مع وضع التقنيات الأخرى في الاعتبار، فمن غير المحتمل أن تكون فعالة أو توفر حماية موثوقة للتكنولوجيات الأخرى، ومن المرجح أن يكون التنفيذ صعباً، وأن خطر التعرض للإيجابيات الخاطئة والكشف عن الأخطاء يكون عالياً جداً.<br /><br /></div>

#### <div dir="rtl"><a name="CDN_CACHING_PROBLEMS"></a>هل تحدث مشكلات إذا كنت أستخدم CIDRAM في نفس وقت استخدام خدمات CDN أو خدمات التخزين المؤقت؟<br /><br /></div>

<div dir="rtl">ربما. يعتمد هذا على طبيعة الخدمة المعنية وكيفية استخدامك لها. بشكل عام، إذا كنت تقوم فقط بالتخزين المؤقت لأصول ثابتة (الصور، CSS، إلخ؛ أي شيء لا يتغير بشكل عام بمرور الوقت)، فلا يجب أن تكون هناك أية مشكلات. قد تكون هناك مشكلات على الرغم من ذلك، إذا كنت تقوم بتخزين البيانات في ذاكرة التخزين المؤقت التي عادة ما يتم إنشاؤها بشكل ديناميكي عند طلبها، أو إذا كنت تخبئ نتائج طلبات POST مؤقتًا (هذا من شأنه أن يجعل موقعك على الويب وبيئته ثابتًا تمامًا، ومن غير المرجح أن يوفر CIDRAM أي فائدة ذات معنى في بيئة ساكنة تمامًا). قد يكون هناك أيضًا متطلبات تهيئة محددة لـ CIDRAM، بناءً على نوع CDN أو خدمة التخزين المؤقت التي تستخدمها (ستحتاج إلى التأكد من تكوين CIDRAM بشكل صحيح لـ CDN أو خدمة التخزين المؤقت التي تستخدمها). قد يؤدي الفشل في تكوين CIDRAM بشكل صحيح إلى وجود إشكاليات خاطئة معضلة إلى حد كبير وعمليات الكشف المفقودة.<br /><br /></div>

---


### <div dir="rtl">١١. <a name="SECTION11"></a>المعلومات القانونية</div>

#### <div dir="rtl">١١.٠ مقدمة القسم</div>

<div dir="rtl">يصف هذا القسم من الوثائق الاعتبارات القانونية الممكنة فيما يتعلق باستخدام الحزمة وتنفيذها، ويوفر بعض المعلومات الأساسية ذات الصلة. قد يكون هذا مهمًا لبعض المستخدمين كوسيلة لضمان التوافق مع أي متطلبات قانونية قد تكون موجودة في البلدان التي يعملون فيها، وقد يحتاج بعض المستخدمين إلى تعديل سياسات موقع الويب الخاصة بهم وفقًا لهذه المعلومات.<br /><br /></div>

<div dir="rtl">أولا، يرجى ندرك أنني (مؤلف حزمة) لست محام، وليس أي نوع من المهنيين القانونيين المؤهلين. لذلك، لست مؤهلاً قانونًا لتقديم المشورة القانونية. أيضا، في بعض الحالات، قد تختلف المتطلبات القانونية بين الدول والاختصاصات المختلفة، وهذه المتطلبات القانونية المتفاوتة قد تكون متناقضة في بعض الأحيان (على سبيل المثال، الدول التي تفضل "<a href="https://ar.wikipedia.org/wiki/%D8%AD%D9%82_%D9%81%D9%8A_%D8%A7%D9%84%D8%AE%D8%B5%D9%88%D8%B5%D9%8A%D8%A9">حقوق الخصوصية</a>" و "<a href="https://ar.wikipedia.org/wiki/%D8%AD%D9%82_%D8%A7%D9%84%D9%85%D8%B1%D8%A1_%D8%A3%D9%86_%D9%8A%D9%86%D8%B3%D9%89">الحق في أن تنسى</a>"، مقارنة بالبلدان التي تفضل "الاحتفاظ بالبيانات"). ضع في اعتبارك أيضًا أن الوصول إلى الحزمة لا يقتصر على بلدان أو ولايات قضائية محددة، وبالتالي، فإن مستخدمي الحزمة من المحتمل أن يكونوا متنوعين جغرافيًا. بالنظر إلى هذه النقاط، فأنا لست في وضع يسمح لي بالإشارة إلى ما يعنيه أن يكون "متوافقة مع القانون" مع الجميع. ومع ذلك، آمل أن تساعدك هذه المعلومات على أن تقرر بنفسك ما يجب عليك القيام به للبقاء ملتزمين قانونًا في سياق الحزمة. إذا كانت لديك أي شكوك بخصوص هذه المعلومات، أو إذا كنت بحاجة إلى مساعدة ومشورة إضافية من منظور قانوني، فإنني أوصيك باستشارة متخصص قانوني مؤهل.<br /><br /></div>

#### <div dir="rtl">١١.١ المسؤولية

<div dir="rtl">كما هو مذكور بالفعل من قبل ترخيص الحزمة، يتم توفير الحزمة دون أي ضمان. وهذا يشمل (على سبيل المثال لا الحصر) كل نطاق المسؤولية. يتم توفير الحزمة لك لراحتك، على أمل أن تكون مفيدة، وأنها سوف توفر بعض الفائدة بالنسبة لك. ومع ذلك، سواء كنت تستخدم أو تنفذ الحزمة، فذلك هو خيارك. لا تضطر إلى استخدام الحزمة أو تنفيذها، ولكن عندما تقوم بذلك، فأنت مسؤول عن هذا القرار. لا أنا ولا أي مساهم آخر في الحزمة مسؤول قانونيًا عن عواقب القرارات التي تتخذها، بصرف النظر عما إذا كانت مباشرة أو غير مباشرة أو ضمنية أو غير ذلك.<br /><br /></div>

#### <div dir="rtl">١١.٢ الأطراف الثالثة

Depending on its exact configuration and implementation, the package may communicate and share information with third parties in some cases. This information may be defined as "personally identifiable information" (PII) in some contexts, by some jurisdictions.

How this information may be used by these third parties, is subject to the various policies set forth by these third parties, and is outside the scope of this documentation. However, in all such cases, sharing of information with these third parties can be disabled. In all such cases, if you choose to enable it, it is your responsibility to research any concerns that you may have regarding the privacy, security, and usage of PII by these third parties. If any doubts exist, or if you're unsatisfied with the conduct of these third parties in regards to PII, it may be best to disable all sharing of information with these third parties.

For the purpose of transparency, the type of information shared, and with whom, is described below.

##### 11.2.0 HOSTNAME LOOKUP

If you use any features or modules intended to work with hostnames (such as the "bad hosts blocker module", "tor project exit nodes block module", or "search engine verification", for example), CIDRAM needs to be able to obtain the hostname of inbound requests somehow. Typically, it does this by requesting the hostname of the IP address of inbound requests from a DNS server, or by requesting the information through functionality provided by the system where CIDRAM is installed (this is typically referred to as a "hostname lookup"). The DNS servers defined by default belong to the Google DNS service (but this can be easily changed via configuration). The exact services communicated with is configurable, and depends on how you configure the package. In the case of using functionality provided by the system where CIDRAM is installed, you'll need to contact your system administrator to determine which routes hostname lookups use. Hostname lookups can be prevented in CIDRAM by avoiding the affected modules or by modifying the package configuration in accordance with your needs.

<div dir="rtl">خيارات التكوين ذات الصلة:<br /></div>
<div dir="rtl"><ul>
 <li><code dir="ltr">default_dns</code> &lt;- <code dir="ltr">general</code></li>
 <li><code dir="ltr">search_engine_verification</code> &lt;- <code dir="ltr">general</code></li>
 <li><code dir="ltr">force_hostname_lookup</code> &lt;- <code dir="ltr">general</code></li>
 <li><code dir="ltr">allow_gethostbyaddr_lookup</code> &lt;- <code dir="ltr">general</code></li>
</ul></div>

##### 11.2.1 WEBFONTS

Some custom themes, as well as the the standard UI ("user interface") for the CIDRAM front-end and the "Access Denied" page, may use webfonts for aesthetic reasons. Webfonts are disabled by default, but when enabled, direct communication between the user's browser and the service hosting the webfonts occurs. This may potentially involve communicating information such as the user's IP address, user agent, operating system, and other details available to the request. Most of these webfonts are hosted by the Google Fonts service.

<div dir="rtl">خيارات التكوين ذات الصلة:<br /></div>
<div dir="rtl"><ul>
 <li><code dir="ltr">disable_webfonts</code> &lt;- <code dir="ltr">general</code></li>
</ul></div>

##### 11.2.2 SEARCH ENGINE VERIFICATION

When search engine verification is enabled, CIDRAM attempts to perform "forward DNS lookups" to verify whether requests claiming to originate from search engines are authentic. To do this, it uses the Google DNS service to attempt to resolve IP addresses from the hostnames of these inbound requests (in this process, the hostnames of these inbound requests is shared with the service).

<div dir="rtl">خيارات التكوين ذات الصلة:<br /></div>
<div dir="rtl"><ul>
 <li><code dir="ltr">search_engine_verification</code> &lt;- <code dir="ltr">general</code></li>
</ul></div>

##### 11.2.3 GOOGLE reCAPTCHA

CIDRAM optionally supports Google reCAPTCHA, providing a means for users to bypass the "Access Denied" page by completing a reCAPTCHA instance (more information about this feature is described earlier in the documentation, most notably in the configuration section). Google reCAPTCHA requires API keys in order to be work correctly, and is thereby disabled by default. It can be enabled by defining the required API keys in the package configuration. When enabled, direct communication between the user's browser and the reCAPTCHA service occurs. This may potentially involve communicating information such as the user's IP address, user agent, operating system, and other details available to the request. The user's IP address may also be shared in communication between CIDRAM and the reCAPTCHA service when verifying the validity of a reCAPTCHA instance and verifying whether it was completed successfully.

<div dir="rtl">خيارات التكوين ذات الصلة: أي شيء مدرج ضمن فئة تهيئة "recaptcha".<br /><br /></div>

##### 11.2.4 STOP FORUM SPAM

[Stop Forum Spam](https://www.stopforumspam.com/) is a fantastic, freely available service that can help to protect forums, blogs, and websites from spammers. It does this by providing a database of known spammers, and an API that can be leveraged to check whether an IP address, username, or email address is listed on its database.

CIDRAM provides an optional module that leverages this API to check whether the IP address of inbound requests belongs to a suspected spammer. The module is not installed by default, but if you choose to install it, user IP addresses may be shared with the Stop Forum Spam API in accordance with the intended purpose of the module. When the module is installed, CIDRAM communicates with this API whenever an inbound request requests a resource that CIDRAM recognises as a type of resource frequently targeted by spammers (such as login pages, registration pages, email verification pages, comment forms, etc).

#### 11.3 LOGGING

Logging is an important part of CIDRAM for a number of reasons. It may be difficult to diagnose and resolve false positives when the block events that cause them aren't logged. Without logging block events, it may be difficult to ascertain exactly how performant CIDRAM is in any particular context, and it may be difficult to determine where its shortfalls may be, and what changes may be required to its configuration or signatures accordingly, in order for it to continue functioning as intended. Regardless, logging mightn't be desirable for all users, and remains entirely optional. In CIDRAM, logging is disabled by default. To enable it, CIDRAM must be configured accordingly.

Additionally, whether logging is legally permissible, and to the extent that it is legally permissible (e.g., the types of information that may logged, for how long, and under what circumstances), may vary, depending on jurisdiction and on the context where CIDRAM is implemented (e.g., whether you're operating as an individual, as a corporate entity, and whether on a commercial or non-commercial basis). It may therefore be useful for you to read through this section carefully.

There are multiple types of logging that CIDRAM can perform. Different types of logging involves different types of information, for different reasons.

##### 11.3.0 BLOCK EVENTS

The primary type of logging that CIDRAM can perform relates to "block events". This type of logging relates to when CIDRAM blocks a request, and can be provided in three different formats:
- Human readable logfiles.
- Apache-style logfiles.
- Serialised logfiles.

A block event, logged to a human readable logfile, typically looks something like this (as an example):

```
ID: 1234
Script Version: CIDRAM v1.6.0
Date/Time: Day, dd Mon 20xx hh:ii:ss +0000
IP Address: x.x.x.x
Hostname: dns.hostname.tld
Signatures Count: 1
Signatures Reference: x.x.x.x/xx
Why Blocked: Cloud service ("Network Name", Lxx:Fx, [XX])!
User Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36
Reconstructed URI: http://your-site.tld/index.php
reCAPTCHA State: Enabled.
```

That same block event, logged to an Apache-style logfile, would look something like this:

```
x.x.x.x - - [Day, dd Mon 20xx hh:ii:ss +0000] "GET /index.php HTTP/1.1" 200 xxxx "-" "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36"
```

A logged block event typically includes the following information:
- An ID number referencing the block event.
- The version of CIDRAM currently in use.
- The date and time that the block event occurred.
- The IP address of the blocked request.
- The hostname of the IP address of the blocked request (when available).
- The number of signatures triggered by the request.
- References to the signatures triggered.
- References to the reasons for the block event and some basic, related debug information.
- The user agent of the blocked request (i.e., how the requesting entity identified itself to the request).
- A reconstruction of the identifier for the resource originally requested.
- The reCAPTCHA state for the current request (when relevant).

The configuration directives responsible for this type of logging, and for each of the three formats available, are:
 <li><code dir="ltr">logfile</code> &lt;- <code dir="ltr">general</code></li>
 <li><code dir="ltr">logfileApache</code> &lt;- <code dir="ltr">general</code></li>
 <li><code dir="ltr">logfileSerialized</code> &lt;- <code dir="ltr">general</code></li>

When these directives are left empty, this type of logging will remain disabled.

##### 11.3.1 reCAPTCHA LOGGING

This type of logging relates specifically to reCAPTCHA instances, and occurs only when a user attempts to complete a reCAPTCHA instance.

A reCAPTCHA log entry contains the IP address of the user attempting to complete a reCAPTCHA instance, the date and time that the attempt occurred, and the reCAPTCHA state. A reCAPTCHA log entry typically looks something like this (as an example):

```
IP Address: x.x.x.x - Date/Time: Day, dd Mon 20xx hh:ii:ss +0000 - reCAPTCHA State: Passed!
```

The configuration directive responsible for reCAPTCHA logging is:
 <li><code dir="ltr">logfile</code> &lt;- <code dir="ltr">recaptcha</code></li>

##### 11.3.2 FRONT-END LOGGING

This type of logging relates front-end login attempts, and occurs only when a user attempts to log into the front-end (assuming front-end access is enabled).

A front-end log entry contains the IP address of the user attempting to log in, the date and time that the attempt occurred, and the results of the attempt (successfully logged in, or failed to log in). A front-end log entry typically looks something like this (as an example):

```
x.x.x.x - Day, dd Mon 20xx hh:ii:ss +0000 - "admin" - Logged in.
```

The configuration directive responsible for front-end logging is:
 <li><code dir="ltr">FrontEndLog</code> &lt;- <code dir="ltr">general</code></li>

##### 11.3.3 LOG ROTATION

You may want to purge logs after a period of time, or may be required to do so by law (i.e., the amount of time that it's legally permissible for you to retain logs may be limited by law). You can achieve this by including date/time markers in the names of your logfiles as per specified by your package configuration (e.g., `{yyyy}-{mm}-{dd}.log`), and then enabling log rotation (log rotation allows you to perform some action on logfiles when specified limits are exceeded).

For example: If I was legally required to delete logs after 30 days, I could specify `{dd}.log` in the names of my logfiles (`{dd}` represents days), set the value of `log_rotation_limit` to 30, and set the value of `log_rotation_action` to `Delete`.

Conversely, if you're required to retain logs for an extended period of time, you could either not use log rotation at all, or you could set the value of `log_rotation_action` to `Archive`, to compress logfiles, thereby reducing the total amount of disk space that they occupy.

<div dir="rtl">خيارات التكوين ذات الصلة:<br /></div>
<div dir="rtl"><ul>
 <li><code dir="ltr">log_rotation_limit</code> &lt;- <code dir="ltr">general</code></li>
 <li><code dir="ltr">log_rotation_action</code> &lt;- <code dir="ltr">general</code></li>
</ul></div>

##### 11.3.4 LOG TRUNCATION

It's also possible to truncate individual logfiles when they exceed a certain size, if this is something you might need or want to do.

<div dir="rtl">خيارات التكوين ذات الصلة:<br /></div>
<div dir="rtl"><ul>
 <li><code dir="ltr">truncate</code> &lt;- <code dir="ltr">general</code></li>
</ul></div>

##### 11.3.5 IP ADDRESS PSEUDONYMISATION

Firstly, if you're not familiar with the term "pseudonymisation", the following resources can help explain it in some detail:
- [[trust-hub.com] What is pseudonymisation?](https://www.trust-hub.com/news/what-is-pseudonymisation/)
- [[Wikipedia] Pseudonymization](https://en.wikipedia.org/wiki/Pseudonymization)

In some circumstances, you may be legally required to anonymise or pseudonymise any PII collected, processed, or stored. Although this concept has existed for quite some time now, GDPR/DSGVO notably mentions, and specifically encourages "pseudonymisation".

CIDRAM is able to pseudonymise IP addresses when logging them, if this is something you might need or want to do. When CIDRAM pseudonymises IP addresses, when logged, the final octet of IPv4 addresses, and everything after the second part of IPv6 addresses is represented by an "x" (effectively rounding IPv4 addresses to the initial address of the 24th subnet they factor into, and IPv6 addresses to the initial address of the 32nd subnet they factor into).

*Note: CIDRAM's IP address pseudonymisation process doesn't affect CIDRAM's IP tracking feature. If this is a problem for you, it may be best to disable IP tracking entirely. This can be achieved by setting `track_mode` to `false` and by avoiding any modules.*

<div dir="rtl">خيارات التكوين ذات الصلة:<br /></div>
<div dir="rtl"><ul>
 <li><code dir="ltr">track_mode</code> &lt;- <code dir="ltr">signatures</code></li>
 <li><code dir="ltr">pseudonymise_ip_addresses</code> &lt;- <code dir="ltr">legal</code></li>
</ul></div>

##### 11.3.6 OMITTING LOG INFORMATION

If you want to take it a step further by preventing specific types of information from being logged entirely, this is also possible to do. CIDRAM provides configuration directives to control whether IP addresses, hostnames, and user agents are included in logs. By default, all three of these data points are included in logs when available. Setting any of these configuration directives to `true` will omit the corresponding information from logs.

*Note: There's no reason to pseudonymise IP addresses when omitting them from logs entirely.*

<div dir="rtl">خيارات التكوين ذات الصلة:<br /></div>
<div dir="rtl"><ul>
 <li><code dir="ltr">omit_ip</code> &lt;- <code dir="ltr">legal</code></li>
 <li><code dir="ltr">omit_hostname</code> &lt;- <code dir="ltr">legal</code></li>
 <li><code dir="ltr">omit_ua</code> &lt;- <code dir="ltr">legal</code></li>
</ul></div>

##### 11.3.7 STATISTICS

CIDRAM is optionally able to track statistics such as the total number of block events or reCAPTCHA instances that have occurred since some particular point in time. This feature is disabled by default, but can be enabled via the package configuration. This feature only tracks the total number of events occurred, and doesn't include any information about specific events (and therefore, shouldn't be regarded as PII).

<div dir="rtl">خيارات التكوين ذات الصلة:<br /></div>
<div dir="rtl"><ul>
 <li><code dir="ltr">statistics</code> &lt;- <code dir="ltr">general</code></li>
</ul></div>

##### 11.3.8 ENCRYPTION

CIDRAM doesn't encrypt its cache or any log information. Cache and log encryption may be introduced in the future, but there aren't any specific plans for it currently. If you're concerned about unauthorised third parties gaining access to parts of CIDRAM that may contain PII or sensitive information such as its cache or logs, I would recommend that CIDRAM not be installed at a publicly accessible location (e.g., install CIDRAM outside the standard `public_html` directory or equivalent thereof available to most standard webservers) and that appropriately restrictive permissions be enforced for the directory where it resides (in particular, for the vault directory). If that isn't sufficient to address your concerns, then configure CIDRAM as such that the types of information causing your concerns won't be collected or logged in the first place (such as, by disabling logging).

#### 11.4 COOKIES

CIDRAM sets cookies at two points in its codebase. Firstly, when a user successfully completes a reCAPTCHA instance (and assuming that `lockuser` is set to `true`), CIDRAM sets a cookie in order to be able to remember for subsequent requests that the user has already completed a reCAPTCHA instance, so that it won't need to continuously ask the user to complete a reCAPTCHA instance on subsequent requests. Secondly, when a user successfully logs into the front-end, CIDRAM sets a cookie in order to be able to remember the user for subsequent requests (i.e., cookies are used for authenticate the user to a login session).

In both cases, cookie warnings are displayed prominently (when applicable), warning the user that cookies will be set if they engage in the relevant actions. Cookies aren't set at any other points in the codebase.

*Note: CIDRAM's particular implementation of the "invisible" API for reCAPTCHA might be incompatible with cookie laws in some jurisdictions, and should be avoided by any websites subject to such laws. Opting to use the "V2" API instead, or simply disabling reCAPTCHA entirely, may be preferable.*

<div dir="rtl">خيارات التكوين ذات الصلة:<br /></div>
<div dir="rtl"><ul>
 <li><code dir="ltr">disable_frontend</code> &lt;- <code dir="ltr">general</code></li>
 <li><code dir="ltr">lockuser</code> &lt;- <code dir="ltr">recaptcha</code></li>
 <li><code dir="ltr">api</code> &lt;- <code dir="ltr">recaptcha</code></li>
</ul></div>

#### 11.5 MARKETING AND ADVERTISING

CIDRAM doesn't collect or process any information for marketing or advertising purposes, and neither sells nor profits from any collected or logged information. CIDRAM is not a commercial enterprise, nor is related to any commercial interests, so doing these things wouldn't make any sense. This has been the case since the beginning of the project, and continues to be the case today. Additionally, doing these things would be counter-productive to the spirit and intended purpose of the project as a whole, and for as long as I continue to maintain the project, will never happen.

#### 11.6 PRIVACY POLICY

In some circumstances, you may be legally required to clearly display a link to your privacy policy on all pages and sections of your website. This may be important as a means to ensure that users and well-informed of your exact privacy practices, the types of PII you collect, and how you intend to use it. In order to be able to include such a link on CIDRAM's "Access Denied" page, a configuration directive is provided to specify the URL to your privacy policy.

*Note: It's strongly recommended that your privacy policy page isn't placed behind CIDRAM's protection. If CIDRAM protects your privacy policy page, and a user blocked by CIDRAM clicks the link to your privacy policy, they'll just be blocked again, and won't be able to see your privacy policy. Ideally, you should link to a static copy of your privacy policy, such as an HTML page or plain-text file which isn't protected by CIDRAM.*

<div dir="rtl">خيارات التكوين ذات الصلة:<br /></div>
<div dir="rtl"><ul>
 <li><code dir="ltr">privacy_policy</code> &lt;- <code dir="ltr">legal</code></li>
</ul></div>

#### 11.7 GDPR/DSGVO

The General Data Protection Regulation (GDPR) is a regulation of the European Union, which comes into effect as of May 25, 2018. The primary goal of the regulation is to give control to EU citizens and residents regarding their own personal data, and to unify regulation within the EU concerning privacy and personal data.

The regulation contains specific provisions pertaining to the processing of "personally identifiable information" (PII) of any "data subjects" (any identified or identifiable natural person) either from or within the EU. To be compliant with the regulation, "enterprises" (as per defined by the regulation), and any relevant systems and processes must implement "privacy by design" by default, must use the highest possible privacy settings, must implement necessary safeguards for any stored or processed information (including, but not limited to, the implementation of pseudonymisation or full anonymisation of data), must clearly and unambiguously declare the types of data they collect, how they process it, for what reasons, for how long they retain it, and whether they share this data with any third parties, the types of data shared with third parties, how, why, and so on.

Data may not be processed unless there's a lawful basis for doing so, as per defined by the regulation. Generally, this means that in order to process a data subject's data on a lawful basis, it must be done in compliance with legal obligations, or done only after explicit, well-informed, unambiguous consent has been obtained from the data subject.

Because aspects of the regulation may evolve in time, in order to avoid the propagation of outdated information, it may be better to learn about the regulation from an authoritative source, as opposed to simply including the relevant information here in the package documentation (which may eventually become outdated as the regulation evolves).

[EUR-Lex](https://eur-lex.europa.eu/) (a part of the official website of the European Union that provides information about EU law) provides extensive information about GDPR/DSGVO, available in 24 different languages (at the time of writing this), and available for download in PDF format. I would definitely recommend reading the information that they provide, in order to learn more about GDPR/DSGVO:
- [REGULATION (EU) 2016/679 OF THE EUROPEAN PARLIAMENT AND OF THE COUNCIL](https://eur-lex.europa.eu/legal-content/EN/TXT/?uri=celex:32016R0679)

Alternatively, there's a brief (non-authoritative) overview of GDPR/DSGVO available at Wikipedia:
- [General Data Protection Regulation](https://en.wikipedia.org/wiki/General_Data_Protection_Regulation)

---


<div dir="rtl">آخر تحديث: 10 يونيو 2018 (2018.06.10).</div>

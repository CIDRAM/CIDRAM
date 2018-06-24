## CIDRAM 설명서 (한국어).

### 목차
- 1. [서문](#SECTION1)
- 2. [설치 방법](#SECTION2)
- 3. [사용 방법](#SECTION3)
- 4. [프론트 엔드 관리](#SECTION4)
- 5. [본 패키지에 포함 된 파일](#SECTION5)
- 6. [설정 옵션](#SECTION6)
- 7. [서명 포맷](#SECTION7)
- 8. [적합성 문제](#SECTION8)
- 9. [자주 묻는 질문 (FAQ)](#SECTION9)
- 10. *나중에 문서에 추가 할 수 있도록 예약되어 있습니다.*
- 11. [법률 정보](#SECTION11)

*번역에 노트 : 오류가 발생하는 경우 (예를 들어, 번역 사이의 불일치, 오타, 등등), README의 영어 버전은 원본과 권위 버전이라고 생각됩니다. 오류를 발견하면이를 해결하려면 협력을 환영하는 것이다.*

---


### 1. <a name="SECTION1"></a>서문

CIDRAM (시도라무 클래스없는 도메인 간 라우팅 액세스 매니저; "Classless Inter-Domain Routing Access Manager")는 PHP 스크립트입니다. 웹 사이트를 보호하도록 설계되어 IP 주소 (원치 않는 트래픽이있는 소스로 간주합니다)에서 전송 요청을 차단하여 (인간 이외의 액세스 엔드 포인트 클라우드 서비스 스팸봇 스크레이퍼 등). IP 주소의 수 CIDR을 계산하여 CIDR은 시그니처 파일과 비교할 수 있습니다 (이 서명 파일은 불필요한 IP 주소에 해당하는 CIDR의 목록이 포함되어 있습니다); 일치가 발견되면 요청이 차단됩니다.

*(참조하십시오 : ["CIDR"이란 무엇입니까?](#WHAT_IS_A_CIDR)).*

CIDRAM 저작권 2016 년 이후 Caleb M (Maikuolan)의 GNU/GPLv2.

본 스크립트는 프리웨어입니다. 자유 소프트웨어 재단에서 발행 한 GNU 일반 공중 라이선스 버전 2 (또는 이후 버전)에 따라 재배포 및 가공이 가능합니다. 배포의 목적은 도움이되기를 바랍니다 것이지만 "보증 아니며 상품성 또는 특정 목적에 적합한 것을 시사하는 것이기도 없습니다." "LICENSE.txt"에있는 "GNU General Public License" (일반 라이선스)을 참조하십시오. 다음 URL에서도 볼 수 있습니다 :
- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

본 문서 및 관련 패키지 [GitHub](https://cidram.github.io/)에서 다운로드 할 수 있습니다.

---


### 2. <a name="SECTION2"></a>설치 방법

#### 2.0 수동 설치

1) 이 항목을 읽고 있다는 점에서 아카이브 스크립트의 로컬 컴퓨터에 다운로드 및 압축 해제는 종료하고 있다고 생각합니다. 호스트 또는 CMS에 `/public_html/cidram/`와 같은 디렉토리를 만들고 로컬 컴퓨터에서 거기에 콘텐츠를 업로드하는 것이 다음 단계입니다. 파일 저장 디렉토리 이름과 위치는 안전하고 만 있으면 물론 제약 등은 없기 때문에 자유롭게 결정 해주세요.

2) `config.ini`에 `config.ini.RenameMe`의 이름을 변경합니다 (`vault`의 안쪽에 위치한다). 옵션 수정을 위해 (초보자는 권장하지 않지만, 경험이 풍부한 사용자는 좋습니다) 그것을여십시오 (이 파일은 CIDRAM이 가능한 지시자를 포함하고 있으며, 각각의 옵션에 대해 기능과 목적에 관한 간단한 설명이 있습니다). 설치 환경에 따라 적절한 수정을하고 파일을 저장하십시오.

3) 콘텐츠 (CIDRAM 본체와 파일)을 먼저 정한 디렉토리에 업로드합니다. (`*.txt`또는 `*.md`파일 업로드 필요는 없지만, 대개는 모든 업로드 해달라고해도됩니다).

4) `vault`디렉토리 "755"로 권한 변경 (문제가있는 경우 "777"을 시도 할 수 있습니다; 하지만 이것은 안전하지 않습니다). 콘텐츠를 업로드 한 디렉토리 자체는 보통 특히 아무것도 필요하지 않지만, 과거에 권한 문제가있을 경우 CHMOD의 상태는 확인하는 것이 좋습니다. (기본적으로 "755"가 일반적입니다).

5) 그 다음에 시스템 또는 CMS에 CIDRAM를 연결합니다. 방법에는 여러 가지가 있지만 가장 쉬운 것은`require`과`include`에서 스크립트를 시스템 또는 CMS 코어 파일의 첫 부분에 기재하는 방법입니다. (코어 파일은 사이트의 어떤 페이지에 접근이 있어도 반드시로드되는 파일입니다). 일반적으로는 `/includes`또는 `/assets`또는 `/functions`같은 디렉토리에있는 파일에서 `init.php`, `common_functions.php`, `functions.php`라는 파일 이름을 붙일 수 있습니다. 실제로 어떤 파일인지는 찾아도 바닥입니다해야합니다. 잘 모르는 경우 CIDRAM 지원 포럼을 참조하거나 GitHub 때문에 CIDRAM 문제의 페이지 또는 알려주십시오 (CMS 정보 필수). 나 자신을 포함하여 사용자에 유사한 CMS를 다룬 경험이 있으면, 무엇인가의 지원을 제공 할 수 있습니다. 코어 파일이 발견 된 경우, (`require` 또는`include`을 사용하여) 다음 코드를 파일의 맨 위에 삽입하십시오. 그러나 따옴표로 둘러싸인 부분은`loader.php` 파일의 정확한 주소 (HTTP 주소가 아닌 로컬 주소 전술의 vault 주소와 유사)로 바꿉니다.

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

파일을 저장하고 닫은 다음 다시 업로드합니다.

-- 다른 방법 --

Apache 웹서버를 이용하고있어, 한편`php.ini`를 편집 할 수 있도록한다면, `auto_prepend_file` 지시어를 사용하여 PHP 요청이있을 경우에는 항상 CIDRAM을 앞에 추가하도록 할 있습니다. 예를 들면 다음과 같습니다.

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

또는 `.htaccess`에서 :

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

6) 설치가 완료되었습니다. :-)

#### 2.1 COMPOSER를 사용하여 설치한다

[CIDRAM는 Packagist에 등록되어 있습니다](https://packagist.org/packages/cidram/cidram). Composer를 익숙한 경우 Composer를 사용하여 CIDRAM를 설치할 수 있습니다 (당신은 아직 설정과 후크를 준비해야합니다; "수동 설치"의 2 단계와 5 단계를 참조하십시오).

`composer require cidram/cidram`

#### 2.2 WORDPRESS 위해 설치한다

WordPress에 CIDRAM를 사용하려면 위의 단계를 모두 무시 할 수 있습니다. [CIDRAM은 WordPress 플러그인 데이터베이스에 플러그인으로 등록되어 있습니다](https://wordpress.org/plugins/cidram/). 플러그인 대시 보드에서 CIDRAM를 직접 설치할 수 있습니다. 다른 플러그인과 같은 방법으로 설치할 수 (추가 절차는 필요하지 않습니다). 다른 설치 방법과 마찬가지로, `config.ini` 파일의 내용을 변경 또는 프런트 엔드 구성 페이지를 사용하여 설치를 사용자 정의 할 수 있습니다. 프런트 엔드 업데이트 페이지에서 CIDRAM를 업데이트하면, 플러그인 버전 정보가 WordPress에 자동으로 동기화됩니다.

*경고 : 플러그인 대시 보드를 통해 CIDRAM를 업데이트하면 클린 설치가 이루어집니다. 설치를 사용자 정의한 경우 (당신의 설정을 변경 한 모듈을 설치 한 등) 이러한 정의는 플러그인 대시 보드를 통해 업데이트하면 손실됩니다! 로그 파일도 손실됩니다! 로그 파일과 문화를 유지하려면 CIDRAM 프론트 엔드 업 데이트 페이지에 업데이트합니다.*

---


### 3. <a name="SECTION3"></a>사용 방법

CIDRAM은 자동으로 원치 않는 요청을 차단해야합니다; 지원이 필요하지 않습니다 (설치 제외).

당신의 설정 파일을 수정하여 구성 설정을 사용자 정의 할 수 있습니다. 당신의 서명 파일을 변경하여 CIDRs이 차단 된 변경 할 수 있습니다.

오진 (거짓 양성)과 신종 의심스러운 것으로 발생, 관한 것에 대해서는 무엇이든 알려주세요. *(참조하십시오 : ["거짓 양성"는 무엇입니까?](#WHAT_IS_A_FALSE_POSITIVE)).*

CIDRAM은 수동으로 또는 프런트 엔드를 통해 업데이트 할 수 있습니다. CIDRAM은 원래 이러한 수단을 통해 설치된 경우 Composer 또는 WordPress를 통해 업데이트 할 수도 있습니다.

---


### 4. <a name="SECTION4"></a>프론트 엔드 관리

#### 4.0 프론트 엔드는 무엇입니다.

프론트 엔드는 CIDRAM 설치 유지 관리 업데이트하기위한 편리하고 쉬운 방법을 제공합니다. 로그 페이지를 사용하여 로그 파일을 공유, 다운로드 할 수있는 구성 페이지에서 구성을 변경할 수 있습니다, 업데이트 페이지를 사용하여 구성 요소를 설치 및 제거 할 수 있습니다, 그리고 파일 관리자를 사용하여 vault에 파일을 업로드, 다운로드 및 변경할 수 있습니다.

무단 액세스를 방지하기 위해 프론트 엔드는 기본적으로 비활성화되어 있습니다 (무단 액세스가 웹 사이트와 보안에 중대한 영향을 미칠 수 있습니다). 그것을 가능하게하기위한 지침이 절 아래에 포함되어 있습니다.

#### 4.1 프론트 엔드를 사용하는 방법.

1) `config.ini` 안에있는 `disable_frontend` 지시문을 찾습니다 그것을 "`false`"로 설정합니다 (기본값은 "`true`"입니다).

2) 브라우저에서 `loader.php`에 액세스하십시오 (예, `http://localhost/cidram/loader.php`).

3) 기본 사용자 이름과 암호로 로그인 (admin/password).

주의 : 당신이 처음 로그인 한 후 프론트 엔드에 대한 무단 액세스를 방지하기 위해 신속하게 사용자 이름과 암호를 변경해야합니다! 이것은 매우 중요합니다, 왜냐하면 프론트 엔드에서 임의의 PHP 코드를 당신의 웹 사이트에 업로드 할 수 있기 때문입니다.

#### 4.2 프론트 엔드 사용.

프론트 엔드의 각 페이지에는 목적에 대한 설명과 사용 방법에 대한 설명이 있습니다. 전체 설명이나 특별한 지원이 필요한 경우 지원에 문의하십시오. 또한 데모를 제공 할 YouTube에서 사용 가능한 동영상도 있습니다.


---


### 5. <a name="SECTION5"></a>본 패키지에 포함 된 파일

다음은 아카이브에서 일괄 다운로드되는 파일의 목록 및 스크립트 사용에 의해 생성되는 파일과이 파일이 무엇 때문인지는 간단한 설명입니다.

파일 | 설명
----|----
/_docs/ | 문서의 디렉토리입니다 (다양한 파일을 포함합니다).
/_docs/readme.ar.md | 아랍어 문서.
/_docs/readme.de.md | 독일어 문서.
/_docs/readme.en.md | 영어 문서.
/_docs/readme.es.md | 스페인 문서.
/_docs/readme.fr.md | 프랑스어 문서.
/_docs/readme.id.md | 인도네시아어 문서.
/_docs/readme.it.md | 이탈리아 문서.
/_docs/readme.ja.md | 일본어 문서.
/_docs/readme.ko.md | 한국어 문서.
/_docs/readme.nl.md | 네덜란드어 문서.
/_docs/readme.pt.md | 포르투갈어 문서.
/_docs/readme.ru.md | 러시아어 문서.
/_docs/readme.ur.md | 우르두어 문서.
/_docs/readme.vi.md | 베트남어 문서.
/_docs/readme.zh-TW.md | 중국어 번체 문서.
/_docs/readme.zh.md | 중국어 간체 문서.
/vault/ | 보루 토 디렉토리 (다양한 파일을 포함합니다).
/vault/fe_assets/ | 프론트 엔드 자산.
/vault/fe_assets/.htaccess | 하이퍼 텍스트 액세스 파일 (이 경우, 본 스크립트의 중요한 파일을 권한이없는 소스의 액세스로부터 보호하기위한 것입니다).
/vault/fe_assets/_accounts.html | 프론트 엔드의 계정 페이지의 HTML 템플릿.
/vault/fe_assets/_accounts_row.html | 프론트 엔드의 계정 페이지의 HTML 템플릿.
/vault/fe_assets/_cache.html | 프론트 엔드 데이터 캐쉬 페이지의 HTML 템플릿.
/vault/fe_assets/_cidr_calc.html | CIDR 계산기 HTML 템플릿.
/vault/fe_assets/_cidr_calc_row.html | CIDR 계산기 HTML 템플릿.
/vault/fe_assets/_config.html | 프론트 엔드 구성 페이지의 HTML 템플릿.
/vault/fe_assets/_config_row.html | 프론트 엔드 구성 페이지의 HTML 템플릿.
/vault/fe_assets/_files.html | 파일 관리자의 HTML 템플릿.
/vault/fe_assets/_files_edit.html | 파일 관리자의 HTML 템플릿.
/vault/fe_assets/_files_rename.html | 파일 관리자의 HTML 템플릿.
/vault/fe_assets/_files_row.html | 파일 관리자의 HTML 템플릿.
/vault/fe_assets/_home.html | 프론트 엔드의 홈페이지의 HTML 템플릿.
/vault/fe_assets/_ip_aggregator.html | IP 애그리게이터 HTML 템플릿.
/vault/fe_assets/_ip_test.html | IP 테스트 페이지의 HTML 템플릿.
/vault/fe_assets/_ip_test_row.html | IP 테스트 페이지의 HTML 템플릿.
/vault/fe_assets/_ip_tracking.html | IP 추적 페이지의 HTML 템플릿.
/vault/fe_assets/_ip_tracking_row.html | IP 추적 페이지의 HTML 템플릿.
/vault/fe_assets/_login.html | 프론트 엔드 로그인 페이지의 HTML 템플릿.
/vault/fe_assets/_logs.html | 프론트 엔드 로고스 페이지의 HTML 템플릿.
/vault/fe_assets/_nav_complete_access.html | 프론트 엔드의 탐색 링크의 HTML 템플릿, 완전한 액세스를위한 것입니다.
/vault/fe_assets/_nav_logs_access_only.html | 프론트 엔드의 탐색 링크의 HTML 템플릿은 로그에만 액세스를위한 것입니다.
/vault/fe_assets/_range.html | 프론트 엔드 범위 테이블 페이지의 HTML 템플릿.
/vault/fe_assets/_range_row.html | 프론트 엔드 범위 테이블 페이지의 HTML 템플릿.
/vault/fe_assets/_sections.html | 섹션 목록 용 HTML 템플릿.
/vault/fe_assets/_sections_row.html | 섹션 목록 용 HTML 템플릿.
/vault/fe_assets/_statistics.html | 프론트 엔드 통계 페이지의 HTML 템플릿.
/vault/fe_assets/_updates.html | 프론트 엔드 업데이트 페이지의 HTML 템플릿.
/vault/fe_assets/_updates_row.html | 프론트 엔드 업데이트 페이지의 HTML 템플릿.
/vault/fe_assets/frontend.css | 프론트 엔드 CSS 스타일 시트.
/vault/fe_assets/frontend.dat | 프론트 엔드 데이터베이스 (계정 정보와 세션 정보 및 캐시가 포함되어 있습니다; 프론트 엔드가 활성화되어있을 때 생성).
/vault/fe_assets/frontend.html | 프론트 엔드 메인 템플릿 파일.
/vault/fe_assets/icons.php | 아이콘 핸들러 (프론트 엔드 파일 관리자에 의해 사용된다).
/vault/fe_assets/pips.php | 핍 핸들러 (프론트 엔드 파일 관리자에 의해 사용된다).
/vault/fe_assets/scripts.js | 프런트 엔드 JavaScript 데이터가 들어 있습니다.
/vault/lang/ | CIDRAM 언어 데이터가 포함되어 있습니다.
/vault/lang/.htaccess | 하이퍼 텍스트 액세스 파일 (이 경우, 본 스크립트의 중요한 파일을 권한이없는 소스의 액세스로부터 보호하기위한 것입니다).
/vault/lang/lang.ar.cli.php | CLI 아랍어 언어 데이터.
/vault/lang/lang.ar.fe.php | 프론트 엔드 아랍어 언어 데이터.
/vault/lang/lang.ar.php | 아랍어 언어 데이터.
/vault/lang/lang.bn.cli.php | CLI 벵골어 언어 데이터.
/vault/lang/lang.bn.fe.php | 프론트 엔드 벵골어 언어 데이터.
/vault/lang/lang.bn.php | CLI 벵골어 언어 데이터.
/vault/lang/lang.de.cli.php | CLI 독일어 언어 데이터.
/vault/lang/lang.de.fe.php | 프론트 엔드 독일어 언어 데이터.
/vault/lang/lang.de.php | 독일어 언어 데이터.
/vault/lang/lang.en.cli.php | CLI 영어 언어 데이터.
/vault/lang/lang.en.fe.php | 프론트 엔드 영어 데이터.
/vault/lang/lang.en.php | 영어 데이터.
/vault/lang/lang.es.cli.php | CLI 스페인어 언어 데이터.
/vault/lang/lang.es.fe.php | 프론트 엔드 스페인어 언어 데이터.
/vault/lang/lang.es.php | 스페인어 언어 데이터.
/vault/lang/lang.fr.cli.php | CLI 프랑스어 언어 데이터.
/vault/lang/lang.fr.fe.php | 프론트 엔드 프랑스어 언어 데이터.
/vault/lang/lang.fr.php | 프랑스어 언어 데이터.
/vault/lang/lang.hi.cli.php | CLI 힌디어 언어 데이터.
/vault/lang/lang.hi.fe.php | 프론트 엔드 힌디어 언어 데이터.
/vault/lang/lang.hi.php | 힌디어 언어 데이터.
/vault/lang/lang.id.cli.php | CLI 인도네시아어 언어 데이터.
/vault/lang/lang.id.fe.php | 프론트 엔드 인도네시아어 언어 데이터.
/vault/lang/lang.id.php | 인도네시아어 언어 데이터.
/vault/lang/lang.it.cli.php | CLI의 이탈리아 언어 데이터.
/vault/lang/lang.it.fe.php | 프론트 엔드 이탈리아 언어 데이터.
/vault/lang/lang.it.php | 이탈리아 언어 데이터.
/vault/lang/lang.ja.cli.php | CLI는 일본어 언어 데이터.
/vault/lang/lang.ja.fe.php | 프론트 엔드 일본어 언어 데이터.
/vault/lang/lang.ja.php | 일본어 언어 데이터.
/vault/lang/lang.ko.cli.php | CLI 한국어 언어 데이터.
/vault/lang/lang.ko.fe.php | 프론트 엔드의 한국어 언어 데이터.
/vault/lang/lang.ko.php | 한국어 언어 데이터.
/vault/lang/lang.nl.cli.php | CLI 네덜란드어 언어 데이터.
/vault/lang/lang.nl.fe.php | 프론트 엔드 네덜란드어 언어 데이터.
/vault/lang/lang.nl.php | 네덜란드어 언어 데이터.
/vault/lang/lang.no.cli.php | CLI 노르웨이 언어 데이터.
/vault/lang/lang.no.fe.php | 프론트 엔드 노르웨이 언어 데이터.
/vault/lang/lang.no.php | 노르웨이 언어 데이터.
/vault/lang/lang.pt.cli.php | CLI 포르투갈어 언어 데이터.
/vault/lang/lang.pt.fe.php | 프론트 엔드 포르투갈어 언어 데이터.
/vault/lang/lang.pt.php | 포르투갈어 언어 데이터.
/vault/lang/lang.ru.cli.php | CLI 러시아어 언어 데이터.
/vault/lang/lang.ru.fe.php | 프론트 엔드 러시아어 언어 데이터.
/vault/lang/lang.ru.php | 러시아어 언어 데이터.
/vault/lang/lang.sv.cli.php | CLI 스웨덴어 언어 데이터.
/vault/lang/lang.sv.fe.php | 프론트 엔드 스웨덴어 언어 데이터.
/vault/lang/lang.sv.php | 스웨덴어 언어 데이터.
/vault/lang/lang.th.cli.php | CLI 태국어 언어 데이터.
/vault/lang/lang.th.fe.php | 프론트 엔드 태국어 언어 데이터.
/vault/lang/lang.th.php | 태국어 언어 데이터.
/vault/lang/lang.tr.cli.php | CLI 터키어 언어 데이터.
/vault/lang/lang.tr.fe.php | 프론트 엔드 터키어 언어 데이터.
/vault/lang/lang.tr.php | 터키어 언어 데이터.
/vault/lang/lang.ur.cli.php | CLI 우르두어 언어 데이터.
/vault/lang/lang.ur.fe.php | 프론트 엔드 우르두어 언어 데이터.
/vault/lang/lang.ur.php | 우르두어 언어 데이터.
/vault/lang/lang.vi.cli.php | CLI 베트남어 언어 데이터.
/vault/lang/lang.vi.fe.php | 프론트 엔드 베트남어 언어 데이터.
/vault/lang/lang.vi.php | 베트남어 언어 데이터.
/vault/lang/lang.zh-tw.cli.php | CLI 중국어 번체 언어 데이터.
/vault/lang/lang.zh-tw.fe.php | 프론트 엔드 중국어 번체 언어 데이터.
/vault/lang/lang.zh-tw.php | 중국어 번체 언어 데이터.
/vault/lang/lang.zh.cli.php | CLI 중국어 간체 언어 데이터.
/vault/lang/lang.zh.fe.php | 프론트 엔드 중국어 간체 언어 데이터.
/vault/lang/lang.zh.php | 중국어 간체 언어 데이터.
/vault/.htaccess | 하이퍼 텍스트 액세스 파일 (이 경우, 본 스크립트의 중요한 파일을 권한이없는 소스의 액세스로부터 보호하기위한 것입니다).
/vault/.travis.php | 테스트를 위해 Travis CI에서 사용됩니다. (기능에 관계없는 파일입니다).
/vault/.travis.yml | 테스트를 위해 Travis CI에서 사용됩니다. (기능에 관계없는 파일입니다).
/vault/aggregator.php | IP 애그리게이터.
/vault/cache.dat | 캐시 데이터.
/vault/cidramblocklists.dat | Macmathan의 선택적 블록리스트 용 메타 데이터 파일; 프런트 엔드 업데이트 페이지에서 사용됩니다.
/vault/cli.php | CLI 핸들러.
/vault/components.dat | 구성 요소 메타 데이터 파일; 프런트 엔드 업데이트 페이지에서 사용됩니다.
/vault/config.ini.RenameMe | CIDRAM 설정 파일; CIDRAM 모든 옵션 설정을 포함하고 있습니다. 각 옵션의 기능과 작동 방법에 대한 설명입니다 (활성화하기 위해 이름을 변경합니다).
/vault/config.php | 구성 핸들러.
/vault/config.yaml | 설정 기본값 스 파일; CIDRAM의 기본 설정이 포함되어 있습니다.
/vault/frontend.php | 프론트 엔드 핸들러.
/vault/frontend_functions.php | 프론트 엔드 기능 파일.
/vault/functions.php | 기능 파일 (기본적으로 파일).
/vault/hashes.dat | 허용되는 해시 목록 (reCAPTCHA의 기능에 관련합니다; 만 reCAPTCHA 기능이 활성화되어있는 경우에 생성).
/vault/ignore.dat | 무시 파일 (이것은 서명 섹션 무시합니다).
/vault/ipbypass.dat | IP 우회 목록 (reCAPTCHA의 기능에 관련합니다; 만 reCAPTCHA 기능이 활성화되어있는 경우에 생성).
/vault/ipv4.dat | IPv4의 서명 파일 (불필요한 클라우드 서비스와 非人 엔드 포인트).
/vault/ipv4_bogons.dat | IPv4의 서명 파일 (보공/화성 CIDRs).
/vault/ipv4_custom.dat.RenameMe | IPv4에 대한 사용자 정의 시그니처 파일 (활성화하기 위해 이름을 변경합니다).
/vault/ipv4_isps.dat | IPv4의 서명 파일 (스패머를 가진 위험한 ISP).
/vault/ipv4_other.dat | IPv4의 서명 파일 (프록시, VPN 및 기타 불필요한 서비스 CIDR).
/vault/ipv6.dat | IPv6의 서명 파일 (불필요한 클라우드 서비스와 非人 엔드 포인트).
/vault/ipv6_bogons.dat | IPv6의 서명 파일 (보공/화성 CIDRs).
/vault/ipv6_custom.dat.RenameMe | IPv6에 대한 사용자 정의 시그니처 파일 (활성화하기 위해 이름을 변경합니다).
/vault/ipv6_isps.dat | IPv6의 서명 파일 (스패머를 가진 위험한 ISP).
/vault/ipv6_other.dat | IPv6의 서명 파일 (프록시, VPN 및 기타 불필요한 서비스 CIDR).
/vault/lang.php | 언어 처리기.
/vault/modules.dat | 모듈 메타 데이터 파일; 프런트 엔드 업데이트 페이지에서 사용됩니다.
/vault/outgen.php | 출력 발생기.
/vault/php5.4.x.php | PHP 5.4.X 뽀리휘루 (PHP 5.4.X의 하위 호환성을 위해 필요합니다; 더 새로운 PHP 버전을 위해 삭제하는 것이 안전합니다).
/vault/recaptcha.php | reCAPTCHA 모듈.
/vault/rules_as6939.php | 사용자 정의 규칙은 AS6939을위한 파일입니다.
/vault/rules_softlayer.php | 사용자 정의 규칙은 Soft Layer위한 파일.
/vault/rules_specific.php | 사용자 정의 규칙은 일부 특정 CIDR위한 파일.
/vault/salt.dat | 솔트 파일 (일부 주변 기능에 의해 사용됩니다; 필요한 경우에만 생성).
/vault/template_custom.html | CIDRAM 템플릿 파일; CIDRAM가 파일 업로드를 차단했을 때 생성되는 메시지의 HTML 출력 템플릿 (업 로더를 표시하는 메시지).
/vault/template_default.html | CIDRAM 템플릿 파일; CIDRAM가 파일 업로드를 차단했을 때 생성되는 메시지의 HTML 출력 템플릿 (업 로더를 표시하는 메시지).
/vault/themes.dat | 테마 메타 데이터 파일; 프런트 엔드 업데이트 페이지에서 사용됩니다.
/.gitattributes | GitHub 프로젝트 파일 (기능에 관계없는 파일입니다).
/Changelog.txt | 버전에 따른 차이를 기록한 것입니다 (기능에 관계없는 파일입니다).
/composer.json | Composer/Packagist 정보 (기능에 관계없는 파일입니다).
/CONTRIBUTING.md | 프로젝트에 기여하는 방법.
/LICENSE.txt | GNU/GPLv2 라이센스 사본 (기능에 관계없는 파일입니다).
/loader.php | 로더 파일입니다. 주요 스크립트로드, 업로드 등을 실시합니다. 훅하는 것은 바로 이것입니다 (본질적 파일)!
/README.md | 프로젝트 개요 정보.
/web.config | ASP.NET 설정 파일 (스크립트가 ASP.NET 기술을 기초로하는 서버에 설치된 때 `/vault` 디렉토리를 무단 소스에 의한 액세스로부터 보호하는 것입니다).

---


### 6. <a name="SECTION6"></a>설정 옵션
다음은 `config.ini`설정 파일에있는 변수 및 그 목적과 기능의 목록입니다.

#### "general" (카테고리)
일반 설정.

"logfile"
- 액세스 시도 저지를 기록, 인간에 의해 읽기 가능. 파일 이름 지정하거나 해제하려면 비워하십시오.

"logfileApache"
- 액세스 시도 저지를 기록, Apache 스타일. 파일 이름 지정하거나 해제하려면 비워하십시오.

"logfileSerialized"
- 액세스 시도 저지를 기록 직렬화되었습니다. 파일 이름 지정하거나 해제하려면 비워하십시오.

*유용한 팁 : 당신이 원하는 경우 로그 파일 이름에 날짜/시간 정보를 부가 할 수 있습니다 이름 이들을 포함하여 : 전체 연도에 대한 `{yyyy}`생략 된 년간 `{yy}`달 `{mm}`일 `{dd}`시간 `{hh}`.*

*예 :*
- *`logfile='logfile.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileApache='access.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileSerialized='serial.{yyyy}-{mm}-{dd}-{hh}.txt'`*

"truncate"
- 로그 파일이 특정 크기에 도달하면 잘 있습니까? 값은 로그 파일이 잘 리기 전에 커질 가능성이있는 B/KB/MB/GB/TB 단위의 최대 크기입니다. 기본값 "0KB"은 절단을 해제합니다 (로그 파일은 무한정 확장 할 수 있습니다). 참고 : 개별 로그 파일에 적용됩니다! 로그 파일의 크기는 일괄 적으로 고려되지 않습니다.

"log_rotation_limit"
- 로그 회전은 한 번에 존재해야하는 로그 파일 수를 제한합니다. 새 로그 파일을 만들 때 총 로그, 파일 수가 지정된 제한을 초과하면, 지정된 작업이 수행됩니다. 여기서 원하는 한계를 지정할 수 있습니다. 값 0은 로그 회전을 비활성화합니다.

"log_rotation_action"
- 로그 회전은 한 번에 존재해야하는 로그 파일 수를 제한합니다. 새 로그 파일을 만들 때 총 로그, 파일 수가 지정된 제한을 초과하면, 지정된 작업이 수행됩니다. 여기서 원하는 동작을 지정할 수 있습니다. Delete = 제한이 더 이상 초과되지 않을 때까지, 가장 오래된 로그 파일을 삭제하십시오. Archive = 제한이 더 이상 초과되지 않을 때까지, 가장 오래된 로그 파일을 보관 한 다음 삭제하십시오.

*기술적 설명 : 이 문맥에서 "가장 오래된"은 "최근에 수정되지 않은"을 의미합니다.*

"timeOffset"
- 귀하의 서버 시간은 로컬 시간과 일치하지 않는 경우, 당신의 요구에 따라 시간을 조정하기 위해, 당신은 여기에 오프셋을 지정할 수 있습니다. 하지만 그 대신에 일반적으로 시간대 지시문 (당신의`php.ini` 파일)을 조정 る 것이 좋습니다,하지만 때때로 (같은 제한 공유 호스팅 제공 업체에서 작업 할 때) 이것은 무엇을하는 것이 항상 가능하지는 않습니다 따라서이 옵션은 여기에서 볼 수 있습니다. 오프셋 분이며 있습니다.
- 예 (1 시간을 추가합니다) : `timeOffset=60`

"timeFormat"
- CIDRAM에서 사용되는 날짜 형식. Default (기본 설정) = `{Day}, {dd} {Mon} {yyyy} {hh}:{ii}:{ss} {tz}`.

"ipaddr"
- 연결 요청의 IP 주소를 어디에서 찾을 것인가에 대해 (Cloudflare 같은 서비스에 대해 유효). Default (기본 설정) = REMOTE_ADDR. 주의 : 당신이 무엇을하고 있는지 모르는 한이를 변경하지 마십시오.

"forbid_on_block"
- 요청을 차단할 때 CIDRAM이 전송해야하는 HTTP 상태 메시지는 무엇입니까?

현재 지원되는 값 :

상태 코드 | 상태 메시지 | 설명
---|---|---
`200` | `200 OK` | 기본값. 강력하지는 않지만 사용자에게는 친숙합니다.
`403` | `403 Forbidden` | 좀 더 강력하지만 사용자에게는 덜 친숙합니다.
`410` | `410 Gone` | 일부 브라우저는이 상태 메시지를 캐시하고 이후에 더 이상 요청을 보내지 않습니다. 이로 인해 가양 성을 해결하기가 어려울 수 있습니다. 특정 특정 유형의 봇으로부터의 요청을 줄이기 위해 다른 옵션보다 유용 할 수 있습니다.
`418` | `418 I'm a teapot` | 이것은 실제로 만우절 농담을 언급합니다 [[RFC 2324](https://tools.ietf.org/html/rfc2324#section-6.5.14)]. 그것은 고객이 이해하지 못할 것입니다. 놀이와 편의를 위해 제공되지만 일반적으로 추천하지는 않습니다.
`451` | `Unavailable For Legal Reasons` | 주로 법적인 이유로 요청이 차단 된 경우 컨텍스트에 적합합니다. 다른 상황에서는 권장되지 않습니다.
`503` | `Service Unavailable` | 가장 견고하지만 사용자에게는 가장 친숙하지 않습니다.

"silent_mode"
- "액세스 거부"페이지를 표시하는 대신 CIDRAM는 차단 된 액세스 시도를 자동으로 리디렉션해야합니까? 그렇다면 리디렉션 위치를 지정합니다. 아니오의 경우이 변수를 비워 둡니다.

"lang"
- CIDRAM의 기본 언어를 설정합니다.

"numbers"
- 숫자를 표시하는 방법을 지정합니다.

현재 지원되는 값 :

값 | 생산하다 | 설명
---|---|---
`NoSep-1` | `1234567.89`
`NoSep-2` | `1234567,89`
`Latin-1` | `1,234,567.89` | 기본값.
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

*노트 : 이 값은 패키지 외에도 관련이 없습니다. 또한, 지원되는 값은 앞으로 변경 될 수 있습니다.*

"emailaddr"
- 여기에 이메일 주소를 입력하고 사용자가 차단 된 경우 사용자에게 보낼 수 있습니다. 이것은 지원과 지원에 사용할 수 있습니다 (실수로 차단 된 경우 등). 경고 : 여기에 입력 된 전자 이메일 주소는 아마 스팸 로봇에 의해 취득됩니다. 여기에서 제공되는 전자 이메일 주소는 모든 일회용하는 것이 좋습니다 (예를 들어, 기본 개인 주소 또는 비즈니스 주소를 사용하지 않는 등).

"emailaddr_display_style"
- 사용자에게 전자 메일 주소를 어떻게 표시 하시겠습니까? "default" = 클릭 가능한 링크. "noclick" = 클릭 할 수없는 텍스트.

"disable_cli"
- CLI 모드를 해제 하는가? CLI 모드 (시에루아이 모드)는 기본적으로 활성화되어 있지만, 테스트 도구 (PHPUnit 등) 및 CLI 기반의 응용 프로그램과 간섭하는 가능성이 없다고는 단언 할 수 없습니다. CLI 모드를 해제 할 필요가 없으면이 데레쿠티부 무시 받고 괜찮습니다. `false`(거짓) = CLI 모드를 활성화합니다 (Default / 기본 설정); `true`(참된) = CLI 모드를 해제합니다.

"disable_frontend"
- 프론트 엔드에 대한 액세스를 비활성화하거나? 프론트 엔드에 대한 액세스는 CIDRAM을 더 쉽게 관리 할 수 있습니다. 상기 그것은 또한 잠재적 인 보안 위험이 될 수 있습니다. 백엔드를 통해 관리하는 것이 좋습니다,하지만 이것이 불가능한 경우 프론트 엔드에 대한 액세스를 제공. 당신이 그것을 필요로하지 않는 한 그것을 해제합니다. `false`(거짓) = 프론트 엔드에 대한 액세스를 활성화합니다; `true`(참된) = 프론트 엔드에 대한 액세스를 비활성화합니다 (Default / 기본 설정).

"max_login_attempts"
- 로그인 시도 횟수 (프론트 엔드). Default (기본 설정) = 5.

"FrontEndLog"
- 프론트 엔드 로그인 시도를 기록하는 파일. 파일 이름 지정하거나 해제하려면 비워하십시오.

"ban_override"
- "infraction_limit"를 초과하면 "forbid_on_block"를 덮어 쓰시겠습니까? 덮어 쓸 때 : 차단 된 요청은 빈 페이지를 반환합니다 (템플릿 파일은 사용되지 않습니다). 200 = 덮어 쓰지 (Default / 기본 설정). 다른 값은 "forbid_on_block"에 사용할 수있는 값과 같습니다.

"log_banned_ips"
- 금지 된 IP에서 차단 된 요청을 로그 파일에 포함됩니까? True = 예 (Default / 기본 설정); False = 아니오.

"default_dns"
- 호스트 이름 검색에 사용하는 DNS (도메인 이름 시스템) 서버의 쉼표로 구분 된 목록입니다. Default (기본 설정) = "8.8.8.8,8.8.4.4" (Google DNS). 주의 : 당신이 무엇을하고 있는지 모르는 한이를 변경하지 마십시오.

*참조 : ["default_dns"에 사용할 수있는 항목은 무엇입니까?](#WHAT_CAN_I_USE_FOR_DEFAULT_DNS)*

"search_engine_verification"
- 검색 엔진의 요청을 확인해야합니까? 검색 엔진을 확인하여, 위반의 최대 수를 초과했기 때문에 검색 엔진이 금지되지 않는 것이 보증됩니다 (검색 엔진을 금지하는 것은 일반적으로 검색 엔진 순위의, SEO 등에 악영향을 미칩니다). 확인되면, 검색 엔진이 차단 될 수 있지만, 그러나 금지되지 않습니다. 검증되지 않은 경우는, 위반의 최대를 초과 한 결과, 금지 될 수 있습니다. 또한 검색 엔진의 검증은 사칭 된 검색 엔진으로부터 보호합니다 (이러한 요청은 차단됩니다). True = 검색 엔진의 검증을 활성화한다 (Default / 기본 설정); False = 검색 엔진의 검증을 무효로한다.

"protect_frontend"
- CIDRAM 의해 보통 제공되는 보호를 프론트 엔드에 적용할지 여부를 지정합니다. True = 예 (Default / 기본 설정); False = 아니오.

"disable_webfonts"
- 웹 글꼴을 사용하지 않도록 설정 하시겠습니까? True = 예 (Default / 기본 설정); False = 아니오.

"maintenance_mode"
- 유지 관리 모드를 사용 하시겠습니까? True = 예; False = 아니오 (Default / 기본 설정). 프런트 엔드 이외의 모든 것을 비활성화합니다. CMS, 프레임 워크 등을 업데이트 할 때 유용합니다.

"default_algo"
- 향후 모든 암호와 세션에 사용할 알고리즘을 정의합니다. 옵션 : PASSWORD_DEFAULT (default / 기본 설정), PASSWORD_BCRYPT, PASSWORD_ARGON2I (PHP >= 7.2.0 가 필요합니다).

"statistics"
- CIDRAM 사용 통계를 추적합니까? True = 예; False = 아니오 (Default / 기본 설정).

"force_hostname_lookup"
- 호스트 이름 검색을 시행 하시겠습니까 (모든 요청)? True = 예; False = 아니오 (Default / 기본 설정). 호스트 이름 검색은 일반적으로 "필요에 따라"수행됩니다, 그러나 모든 요청에 대해 강제 될 수 있습니다. 이는 로그 파일에보다 자세한 정보를 제공하는 데 유용 할 수 있습니다, 그러나 또한 성능에 약간 부정적인 영향을 줄 수 있습니다.

"allow_gethostbyaddr_lookup"
- UDP를 사용할 수 없을 때 gethostbyaddr 검색을 허용 하시겠습니까? True = 예 (Default / 기본 설정); False = 아니오.
- *참고 : 일부 32 비트 시스템에서는 IPv6 조회가 제대로 작동하지 않을 수 있습니다.*

"hide_version"
- 로그 및 페이지 출력에서 버전 정보 숨기기? True = 예; False = 아니오 (Default / 기본 설정).

#### "signatures" (카테고리)
서명 설정.

"ipv4"
- IPv4의 서명 파일 목록 (CIDRAM는 이것을 사용합니다). 이것은 쉼표로 구분되어 있습니다. 필요에 따라 항목을 추가 할 수 있습니다.

"ipv6"
- IPv6의 서명 파일 목록 (CIDRAM는 이것을 사용합니다). 이것은 쉼표로 구분되어 있습니다. 필요에 따라 항목을 추가 할 수 있습니다.

"block_cloud"
- 클라우드 서비스에서 CIDR을 차단해야합니까? 당신의 웹 사이트에서 API 서비스를 운영하거나 당신이 웹 사이트 간 연결이 예상되는 경우, 이것은 false로 설정해야합니다. 없는 경우에는이를 true로 설정해야합니다.

"block_bogons"
- Bogon/화성 CIDR을 차단해야합니까? 당신은 로컬 호스트에서 또는 귀하의 LAN에서 로컬 네트워크에서 연결을 수신 한 경우, 이것은 false로 설정해야합니다. 없는 경우에는이를 true로 설정해야합니다.

"block_generic"
- 일반적인 CIDR을 차단해야합니까? (다른 옵션과 관련되지 않은).

"block_legal"
- 법적 의무에 대응하여 CIDR을 차단 하시겠습니까? CIDRAM은 어떤 CIDR을 기본적으로 "법적 의무에" 연결할 수 없기 때문에이 지시문은 일반적으로 효과가 없습니다. 하지만, 법적 이유로 존재할 가능성이있는 모든 사용자 정의 시그니처 파일 또는 모듈의 이익을위한 추가 제어 수단으로 존재한다.

"block_malware"
- 멀웨어와 관련된 IP를 차단 하시겠습니까? 여기에는 C&C 서버, 감염된 시스템, 맬웨어 배포와 관련된 컴퓨터 등이 포함됩니다.

"block_proxies"
- 프록시 서비스 또는 VPN에서 CIDR을 차단해야합니까? 프록시 서비스 또는 VPN이 필요한 경우는, false로 설정해야합니다. 없는 경우에는 보안을 향상시키기 위해이를 true로 설정해야합니다.

"block_spam"
- 스팸 때문에 CIDR을 차단해야합니까? 문제가있는 경우를 제외하고 일반적으로이를 true로 설정해야합니다.

"modules"
- IPv4/IPv6 서명을 체크 한 후로드 모듈 파일의 목록입니다. 이것은 쉼표로 구분되어 있습니다.

"default_tracktime"
- 모듈에 의해 금지 된 IP를 추적하는 초. Default (기본 설정) = 604800 (1 주).

"infraction_limit"
- IP가 IP 추적에 의해 금지되기 전에 발생하는 것이 허용된다 위반의 최대 수. Default (기본 설정) = 10.

"track_mode"
- 위반은 언제 계산해야합니까? False = IP가 모듈에 의해 차단되는 경우. True = 뭐든지 이유로 IP가 차단 된 경우.

#### "recaptcha" (카테고리)
사용자에게 reCAPTCHA 인스턴스를 완성하여 "액세스 거부"페이지를 우회하는 방법을 제공 할 수 있습니다. 이것은 잘못된 반응과 관련된 몇 가지 위험을 완화하는 데 도움이됩니다 (요청 기계 또는 인간에서 발생한 것인지 여부는 알 수없는 경우).

"액세스 거부"페이지를 우회 할 위험성이 있습니다. 따라서 일반적으로 필요한 경우를 제외하고는이 기능을 사용하는 것은 권장하지 않습니다. 그것이 필요한 상황 : 사용자는 당신의 웹 사이트에 액세스 할 수 있습니다,하지만 그들은 적대적인 네트워크에서 연결하고 있습니다 그리고 이것은 협상 할 수 없습니다; 사용자는 액세스가 필요합니다 적대적인 네트워크를 거절 할 필요가있다 (무엇을해야합니까?!).. 이러한 상황에서는 reCAPTCHA 기능이 도움이 될 수 있습니다 : 사용자는 권한을 가질 수 있습니다; 불필요한 트래픽을 필터링 할 수 있습니다 (일반적으로). 인간 이외의 트래픽에 대해서만 유효합니다 (예를 들어, 스팸 로봇, 스크레이퍼, 해킹 툴, 자동 교통 등)하지만, 인간의 트래픽에별로 도움이되지 않는다 (예를 들어, 인간의 스패머, 해커, 기타).

"site key"와 "secret key"를 얻기 위해 (reCAPTCHA를 사용하는 데 필요한)이 링크를 클릭하십시오 : [https://developers.google.com/recaptcha/](https://developers.google.com/recaptcha/)

"usemode"
- reCAPTCHA를 CIDRAM에서 사용하는 방법.
- 0 = reCAPTCHA는 비활성화되어 있습니다 (Default / 기본 설정).
- 1 = reCAPTCHA는 모두를 위해 서명이 활성화되어 있습니다.
- 2 = 특별히 표시된 섹션의 경우에만 reCAPTCHA가 활성화됩니다.
- (그렇지 값은 0과 같습니다).

"lockip"
- reCAPTCHA를 IP로 잠금 하시겠습니까? False = 쿠키와 해시는 여러 IP에서 사용할 수 있습니다 (Default / 기본 설정). True = 쿠키와 해시는 여러 IP에서 사용할 수 없습니다 (쿠키와 해시는 IP에 잠겨 있습니다).
- 주의 : "lockuser"이 "false"인 경우 "lockip"값은 무시됩니다. 이것은 사용자를 기억 메커니즘이 값에 의존하기 때문입니다.

"lockuser"
- reCAPTCHA를 사용자에 잠금 하시겠습니까? False = reCAPTCHA 완료하여 책임있는 IP (참고 : 사용자가 아닌) 에서 발생 된 모든 요청에 대한 액세스가 허용됩니다; 쿠키와 해시는 사용되지 않습니다; IP 허용 목록이 사용됩니다. True = reCAPTCHA 완료하여 책임있는 사용자 (참고 : IP가 아닌) 에서 발생 된 모든 요청에 대한 액세스가 허용됩니다; 쿠키와 해시는 고객을 기억하기 위해 사용됩니다; IP 화이트리스트는 사용되지 않습니다 (Default / 기본 설정).

"sitekey"
- 이 값은 당신의 reCAPTCHA에 대한 "site key"에 대응하고있을 필요가 있습니다; 이것은 reCAPTCHA 대시 보드에서 찾을 수 있습니다.

"secret"
- 이 값은 당신의 reCAPTCHA에 대한 "secret key"에 대응하고있을 필요가 있습니다; 이것은 reCAPTCHA 대시 보드에서 찾을 수 있습니다.

"expiry"
- "lockuser"이 "true"때(Default / 기본 설정), reCAPTCHA 인스턴스의 합격/불합격 상태를 기억하고 미래의 페이지 요청 용 CIDRAM 해시를 포함한 표준 HTTP Cookie를 생성합니다; 이 해시는 동일한 해시를 포함한 내부 레코드에 해당합니다; 미래의 페이지 요청은 해당 해시를 사용하여 합격/불합격 상태를 인증합니다. "lockuser"이 "false"때 요청을 허용 할 필요가 있는지 여부를 판단하기 위해 IP 허용 목록이 사용됩니다; reCAPTCHA 인스턴스가 성공적으로 전달되면이 화이트리스트에 항목이 추가됩니다. 이러한 쿠키 해시 화이트리스트 항목은 몇 시간 유효해야하나요? Default (기본 설정) = 720 (1 개월).

"logfile"
- reCAPTCHA 시도 기록. 파일 이름 지정하거나 해제하려면 비워하십시오.

*유용한 팁 : 당신이 원하는 경우 로그 파일 이름에 날짜/시간 정보를 부가 할 수 있습니다 이름 이들을 포함하여 : 전체 연도에 대한 `{yyyy}`생략 된 년간 `{yy}`달 `{mm}`일 `{dd}`시간 `{hh}`.*

*예 :*
- *`logfile='recaptcha.{yyyy}-{mm}-{dd}-{hh}.txt'`*

"signature_limit"
- reCAPTCHA 인스턴스가 제공 될 때 트리거 될 수있는 최대 서명 수입니다. Default (기본 설정) = 1. 특정 요청에 대해이 수가 초과되면, reCAPTCHA 인스턴스가 제공되지 않습니다.

"api"
- 어떤 API를 사용할 수 있습니까? V2 또는 Invisible?

*유럽 연합 사용자를위한 참고 사항 : 쿠키를 사용하도록 CIDRAM을 구성한 경우 (예 : lockuser가 true 인 경우), 쿠키 경고가 [EU 쿠키 법안](http://ec.europa.eu/ipg/basics/legal/cookies/index_en.htm)의 요구 사항에 따라 페이지에 눈에 띄게 표시됩니다. 그러나, invisible API를 사용할 때 CIDRAM은 자동으로 사용자의 reCAPTCHA를 완료하려고 시도합니다. 성공하면 페이지가 다시로드되고 실제로 쿠키 경고를 볼 적절한 시간이 주어지지 않고 쿠키가 생성 될 수 있습니다. 이 문제로 인해 법적 위험이있는 경우, invisible API 대신 V2 API를 사용하는 것이 좋습니다 (V2 API는 자동화되어 있지 않으므로, 사용자가 reCAPTCHA 챌린지를 완료해야하므로 쿠키 경고를 볼 수 있습니다).*

#### "legal" (카테고리)
법적 요구 사항과 관련된 구성.

*법적 요구 사항 및 이것이 구성 요구 사항에 미치는 영향에 대한 자세한 내용은 설명서의 "[법률 정보](#SECTION11)"절을 참조하십시오.*

"pseudonymise_ip_addresses"
- 로그 파일을 쓸 때 가명으로하다 IP 주소? True = 예; False = 아니오 (Default / 기본 설정).

"omit_ip"
- 로그에서 IP 주소를 생략 하시겠습니까? True = 예; False = 아니오 (Default / 기본 설정). 참고 : "pseudonymise_ip_addresses"는 "omit_ip"가 "true"일 때 중복됩니다.

"omit_hostname"
- 로그에서 호스트 이름을 생략 하시겠습니까? True = 예; False = 아니오 (Default / 기본 설정).

"omit_ua"
- 로그에서 사용자 에이전트를 생략 하시겠습니까? True = 예; False = 아니오 (Default / 기본 설정).

"privacy_policy"
- 생성 된 페이지의 꼬리말에 표시 할 관련 개인 정보 정책 방침의 주소입니다. URL 지정, 또는 사용하지 않으려면 비워 두십시오.

#### "template_data" (카테고리)
템플릿과 테마 지시어와 변수.

템플릿의 데이터는 사용자를위한 액세스 거부 메시지를 HTML 형식으로 출력 할 때 사용됩니다. 사용자 지정 테마를 사용하는 경우는`template_custom.html`를 사용하고, 그렇지 않은 경우는`template.html`를 사용하여 HTML 출력이 생성됩니다. 설정 파일에서이 섹션의 변수는 HTML 출력에 대한 해석되어로 둘러싸인 변수 이름은 해당 변수 데이터로 대체합니다. 예를 들어`foo="bar"`하면 HTML 출력의`<p>{foo}</p>`는`<p>bar</p>`입니다.

"theme"
- CIDRAM에 사용할 기본 테마.

"Magnification"
- 글꼴 배율. Default (기본 설정) = 1.

"css_url"
- 사용자 정의 테마 템플릿 파일은 외부 CSS 속성을 사용합니다. 한편, 기본 테마는 내부 CSS입니다. 사용자 정의 테마를 적용하는 CSS 파일의 공개적 HTTP 주소를 "css_url"변수를 사용하여 지정하십시오. 이 변수가 공백이면 기본 테마가 적용됩니다.

---


### 7. <a name="SECTION7"></a>서명 포맷

*참조 :*
- *["서명"이란 무엇입니까?](#WHAT_IS_A_SIGNATURE)*

#### 7.0 기초 (서명 파일의 경우)

CIDRAM에서 사용되는 서명의 형식과 구조에 대한 설명은 사용자 정의 시그니처 파일에 기재되어 있습니다. 자세한 내용은 문서를 참조하십시오.

모든 IPv4 서명이 형식을 따릅니다 : `xxx.xxx.xxx.xxx/yy [기능] [매개 변수]`
- `xxx.xxx.xxx.xxx`는 CIDR 블록의 시작을 나타냅니다 (블록의 첫 번째 IP 주소 옥텟).
- `yy`는 블록 사이즈를 나타냅니다 (1-32).
- `[기능]`스크립트에 서명을 어떻게 처리를 지시합니다.
- `[매개 변수]`은 `[기능]`에 필요, 추가 정보를 나타냅니다.

모든 IPv6 서명이 형식을 따릅니다 : `xxxx:xxxx:xxxx:xxxx::xxxx/yy [기능] [매개 변수]`
- `xxxx:xxxx:xxxx:xxxx::xxxx`는 CIDR 블록의 시작을 나타냅니다 (블록의 첫 번째 IP 주소 옥텟). 전체 표현 및 약식 표기가 모두 가능합니다. 그들은 IPv6 사양을 준수해야합니다 하나의 예외를 제외하고 : CIDRAM에서는 IPv6 주소는 생략로 시작할 수 없습니다. 예를 들면 : `::1/128`는 `0::1/128`, 그리고 `::0/128`는 `0::/128`로 표시해야합니다.
- `yy`는 블록 사이즈를 나타냅니다 (1-128).
- `[기능]`스크립트에 서명을 어떻게 처리를 지시합니다.
- `[매개 변수]`은 `[기능]`에 필요, 추가 정보를 나타냅니다.

서명 파일의 줄 바꿈은 Unix 표준을 사용해야합니다 (`%0A`, `\n`). 다른 표준도 사용할 수 있지만 권장되지 않습니다 (예를 들어, Windows `%0D%0A`, `\r\n`, Mac `%0D`, `\r`, 등). 비 Unix 개행는 정규화됩니다.

정확하고 올바른 CIDR 표기법이 필요합니다. 스크립트는 부정확 한 표기 (또는 부정확 한 표기를 따른 서명)을 인식하지 않습니다. 또한 모든 CIDR은 균등하게 나눌 필요가 있습니다 (예를 들어, `10.128.0.0`에서`11.127.255.255`까지 모두 차단하려는 경우, `10.128.0.0/8`는 스크립트가 인식되지 않습니다, 그러나, `10.128.0.0/9`자 `11.0.0.0/9`를 함께 사용하면 스크립트에 의해 인식됩니다).

스크립트가 인식되지 않는 시그니처 파일의 것은 무시됩니다. 즉, 서명 파일을 손상없이 거의 모든 데이터를 서명 파일에 안전하게 넣을 수 있습니다. 서명 파일의 댓글은 허용입니다. 특별한 서식이 필요하지 않습니다. 쉘 형식의 해시가 권장되지만 강제는되지 않습니다 (쉘 스타일 해시는 IDE이나 일반 텍스트 편집기에 도움이됩니다).

"기능"의 가능한 값 :
- Run
- Whitelist
- Greylist
- Deny

"Run"을 사용하면 서명이 트리거되면 스크립트는`require_once` 문이 (`[매개 변수]` 값으로 지정됩니다) 외부의 PHP 스크립트의 실행을 시도합니다. 작업 디렉토리는 `/vault/` 디렉토리입니다.

예 : `127.0.0.0/8 Run example.php`

특정 IP/CIDR에 대해 특정 PHP 코드를 실행하는 경우에 유용합니다.

"Whitelist"를 사용하면 서명이 트리거되면 스크립트는 모든 검색을 재설정합니다 (뭔가의 검출이 있었을 경우) 테스트 기능을 종료합니다. `[매개 변수]`는 무시됩니다. 이것은 IP 또는 CIDR 화이트리스트에 등록하는 것과 같습니다.

예 : `127.0.0.1/32 Whitelist`

"Greylist"을 사용하면 서명이 트리거되면 스크립트는 모든 검색을 재설정합니다 (뭔가의 검출이 있었을 경우) 처리를 계속하기 위해 다음의 서명 파일로 바로 이동한다. `[매개 변수]`는 무시됩니다.

예 : `127.0.0.1/32 Greylist`

"Deny"를 사용하면 서명이 트리거되면 보호 된 페이지에 대한 액세스가 거부됩니다 (IP/CIDR 화이트리스트에 등록되어 있지 않은 경우). "Deny"실제로 IP 주소와 CIDR 범위를 차단하기 위해 사용하는 것입니다. "Deny"를 사용하는 서명이 트리거 될 때 "액세스 거부"페이지가 생성되고 보호 된 페이지에 대한 요청을 종료합니다.

"Deny"에 의해 받아 들여진 `[매개 변수]`값은 "액세스 거부"페이지 출력 처리됩니다 요청 된 페이지에 대한 액세스가 거부 된 이유는 클라이언트/사용자에게 제공됩니다. 그것은 짧고 간단한 문장을 할 수 있습니다 (왜 그들을 차단하는 것을 선택했는지 설명하기 위해). 또한 축약 할 수 있습니다 (사전 준비된 설명을 클라이언트/사용자에게 제공합니다).

미리 준비된 설명은 L10N의 지원이 스크립트로 번역 할 수 있습니다. 번역은 스크립트 구성의`lang` 지시문을 사용하여 지정된 언어에 따라 이루어집니다. 또한 이러한 단축형 단어를 사용하는 경우 `[매개 변수]`값에 따라 "Deny"서명을 무시하도록 스크립트에 지시 할 수 있습니다. 이것은 스크립트 설정에 지정된 지시어를 통해 이루어집니다 (각각의 약어에 해당하는 지시문이 있습니다). 그러나 다른 `[매개 변수]`값은 L10N이 지원되지 않습니다 (따라서 다른 값은 번역되지 않습니다, 그리고 조직에 의해 통제 가능하지 않다).

약어 :
- Bogon
- Cloud
- Generic
- Proxy
- Spam
- Legal
- Malware

#### 7.1 태그

##### 7.1.0 섹션 태그

섹션 이름 "섹션 태그"를 추가하여 스크립트에 대한 개별 섹션을 식별 할 수 있습니다 (아래의 예를 참조하십시오).

```
# 섹션 1.
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud
4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
6.7.8.9/32 Deny Proxy
Tag: 섹션 1
```

섹션 태그를 종료하고 서명 섹션이 잘못 식별되지 않도록하려면 태그와 이전 섹션 사이에 적어도 2 개의 줄이 연속 있는지 확인하십시오. 태그없이 서명은 기본적으로 "IPv4"또는 "IPv6"중 하나입니다 (어떤 유형의 서명이 트리거되어 있는지에 따라).

```
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud

4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: 섹션 1
```

위의 예에서`1.2.3.4/32`와 `2.3.4.5/32`은 "IPv4"태그됩니다; 니다 한편 `4.5.6.7/32`와 `5.6.7.8/32`은 "섹션 1"태그됩니다.

다른 유형의 태그를 분리하는 데에도 같은 논리를 적용 할 수 있습니다.

섹션 태그는 오 탐지 (false positive)가 발생할 때 문제의 정확한 원인을 쉽게 찾을 수있는 방법을 제공하여 디버깅에 매우 유용합니다. 프런트 엔드 로그 페이지를 통해 로그 파일을 볼 때 로그 파일 항목 필터링에 매우 유용 할 수 있습니다 (섹션 이름은 프런트 엔드 로그 페이지를 통해 클릭 할 수 있으며 필터링 기준으로 사용할 수 있습니다). 특정 서명에 대해 섹션 태그가 생략 된 경우 해당, 서명이 트리거되면, CIDRAM은 차단 된 IP 주소 유형 (IPv4 또는 IPv6)과 함께 서명 파일의 이름을 폴백으로 사용합니다. 따라서 섹션 태그는 전적으로 선택 사항입니다. 서명 파일이 모호하게 이름이 지정된 경우 또는, 요청을 차단하도록 만드는 서명의 출처를 명확하게 식별하기 어려운 경우와 같이, 일부 경우에 권장 될 수 있습니다.

##### 7.1.1 기한 태그

"기한 태그"를 사용하여 서명의 유효 기간을 지정할 수 있습니다. 만료 된 태그가이 형식을 사용합니다 : "년년년년.월월.날날" (아래의 예를 참조하십시오).

```
# 섹션 1.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Expires: 2016.12.31
```

만료 된 서명은 요청에 대해 트리거되지 않습니다.

##### 7.1.2 원산지 태그

특정 서명의 원산지를 지정하려면 "원산지 태그"를 사용하십시오. 원산지 태그는 "[ISO 3166-1 Alpha-2](https://ko.wikipedia.org/wiki/ISO_3166-1_alpha-2)" 코드를 허용합니다. 이 코드는 해당 서명의 원산지 국가에 해당합니다. 이 코드는 대문자로 작성해야합니다 (소문자 또는 대소 문자가 혼합되어 올바르게 표시되지 않습니다). 원산지 태그가 사용되면 관련 차단 요청에 대한 "왜 차단이 되셨나요"로그 필드 항목에 추가됩니다.

선택적 "flags CSS"구성 요소가 설치된 경우, 프런트 엔드 로그 페이지를 통해 로그 파일을 볼 때 원본 태그로 추가 된 정보가 해당 국가 플래그로 바뀝니다. 이 정보는 원시 형식이든 국가 국기이든간에 클릭 할 수 있습니다. 클릭하면, 비슷한 로그 항목에 따라 로그 항목이 필터링됩니다 (이로 인해 원산지별로 필터링 할 수 있습니다).

참고 : 기술적으로 이것은 특정 위치 정보를 적극적으로 찾지 않기 때문에 위치 정보가 아닙니다. 대신 특정 국가의 서명을 원산지 국가에 명시 할 수 있습니다. 동일한 서명 섹션 내에서 여러 개의 원산지 태그를 사용할 수 있습니다.

가설적인 예 :

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

모든 태그를 함께 사용할 수 있으며 모든 태그는 선택 사항입니다 (아래의 예를 참조하십시오).

```
# 예 섹션.
1.2.3.4/32 Deny Generic
Origin: US
Tag: 예 섹션
Expires: 2016.12.31
```

#### 7.2 YAML

##### 7.2.0 YAML 기초

섹션 관련 설정을 정의하기 위해, 간단한 형식의 YAML 마크 업을 서명 파일로 사용할 수 있습니다. 이것은 다른 서명 섹션에 대해 다른 설정을 할 때 유용합니다 (예를 들면 : 지원 티켓의 이메일 주소를 지정하려면, 그러나 특정 섹션 만; 특정 서명으로 페이지 리디렉션을 트리거하려면; reCAPTCHA에서 사용하기 위해 서명 섹션을 표시하려면; 개별 서명에 따라 그리고/또는 서명 섹션에 따라, 차단 된 액세스 시도를 별도의 파일에 기록하려면).

서명 파일로 YAML 마크 업의 사용은 옵션입니다 (즉, 당신이 원한다면 그것을 사용할 수 있지만 그렇게 할 필요는 없습니다). 대부분의 (하지만 전부는 아니지만) 구성 지시문을 활용할 수 있습니다.

주의 : CIDRAM의 YAML 마크 업의 구현은 매우 단순화되어 매우 제한되어 있습니다. 이것은 YAML 마크 업에 정통한 방법으로하지만 공식 규격을 따르지하거나 준수 할 수없는 CIDRAM의 특정 요구 사항을 충족하기위한 것입니다 (다른 구현과 같은 방식으로 작동하지 않을 가능성이 있고, 다른 프로젝트에 적합하지 않을 수 있습니다).

CIDRAM는 YAML 마크 업 세그먼트는 스크립트에 3 개의 대시 ("---") 식별됩니다. YAML 마크 업 세그먼트는 이중 줄 바꿈에 의해 서명 섹션과 함께 종료합니다. 전형적인 세그먼트는 CIDR 및 태그 목록의 직후의 행에 3 개의 대시로 구성되며 이어 2 차원의 키와 값 쌍의 목록이 나옵니다. (첫 번째 차원은 지시어의 카테고리입니다; 두 번째 차원은 설정 지시어입니다). 다음의 예를 참조하십시오.

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

##### 7.2.1 reCAPTCHA에서 사용하기 위해 서명 섹션을 표시하는 방법.

"usemode"가 0 또는 1의 경우 reCAPTCHA에서 사용하기 위해 서명 섹션을 표시 할 필요가 없습니다 (reCAPTCHA를 사용할지 여부는 이미 결정되어 있기 때문입니다).

"usemode"가 2이면 reCAPTCHA에서 사용하기 위해 서명 섹션을 표시하려면 시그니처 섹션 YAML 마커 세그먼트를 포함해야합니다 (아래의 예를 참조하십시오).

```
# 이 섹션에서는 reCAPTCHA를 사용합니다.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Tag: reCAPTCHA-Enabled
---
recaptcha:
 enabled: true
```

*주의 : 기본적으로, reCAPTCHA 인스턴스는 reCAPTCHA가 유효한 경우에만 사용자에게 제공됩니다 ("usemode"가 1 일 때, 또는 "usemode"가 2 "enabled"이 "true"인 경우), 그리고, 정확히 하나의 서명이 트리거 된 경우 (큰 수없는 작은 수 없다; 여러 서명이 트리거 된 경우 reCAPTCHA 인스턴스는 제공되지 않습니다). 그러나이 동작은 "signature_limit"지시문을 통해 수정할 수 있습니다.*

#### 7.3 보조

또한 CIDRAM 특정 서명 섹션을 완전히 무시하려는 경우 `ignore.dat`파일을 사용하여 무시하는 섹션을 지정할 수 있습니다. 새로운 행에`Ignore`과 써주세요 다음, 공간, 그런 CIDRAM 무시하는 섹션의 이름 (다음의 예를 참조하십시오).

```
Ignore 섹션 1
```

#### 7.4 <a name="MODULE_BASICS"></a>기초 (모듈 경우)

모듈은 CIDRAM의 기능을 확장하거나, 추가 작업을 수행하거나 추가 논리를 처리하는 데 사용할 수 있습니다. 일반적으로, 모듈은 원래 IP 주소 이외의 이유로 요청을 차단해야 할 때 사용됩니다 (따라서 CIDR 서명으로 요청을 차단할 수 없을 때). 모듈은 PHP 파일로 작성되므로 일반적으로 모듈 서명은 PHP 코드로 작성됩니다.

CIDRAM 모듈의 좋은 예는 다음에서 찾을 수 있습니다.
- https://github.com/CIDRAM/CIDRAM-Extras/tree/master/modules

새 모듈 작성을위한 템플릿은 다음에서 찾을 수 있습니다.
- https://github.com/CIDRAM/CIDRAM-Extras/blob/master/modules/module_template.php

모듈은 PHP 파일로 작성되기 때문에, CIDRAM 코드베이스에 대해 잘 알고 있다면, 원하는대로 모듈과 모듈 서명을 구성 할 수 있습니다 (합리적인 한도 내에서). 그러나 기존 모듈과 자신의 편의를 위해 더 나은 상호 명료성을 위해 위에서 링크 된 템플릿을 분석하여 제공되는 구조와 형식을 사용할 수 있도록하는 것이 좋습니다.

*노트 : PHP 코드 작업에 익숙하지 않은 경우 자신 만의 모듈을 작성하는 것은 좋지 않습니다.*

CIDRAM이 제공하는 일부 기능을 사용하면 모듈을 더 간단하고 쉽게 작성할 수 있습니다. 이 기능에 대한 정보는 아래에 설명되어 있습니다.

#### 7.5 모듈 기능

##### 7.5.0 "$Trigger"

모듈 서명은 일반적으로 "$Trigger"로 작성됩니다. 대부분의 경우, 모듈 작성을 목적으로이 closure 다른 어떤 것보다 중요합니다.

"$Trigger"는 4 개의 매개 변수를 허용합니다 : "$Condition", "$ReasonShort", "$ReasonLong" (선택 과목), 및 "$DefineOptions" (선택 과목).

"$Condition"의 진실성이 평가됩니다. True의 경우, 서명은 트리거됩니다. False의 경우, 서명은 트리거되지 않습니다. "$Condition"에는 대개 요청을 차단해야하는 조건을 평가하는 PHP 코드가 들어 있습니다.

서명이 트리거되면, "$ReasonShort"가 "왜 차단이 되셨나요"필드에 표시됩니다.

"$ReasonLong"은 차단되었을 때 사용자/클라이언트에게 차단 된 이유를 설명하기 위해 표시되는 선택적 메시지입니다. 생략시 표준 "액세스 거부"메시지를 사용합니다.

"$DefineOptions"는 키/값 쌍을 포함하는 선택적 배열입니다. 요청 인스턴스와 관련된 구성 옵션을 정의하는 데 사용됩니다. 구성 옵션은 서명이 트리거 될 때 적용됩니다.

"$Trigger"는 서명이 트리거되면 true를 반환하고, 그렇지 않으면 false를 반환합니다.

이 closure를 모듈에서 사용하려면 먼저 부모 범위에서 상속 받는다는 것을 기억하십시오 :
```PHP
$Trigger = $CIDRAM['Trigger'];
```

##### 7.5.1 "$Bypass"

서명 우회는 일반적으로 "$Bypass"로 작성됩니다.

"$Bypass"는 3 개의 매개 변수를 허용합니다 : "$Condition", "$ReasonShort", 및 "$DefineOptions" (선택 과목).

"$Condition"의 진실성이 평가됩니다. True의 경우, 우회가은 트리거됩니다. False의 경우, 우회가은 트리거되지 않습니다. "$Condition"에는 일반적으로 요청을 차단해서는 안되는 조건을 평가하는 PHP 코드가 들어 있습니다.

우회가이 트리거되면, "$ReasonShort"가 "왜 차단이 되셨나요"필드에 표시됩니다.

"$DefineOptions"는 키/값 쌍을 포함하는 선택적 배열입니다. 요청 인스턴스와 관련된 구성 옵션을 정의하는 데 사용됩니다. 구성 옵션은 우회가이 트리거 될 때 적용됩니다.

"$Bypass"는 우회가 트리거되면 true를, 그렇지 않으면 false를 반환합니다.

이 closure를 모듈에서 사용하려면 먼저 부모 범위에서 상속 받는다는 것을 기억하십시오 :
```PHP
$Bypass = $CIDRAM['Bypass'];
```

##### 7.5.2 "$CIDRAM['DNS-Reverse']"

IP 주소의 호스트 이름을 가져 오는 데 사용할 수 있습니다. 호스트 이름을 차단하는 모듈을 만들려면이, closure가 유용 할 수 있습니다.

예 :
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

#### 7.6 모듈 변수

모듈은 자체 범위 내에서 실행되며 모듈에 정의 된 변수는 다른 모듈이나 상위 스크립트에 액세스 할 수 없습니다 ("$CIDRAM"배열에 저장되어 있지 않으면; 모듈 실행이 끝난 후에 다른 모든 것은 비워진다).

다음은 모듈에 유용한 몇 가지 일반적인 변수입니다 :

변수 | 설명
----|----
`$CIDRAM['BlockInfo']['DateTime']` | 현재 날짜와 시간.
`$CIDRAM['BlockInfo']['IPAddr']` | 현재 요청의 IP 주소.
`$CIDRAM['BlockInfo']['ScriptIdent']` | CIDRAM 스크립트 버전.
`$CIDRAM['BlockInfo']['Query']` | 현재 요청에 대한 쿼리입니다.
`$CIDRAM['BlockInfo']['Referrer']` | 현재 요청에 대한 리퍼러 (존재하는 경우).
`$CIDRAM['BlockInfo']['UA']` | 현재 요청에 대한 사용자 에이전트입니다 (user agent).
`$CIDRAM['BlockInfo']['UALC']` | 현재 요청에 대한 소문자의 사용자 에이전트입니다 (user agent).
`$CIDRAM['BlockInfo']['ReasonMessage']` | 현재 요청이 차단 된 경우 사용자/클라이언트에게 표시 할 메시지입니다.
`$CIDRAM['BlockInfo']['SignatureCount']` | 현재 요청에 대해 트리거 된 서명 수입니다.
`$CIDRAM['BlockInfo']['Signatures']` | 현재 요청에 대해 트리거 된 모든 서명에 대한 참조 정보.
`$CIDRAM['BlockInfo']['WhyReason']` | 현재 요청에 대해 트리거 된 모든 서명에 대한 참조 정보.

---


### 8. <a name="SECTION8"></a>적합성 문제

다음 패키지 및 제품이 CIDRAM과 호환되지 않습니다.
- __[Endurance Page Cache](https://github.com/CIDRAM/CIDRAM/issues/52)__

CIDRAM과의 호환성을 보장하기 위해, 다음 패키지 및 제품에, 모듈을 사용할 수 있습니다.
- __[BunnyCDN](https://github.com/CIDRAM/CIDRAM/issues/56)__

*참조 : [호환성 차트](https://maikuolan.github.io/Compatibility-Charts/).*

---


### 9. <a name="SECTION9"></a>자주 묻는 질문 (FAQ)

- ["서명"이란 무엇입니까?](#WHAT_IS_A_SIGNATURE)
- ["CIDR"이란 무엇입니까?](#WHAT_IS_A_CIDR)
- ["거짓 양성"는 무엇입니까?](#WHAT_IS_A_FALSE_POSITIVE)
- [CIDRAM는 나라 전체를 차단할 수 있습니까?](#BLOCK_ENTIRE_COUNTRIES)
- [서명은 얼마나 자주 업데이트됩니까?](#SIGNATURE_UPDATE_FREQUENCY)
- [CIDRAM을 사용하는 데 문제가 발생했지만 무엇을 해야할지 모르겠어요! 도와주세요!](#ENCOUNTERED_PROBLEM_WHAT_TO_DO)
- [나는 CIDRAM 의해 웹 사이트에서 차단되어 있습니다! 도와주세요!](#BLOCKED_WHAT_TO_DO)
- [5.4.0보다 오래된 PHP 버전에서 CIDRAM을 사용하고 싶습니다; 도울 수 있니?](#MINIMUM_PHP_VERSION)
- [단일 CIDRAM 설치를 사용하여 여러 도메인을 보호 할 수 있습니까?](#PROTECT_MULTIPLE_DOMAINS)
- [나는 이것을 설치하거나 그것이 내 웹 사이트상에서 동작하는 것을 보장하는 시간을 보내고, 하고 싶지 않아; 그것을 할 수 있습니까? 나는 당신을 고용 할 수 있습니까?](#PAY_YOU_TO_DO_IT)
- [당신 또는 이 프로젝트의 모든 개발자는 고용 가능합니까?](#HIRE_FOR_PRIVATE_WORK)
- [나는 전문가의 변경 및 사용자 맞춤형 등이 필요합니다; 도울 수 있니?](#SPECIALIST_MODIFICATIONS)
- [나는 개발자, 웹 사이트 디자이너, 또는 프로그래머입니다. 이 프로젝트 관련 작업을 할 수 있습니까?](#ACCEPT_OR_OFFER_WORK)
- [나는 프로젝트에 공헌하고 싶다; 이것은 수 있습니까?](#WANT_TO_CONTRIBUTE)
- ["ipaddr"의 권장 값입니다.](#RECOMMENDED_VALUES_FOR_IPADDR)
- [Cron을 사용하여 자동으로 업데이트 할 수 있습니까?](#CRON_TO_UPDATE_AUTOMATICALLY)
- ["위반"이란 무엇입니까?](#WHAT_ARE_INFRACTIONS)
- [CIDRAM이 호스트 이름을 차단할 수 있습니까?](#BLOCK_HOSTNAMES)
- ["default_dns"에 사용할 수있는 항목은 무엇입니까?](#WHAT_CAN_I_USE_FOR_DEFAULT_DNS)
- [CIDRAM을 사용하여 웹 사이트 (예 : 이메일 서버, FTP, SSH, IRC, 등) 이외의 것을 보호 할 수 있습니까?](#PROTECT_OTHER_THINGS)
- [CDN 또는 캐싱 서비스를 사용하는 것과 동시에 CIDRAM을 사용하면 문제가 발생합니까?](#CDN_CACHING_PROBLEMS)
- [CIDRAM이 내 웹 사이트를 DDoS 공격으로부터 보호합니까?](#DDOS_ATTACKS)

#### <a name="WHAT_IS_A_SIGNATURE"></a>"서명"이란 무엇입니까?

CIDRAM의 맥락에서 "서명"이라 함은 지표/식별자 역할을하는 데이터이다. 우리는 그것을 사용하여 원하는 정보를 찾습니다 (일반적으로 IP 주소 또는 CIDR입니다). 종종 그것은 CIDRAM위한 어떤 명령을 포함 (응답하는 최선의 방법 등). CIDRAM의 전형적인 서명은 다음과 같습니다.

"서명 파일"의 경우 :

`1.2.3.4/32 Deny Generic`

"모듈"의 경우 :

```PHP
$Trigger(strpos($CIDRAM['BlockInfo']['UA'], 'Foobar') !== false, 'Foobar-UA', 'User agent "Foobar" not allowed.');
```

*노트 : "서명 파일"에 대한 서명과, "모듈"에 대한 서명은, 동일한 것이 아닙니다.*

종종 (그러나 항상은 아니지만) 서명은 함께 묶어 "서명 섹션"을 형성합니다. 코멘트, 마크 업 및 관련 메타 데이터를 가진 경우가 종종 있습니다 (이것은 추가 문맥 및 추가 지침을 제공하는 데 사용할 수있다).

#### <a name="WHAT_IS_A_CIDR"></a>"CIDR"이란 무엇입니까?

"CIDR"은 "Classless Inter-Domain Routing"의 두문자어 입니다 *[[1](https://ko.wikipedia.org/wiki/%EC%82%AC%EC%9D%B4%EB%8D%94_(%EB%84%A4%ED%8A%B8%EC%9B%8C%ED%82%B9)), [2](http://whatismyipaddress.com/cidr)]*. 이 두문자어 패키지의 이름의 일부로 사용됩니다 (CIDRAM). "CIDRAM"은 "Classless Inter-Domain Routing Access Manager"의 두문자어 입니다.

그러나 CIDRAM의 맥락에서 (예를 들어, 이 문서, CIDRAM 관한 논의에서, CIDRAM 언어 데이터에), "CIDR"또는 "CIDRs"이 언급 될 때마다, 우리가 의도 한 의미는 CIDR 표기법을 사용하여 표현 된 서브넷입니다. 이 목적은 우리의 의미를 분명히하기위한 것입니다 (서브넷이 여러 가지 방법으로 표현 될 수 있기 때문에). 따라서 CIDRAM은 "서브넷 액세스 관리자"로 간주 될 수 있습니다.

이 설명은 제공된 컨텍스트와 함께 모호성을 해결하는 데 도움이됩니다.

#### <a name="WHAT_IS_A_FALSE_POSITIVE"></a>"거짓 양성"는 무엇입니까?

일반화 된 상황에서 쉽게 설명 조건의 상태를 테스트 할 때 결과를 참조 할 목적으로 용어 "거짓 양성"의 (*또는 : 위양성의 오류, 허위 보도;* 영어 : *false positive*; *false positive error*; *false alarm*) 의미는 결과는 "양성"의 것, 그러나 결과는 실수 (즉, 진실의 조건은 "양성/진실"로 간주됩니다, 그러나 정말 "음성/거짓"입니다). "거짓 양성"는 "우는 늑대"와 유사하다고 생각할 수 있습니다 (그 상태는 군 근처에 늑대가 있는지 여부이다, 진실 조건은 "거짓/음성"입니다 무리의 가까이에 늑대가 없기 때문입니다하지만 조건은 "진실/양성"로보고됩니다 목자가 "늑대! 늑대!"를 외쳤다 때문입니다) 또는 의료 검사와 유사 환자가 잘못 진단 된 경우.

몇 가지 관련 용어는 "진실 양성", "진실 음성"와 "거짓 음성"입니다. 이러한 용어가 나타내는 의미 : "진실 양성"의 의미는 테스트 결과와 진실 조건이 진실입니다 (즉, "양성"입니다). "진실 음성"의 의미는 테스트 결과와 진실 조건이 거짓 (즉, "음성"입니다). "진실 양성"과 "진실 음성"는 "올바른 추론"로 간주됩니다. "거짓 양성"의 반대는 "거짓 음성"입니다. "거짓 음성"의 의미는 테스트 결과가 거짓입니다 (즉, "음성"입니다) 하지만 진실의 조건이 정말 진실입니다 (즉, "양성"입니다); 두 테스트 결과와 진실 인 조건이 "진실/양성" 해야한다 것입니다.

CIDRAM의 맥락에서 이러한 용어는 CIDRAM 서명과 그들이 차단하는 것을 말합니다. CIDRAM가 잘못 IP 주소를 차단하면 (예를 들어, 부정확 한 서명 구식의 서명 등에 의한), 우리는이 이벤트 "거짓 양성"를 호출합니다. CIDRAM가 IP 주소를 차단할 수없는 경우 (예를 들어, 예상치 못한 위협 서명 누락 등으로 인한), 우리는이 이벤트 "부재 감지"를 호출합니다 ("거짓 음성" 아날로그입니다).

이것은 다음 표에 요약 할 수 있습니다.

&nbsp; | IP 주소를 차단 필요가 CIDRAM 없습니다 | IP 주소를 CIDRAM 차단해야합니다
---|---|---
IP 주소를 CIDRAM 차단하지 않습니다 | 진실 음성 (올바른 추론) | 놓친 (그것은 "거짓 음성"와 같습니다)
IP 주소를 CIDRAM 차단합니다 | __거짓 양성__ | 진실 양성 (올바른 추론)

#### <a name="BLOCK_ENTIRE_COUNTRIES"></a>CIDRAM는 나라 전체를 차단할 수 있습니까?

예. 이것을 달성하는 가장 쉬운 방법은 Macmathan 제공하는 국가 선택적 차단 목록의 일부를 설치합니다. 이것은 프론트 엔드 업데이트 페이지에서 직접 할 수 있습니다. 또는, 프론트 엔드를 계속 사용 중지하려는 경우, **[국가 선택적 차단 목록의 다운로드 페이지](https://macmathan.info/blocklists)** 에서 다운로드 할 수 있습니다. 다운로드 후, 그들을 vault에 업로드, 관련 지시에 의해 지명하십시오.

#### <a name="SIGNATURE_UPDATE_FREQUENCY"></a>서명은 얼마나 자주 업데이트됩니까?

업 데이트 빈도는 서명 파일에 따라 다릅니다. CIDRAM 서명 파일의 모든 메인테이너가 자주 업 데이트를 시도하지만, 우리의 여러분에게는 그 밖에도 다양한 노력이있어, 우리는 프로젝트 외부에서 생활하고 있으며, 아무도 재정적으로 보상되지 않는, 따라서 정확한 업 데이트 일정은 보장되지 않습니다. 일반적으로 충분한 시간이 있으면 서명이 업 데이트됩니다. 메인테이너는 필요성과 범위 사이의 변화의 빈도에 따라 우선 순위를 내려고한다. 당신이 뭔가를 제공 할 수 있다면, 원조는 항상 높게 평가됩니다.

#### <a name="ENCOUNTERED_PROBLEM_WHAT_TO_DO"></a>CIDRAM을 사용하는 데 문제가 발생했지만 무엇을 해야할지 모르겠어요! 도와주세요!

- 당신은 최신 소프트웨어 버전을 사용하고 있습니까? 당신은 최신 서명 파일 버전을 사용하고 있습니까? 그렇지 않은 경우, 먼저 업 데이트하십시오. 문제가 해결되지 여부를 확인하십시오. 그것이 계속되면 읽어보십시오.
- 당신은 문서를 확인 했습니까? 만약 그렇지 않으면, 그렇지하십시오. 문서를 사용하여 문제를 해결할 수없는 경우, 계속 읽어보십시오.
- **[이슈 페이지를](https://github.com/CIDRAM/CIDRAM/issues)** 확인 했습니까? 문제가 이전에 언급되어 있는지 확인하십시오. 제안, 아이디어, 솔루션이 제공되었는지 여부를 확인하십시오.
- 문제가 해결되지 않으면 알려 주시기 바랍니다. 이슈 페이지에서 토론을 창조한다.

#### <a name="BLOCKED_WHAT_TO_DO"></a>나는 CIDRAM 의해 웹 사이트에서 차단되어 있습니다! 도와주세요!

CIDRAM는 웹 사이트 소유자가 원하지 않는 트래픽을 차단하는 수단을 제공합니다, 하지만 웹 사이트 소유자는 그 사용 방법을 결정해야합니다. 서명 파일에 오류 검출이있는 경우, 정정을 할 수, 그러나 특정 웹 사이트에서 차단 해제되어 관해서, 당신은 웹 사이트 소유자에게 문의해야합니다. 수정이 이루어지면 업데이트가 필요합니다. 그렇지 않으면 문제를 해결하는 것은 그들의 책임입니다. 맞춤화와 개인 선택은 전적으로 우리가 통제 할 수없는 것입니다.

#### <a name="MINIMUM_PHP_VERSION"></a>5.4.0보다 오래된 PHP 버전에서 CIDRAM을 사용하고 싶습니다; 도울 수 있니?

아니오. PHP 5.4.0은 2014 년 공식 EoL에 ("End of Life" / 삶의 끝) 도달했습니다. 2015 년에 연장 된 보안 지원이 종료되었습니다. 현재는 2017이며 PHP 7.1.0을 이미 사용할 수 있습니다. 현재, PHP 5.4.0 및 모든 더 최신 PHP 버전 CIDRAM를 사용하기위한 지원이 제공되고 있습니다. 더 오래된 PHP 버전에 대한 지원은 제공하지 않습니다.

*참조 : [호환성 차트](https://maikuolan.github.io/Compatibility-Charts/).*

#### <a name="PROTECT_MULTIPLE_DOMAINS"></a>단일 CIDRAM 설치를 사용하여 여러 도메인을 보호 할 수 있습니까?

예. CIDRAM 설치는 특정 도메인에 국한되지 않습니다, 따라서 여러 도메인을 보호하기 위해 사용할 수 있습니다. 일반적으로, 하나의 도메인 만 보호 설치 우리는 "단일 도메인 설치"이 라고 부릅니다에서 여러 도메인을 보호하는 설치 우리는 "멀티 도메인 설치"이 라고 있습니다. 다중 도메인 설치를 사용하는 경우 다른 도메인에 다른 서명 파일 세트를 사용할 필요가 있거나 다른 도메인에 CIDRAM을 다른 설정해야합니다 이것을 할 수 있습니다. 설정 파일을로드 한 후 (`config.ini`), CIDRAM 요청 된 도메인의 "구성 재정 파일"의 존재를 확인합니다 (`xn--hq1bngz0pl7nd2aqft27a.tld.config.ini`), 그리고 발견 된 경우, 구성 재정 파일에 의해 정의 된 구성 값은 설정 파일에 의해 정의 된 구성 값이 아니라 실행 인스턴스에 사용됩니다. 구성 재정 파일은 설정 파일과 동일합니다. 귀하의 재량에 따라 CIDRAM에서 사용할 수있는 모든 구성 지시문 전체 또는 필요한 하위 섹션을 포함 할 수 있습니다. 구성 재정 파일은 그들이 의도하는 도메인에 따라 지정됩니다 (그래서 예를 들면, 도메인 `http://www.some-domain.tld/` 컨피규레이션 재정 파일이 필요한 경우, 구성 재정 파일의 이름은 `some-domain.tld.config.ini` 할 필요가 있습니다. 일반 구성 파일과 동일한 위치에 보관해야합니다). 도메인 이름은 `HTTP_HOST` 에서옵니다. "www"는 무시됩니다.

#### <a name="PAY_YOU_TO_DO_IT"></a>나는 이것을 설치하거나 그것이 내 웹 사이트상에서 동작하는 것을 보장하는 시간을 보내고, 하고 싶지 않아; 그것을 할 수 있습니까? 나는 당신을 고용 할 수 있습니까?

아마. 이는 사례별로 검토되고 있습니다. 당신의 요구로 제공할 수 있는 것을 가르쳐주세요. 우리가 도울 수 있는지를 가르쳐주고 있습니다.

#### <a name="HIRE_FOR_PRIVATE_WORK"></a>당신 또는 이 프로젝트의 모든 개발자는 고용 가능합니까?

*위를 참조하십시오.*

#### <a name="SPECIALIST_MODIFICATIONS"></a>나는 전문가의 변경 및 사용자 맞춤형 등이 필요합니다; 도울 수 있니?

*위를 참조하십시오.*

#### <a name="ACCEPT_OR_OFFER_WORK"></a>나는 개발자, 웹 사이트 디자이너, 또는 프로그래머입니다. 이 프로젝트 관련 작업을 할 수 있습니까?

예. 우리의 라이센스는이를 금지하지 않습니다.

#### <a name="WANT_TO_CONTRIBUTE"></a>나는 프로젝트에 공헌하고 싶다; 이것은 수 있습니까?

예. 프로젝트에 기여 환영합니다. 자세한 내용은 "CONTRIBUTING.md"를 참조하십시오.

#### <a name="RECOMMENDED_VALUES_FOR_IPADDR"></a>"ipaddr"의 권장 값입니다.

값 | 사용
---|---
`HTTP_INCAP_CLIENT_IP` | Incapsula 리버스 프록시.
`HTTP_CF_CONNECTING_IP` | Cloudflare 리버스 프록시.
`CF-Connecting-IP` | Cloudflare 리버스 프록시 (대체; 위가 잘되지 않는 경우).
`HTTP_X_FORWARDED_FOR` | Cloudbric 리버스 프록시.
`X-Forwarded-For` | [Squid 리버스 프록시](http://www.squid-cache.org/Doc/config/forwarded_for/).
*서버 구성에 의해 정의됩니다.* | [Nginx 리버스 프록시](https://www.nginx.com/resources/admin-guide/reverse-proxy/).
`REMOTE_ADDR` | 리버스 프록시는 없습니다 (기본값).

#### <a name="CRON_TO_UPDATE_AUTOMATICALLY"></a>Cron을 사용하여 자동으로 업데이트 할 수 있습니까?

예. 외부 스크립트를 통해 업데이트 페이지와 상호 작용하기위한 프런트 엔드에 API가 내장되어 있습니다. 별도의 스크립트 인 "[Cronable](https://github.com/Maikuolan/Cronable)"을 사용할 수 있습니다. Cron 관리자 또는 Cron 스케줄러가이 사용할 수 있습니다, 패키지 및 기타 지원되는 패키지를 자동으로 업데이트하는 데 사용할 수 있습니다 (이 스크립트는 자체 문서를 제공합니다.).

#### <a name="WHAT_ARE_INFRACTIONS"></a>"위반"이란 무엇입니까?

"위반"은 특정 서명 파일에 의해 아직 차단되지 않은 IP가 이후 요청에 대해 차단되기 시작해야하는시기를 결정합니다. 이들은 IP 추적과 밀접하게 관련되어 있습니다. 원래의 IP가 아닌 이유로 요청을 차단할 수있는 기능 및 모듈이 있습니다 (예를 들어, 스팸봇 또는 해킹 도구에 해당하는 사용자 에이전트가있는 것과 같은 이유, 위험한 검색어, 스푸핑 된 DNS, 기타). 이 경우 "위반"이 발생할 수 있습니다. 이들은 특정 서명 파일에 의해 아직 차단되지 않은 원치 않는 요청에 해당하는 IP 주소를 식별하는 방법을 제공합니다. 위반은 일반적으로 IP가 차단 된 횟수와 일대일로 대응하지만 항상 그렇지는 않습니다 (심한 경우에는 위반 값이 더 클 수 있습니다, 또한, "track_mode"가 false 인 경우, 서명 파일에 의해서만 트리거 된 블록 이벤트에 대해서는 위반이 발생하지 않습니다).

#### <a name="BLOCK_HOSTNAMES"></a>CIDRAM이 호스트 이름을 차단할 수 있습니까?

예. 이렇게하려면 사용자 지정 모듈 파일을 만들어야합니다. *참조하십시오 : [기초 (모듈 경우)](#MODULE_BASICS)*.

#### <a name="WHAT_CAN_I_USE_FOR_DEFAULT_DNS"></a>"default_dns"에 사용할 수있는 항목은 무엇입니까?

제안을 찾고있는 경우, [public-dns.info](https://public-dns.info/)및 [OpenNIC](https://servers.opennic.org/)는 알려진 공개 DNS 서버의 광범위한 목록을 제공합니다. 또는 아래 표를 참조하십시오 :

IP | 운영자
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

*노트 : 본인은 모든 DNS 서비스의 개인, 열거 되든 그렇지 않든, 정보 보호 관행, 보안, 효능 또는 신뢰성과 관련하여 어떠한 보증이나 보장도하지 않습니다. 그들에 대한 결정을 내릴 때 자신의 연구를하십시오.*

#### <a name="PROTECT_OTHER_THINGS"></a>CIDRAM을 사용하여 웹 사이트 (예 : 이메일 서버, FTP, SSH, IRC, 등) 이외의 것을 보호 할 수 있습니까?

합법적으로, 당신은 이것을 할 수 있습니다. 기술적으로나 실질적으로, 당신은해서는 안됩니다. 우리의 라이센스는 CIDRAM을 구현하는 기술을 제한하지 않습니다. 그러나, CIDRAM은 WAF (웹 응용 프로그램 방화벽)이며 웹 사이트를 보호하기위한 것입니다. 다른 기술을 염두에두고 설계되지 않았기 때문에 다른 기술에 대한 효과적이거나 신뢰할 수있는 보호를 제공하지는 않습니다. 구현이 어려울 가능성이 높으며, 가양 성 및 누락 감지 위험이 매우 높습니다.

#### <a name="CDN_CACHING_PROBLEMS"></a>CDN 또는 캐싱 서비스를 사용하는 것과 동시에 CIDRAM을 사용하면 문제가 발생합니까?

아마도. 이것은 서비스와 사용 방법에 따라 달라질 수 있습니다. 일반적으로, 정적 애셋 만 캐싱하는 경우 아무 문제가 없어야합니다 (정적 애셋이란 시간이 지나도 변하지 않는 것을 의미합니다; 예 : 이미지, CSS, 등). 그러나, 일반적으로 요청에 따라 동적으로 생성되는 데이터를 캐싱하거나 POST 요청의 결과를 캐싱 할 때 문제가 발생할 수 있습니다 (이것은 귀하의 웹 사이트와 그 환경을 정적으로 렌더링 할 것이며, CIDRAM은 정적 환경에서 의미있는 이점을 제공하지 않을 것입니다). 사용중인 CDN 또는 캐싱 서비스에 따라, CIDRAM에 대한 특정 구성 요구 사항이있을 수 있습니다 (귀하의 필요에 맞게 CIDRAM이 올바르게 구성되었는지 확인하십시오). 잘못된 구성은 심각한 문제를 일으킬 수 있습니다.

#### <a name="DDOS_ATTACKS"></a>CIDRAM이 내 웹 사이트를 DDoS 공격으로부터 보호합니까?

Short answer: No.

More detailed answer: CIDRAM will help reduce the impact that unwanted traffic can have on your website (thus reducing your website's bandwidth costs), will help reduce the impact that unwanted traffic can have on your hardware (e.g., your server's ability to process and serve requests), and can help to reduce various other potential negative effects associated with unwanted traffic. However, there are two important things that must be remembered in order to understand this question.

Firstly, CIDRAM is a PHP package, and therefore operates at the machine where PHP is installed. This means that CIDRAM can only see and block a request after the server has already received it. Secondly, effective DDoS mitigation should filter requests before they reach the server targeted by the DDoS attack. Ideally, DDoS attacks should be detected and mitigated by solutions capable of dropping or rerouting traffic associated with attacks, before it reaches the targeted server in the first place.

This can be implemented using dedicated, on-premise hardware solutions, and/or cloud-based solutions such as dedicated DDoS mitigation services, routing a domain's DNS through DDoS-resistant networks, cloud-based filtering, or some combination thereof. In any case though, this subject is a little too complex to explain thoroughly with just a mere paragraph or two, so I would recommend doing further research if this is a subject you want to pursue. When the true nature of DDoS attacks is properly understood, this answer will make more sense.

---


### 11. <a name="SECTION11"></a>법률 정보

#### 11.0 섹션 프리앰블

이 절은 패키지의 사용 및 구현에 관한 가능한 법적 고려 사항을 설명하고 기본 관련 정보를 제공하기위한 것입니다. 이 정보는 자국에서있을 수있는 법적 요구 사항 때문에 일부 사용자에게 중요 할 수 있습니다. 일부 사용자는이 정보에 따라 웹 사이트 정책을 조정해야 할 수도 있습니다.

무엇보다, 나는 (패키지 저자)가 변호사 또는 자격을 갖춘 법률 전문가가 아님을 알아 주시기 바랍니다. 따라서, 나는 법률 자문을 제공 할 자격이 없다. 또한 법률 요건은 국가 및 관할 구역마다 다를 수 있습니다. 이러한 다양한 법적 요구 사항도 때로는 충돌 할 수 있습니다 (예를 들면 : [개인 정보 보호 권리와](https://ko.wikipedia.org/wiki/%EC%A0%95%EB%B3%B4%ED%86%B5%EC%8B%A0%EB%A7%9D_%EC%9D%B4%EC%9A%A9%EC%B4%89%EC%A7%84_%EB%B0%8F_%EC%A0%95%EB%B3%B4%EB%B3%B4%ED%98%B8_%EB%93%B1%EC%97%90_%EA%B4%80%ED%95%9C_%EB%B2%95%EB%A5%A0#%EA%B0%9C%EC%9D%B8%EC%A0%95%EB%B3%B4%EC%9D%98_%EB%B3%B4%ED%98%B8) [잊혀진 권리를](https://namu.wiki/w/%EC%9E%8A%ED%9E%90%20%EA%B6%8C%EB%A6%AC) 선호하는 국가들, 확장 된 데이터 보존을 선호하는 국가들에 비해). 패키지에 대한 액세스가 특정 국가 또는 관할 지역에만 국한되지, 않으므로 패키지 사용자베이스가 지리적으로 다양 할 수 있습니다. 이 점을 고려해 볼 때, 나는 모든 사람에게 "법적으로 준수하는"것이 무엇을 의미 하는지를 말할 입장이 아닙니다. 그러나 여기에있는 정보가 패키지의 맥락에서 법적으로 준수하기 위해해야 할 일을 스스로 결정하는 데 도움이되기를 바랍니다. 의심의 여지가 있거나 법률적인 관점에서 추가 도움과 조언이 필요한 경우 자격을 갖춘 법률 전문가와상의하는 것이 좋습니다.

#### 11.1 책임

패키지 라이센스에 의해 이미 명시된 바와 같이, 패키지에는 어떠한 보증도 없습니다. 여기에는 모든 책임 범위가 포함되지만 이에 국한되지는 않습니다. 이 패키지는 편리함을 위해 제공되며, 유용 할 것으로 기대되며, 귀하에게 도움이 될 것입니다. 그러나, 당신이 패키지를 사용하든, 당신 자신의 선택입니다. 당신은 패키지를 사용하도록 강요 당하지 않지만, 그렇게 할 때, 당신은 그 결정에 대한 책임이 있습니다. 본인 및 기타 패키지 제공자는 귀하가 직접, 간접적으로, 암시 적으로, 또는 다른 방법으로 관계없이, 결정한 결과에 대해 법적 책임을지지 않습니다.

#### 11.2 제 3 자

정확한 구성과 구현에 따라, 패키지는 경우에 따라 제 3 자와 통신하고 정보를 공유 할 수 있습니다. 이 정보는 일부 관할 구역에 따라 일부 상황에서 "[개인 식별 정보](https://ko.wikipedia.org/wiki/%EA%B0%9C%EC%9D%B8%EC%A0%95%EB%B3%B4)"(PII)로 정의 될 수 있습니다.

이 정보가 이러한 제 3 자에 의해 어떻게 사용될 수 있는지는 제 3 자에 의해 설정된 다양한 정책의 적용을받습니다. 설명서는 이러한 요점을 다루지 않습니다. 그러나 이러한 모든 경우에 이러한 제 3 자와의 정보 공유를 비활성화 할 수 있습니다. 그러한 모든 경우, 이를 사용하기로 선택한 경우, 이러한 제 3 자의 개인 정보, 보안 및 PII 사용과 관련하여 우려 할 사항을 조사하는 것은 귀하의 책임입니다. 의심스러운 점이 있거나 PII와 관련하여 이러한 제 3 자의 행위에 만족하지 않는 경우, 이러한 제 3 자와의 모든 정보 공유를 비활성화하는 것이 가장 좋습니다.

투명성을 목적으로, 공유되는 정보의 유형은 아래에 설명되어 있습니다.

##### 11.2.0 HOSTNAME LOOKUP

If you use any features or modules intended to work with hostnames (such as the "bad hosts blocker module", "tor project exit nodes block module", or "search engine verification", for example), CIDRAM needs to be able to obtain the hostname of inbound requests somehow. Typically, it does this by requesting the hostname of the IP address of inbound requests from a DNS server, or by requesting the information through functionality provided by the system where CIDRAM is installed (this is typically referred to as a "hostname lookup"). The DNS servers defined by default belong to the Google DNS service (but this can be easily changed via configuration). The exact services communicated with is configurable, and depends on how you configure the package. In the case of using functionality provided by the system where CIDRAM is installed, you'll need to contact your system administrator to determine which routes hostname lookups use. Hostname lookups can be prevented in CIDRAM by avoiding the affected modules or by modifying the package configuration in accordance with your needs.

*관련 설정 지시어 :*
- `general` -> `default_dns`
- `general` -> `search_engine_verification`
- `general` -> `force_hostname_lookup`
- `general` -> `allow_gethostbyaddr_lookup`

##### 11.2.1 WEBFONTS

Some custom themes, as well as the the standard UI ("user interface") for the CIDRAM front-end and the "Access Denied" page, may use webfonts for aesthetic reasons. Webfonts are disabled by default, but when enabled, direct communication between the user's browser and the service hosting the webfonts occurs. This may potentially involve communicating information such as the user's IP address, user agent, operating system, and other details available to the request. Most of these webfonts are hosted by the Google Fonts service.

*관련 설정 지시어 :*
- `general` -> `disable_webfonts`

##### 11.2.2 SEARCH ENGINE VERIFICATION

When search engine verification is enabled, CIDRAM attempts to perform "forward DNS lookups" to verify whether requests claiming to originate from search engines are authentic. To do this, it uses the Google DNS service to attempt to resolve IP addresses from the hostnames of these inbound requests (in this process, the hostnames of these inbound requests is shared with the service).

*관련 설정 지시어 :*
- `general` -> `search_engine_verification`

##### 11.2.3 GOOGLE reCAPTCHA

CIDRAM optionally supports Google reCAPTCHA, providing a means for users to bypass the "Access Denied" page by completing a reCAPTCHA instance (more information about this feature is described earlier in the documentation, most notably in the configuration section). Google reCAPTCHA requires API keys in order to be work correctly, and is thereby disabled by default. It can be enabled by defining the required API keys in the package configuration. When enabled, direct communication between the user's browser and the reCAPTCHA service occurs. This may potentially involve communicating information such as the user's IP address, user agent, operating system, and other details available to the request. The user's IP address may also be shared in communication between CIDRAM and the reCAPTCHA service when verifying the validity of a reCAPTCHA instance and verifying whether it was completed successfully.

*관련 설정 지시어 : "recaptcha"구성 카테고리 아래에 나열된 모든 것.*

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
- `general` -> `logfile`
- `general` -> `logfileApache`
- `general` -> `logfileSerialized`

When these directives are left empty, this type of logging will remain disabled.

##### 11.3.1 reCAPTCHA LOGGING

This type of logging relates specifically to reCAPTCHA instances, and occurs only when a user attempts to complete a reCAPTCHA instance.

A reCAPTCHA log entry contains the IP address of the user attempting to complete a reCAPTCHA instance, the date and time that the attempt occurred, and the reCAPTCHA state. A reCAPTCHA log entry typically looks something like this (as an example):

```
IP Address: x.x.x.x - Date/Time: Day, dd Mon 20xx hh:ii:ss +0000 - reCAPTCHA State: Passed!
```

The configuration directive responsible for reCAPTCHA logging is:
- `recaptcha` -> `logfile`

##### 11.3.2 FRONT-END LOGGING

This type of logging relates front-end login attempts, and occurs only when a user attempts to log into the front-end (assuming front-end access is enabled).

A front-end log entry contains the IP address of the user attempting to log in, the date and time that the attempt occurred, and the results of the attempt (successfully logged in, or failed to log in). A front-end log entry typically looks something like this (as an example):

```
x.x.x.x - Day, dd Mon 20xx hh:ii:ss +0000 - "admin" - Logged in.
```

The configuration directive responsible for front-end logging is:
- `general` -> `FrontEndLog`

##### 11.3.3 LOG ROTATION

You may want to purge logs after a period of time, or may be required to do so by law (i.e., the amount of time that it's legally permissible for you to retain logs may be limited by law). You can achieve this by including date/time markers in the names of your logfiles as per specified by your package configuration (e.g., `{yyyy}-{mm}-{dd}.log`), and then enabling log rotation (log rotation allows you to perform some action on logfiles when specified limits are exceeded).

For example: If I was legally required to delete logs after 30 days, I could specify `{dd}.log` in the names of my logfiles (`{dd}` represents days), set the value of `log_rotation_limit` to 30, and set the value of `log_rotation_action` to `Delete`.

Conversely, if you're required to retain logs for an extended period of time, you could either not use log rotation at all, or you could set the value of `log_rotation_action` to `Archive`, to compress logfiles, thereby reducing the total amount of disk space that they occupy.

*관련 설정 지시어 :*
- `general` -> `log_rotation_limit`
- `general` -> `log_rotation_action`

##### 11.3.4 LOG TRUNCATION

It's also possible to truncate individual logfiles when they exceed a certain size, if this is something you might need or want to do.

*관련 설정 지시어 :*
- `general` -> `truncate`

##### 11.3.5 IP ADDRESS PSEUDONYMISATION

Firstly, if you're not familiar with the term "pseudonymisation", the following resources can help explain it in some detail:
- [[trust-hub.com] What is pseudonymisation?](https://www.trust-hub.com/news/what-is-pseudonymisation/)
- [[Wikipedia] Pseudonymization](https://en.wikipedia.org/wiki/Pseudonymization)

In some circumstances, you may be legally required to anonymise or pseudonymise any PII collected, processed, or stored. Although this concept has existed for quite some time now, GDPR/DSGVO notably mentions, and specifically encourages "pseudonymisation".

CIDRAM is able to pseudonymise IP addresses when logging them, if this is something you might need or want to do. When CIDRAM pseudonymises IP addresses, when logged, the final octet of IPv4 addresses, and everything after the second part of IPv6 addresses is represented by an "x" (effectively rounding IPv4 addresses to the initial address of the 24th subnet they factor into, and IPv6 addresses to the initial address of the 32nd subnet they factor into).

*Note: CIDRAM's IP address pseudonymisation process doesn't affect CIDRAM's IP tracking feature. If this is a problem for you, it may be best to disable IP tracking entirely. This can be achieved by setting `track_mode` to `false` and by avoiding any modules.*

*관련 설정 지시어 :*
- `signatures` -> `track_mode`
- `legal` -> `pseudonymise_ip_addresses`

##### 11.3.6 OMITTING LOG INFORMATION

If you want to take it a step further by preventing specific types of information from being logged entirely, this is also possible to do. CIDRAM provides configuration directives to control whether IP addresses, hostnames, and user agents are included in logs. By default, all three of these data points are included in logs when available. Setting any of these configuration directives to `true` will omit the corresponding information from logs.

*Note: There's no reason to pseudonymise IP addresses when omitting them from logs entirely.*

*관련 설정 지시어 :*
- `legal` -> `omit_ip`
- `legal` -> `omit_hostname`
- `legal` -> `omit_ua`

##### 11.3.7 STATISTICS

CIDRAM is optionally able to track statistics such as the total number of block events or reCAPTCHA instances that have occurred since some particular point in time. This feature is disabled by default, but can be enabled via the package configuration. This feature only tracks the total number of events occurred, and doesn't include any information about specific events (and therefore, shouldn't be regarded as PII).

*관련 설정 지시어 :*
- `general` -> `statistics`

##### 11.3.8 ENCRYPTION

CIDRAM doesn't encrypt its cache or any log information. Cache and log encryption may be introduced in the future, but there aren't any specific plans for it currently. If you're concerned about unauthorised third parties gaining access to parts of CIDRAM that may contain PII or sensitive information such as its cache or logs, I would recommend that CIDRAM not be installed at a publicly accessible location (e.g., install CIDRAM outside the standard `public_html` directory or equivalent thereof available to most standard webservers) and that appropriately restrictive permissions be enforced for the directory where it resides (in particular, for the vault directory). If that isn't sufficient to address your concerns, then configure CIDRAM as such that the types of information causing your concerns won't be collected or logged in the first place (such as, by disabling logging).

#### 11.4 COOKIES

CIDRAM sets cookies at two points in its codebase. Firstly, when a user successfully completes a reCAPTCHA instance (and assuming that `lockuser` is set to `true`), CIDRAM sets a cookie in order to be able to remember for subsequent requests that the user has already completed a reCAPTCHA instance, so that it won't need to continuously ask the user to complete a reCAPTCHA instance on subsequent requests. Secondly, when a user successfully logs into the front-end, CIDRAM sets a cookie in order to be able to remember the user for subsequent requests (i.e., cookies are used for authenticate the user to a login session).

In both cases, cookie warnings are displayed prominently (when applicable), warning the user that cookies will be set if they engage in the relevant actions. Cookies aren't set at any other points in the codebase.

*Note: CIDRAM's particular implementation of the "invisible" API for reCAPTCHA might be incompatible with cookie laws in some jurisdictions, and should be avoided by any websites subject to such laws. Opting to use the "V2" API instead, or simply disabling reCAPTCHA entirely, may be preferable.*

*관련 설정 지시어 :*
- `general` -> `disable_frontend`
- `recaptcha` -> `lockuser`
- `recaptcha` -> `api`

#### 11.5 마케팅과 광고

CIDRAM은 마케팅이나 광고 목적으로 정보를 수집하거나 처리하지 않습니다. 수집되거나 기록 된 정보를 판매하거나 이익을 얻지 않습니다. CIDRAM은 상업적 기업이 아니며 상업적 이익과 관련이 없으므로, 이러한 일을하는 것이 타당하지 않습니다. 이것은 프로젝트가 시작된 이래로 그랬다, 오늘날에도 계속해서 그러합니다. 또한, 이러한 일을하는 것은 프로젝트의 정신과 목적에 맞지 않습니다과, 내가 프로젝트를 계속 유지한다면, 결코 일어나지 않을 것이다.

#### 11.6 개인 정보 정책

경우에 따라 웹 사이트의 모든 페이지와 섹션에 개인 정보 취급 방침에 대한 링크를 명확하게 표시해야 할 수도 있습니다. 이는 개인 정보 보호 관행, 수집하는 개인 식별 정보 유형 및 개인 정보 사용 방법에 대해 사용자에게 알리는 데 중요 할 수 있습니다. 이러한 링크를 CIDRAM의 "액세스 거부"페이지에 포함 시키려면 개인 정보 보호 정책에 대한 URL을 지정하는 구성 지시문이 제공됩니다.

*노트 : 개인 정보 취급 방침 페이지가 CIDRAM의 보호 뒤에 있지 않도록 적극 권장합니다. CIDRAM이 개인 정보 취급 방침 페이지를 보호하고 CIDRAM에 의해 차단 된 사용자가 개인 정보 취급 방침 링크를 클릭하면 다시 차단되어 개인 정보 취급 방침을 볼 수 없습니다. 이상적으로는 HTML 페이지 또는 일반 텍스트 파일과 같은 개인 정보 취급 방침의 정적 복사본에 연결해야합니다.*

*관련 설정 지시어 :*
- `legal` -> `privacy_policy`

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


최종 업데이트 : 2018년 6월 21일.

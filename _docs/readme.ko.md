## CIDRAM 설명서 (한국어).

### 목차
- 1. [서문](#SECTION1)
- 2. [설치 방법](#SECTION2)
- 3. [사용 방법](#SECTION3)
- 4. [프론트 엔드 관리](#SECTION4)
- 5. [본 패키지에 포함 된 파일](#SECTION5)
- 6. [설정 옵션](#SECTION6)
- 7. [서명 포맷](#SECTION7)
- 8. [자주 묻는 질문 (FAQ)](#SECTION8)

*번역에 대한주의: 오류가 발생하는 경우 (예를 들어, 번역 사이의 불일치, 오타 등 등), README의 영어 버전은 원본과 권위 버전이라고 생각됩니다. 오류를 발견하면이를 해결하려면 협력을 환영하는 것이다.*

---


### 1. <a name="SECTION1"></a>서문

CIDRAM (시도라무 클래스없는 도메인 간 라우팅 액세스 매니저; "Classless Inter-Domain Routing Access Manager")는 PHP 스크립트입니다. 웹 사이트를 보호하도록 설계되어 IP 주소 (원치 않는 트래픽이있는 소스로 간주합니다)에서 전송 요청을 차단하여 (인간 이외의 액세스 엔드 포인트 클라우드 서비스 스팸봇 스크레이퍼 등). IP 주소의 수 CIDR을 계산하여 CIDR은 시그니처 파일과 비교할 수 있습니다 (이 서명 파일은 불필요한 IP 주소에 해당하는 CIDR의 목록이 포함되어 있습니다); 일치가 발견되면 요청이 차단됩니다.

*(참조하십시오: ["CIDR"이란 무엇입니까?](#WHAT_IS_A_CIDR)).*

CIDRAM 저작권 2016 년 이후 Caleb M (Maikuolan)의 GNU/GPLv2.

본 스크립트는 프리웨어입니다. 자유 소프트웨어 재단에서 발행 한 GNU 일반 공중 라이선스 버전 2 (또는 이후 버전)에 따라 재배포 및 가공이 가능합니다. 배포의 목적은 도움이되기를 바랍니다 것이지만 "보증 아니며 상품성 또는 특정 목적에 적합한 것을 시사하는 것이기도 없습니다." "LICENSE.txt"에있는 "GNU General Public License" (일반 라이선스)을 참조하십시오. 다음 URL에서도 볼 수 있습니다:
- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

본 문서 및 관련 패키지 [GitHub](https://github.com/Maikuolan/CIDRAM/)에서 다운로드 할 수 있습니다.

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

또는 `.htaccess`에서:

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

6) 설치가 완료되었습니다. :-)

#### 2.1 COMPOSER를 사용하여 설치한다

[CIDRAM는 Packagist에 등록되어 있습니다](https://packagist.org/packages/maikuolan/cidram). Composer를 익숙한 경우 Composer를 사용하여 CIDRAM를 설치할 수 있습니다 (당신은 아직 설정과 후크를 준비해야합니다; "수동 설치"의 2 단계와 5 단계를 참조하십시오).

`composer require maikuolan/cidram`

#### 2.2 WORDPRESS 위해 설치한다

WordPress에 CIDRAM를 사용하려면 위의 단계를 모두 무시 할 수 있습니다. [CIDRAM은 WordPress 플러그인 데이터베이스에 플러그인으로 등록되어 있습니다](https://wordpress.org/plugins/cidram/). 플러그인 대시 보드에서 CIDRAM를 직접 설치할 수 있습니다. 다른 플러그인과 같은 방법으로 설치할 수 (추가 절차는 필요하지 않습니다). 다른 설치 방법과 마찬가지로, `config.ini` 파일의 내용을 변경 또는 프런트 엔드 구성 페이지를 사용하여 설치를 사용자 정의 할 수 있습니다. 프런트 엔드 업데이트 페이지에서 CIDRAM를 업데이트하면, 플러그인 버전 정보가 WordPress에 자동으로 동기화됩니다.

---


### 3. <a name="SECTION3"></a>사용 방법

CIDRAM은 자동으로 원치 않는 요청을 차단해야합니다; 지원이 필요하지 않습니다 (설치 제외).

업데이트 (업 데이트)은 수동으로 이루어지고 있습니다. 당신의 설정 파일을 수정하여 구성 설정을 사용자 정의 할 수 있습니다. 당신의 서명 파일을 변경하여 CIDRs이 차단 된 변경 할 수 있습니다.

오진 (거짓 양성)과 신종 의심스러운 것으로 발생, 관한 것에 대해서는 무엇이든 알려주세요.

---


### 4. <a name="SECTION4"></a>프론트 엔드 관리

#### 4.0 프론트 엔드는 무엇입니다.

프론트 엔드는 CIDRAM 설치 유지 관리 업데이트하기위한 편리하고 쉬운 방법을 제공합니다. 로그 페이지를 사용하여 로그 파일을 공유, 다운로드 할 수있는 구성 페이지에서 구성을 변경할 수 있습니다, 업데이트 페이지를 사용하여 구성 요소를 설치 및 제거 할 수 있습니다, 그리고 파일 관리자를 사용하여 vault에 파일을 업로드, 다운로드 및 변경할 수 있습니다.

무단 액세스를 방지하기 위해 프론트 엔드는 기본적으로 비활성화되어 있습니다 (무단 액세스가 웹 사이트와 보안에 중대한 영향을 미칠 수 있습니다). 그것을 가능하게하기위한 지침이 절 아래에 포함되어 있습니다.

#### 4.1 프론트 엔드를 사용하는 방법.

1) `config.ini` 안에있는 `disable_frontend` 지시문을 찾습니다 그것을 "true"로 설정합니다 (기본값은 "false"입니다).

2) 브라우저에서 `loader.php`에 액세스하십시오 (예, `http://localhost/cidram/loader.php`).

3) 기본 사용자 이름과 암호로 로그인 (admin/password).

주의: 당신이 처음 로그인 한 후 프론트 엔드에 대한 무단 액세스를 방지하기 위해 신속하게 사용자 이름과 암호를 변경해야합니다! 이것은 매우 중요합니다, 왜냐하면 프론트 엔드에서 임의의 PHP 코드를 당신의 웹 사이트에 업로드 할 수 있기 때문입니다.

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
/vault/fe_assets/_cidr_calc.html | CIDR 계산기 HTML 템플릿.
/vault/fe_assets/_cidr_calc_row.html | CIDR 계산기 HTML 템플릿.
/vault/fe_assets/_config.html | 프론트 엔드 구성 페이지의 HTML 템플릿.
/vault/fe_assets/_config_row.html | 프론트 엔드 구성 페이지의 HTML 템플릿.
/vault/fe_assets/_files.html | 파일 관리자의 HTML 템플릿.
/vault/fe_assets/_files_edit.html | 파일 관리자의 HTML 템플릿.
/vault/fe_assets/_files_rename.html | 파일 관리자의 HTML 템플릿.
/vault/fe_assets/_files_row.html | 파일 관리자의 HTML 템플릿.
/vault/fe_assets/_home.html | 프론트 엔드의 홈페이지의 HTML 템플릿.
/vault/fe_assets/_ip_test.html | IP 테스트 페이지의 HTML 템플릿.
/vault/fe_assets/_ip_test_row.html | IP 테스트 페이지의 HTML 템플릿.
/vault/fe_assets/_ip_tracking.html | IP 추적 페이지의 HTML 템플릿.
/vault/fe_assets/_ip_tracking_row.html | IP 추적 페이지의 HTML 템플릿.
/vault/fe_assets/_login.html | 프론트 엔드 로그인 페이지의 HTML 템플릿.
/vault/fe_assets/_logs.html | 프론트 엔드 로고스 페이지의 HTML 템플릿.
/vault/fe_assets/_nav_complete_access.html | 프론트 엔드의 탐색 링크의 HTML 템플릿, 완전한 액세스를위한 것입니다.
/vault/fe_assets/_nav_logs_access_only.html | 프론트 엔드의 탐색 링크의 HTML 템플릿은 로그에만 액세스를위한 것입니다.
/vault/fe_assets/_updates.html | 프론트 엔드 업데이트 페이지의 HTML 템플릿.
/vault/fe_assets/_updates_row.html | 프론트 엔드 업데이트 페이지의 HTML 템플릿.
/vault/fe_assets/frontend.css | 프론트 엔드 CSS 스타일 시트.
/vault/fe_assets/frontend.dat | 프론트 엔드 데이터베이스 (계정 정보와 세션 정보 및 캐시가 포함되어 있습니다; 프론트 엔드가 활성화되어있을 때 생성).
/vault/fe_assets/frontend.html | 프론트 엔드 메인 템플릿 파일.
/vault/lang/ | CIDRAM 언어 데이터가 포함되어 있습니다.
/vault/lang/.htaccess | 하이퍼 텍스트 액세스 파일 (이 경우, 본 스크립트의 중요한 파일을 권한이없는 소스의 액세스로부터 보호하기위한 것입니다).
/vault/lang/lang.ar.cli.php | CLI 아랍어 언어 데이터.
/vault/lang/lang.ar.fe.php | 프론트 엔드 아랍어 언어 데이터.
/vault/lang/lang.ar.php | 아랍어 언어 데이터.
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
/vault/lang/lang.pt.cli.php | CLI 포르투갈어 언어 데이터.
/vault/lang/lang.pt.fe.php | 프론트 엔드 포르투갈어 언어 데이터.
/vault/lang/lang.pt.php | 포르투갈어 언어 데이터.
/vault/lang/lang.ru.cli.php | CLI 러시아어 언어 데이터.
/vault/lang/lang.ru.fe.php | 프론트 엔드 러시아어 언어 데이터.
/vault/lang/lang.ru.php | 러시아어 언어 데이터.
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
/vault/cache.dat | 캐시 데이터.
/vault/cidramblocklists.dat | Macmathan 제공하는 국가 선택적 차단 목록. 업데이트 기능 의해 사용됩니다 (프론트 엔드를 제공합니다).
/vault/cli.php | CLI 핸들러.
/vault/components.dat | CIDRAM 구성 요소 정보가 포함되어 있습니다. 업데이트 기능 의해 사용됩니다 (프론트 엔드를 제공합니다).
/vault/config.ini.RenameMe | CIDRAM 설정 파일; CIDRAM 모든 옵션 설정을 포함하고 있습니다. 각 옵션의 기능과 작동 방법에 대한 설명입니다 (활성화하기 위해 이름을 변경합니다).
/vault/config.php | 구성 핸들러.
/vault/config.yaml | 설정 기본값 스 파일; CIDRAM의 기본 설정이 포함되어 있습니다.
/vault/frontend.php | 프론트 엔드 핸들러.
/vault/functions.php | 기능 파일 (기본적으로 파일).
/vault/hashes.dat | 허용되는 해시 목록 (reCAPTCHA의 기능에 관련합니다; 만 reCAPTCHA 기능이 활성화되어있는 경우에 생성).
/vault/icons.php | 아이콘 핸들러 (프론트 엔드 파일 관리자에 의해 사용된다).
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
/vault/modules.dat | CIDRAM 모듈 정보가 포함되어 있습니다; 업데이트 기능 사용 (프론트 엔드를 제공합니다).
/vault/outgen.php | 출력 발생기.
/vault/php5.4.x.php | PHP 5.4.X 뽀리휘루 (PHP 5.4.X의 하위 호환성을 위해 필요합니다; 더 새로운 PHP 버전을 위해 삭제하는 것이 안전합니다).
/vault/recaptcha.php | reCAPTCHA 모듈.
/vault/rules_as6939.php | 사용자 정의 규칙은 AS6939을위한 파일입니다.
/vault/rules_softlayer.php | 사용자 정의 규칙은 Soft Layer위한 파일.
/vault/rules_specific.php | 사용자 정의 규칙은 일부 특정 CIDR위한 파일.
/vault/salt.dat | 솔트 파일 (일부 주변 기능에 의해 사용됩니다; 필요한 경우에만 생성).
/vault/template.html | CIDRAM 템플릿 파일; CIDRAM가 파일 업로드를 차단했을 때 생성되는 메시지의 HTML 출력 템플릿 (업 로더를 표시하는 메시지).
/vault/template_custom.html | CIDRAM 템플릿 파일; CIDRAM가 파일 업로드를 차단했을 때 생성되는 메시지의 HTML 출력 템플릿 (업 로더를 표시하는 메시지).
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

*유용한 팁: 당신이 원하는 경우 로그 파일 이름에 날짜/시간 정보를 부가 할 수 있습니다 이름 이들을 포함하여: 전체 연도에 대한 `{yyyy}`생략 된 년간 `{yy}`달 `{mm}`일 `{dd}`시간 `{hh}`.*

*예:*
- *`logfile='logfile.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileApache='access.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileSerialized='serial.{yyyy}-{mm}-{dd}-{hh}.txt'`*

"truncate"
- 로그 파일이 특정 크기에 도달하면 잘 있습니까? 값은 로그 파일이 잘 리기 전에 커질 가능성이있는 B/KB/MB/GB/TB 단위의 최대 크기입니다. 기본값 "0KB"은 절단을 해제합니다 (로그 파일은 무한정 확장 할 수 있습니다). 참고: 개별 로그 파일에 적용됩니다! 로그 파일의 크기는 일괄 적으로 고려되지 않습니다.

"timeOffset"
- 귀하의 서버 시간은 로컬 시간과 일치하지 않는 경우, 당신의 요구에 따라 시간을 조정하기 위해, 당신은 여기에 오프셋을 지정할 수 있습니다. 하지만 그 대신에 일반적으로 시간대 지시문 (당신의`php.ini` 파일)을 조정 る 것이 좋습니다,하지만 때때로 (같은 제한 공유 호스팅 제공 업체에서 작업 할 때) 이것은 무엇을하는 것이 항상 가능하지는 않습니다 따라서이 옵션은 여기에서 볼 수 있습니다. 오프셋 분이며 있습니다.
- 예 (1 시간을 추가합니다): `timeOffset=60`

"timeFormat"
- CIDRAM에서 사용되는 날짜 형식. Default (기본 설정) = `{Day}, {dd} {Mon} {yyyy} {hh}:{ii}:{ss} {tz}`.

"ipaddr"
- 연결 요청의 IP 주소를 어디에서 찾을 것인가에 대해 (Cloudflare 같은 서비스에 대해 유효). Default (기본 설정) = REMOTE_ADDR. 주의: 당신이 무엇을하고 있는지 모르는 한이를 변경하지 마십시오.

"forbid_on_block"
- 무엇 헤더 사용해야합니까 (요청을 차단했을 때)? `false`(그릇된)/200 = 200 OK (Default / 기본 설정); `true`(참된)/403 = 403 Forbidden (403 금지되어있다); 503 = 503 Service unavailable (503 서비스 이용 불가).

"silent_mode"
- "액세스 거부"페이지를 표시하는 대신 CIDRAM는 차단 된 액세스 시도를 자동으로 리디렉션해야합니까? 그렇다면 리디렉션 위치를 지정합니다. 아니오의 경우이 변수를 비워 둡니다.

"lang"
- CIDRAM의 기본 언어를 설정합니다.

"emailaddr"
- 여기에 이메일 주소를 입력하고 사용자가 차단 된 경우 사용자에게 보낼 수 있습니다. 이것은 지원과 지원에 사용할 수 있습니다 (실수로 차단 된 경우 등). 경고: 여기에 입력 된 전자 이메일 주소는 아마 스팸 로봇에 의해 취득됩니다. 여기에서 제공되는 전자 이메일 주소는 모든 일회용하는 것이 좋습니다 (예를 들어, 기본 개인 주소 또는 비즈니스 주소를 사용하지 않는 등).

"disable_cli"
- CLI 모드를 해제 하는가? CLI 모드 (시에루아이 모드)는 기본적으로 활성화되어 있지만, 테스트 도구 (PHPUnit 등) 및 CLI 기반의 응용 프로그램과 간섭하는 가능성이 없다고는 단언 할 수 없습니다. CLI 모드를 해제 할 필요가 없으면이 데레쿠티부 무시 받고 괜찮습니다. `false`(그릇된) = CLI 모드를 활성화합니다 (Default / 기본 설정); `true`(참된) = CLI 모드를 해제합니다.

"disable_frontend"
- 프론트 엔드에 대한 액세스를 비활성화하거나? 프론트 엔드에 대한 액세스는 CIDRAM을 더 쉽게 관리 할 수 있습니다. 상기 그것은 또한 잠재적 인 보안 위험이 될 수 있습니다. 백엔드를 통해 관리하는 것이 좋습니다,하지만 이것이 불가능한 경우 프론트 엔드에 대한 액세스를 제공. 당신이 그것을 필요로하지 않는 한 그것을 해제합니다. `false`(그릇된) = 프론트 엔드에 대한 액세스를 활성화합니다; `true`(참된) = 프론트 엔드에 대한 액세스를 비활성화합니다 (Default / 기본 설정).

"max_login_attempts"
- 로그인 시도 횟수 (프론트 엔드). Default (기본 설정) = 5.

"FrontEndLog"
- 프론트 엔드 로그인 시도를 기록하는 파일. 파일 이름 지정하거나 해제하려면 비워하십시오.

"ban_override"
- "infraction_limit"를 초과하면 "forbid_on_block"를 덮어 쓰시겠습니까? 덮어 쓸 때: 차단 된 요청은 빈 페이지를 반환합니다 (템플릿 파일은 사용되지 않습니다). 200 = 덮어 쓰지 (Default / 기본 설정); 403 = "403 Forbidden"로 덮어한다; 503 = "503 Service unavailable"로 덮어한다.

"log_banned_ips"
- 금지 된 IP에서 차단 된 요청을 로그 파일에 포함됩니까? True = 예 (Default / 기본 설정); False = 아니오.

"default_dns"
- 호스트 이름 검색에 사용하는 DNS (도메인 이름 시스템) 서버의 쉼표로 구분 된 목록입니다. Default (기본 설정) = "8.8.8.8,8.8.4.4" (Google DNS). 주의: 당신이 무엇을하고 있는지 모르는 한이를 변경하지 마십시오.

"search_engine_verification"
- 검색 엔진의 요청을 확인해야합니까? 검색 엔진을 확인하여, 위반의 최대 수를 초과했기 때문에 검색 엔진이 금지되지 않는 것이 보증됩니다 (검색 엔진을 금지하는 것은 일반적으로 검색 엔진 순위의, SEO 등에 악영향을 미칩니다). 확인되면, 검색 엔진이 차단 될 수 있지만, 그러나 금지되지 않습니다. 검증되지 않은 경우는, 위반의 최대를 초과 한 결과, 금지 될 수 있습니다. 또한 검색 엔진의 검증은 사칭 된 검색 엔진으로부터 보호합니다 (이러한 요청은 차단됩니다). True = 검색 엔진의 검증을 활성화한다 (Default / 기본 설정); False = 검색 엔진의 검증을 무효로한다.

"protect_frontend"
- CIDRAM 의해 보통 제공되는 보호를 프론트 엔드에 적용할지 여부를 지정합니다. True = 예 (Default / 기본 설정); False = 아니오.

"disable_webfonts"
- 웹 글꼴을 사용하지 않도록 설정 하시겠습니까? True = 예; False = 아니오 (Default / 기본 설정).

#### "signatures" (카테고리)
서명 설정.

"ipv4"
- IPv4의 서명 파일 목록 (CIDRAM는 이것을 사용합니다). 이것은 쉼표로 구분되어 있습니다. 필요에 따라 항목을 추가 할 수 있습니다.

"ipv6"
- IPv6의 서명 파일 목록 (CIDRAM는 이것을 사용합니다). 이것은 쉼표로 구분되어 있습니다. 필요에 따라 항목을 추가 할 수 있습니다.

"block_cloud"
- 클라우드 서비스에서 CIDR 차단해야합니까? 당신의 웹 사이트에서 API 서비스를 운영하거나 당신이 웹 사이트 간 연결이 예상되는 경우, 이것은 false로 설정해야합니다. 없는 경우에는이를 true로 설정해야합니다.

"block_bogons"
- 화성\ぼごんから의 CIDR 차단해야합니까? 당신은 로컬 호스트에서 또는 귀하의 LAN에서 로컬 네트워크에서 연결을 수신 한 경우, 이것은 false로 설정해야합니다. 없는 경우에는이를 true로 설정해야합니다.

"block_generic"
- 일반적인 CIDR 차단해야합니까? (다른 옵션과 관련되지 않은).

"block_proxies"
- 프록시 서비스에서 CIDR 차단해야합니까? 익명 프록시 서비스가 필요한 경우는 false로 설정해야합니다. 없는 경우에는 보안을 향상시키기 위해이를 true로 설정해야합니다.

"block_spam"
- 스팸 때문에 CIDR 차단해야합니까? 문제가있는 경우를 제외하고 일반적으로이를 true로 설정해야합니다.

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

"액세스 거부"페이지를 우회 할 위험성이 있습니다. 따라서 일반적으로 필요한 경우를 제외하고는이 기능을 사용하는 것은 권장하지 않습니다. 그것이 필요한 상황: 사용자는 당신의 웹 사이트에 액세스 할 수 있습니다,하지만 그들은 적대적인 네트워크에서 연결하고 있습니다 그리고 이것은 협상 할 수 없습니다; 사용자는 액세스가 필요합니다 적대적인 네트워크를 거절 할 필요가있다 (무엇을해야합니까?!).. 이러한 상황에서는 reCAPTCHA 기능이 도움이 될 수 있습니다: 사용자는 권한을 가질 수 있습니다; 불필요한 트래픽을 필터링 할 수 있습니다 (일반적으로). 인간 이외의 트래픽에 대해서만 유효합니다 (예를 들어, 스팸 로봇, 스크레이퍼, 해킹 툴, 자동 교통 등)하지만, 인간의 트래픽에별로 도움이되지 않는다 (예를 들어, 인간의 스패머, 해커, 기타).

"site key"와 "secret key"를 얻기 위해 (reCAPTCHA를 사용하는 데 필요한)이 링크를 클릭하십시오: [https://developers.google.com/recaptcha/](https://developers.google.com/recaptcha/)

"usemode"
- reCAPTCHA를 CIDRAM에서 사용하는 방법.
- 0 = reCAPTCHA는 비활성화되어 있습니다 (Default / 기본 설정).
- 1 = reCAPTCHA는 모두를 위해 서명이 활성화되어 있습니다.
- 2 = 특별히 표시된 섹션의 경우에만 reCAPTCHA가 활성화됩니다.
- (그렇지 값은 0과 같습니다).

"lockip"
- reCAPTCHA를 IP로 잠금 하시겠습니까? False = 쿠키와 해시는 여러 IP에서 사용할 수 있습니다 (Default / 기본 설정). True = 쿠키와 해시는 여러 IP에서 사용할 수 없습니다 (쿠키와 해시는 IP에 잠겨 있습니다).
- 주의: "lockuser"이 "false"인 경우 "lockip"값은 무시됩니다. 이것은 사용자를 기억 메커니즘이 값에 의존하기 때문입니다.

"lockuser"
- reCAPTCHA를 사용자에 잠금 하시겠습니까? False = reCAPTCHA 완료하여 책임있는 IP (참고: 사용자가 아닌) 에서 발생 된 모든 요청에 대한 액세스가 허용됩니다; 쿠키와 해시는 사용되지 않습니다; IP 허용 목록이 사용됩니다. True = reCAPTCHA 완료하여 책임있는 사용자 (참고: IP가 아닌) 에서 발생 된 모든 요청에 대한 액세스가 허용됩니다; 쿠키와 해시는 고객을 기억하기 위해 사용됩니다; IP 화이트리스트는 사용되지 않습니다 (Default / 기본 설정).

"sitekey"
- 이 값은 당신의 reCAPTCHA에 대한 "site key"에 대응하고있을 필요가 있습니다; 이것은 reCAPTCHA 대시 보드에서 찾을 수 있습니다.

"secret"
- 이 값은 당신의 reCAPTCHA에 대한 "secret key"에 대응하고있을 필요가 있습니다; 이것은 reCAPTCHA 대시 보드에서 찾을 수 있습니다.

"expiry"
- "lockuser"이 "true"때(Default / 기본 설정), reCAPTCHA 인스턴스의 합격/불합격 상태를 기억하고 미래의 페이지 요청 용 CIDRAM 해시를 포함한 표준 HTTP Cookie를 생성합니다; 이 해시는 동일한 해시를 포함한 내부 레코드에 해당합니다; 미래의 페이지 요청은 해당 해시를 사용하여 합격/불합격 상태를 인증합니다. "lockuser"이 "false"때 요청을 허용 할 필요가 있는지 여부를 판단하기 위해 IP 허용 목록이 사용됩니다; reCAPTCHA 인스턴스가 성공적으로 전달되면이 화이트리스트에 항목이 추가됩니다. 이러한 쿠키 해시 화이트리스트 항목은 몇 시간 유효해야하나요? Default (기본 설정) = 720 (1 개월).

"logfile"
- reCAPTCHA 시도 기록. 파일 이름 지정하거나 해제하려면 비워하십시오.

*유용한 팁: 당신이 원하는 경우 로그 파일 이름에 날짜/시간 정보를 부가 할 수 있습니다 이름 이들을 포함하여: 전체 연도에 대한 `{yyyy}`생략 된 년간 `{yy}`달 `{mm}`일 `{dd}`시간 `{hh}`.*

*예:*
- *`logfile='recaptcha.{yyyy}-{mm}-{dd}-{hh}.txt'`*

#### "template_data" (카테고리)
템플릿과 테마 지시어와 변수.

템플릿의 데이터는 사용자를위한 액세스 거부 메시지를 HTML 형식으로 출력 할 때 사용됩니다. 사용자 지정 테마를 사용하는 경우는`template_custom.html`를 사용하고, 그렇지 않은 경우는`template.html`를 사용하여 HTML 출력이 생성됩니다. 설정 파일에서이 섹션의 변수는 HTML 출력에 대한 해석되어로 둘러싸인 변수 이름은 해당 변수 데이터로 대체합니다. 예를 들어`foo="bar"`하면 HTML 출력의`<p>{foo}</p>`는`<p>bar</p>`입니다.

"css_url"
- 사용자 정의 테마 템플릿 파일은 외부 CSS 속성을 사용합니다. 한편, 기본 테마는 내부 CSS입니다. 사용자 정의 테마를 적용하는 CSS 파일의 공개적 HTTP 주소를 "css_url"변수를 사용하여 지정하십시오. 이 변수가 공백이면 기본 테마가 적용됩니다.

---


### 7. <a name="SECTION7"></a>서명 포맷

#### 7.0 기초

CIDRAM에서 사용되는 서명의 형식과 구조에 대한 설명은 사용자 정의 시그니처 파일에 기재되어 있습니다. 자세한 내용은 문서를 참조하십시오.

모든 IPv4 서명이 형식을 따릅니다: `xxx.xxx.xxx.xxx/yy [기능] [매개 변수]`
- `xxx.xxx.xxx.xxx`는 CIDR 블록의 시작을 나타냅니다 (블록의 첫 번째 IP 주소 옥텟).
- `yy`는 블록 사이즈를 나타냅니다 (1-32).
- `[기능]`스크립트에 서명을 어떻게 처리를 지시합니다.
- `[매개 변수]`은 `[기능]`에 필요, 추가 정보를 나타냅니다.

모든 IPv6 서명이 형식을 따릅니다: `xxxx:xxxx:xxxx:xxxx::xxxx/yy [기능] [매개 변수]`
- `xxxx:xxxx:xxxx:xxxx::xxxx`는 CIDR 블록의 시작을 나타냅니다 (블록의 첫 번째 IP 주소 옥텟). 전체 표현 및 약식 표기가 모두 가능합니다. 그들은 IPv6 사양을 준수해야합니다 하나의 예외를 제외하고: CIDRAM에서는 IPv6 주소는 생략로 시작할 수 없습니다. 예를 들면: `::1/128`는 `0::1/128`, 그리고 `::0/128`는 `0::/128`로 표시해야합니다.
- `yy`는 블록 사이즈를 나타냅니다 (1-128).
- `[기능]`스크립트에 서명을 어떻게 처리를 지시합니다.
- `[매개 변수]`은 `[기능]`에 필요, 추가 정보를 나타냅니다.

서명 파일의 줄 바꿈은 Unix 표준을 사용해야합니다 (`%0A`, `\n`). 다른 표준도 사용할 수 있지만 권장되지 않습니다 (예를 들어, Windows `%0D%0A`, `\r\n`, Mac `%0D`, `\r`, 등). 비 Unix 개행는 정규화됩니다.

정확하고 올바른 CIDR 표기법이 필요합니다. 스크립트는 부정확 한 표기 (또는 부정확 한 표기를 따른 서명)을 인식하지 않습니다. 또한 모든 CIDR은 균등하게 나눌 필요가 있습니다 (예를 들어, `10.128.0.0`에서`11.127.255.255`까지 모두 차단하려는 경우, `10.128.0.0/8`는 스크립트가 인식되지 않습니다, 그러나, `10.128.0.0/9`자 `11.0.0.0/9`를 함께 사용하면 스크립트에 의해 인식됩니다).

스크립트가 인식되지 않는 시그니처 파일의 것은 무시됩니다. 즉, 서명 파일을 손상없이 거의 모든 데이터를 서명 파일에 안전하게 넣을 수 있습니다. 서명 파일의 댓글은 허용입니다. 특별한 서식이 필요하지 않습니다. 쉘 형식의 해시가 권장되지만 강제는되지 않습니다 (쉘 스타일 해시는 IDE이나 일반 텍스트 편집기에 도움이됩니다).

"기능"의 가능한 값:
- Run
- Whitelist
- Greylist
- Deny

"Run"을 사용하면 서명이 트리거되면 스크립트는`require_once` 문이 (`[매개 변수]` 값으로 지정됩니다) 외부의 PHP 스크립트의 실행을 시도합니다. 작업 디렉토리는 `/vault/` 디렉토리입니다.

예: `127.0.0.0/8 Run example.php`

특정 IP/CIDR에 대해 특정 PHP 코드를 실행하는 경우에 유용합니다.

"Whitelist"를 사용하면 서명이 트리거되면 스크립트는 모든 검색을 재설정합니다 (뭔가의 검출이 있었을 경우) 테스트 기능을 종료합니다. `[매개 변수]`는 무시됩니다. 이것은 IP 또는 CIDR 화이트리스트에 등록하는 것과 같습니다.

예: `127.0.0.1/32 Whitelist`

"Greylist"을 사용하면 서명이 트리거되면 스크립트는 모든 검색을 재설정합니다 (뭔가의 검출이 있었을 경우) 처리를 계속하기 위해 다음의 서명 파일로 바로 이동한다. `[매개 변수]`는 무시됩니다.

예: `127.0.0.1/32 Greylist`

"Deny"를 사용하면 서명이 트리거되면 보호 된 페이지에 대한 액세스가 거부됩니다 (IP/CIDR 화이트리스트에 등록되어 있지 않은 경우). "Deny"실제로 IP 주소와 CIDR 범위를 차단하기 위해 사용하는 것입니다. "Deny"를 사용하는 서명이 트리거 될 때 "액세스 거부"페이지가 생성되고 보호 된 페이지에 대한 요청을 종료합니다.

"Deny"에 의해 받아 들여진 `[매개 변수]`값은 "액세스 거부"페이지 출력 처리됩니다 요청 된 페이지에 대한 액세스가 거부 된 이유는 클라이언트/사용자에게 제공됩니다. 그것은 짧고 간단한 문장을 할 수 있습니다 (왜 그들을 차단하는 것을 선택했는지 설명하기 위해). 또한 축약 할 수 있습니다 (사전 준비된 설명을 클라이언트/사용자에게 제공합니다).

미리 준비된 설명은 L10N의 지원이 스크립트로 번역 할 수 있습니다. 번역은 스크립트 구성의`lang` 지시문을 사용하여 지정된 언어에 따라 이루어집니다. 또한 이러한 단축형 단어를 사용하는 경우 `[매개 변수]`값에 따라 "Deny"서명을 무시하도록 스크립트에 지시 할 수 있습니다. 이것은 스크립트 설정에 지정된 지시어를 통해 이루어집니다 (각각의 약어에 해당하는 지시문이 있습니다). 그러나 다른 `[매개 변수]`값은 L10N이 지원되지 않습니다 (따라서 다른 값은 번역되지 않습니다, 그리고 조직에 의해 통제 가능하지 않다).

약어:
- Bogon
- Cloud
- Generic
- Proxy
- Spam

#### 7.1 태그

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

"기한 태그"를 사용하여 서명의 유효 기간을 지정할 수 있습니다. 만료 된 태그가이 형식을 사용합니다: "년년년년.월월.날날" (아래의 예를 참조하십시오).

```
# 섹션 1.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Expires: 2016.12.31
```

섹션 태그와 만료 태그를 함께 사용할 수; 모두는 옵션입니다 (아래의 예를 참조하십시오).

```
# 예 섹션.
1.2.3.4/32 Deny Generic
Tag: 예 섹션
Expires: 2016.12.31
```

#### 7.2 YAML

##### 7.2.0 YAML 기초

섹션 관련 설정을 정의하기 위해, 간단한 형식의 YAML 마크 업을 서명 파일로 사용할 수 있습니다. 이것은 다른 서명 섹션에 대해 다른 설정을 할 때 유용합니다 (예를 들면: 지원 티켓의 이메일 주소를 지정하려면, 그러나 특정 섹션 만; 특정 서명으로 페이지 리디렉션을 트리거하려면; reCAPTCHA에서 사용하기 위해 서명 섹션을 표시하려면; 개별 서명에 따라 그리고/또는 서명 섹션에 따라, 차단 된 액세스 시도를 별도의 파일에 기록하려면).

서명 파일로 YAML 마크 업의 사용은 옵션입니다 (즉, 당신이 원한다면 그것을 사용할 수 있지만 그렇게 할 필요는 없습니다). 대부분의 (하지만 전부는 아니지만) 구성 지시문을 활용할 수 있습니다.

주의: CIDRAM의 YAML 마크 업의 구현은 매우 단순화되어 매우 제한되어 있습니다. 이것은 YAML 마크 업에 정통한 방법으로하지만 공식 규격을 따르지하거나 준수 할 수없는 CIDRAM의 특정 요구 사항을 충족하기위한 것입니다 (다른 구현과 같은 방식으로 작동하지 않을 가능성이 있고, 다른 프로젝트에 적합하지 않을 수 있습니다).

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

주의: reCAPTCHA 인스턴스는 reCAPTCHA가 유효한 경우에만 사용자에게 제공됩니다 ("usemode"가 1 일 때, 또는 "usemode"가 2 "enabled"이 "true"인 경우), 그리고, 정확히 하나의 서명이 트리거 된 경우 (큰 수없는 작은 수 없다; 여러 서명이 트리거 된 경우 reCAPTCHA 인스턴스는 제공되지 않습니다).

#### 7.3 보조

또한 CIDRAM 특정 서명 섹션을 완전히 무시하려는 경우 `ignore.dat`파일을 사용하여 무시하는 섹션을 지정할 수 있습니다. 새로운 행에`Ignore`과 써주세요 다음, 공간, 그런 CIDRAM 무시하는 섹션의 이름 (다음의 예를 참조하십시오).

```
Ignore 섹션 1
```

자세한 내용은 사용자 정의 시그니처 파일을 참조하십시오.

---


### 8. <a name="SECTION8"></a>자주 묻는 질문 (FAQ)

#### "서명"이란 무엇입니까?

In the context of CIDRAM, a "signature" refers to data that acts as an indicator/identifier for something specific that we're looking for, usually an IP address or CIDR, and includes some instruction for CIDRAM, telling it the best way to respond when it encounters what we're looking for. A typical signature for CIDRAM looks something like this:

`1.2.3.4/32 Deny Generic`

Often (but not always), signatures will bundled together in groups, forming "signature sections", often accompanied by comments, markup, and/or related metadata that can be used to provide additional context for the signatures and/or further instruction.

#### <a name="WHAT_IS_A_CIDR"></a>"CIDR"이란 무엇입니까?

"CIDR" is an acronym for "Classless Inter-Domain Routing" *[[1](https://ko.wikipedia.org/wiki/%EC%82%AC%EC%9D%B4%EB%8D%94_(%EB%84%A4%ED%8A%B8%EC%9B%8C%ED%82%B9)), [2](http://whatismyipaddress.com/cidr)]*, and it's this acronym that's used as part of the name for this package, "CIDRAM", which is an acronym for "Classless Inter-Domain Routing Access Manager".

However, in the context of CIDRAM (such as, within this documentation, within discussions relating to CIDRAM, or within the CIDRAM language data), whenever a "CIDR" (singular) or "CIDRs" (plural) is mentioned or referred to (and thus whereby we use these words as nouns in their own right, as opposed to as acronyms), what's intended and meant by this is a subnet (or subnets), expressed using CIDR notation. The reason that CIDR (or CIDRs) is used instead of subnet (or subnets) is to make it clear that it's specifically subnets expressed using CIDR notation that's being referred to (because CIDR notation is just one of several different ways that subnets can be expressed). CIDRAM could, therefore, be considered a "subnet access manager".

Although this dual meaning of "CIDR" may present some ambiguity in some cases, this explanation, along with the context provided, should help to resolve such ambiguity.

#### "거짓 양성"는 무엇입니까?

일반화 된 상황에서 쉽게 설명 조건의 상태를 테스트 할 때 결과를 참조 할 목적으로 용어 "거짓 양성"의 (*또는: 위양성의 오류, 허위 보도;* 영어: *false positive*; *false positive error*; *false alarm*) 의미는 결과는 "양성"의 것, 그러나 결과는 실수 (즉, 진실의 조건은 "양성/진실"로 간주됩니다, 그러나 정말 "음성/거짓"입니다). "거짓 양성"는 "우는 늑대"와 유사하다고 생각할 수 있습니다 (그 상태는 군 근처에 늑대가 있는지 여부이다, 진실 조건은 "거짓/음성"입니다 무리의 가까이에 늑대가 없기 때문입니다하지만 조건은 "진실/양성"로보고됩니다 목자가 "늑대! 늑대!"를 외쳤다 때문입니다) 또는 의료 검사와 유사 환자가 잘못 진단 된 경우.

몇 가지 관련 용어는 "진실 양성", "진실 음성"와 "거짓 음성"입니다. 이러한 용어가 나타내는 의미: "진실 양성"의 의미는 테스트 결과와 진실 조건이 진실입니다 (즉, "양성"입니다). "진실 음성"의 의미는 테스트 결과와 진실 조건이 거짓 (즉, "음성"입니다). "진실 양성"과 "진실 음성"는 "올바른 추론"로 간주됩니다. "거짓 양성"의 반대는 "거짓 음성"입니다. "거짓 음성"의 의미는 테스트 결과가 거짓입니다 (즉, "음성"입니다) 하지만 진실의 조건이 정말 진실입니다 (즉, "양성"입니다); 두 테스트 결과와 진실 인 조건이 "진실/양성" 해야한다 것입니다.

CIDRAM의 맥락에서 이러한 용어는 CIDRAM 서명과 그들이 차단하는 것을 말합니다. CIDRAM가 잘못 IP 주소를 차단하면 (예를 들어, 부정확 한 서명 구식의 서명 등에 의한), 우리는이 이벤트 "거짓 양성"를 호출합니다. CIDRAM가 IP 주소를 차단할 수없는 경우 (예를 들어, 예상치 못한 위협 서명 누락 등으로 인한), 우리는이 이벤트 "부재 감지"를 호출합니다 ("거짓 음성" 아날로그입니다).

이것은 다음 표에 요약 할 수 있습니다.

&nbsp; | IP 주소를 차단 필요가 CIDRAM 없습니다 | IP 주소를 CIDRAM 차단해야합니다
---|---|---
IP 주소를 CIDRAM 차단하지 않습니다 | 진실 음성 (올바른 추론) | 놓친 (그것은 "거짓 음성"와 같습니다)
IP 주소를 CIDRAM 차단합니다 | __거짓 양성__ | 진실 양성 (올바른 추론)

#### CIDRAM는 나라 전체를 차단할 수 있습니까?

예. 이것을 달성하는 가장 쉬운 방법은 Macmathan 제공하는 국가 선택적 차단 목록의 일부를 설치합니다. 이것은 프론트 엔드 업데이트 페이지에서 직접 할 수 있습니다. 또는, 프론트 엔드를 계속 사용 중지하려는 경우, **[국가 선택적 차단 목록의 다운로드 페이지](https://macmathan.info/blocklists)** 에서 다운로드 할 수 있습니다. 다운로드 후, 그들을 vault에 업로드, 관련 지시에 의해 지명하십시오.

#### 서명은 얼마나 자주 업데이트됩니까?

업 데이트 빈도는 서명 파일에 따라 다릅니다. CIDRAM 서명 파일의 모든 메인테이너가 자주 업 데이트를 시도하지만, 우리의 여러분에게는 그 밖에도 다양한 노력이있어, 우리는 프로젝트 외부에서 생활하고 있으며, 아무도 재정적으로 보상되지 않는, 따라서 정확한 업 데이트 일정은 보장되지 않습니다. 일반적으로 충분한 시간이 있으면 서명이 업 데이트됩니다. 메인테이너는 필요성과 범위 사이의 변화의 빈도에 따라 우선 순위를 내려고한다. 당신이 뭔가를 제공 할 수 있다면, 원조는 항상 높게 평가됩니다.

#### CIDRAM을 사용하는 데 문제가 발생했지만 무엇을 해야할지 모르겠어요! 도와주세요!

- 당신은 최신 소프트웨어 버전을 사용하고 있습니까? 당신은 최신 서명 파일 버전을 사용하고 있습니까? 그렇지 않은 경우, 먼저 업 데이트하십시오. 문제가 해결되지 여부를 확인하십시오. 그것이 계속되면 읽어보십시오.
- 당신은 문서를 확인 했습니까? 만약 그렇지 않으면, 그렇지하십시오. 문서를 사용하여 문제를 해결할 수없는 경우, 계속 읽어보십시오.
- **[이슈 페이지를](https://github.com/Maikuolan/CIDRAM/issues)** 확인 했습니까? 문제가 이전에 언급되어 있는지 확인하십시오. 제안, 아이디어, 솔루션이 제공되었는지 여부를 확인하십시오.
- **[Spambot Security가 제공하는 CIDRAM 지원 포럼을](http://www.spambotsecurity.com/forum/viewforum.php?f=61)** 확인 했습니까? 문제가 이전에 언급되어 있는지 확인하십시오. 제안, 아이디어, 솔루션이 제공되었는지 여부를 확인하십시오.
- 문제가 해결되지 않으면 알려 주시기 바랍니다. 이슈 페이지 또는 지원 포럼과 새로운 토론을 창조한다.

#### 나는 CIDRAM 의해 웹 사이트에서 차단되어 있습니다! 도와주세요!

CIDRAM는 웹 사이트 소유자가 원하지 않는 트래픽을 차단하는 수단을 제공합니다, 하지만 웹 사이트 소유자는 그 사용 방법을 결정해야합니다. 서명 파일에 오류 검출이있는 경우, 정정을 할 수, 그러나 특정 웹 사이트에서 차단 해제되어 관해서, 당신은 웹 사이트 소유자에게 문의해야합니다. 수정이 이루어지면 업데이트가 필요합니다. 그렇지 않으면 문제를 해결하는 것은 그들의 책임입니다. 맞춤화와 개인 선택은 전적으로 우리가 통제 할 수없는 것입니다.

#### 5.4.0보다 오래된 PHP 버전에서 CIDRAM을 사용하고 싶습니다; 도울 수 있니?

아니오. PHP 5.4.0은 2014 년 공식 EoL에 ("End of Life" / 삶의 끝) 도달했습니다. 2015 년에 연장 된 보안 지원이 종료되었습니다. 현재는 2017이며 PHP 7.1.0을 이미 사용할 수 있습니다. 현재, PHP 5.4.0 및 모든 더 최신 PHP 버전 CIDRAM를 사용하기위한 지원이 제공되고 있습니다. 더 오래된 PHP 버전에 대한 지원은 제공하지 않습니다.

#### 단일 CIDRAM 설치를 사용하여 여러 도메인을 보호 할 수 있습니까?

Yes. CIDRAM installations are not naturally locked to specific domains, and can therefore be used to protect multiple domains. Generally, we refer to CIDRAM installations protecting only one domain as "single-domain installations", and we refer to CIDRAM installations protecting multiple domains and/or sub-domains as "multi-domain installations". If you operate a multi-domain installation and need to use different sets of signature files for different domains, or need CIDRAM to be configured differently for different domains, it's possible to do this. After loading the configuration file (`config.ini`), CIDRAM will check for the existence of a "configuration overrides file" specific to the domain (or sub-domain) being requested (`the-domain-being-requested.tld.config.ini`), and if found, any configuration values defined by the configuration overrides file will be used for the execution instance instead of the configuration values defined by the configuration file. Configuration overrides files are identical to the configuration file, and at your discretion, may contain either the entirety of all configuration directives available to CIDRAM, or whichever small subsection required which differs from the values normally defined by the configuration file. Configuration overrides files are named according to the domain that they are intended for (so, for example, if you need a configuration overrides file for the domain, `http://www.some-domain.tld/`, its configuration overrides file should be named as `some-domain.tld.config.ini`, and should be placed within the vault alongside the configuration file, `config.ini`). The domain name for the execution instance is derived from the `HTTP_HOST` header of the request; "www" is ignored.

---


최종 업데이트: 2017년 4월 28일.

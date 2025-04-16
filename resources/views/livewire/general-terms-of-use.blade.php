<div class="row justify-content-center mt-2">
    @section('title')
        {{ __('General Terms of Use') }}
    @endsection
    <div class="col-lg-10">
        <div class="card">
            <div class="bg-warning-subtle position-relative">
                <div class="card-body p-5">
                    <a href="{{route('home',app()->getLocale())}}" class="d-block">
                        <img src="{{ Vite::asset('resources/images/2earn.png') }}"
                             alt="2earn.cash">
                    </a>
                </div>
                <div class="card-body p-5">
                    <div class="text-center">
                        <h3>{{__('General Terms of Use')}}</h3>
                        <p class="mb-0 text-muted">{{__('General Terms of Use last update')}}</p>
                    </div>
                </div>
                <div class="shape">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                         width="1440" height="60" preserveAspectRatio="none"
                         viewBox="0 0 1440 60">
                        <g mask="url(&quot;#SvgjsMask1001&quot;)" fill="none">
                            <path d="M 0,4 C 144,13 432,48 720,49 C 1008,50 1296,17 1440,9L1440 60L0 60z"
                                  style="fill: var(--vz-secondary-bg);"></path>
                        </g>
                        <defs>
                            <mask id="SvgjsMask1001">
                                <rect width="1440" height="60" fill="#ffffff"></rect>
                            </mask>
                        </defs>
                    </svg>
                </div>
            </div>
            <div class="card-body p-4">

                @if(app()->getLocale()=='fr')
                    <div class="row mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">Introduction</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">
                                    La plateforme 2earn.cash se positionne comme un outil de gestion
                                    centralisé, permettant le suivi des activités et des soldes des utilisateurs,
                                    tout en s’appuyant sur un réseau de plateformes e-commerce
                                    interconnectées : <b>learn2earn.cash</b>, <b>move2earn.cash</b>,
                                    <b>belegant2earn.cash</b>, <b>takecare2earn.cash</b>, <b>shop2earn.cash</b>,
                                    <b>eat2earn.cash,</b> et <b>travel2earn.cash</b>. Bien que ces
                                    plateformes opèrent
                                    de manière autonome dans leurs fonctions respectives, elles partagent
                                    une vision commune et sont unifiées sous le concept directeur de
                                    2earn.cash.
                                </p>
                                <p class="text-muted">
                                    Les présentes <b>Conditions Générales d’Utilisation</b> s’appliquent à tous les
                                    utilisateurs de la plateforme, qu’il s’agisse d’utilisateurs réguliers,
                                    d’investisseurs ayant acquis des actions ou de partenaires d’affaires
                                    explorant des opportunités au sein de notre <b>Business Hub</b>. Le simple fait
                                    d’accéder à la plateforme, de naviguer sur ses pages ou d’utiliser ses
                                    fonctionnalités constitue une acceptation inconditionnelle des présentes
                                    conditions par l’utilisateur.
                                </p>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">Inscription</h6>
                            </div>
                            <div class="card-body">
                                <ul class="text-muted">
                                    <li>
                                        Lors de l’inscription sur notre plateforme, il vous sera demandé de
                                        fournir un numéro de téléphone, utilisé comme identifiant unique. Afin
                                        de garantir la sécurité de vos informations, <b>un code OTP (One-Time
                                            Password)</b> sera envoyé par SMS. Vous devrez entrer ce code pour valider
                                        la procédure d’inscription.
                                    </li>
                                    <li>
                                        Un mot de passe provisoire vous sera également transmis par SMS, que
                                        vous pourrez modifier ultérieurement afin de sécuriser l’accès à votre
                                        compte.
                                    </li>
                                    <li>
                                        Votre compte est strictement personnel et ne peut être transféré ou
                                        partagé avec un tiers. Tout manquement à cette règle entraînera la
                                        suspension ou la suppression immédiate de votre compte.
                                    </li>
                                    <li>
                                        Vous avez la possibilité de mettre à jour certaines de vos informations
                                        (telles que l'adresse e-mail, le mot de passe, etc.) à tout moment pour
                                        bénéficier pleinement des services offerts. Toutefois, des informations
                                        telles que le nom, le prénom et la date de naissance ne peuvent être
                                        modifiées une fois saisies.
                                    </li>
                                    <li>
                                        Si vous souhaitez clôturer votre compte, il vous suffit de nous contacter
                                        via les coordonnées fournies sur la plateforme. La suppression de votre
                                        compte entraînera la perte définitive de tous les soldes associés.
                                    </li>
                                </ul>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">Confidentialité et Protection des Données</h6>
                            </div>
                            <div class="card-body">
                                <ul class="text-muted">
                                    <li>Nous collectons des informations à caractère personnel que vous nous
                                        fournissez volontairement afin de traiter vos demandes et d’améliorer
                                        votre expérience utilisateur
                                    </li>
                                    <li>Nous nous engageons à utiliser vos données de manière éthique, en les
                                        protégeant contre tout accès non autorisé, et en conformité avec les lois
                                        relatives à la protection des données et de la vie privée.
                                    </li>
                                    <li>Vous avez le droit de modifier, rectifier ou supprimer vos informations
                                        personnelles à tout moment, selon les dispositions légales applicables.
                                    </li>
                                    <li>Nous mettons en œuvre des mesures de sécurité avancées pour
                                        garantir la confidentialité et l’intégrité de vos données
                                    </li>
                                </ul>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">Responsabilité de l’Utilisateur</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">
                                    L’utilisateur s’engage à respecter les règles suivantes lors de l’utilisation
                                    de la plateforme
                                </p>
                                <ol class="text-muted">
                                    <li>
                                        Ne pas utiliser la plateforme de manière abusive ou contraire aux
                                        règles d’éthique
                                    </li>
                                    <li>
                                        Ne pas publier de contenus à caractère illégal, offensant, ou
                                        discriminatoire
                                    </li>
                                    <li>
                                        Ne pas tenter d’exploiter les vulnérabilités de sécurité ou de
                                        contourner les dispositifs de protection mis en place.
                                    </li>
                                </ol>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">Propriété Intellectuelle</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">
                                    Toutes les marques, logos, concepts, et contenus de la plateforme
                                    <b>2earn.cash</b> sont protégés par des droits de propriété intellectuelle. Ils ne
                                    peuvent être reproduits, modifiés, ou utilisés sans autorisation expresse
                                    et préalable de la société
                                </p>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">Violation des Conditions Générales</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">
                                    En cas de non-respect des présentes conditions, <b>2earn.cash</b> se réserve le
                                    droit de suspendre ou de restreindre l’accès de l’utilisateur à tout ou
                                    partie des services, ainsi que de prendre les mesures juridiques
                                    nécessaires.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                @if(app()->getLocale()=='en')
                    <div class="row mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">Introduction</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">
                                    The <b>2earn.cash</b> platform is positioned as a centralized management
                                    tool
                                    that allows users to track activities and balances, while relying on a
                                    network of interconnected e-commerce platforms: <b>learn2earn.cash</b>,
                                    <b>move2earn.cash</b>, <b>belegant2earn.cash</b>, <b>takecare2earn.cash</b>,
                                    shop2earn.cash, <b>eat2earn.cash</b>, and
                                    <b>travel2earn.cash</b>. Although these
                                    platforms operate autonomously within their respective functions, they
                                    share a common vision and are unified under the central concept of
                                    <b>2earn.cash</b>.
                                </p>
                                <p class="text-muted">
                                    The present <b>General Terms of Use</b> apply to all users of the
                                    platform,
                                    whether they are regular users, investors who have purchased shares, or
                                    business partners exploring opportunities through our <b>Business Hub</b>.
                                    By
                                    merely accessing the platform, browsing its pages, or using its features,
                                    the user unconditionally accepts these terms.
                                </p>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">Registration</h6>
                            </div>
                            <div class="card-body">
                                <ul class="text-muted">
                                    <li>
                                        When registering on our platform, you will be asked to provide a phone
                                        number to be used as a unique identifier. To ensure the security of your
                                        information, a <b>One-Time Password (OTP)</b> code will be sent via
                                        SMS. You
                                        must enter this code to validate the registration process.
                                    </li>
                                    <li>
                                        A temporary password will also be sent to you by SMS, which you can
                                        change later to ensure secure access to your account
                                    </li>
                                    <li>
                                        Your account is strictly personal and cannot be transferred or shared
                                        with any third party. Any violation of this rule will result in the suspension
                                        or immediate deletion of your account.
                                    </li>
                                    <li>
                                        You can update some of your information (e.g., email address, password,
                                        etc.) at any time to fully benefit from the services offered. However,
                                        information such as your name, surname, and date of birth cannot be
                                        modified once entered.
                                    </li>
                                    <li>
                                        If you wish to close your account, simply contact us using the details
                                        provided on the platform. Deleting your account will result in the
                                        permanent loss of all associated balances
                                    </li>
                                </ul>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">Confidentiality and Data Protection</h6>
                            </div>
                            <div class="card-body">
                                <ul class="text-muted">
                                    <li>
                                        We collect personal information that you voluntarily provide in order to
                                        process your requests and enhance your user experience.
                                    </li>
                                    <li>
                                        We commit to using your data ethically, protecting it from any
                                        unauthorized access, and in full compliance with data protection and
                                        privacy laws.
                                    </li>
                                    <li>
                                        You have the right to modify, rectify, or delete your personal
                                        information at any time, in accordance with applicable legal provisions.
                                    </li>
                                    <li>
                                        We implement advanced security measures to ensure the confidentiality
                                        and integrity of your data.
                                    </li>
                                </ul>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">User Responsibility:</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">
                                    The user agrees to abide by the following rules when using the platform:
                                </p>
                                <ol class="text-muted">
                                    <li>Do not misuse the platform or use it in a manner that violates
                                        ethical guidelines
                                    </li>
                                    <li>Do not publish content that is illegal, offensive, or discriminatory.

                                    </li>
                                    <li>Do not attempt to exploit security vulnerabilities or bypass the
                                        protection features in place
                                    </li>
                                </ol>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">Intellectual Property</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">
                                    All trademarks, logos, concepts, and content of the <b>2earn.cash</b>
                                    platform
                                    are protected by intellectual property rights. They may not be
                                    reproduced, modified, or used without the express prior authorization of
                                    the company
                                </p>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">Violation of General Terms</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">
                                    In the event of a breach of these terms, <b>2earn.cash</b> reserves the
                                    right to
                                    suspend or restrict the user's access to all or part of the services, as well
                                    as to take the necessary legal actions.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                @if(app()->getLocale()=='ar')
                    <div class="row mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">مقدمة:</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">
                                    تتموضع منصة <b> 2earn.cash </b> كأداة إدارة مركزية تتيح للمستخدمين متابعة
                                    أنشطتهم وأرصدة حساباتهم، مع الإستناد إلى شبكة من المنصات التجارية
                                    <b>learn2earn.cash</b>، <b>move2earn.cash</b>، :المترابطة اإللكترونية
                                    <b>belegant2earn.cash</b>، <b>takecare2earn.cash</b>، <b>shop2earn.cash</b>،
                                    <b> eat2earn.cash </b> و<b> travel2earn.cash </b> . وعلى الرغم من أن هذه المنصات
                                    تعمل
                                    بشكل مستقل في وظائفها الخاصة، الا أنها تشترك في رؤية موحدة ومفهوم
                                    مركزي يجمعها تحت مظلة <b>2cash.earn</b>..
                                </p>
                                <p class="text-muted">
                                    تنطبق هذه الشروط العامة للإستخدام على جميع مستخدمي المنصة،
                                    سواء كانوا مستخدمين عاديين، أو مستثمرين يمتلكون أسهمًا، أو شركاء
                                    أعمال يبحثون عن فرص من خلال <b>Hub Business</b>. إن مجرد الوصول إلى المنصة
                                    قبوًل غير مشروط لهذه أو تصفح صفحاتها أو استخدام ميزاتها ُيعتبر ا
                                    الشروط.
                                </p>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">التسجيل</h6>
                            </div>
                            <div class="card-body">
                                <ul class="text-muted">
                                    <li>
                                        - عند التسجيل على منصتنا، سيُطلب منك تقديم رقم هاتف يتم استخدامه كمعرف فريد.
                                        ولضمان أمان معلوماتك، سيتم إرسال <b>رمز تحقق لمرة واحدة (OTP)</b> عبر رسالة
                                        نصية. يجب عليك إدخال هذا الرمز لتأكيد عملية التسجيل.

                                    </li>
                                    <li>
                                        إرسال كلمة مرور مؤقتة إليك عبر رسالة نصية، يمكنك تغييرها ًض - سيتم ا
                                        الح ًق لضمان الوصول اآلمن إلى حسابك.
                                    </li>
                                    <li>
                                        حسابك شخصي بحت وال يمكن نقله أو مشاركته مع أي طرف ثالث. أي
                                        انتهاك لهذه القاعدة سيؤدي إلى تعليق أو حذف حسابك بشكل فوري.
                                    </li>
                                    <li>
                                        - يمكنك تحديث بعض معلوماتك )مثل البريد اإللكتروني، وكلمة المرور، وما إلى
                                        ذلك( في أي وقت لالستفادة الكاملة من الخدمات المقدمة. ومع ذلك، ال يمكن
                                        تعديل معلومات مثل اسمك، ولقبك، وتاريخ ميالدك بعد إدخالها.
                                    </li>
                                    <li>
                                        إذا كنت ترغب في إغالق حسابك، فما عليك سوى االتصال بنا باستخدام
                                        التفاصيل المقدمة على المنصة. سيؤدي حذف حسابك إلى فقدان نهائي لجميع
                                        األرصدة المرتبطة.
                                    </li>
                                </ul>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">الخصوصية وحماية البيانات:</h6>
                            </div>
                            <div class="card-body">
                                <ul class="text-muted">
                                    <li>
                                        طواعي من أجل معالجة ًة - نقوم بجمع المعلومات الشخصية التي تقدمها لنا
                                        طلباتك وتحسين تجربتك كمستخدم.
                                    </li>
                                    <li>
                                        نحن ملتزمون باستخدام بياناتك بطريقة أخالقية وحمايتها من أي وصول غير
                                        مصرح به، وبما يتوافق مع قوانين حماية البيانات والخصوصية.
                                    </li>
                                    <li>
                                        لديك الحق في تعديل أو تصحيح أو حذف معلوماتك الشخصية في أي وقت،
                                        وف ًق لألحكام القانونية المعمول بها.
                                    </li>
                                    <li>
                                        - نقوم بتنفيذ تدابير أمان متقدمة لضمان سرية وسالمة بياناتك.
                                    </li>
                                </ul>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">مسؤولية المستخدم</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">
                                    يوافق المستخدم على االلتزام بالقواعد التالية عند استخدام المنصة
                                </p>
                                <ol class="text-muted">
                                    <li>
                                        عدم استخدام المنصة بشكل مسيء أو بما يخالف المبادئ الأخلاقية.

                                    </li>
                                    <li>
                                        . عدم نشر محتويات غير قانونية أو مسيئة أو تمييزية.
                                    </li>
                                    <li>
                                        عدم محاولة استغالل نقاط الضعف األمنية أو التحايل على خصائص الحماية
                                        الموضوعة.
                                    </li>
                                </ol>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">الملكية الفكرية</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">
                                    جميع العالمات التجارية، والشعارات، والمفاهيم، والمحتويات الموجودة على
                                    منصة <b> 2earn.cash </b> محمية بحقوق الملكية الفكرية. ال يجوز إعادة إنتاجها أو
                                    تعديلها أو استخدامها دون إذن صريح مسبق من الشركة.

                                </p>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">انتهاك الشروط العامة:</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">
                                    في حالة حدوث أي انتهاك لهذه الشروط، تحتفظ <b> 2earn.cash </b> بالحق في تعليق
                                    أو تقييد وصول المستخدم إلى كل أو جزء من الخدمات، باإلضافة إلى اتخاذ
                                    اإلجراءات القانونية الالزمة.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                @if(app()->getLocale()=='tr')
                    <div class="row mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">Giriş</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">
                                    <b>2earn.cash</b> platformu, kullanıcıların faaliyetlerini ve bakiyelerini takip
                                    etmelerine olanak tanıyan merkezi bir yönetim aracı olarak konumlanmıştır. Bu araç,
                                    birbirine bağlı e-ticaret platformları ağına dayanır: <b>learn2earn.cash</b>, <b>move2earn.cash</b>,
                                    <b>belegant2earn.cash</b>, <b>takecare2earn.cash</b>, shop2earn.cash, <b>eat2earn.cash</b>
                                    ve <b>travel2earn.cash</b>. Bu platformlar kendi işlevleri içinde bağımsız olarak
                                    çalışsa da ortak bir vizyonu paylaşırlar ve <b>2earn.cash</b> merkezi konsepti
                                    altında birleştirilmişlerdir.
                                </p>
                                <p class="text-muted">
                                    Bu <b>Genel Kullanım Şartları</b>, platformun tüm kullanıcılarına uygulanır; ister
                                    düzenli kullanıcılar, ister hisse satın almış olan yatırımcılar, isterse <b>İş
                                        Merkezi</b> aracılığıyla fırsatları keşfeden iş ortakları olsun. Platforma
                                    sadece erişim sağlamak, sayfalarını gezinmek veya özelliklerini kullanmakla
                                    kullanıcı bu şartları koşulsuz olarak kabul etmiş olur.
                                </p>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">Kayıt Olma</h6>
                            </div>
                            <div class="card-body">
                                <ul class="text-muted">
                                    <li>
                                        Platformumuza kaydolurken, benzersiz bir kimlik olarak kullanılacak bir telefon
                                        numarası sağlamanız istenecektir. Bilgilerinizin güvenliğini sağlamak için bir
                                        <b>Tek Kullanımlık Şifre (OTP)</b> kodu SMS yoluyla gönderilecektir. Kayıt
                                        işlemini doğrulamak için bu kodu girmeniz gerekmektedir.
                                    </li>
                                    <li>
                                        Size SMS ile geçici bir şifre gönderilecektir; bu şifreyi daha sonra
                                        değiştirerek hesabınıza güvenli bir şekilde erişim sağlayabilirsiniz.
                                    </li>
                                    <li>
                                        Hesabınız tamamen kişisel olup, başka bir tarafla aktarılması veya paylaşılması
                                        yasaktır. Bu kuralın ihlali, hesabınızın askıya alınması veya derhal silinmesi
                                        ile sonuçlanacaktır.
                                    </li>
                                    <li>
                                        E-posta adresi, şifre vb. bilgilerinizi, sunulan hizmetlerden tam olarak
                                        yararlanabilmek için istediğiniz zaman güncelleyebilirsiniz. Ancak, adınız,
                                        soyadınız ve doğum tarihiniz gibi bilgiler girildikten sonra değiştirilemez.
                                    </li>
                                    <li>
                                        Hesabınızı kapatmak isterseniz, platformda sağlanan detayları kullanarak bizimle
                                        iletişime geçebilirsiniz. Hesabınızın silinmesi, ilgili tüm bakiyelerin kalıcı
                                        olarak kaybedilmesi ile sonuçlanacaktır.
                                    </li>
                                </ul>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">Gizlilik ve Veri Koruma</h6>
                            </div>
                            <div class="card-body">
                                <ul class="text-muted">
                                    <li>
                                        Taleplerinizi işlemek ve kullanıcı deneyiminizi geliştirmek amacıyla gönüllü
                                        olarak sağladığınız kişisel bilgileri topluyoruz.
                                    </li>
                                    <li>
                                        Verilerinizi etik bir şekilde kullanmayı, yetkisiz erişimden korumayı ve veri
                                        koruma ve gizlilik yasalarına tam uyum sağlamayı taahhüt ederiz.
                                    </li>
                                    <li>
                                        Mevcut yasal hükümler uyarınca kişisel bilgilerinizi istediğiniz zaman
                                        değiştirme, düzeltme veya silme hakkına sahipsiniz.
                                    </li>
                                    <li>
                                        Verilerinizin gizliliğini ve bütünlüğünü sağlamak için gelişmiş güvenlik
                                        önlemleri uyguluyoruz.
                                    </li>
                                </ul>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">Kullanıcı Sorumluluğu</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">
                                    Kullanıcı platformu kullanırken aşağıdaki kurallara uymayı kabul eder:
                                </p>
                                <ol class="text-muted">
                                    <li>Platformu yanlış kullanmayın veya etik kuralları ihlal edecek şekilde
                                        kullanmayın.
                                    </li>
                                    <li>Yasa dışı, saldırgan veya ayrımcı içerikler yayınlamayın.</li>
                                    <li>Güvenlik açıklarını kötüye kullanmaya veya mevcut koruma özelliklerini atlamaya
                                        çalışmayın.
                                    </li>
                                </ol>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">Fikri Mülkiyet</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">
                                    <b>2earn.cash</b> platformunun tüm ticari markaları, logoları, konseptleri ve
                                    içerikleri fikri mülkiyet hakları ile korunmaktadır. Şirketin açık ön izni olmadan
                                    kopyalanamaz, değiştirilemez veya kullanılamaz.
                                </p>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">Genel Şartların İhlali</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">
                                    Bu şartların ihlali durumunda <b>2earn.cash</b>, kullanıcının tüm hizmetlere veya
                                    hizmetlerin bir kısmına erişimini askıya alma veya sınırlandırma hakkını saklı tutar
                                    ve gerekli yasal işlemleri başlatabilir.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                @if(app()->getLocale()=='es')
                    <div class="row mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">Giriş</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">
                                    <b>2earn.cash</b> platformu, kullanıcıların faaliyetlerini ve bakiyelerini takip
                                    etmelerine olanak tanıyan merkezi bir yönetim aracı olarak konumlanmıştır. Bu araç,
                                    birbirine bağlı e-ticaret platformları ağına dayanır: <b>learn2earn.cash</b>, <b>move2earn.cash</b>,
                                    <b>belegant2earn.cash</b>, <b>takecare2earn.cash</b>, shop2earn.cash, <b>eat2earn.cash</b>
                                    ve <b>travel2earn.cash</b>. Bu platformlar kendi işlevleri içinde bağımsız olarak
                                    çalışsa da ortak bir vizyonu paylaşırlar ve <b>2earn.cash</b> merkezi konsepti
                                    altında birleştirilmişlerdir.
                                </p>
                                <p class="text-muted">
                                    Bu <b>Genel Kullanım Şartları</b>, platformun tüm kullanıcılarına uygulanır; ister
                                    düzenli kullanıcılar, ister hisse satın almış olan yatırımcılar, isterse <b>İş
                                        Merkezi</b> aracılığıyla fırsatları keşfeden iş ortakları olsun. Platforma
                                    sadece erişim sağlamak, sayfalarını gezinmek veya özelliklerini kullanmakla
                                    kullanıcı bu şartları koşulsuz olarak kabul etmiş olur.
                                </p>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">Kayıt Olma</h6>
                            </div>
                            <div class="card-body">
                                <ul class="text-muted">
                                    <li>
                                        Platformumuza kaydolurken, benzersiz bir kimlik olarak kullanılacak bir telefon
                                        numarası sağlamanız istenecektir. Bilgilerinizin güvenliğini sağlamak için bir
                                        <b>Tek Kullanımlık Şifre (OTP)</b> kodu SMS yoluyla gönderilecektir. Kayıt
                                        işlemini doğrulamak için bu kodu girmeniz gerekmektedir.
                                    </li>
                                    <li>
                                        Size SMS ile geçici bir şifre gönderilecektir; bu şifreyi daha sonra
                                        değiştirerek hesabınıza güvenli bir şekilde erişim sağlayabilirsiniz.
                                    </li>
                                    <li>
                                        Hesabınız tamamen kişisel olup, başka bir tarafla aktarılması veya paylaşılması
                                        yasaktır. Bu kuralın ihlali, hesabınızın askıya alınması veya derhal silinmesi
                                        ile sonuçlanacaktır.
                                    </li>
                                    <li>
                                        E-posta adresi, şifre vb. bilgilerinizi, sunulan hizmetlerden tam olarak
                                        yararlanabilmek için istediğiniz zaman güncelleyebilirsiniz. Ancak, adınız,
                                        soyadınız ve doğum tarihiniz gibi bilgiler girildikten sonra değiştirilemez.
                                    </li>
                                    <li>
                                        Hesabınızı kapatmak isterseniz, platformda sağlanan detayları kullanarak bizimle
                                        iletişime geçebilirsiniz. Hesabınızın silinmesi, ilgili tüm bakiyelerin kalıcı
                                        olarak kaybedilmesi ile sonuçlanacaktır.
                                    </li>
                                </ul>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">Gizlilik ve Veri Koruma</h6>
                            </div>
                            <div class="card-body">
                                <ul class="text-muted">
                                    <li>
                                        Taleplerinizi işlemek ve kullanıcı deneyiminizi geliştirmek amacıyla gönüllü
                                        olarak sağladığınız kişisel bilgileri topluyoruz.
                                    </li>
                                    <li>
                                        Verilerinizi etik bir şekilde kullanmayı, yetkisiz erişimden korumayı ve veri
                                        koruma ve gizlilik yasalarına tam uyum sağlamayı taahhüt ederiz.
                                    </li>
                                    <li>
                                        Mevcut yasal hükümler uyarınca kişisel bilgilerinizi istediğiniz zaman
                                        değiştirme, düzeltme veya silme hakkına sahipsiniz.
                                    </li>
                                    <li>
                                        Verilerinizin gizliliğini ve bütünlüğünü sağlamak için gelişmiş güvenlik
                                        önlemleri uyguluyoruz.
                                    </li>
                                </ul>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">Kullanıcı Sorumluluğu</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">
                                    Kullanıcı platformu kullanırken aşağıdaki kurallara uymayı kabul eder:
                                </p>
                                <ol class="text-muted">
                                    <li>Platformu yanlış kullanmayın veya etik kuralları ihlal edecek şekilde
                                        kullanmayın.
                                    </li>
                                    <li>Yasa dışı, saldırgan veya ayrımcı içerikler yayınlamayın.</li>
                                    <li>Güvenlik açıklarını kötüye kullanmaya veya mevcut koruma özelliklerini atlamaya
                                        çalışmayın.
                                    </li>
                                </ol>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">Fikri Mülkiyet</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">
                                    <b>2earn.cash</b> platformunun tüm ticari markaları, logoları, konseptleri ve
                                    içerikleri fikri mülkiyet hakları ile korunmaktadır. Şirketin açık ön izni olmadan
                                    kopyalanamaz, değiştirilemez veya kullanılamaz.
                                </p>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">Genel Şartların İhlali</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">
                                    Bu şartların ihlali durumunda <b>2earn.cash</b>, kullanıcının tüm hizmetlere veya
                                    hizmetlerin bir kısmına erişimini askıya alma veya sınırlandırma hakkını saklı tutar
                                    ve gerekli yasal işlemleri başlatabilir.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                @if(app()->getLocale()=='de')
                    <div class="row mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">Giriş</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">
                                    <b>2earn.cash</b> platformu, kullanıcıların faaliyetlerini ve bakiyelerini takip
                                    etmelerine olanak tanıyan merkezi bir yönetim aracı olarak konumlanmıştır. Bu araç,
                                    birbirine bağlı e-ticaret platformları ağına dayanır: <b>learn2earn.cash</b>, <b>move2earn.cash</b>,
                                    <b>belegant2earn.cash</b>, <b>takecare2earn.cash</b>, shop2earn.cash, <b>eat2earn.cash</b>
                                    ve <b>travel2earn.cash</b>. Bu platformlar kendi işlevleri içinde bağımsız olarak
                                    çalışsa da ortak bir vizyonu paylaşırlar ve <b>2earn.cash</b> merkezi konsepti
                                    altında birleştirilmişlerdir.
                                </p>
                                <p class="text-muted">
                                    Bu <b>Genel Kullanım Şartları</b>, platformun tüm kullanıcılarına uygulanır; ister
                                    düzenli kullanıcılar, ister hisse satın almış olan yatırımcılar, isterse <b>İş
                                        Merkezi</b> aracılığıyla fırsatları keşfeden iş ortakları olsun. Platforma
                                    sadece erişim sağlamak, sayfalarını gezinmek veya özelliklerini kullanmakla
                                    kullanıcı bu şartları koşulsuz olarak kabul etmiş olur.
                                </p>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">Kayıt Olma</h6>
                            </div>
                            <div class="card-body">
                                <ul class="text-muted">
                                    <li>
                                        Platformumuza kaydolurken, benzersiz bir kimlik olarak kullanılacak bir telefon
                                        numarası sağlamanız istenecektir. Bilgilerinizin güvenliğini sağlamak için bir
                                        <b>Tek Kullanımlık Şifre (OTP)</b> kodu SMS yoluyla gönderilecektir. Kayıt
                                        işlemini doğrulamak için bu kodu girmeniz gerekmektedir.
                                    </li>
                                    <li>
                                        Size SMS ile geçici bir şifre gönderilecektir; bu şifreyi daha sonra
                                        değiştirerek hesabınıza güvenli bir şekilde erişim sağlayabilirsiniz.
                                    </li>
                                    <li>
                                        Hesabınız tamamen kişisel olup, başka bir tarafla aktarılması veya paylaşılması
                                        yasaktır. Bu kuralın ihlali, hesabınızın askıya alınması veya derhal silinmesi
                                        ile sonuçlanacaktır.
                                    </li>
                                    <li>
                                        E-posta adresi, şifre vb. bilgilerinizi, sunulan hizmetlerden tam olarak
                                        yararlanabilmek için istediğiniz zaman güncelleyebilirsiniz. Ancak, adınız,
                                        soyadınız ve doğum tarihiniz gibi bilgiler girildikten sonra değiştirilemez.
                                    </li>
                                    <li>
                                        Hesabınızı kapatmak isterseniz, platformda sağlanan detayları kullanarak bizimle
                                        iletişime geçebilirsiniz. Hesabınızın silinmesi, ilgili tüm bakiyelerin kalıcı
                                        olarak kaybedilmesi ile sonuçlanacaktır.
                                    </li>
                                </ul>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">Gizlilik ve Veri Koruma</h6>
                            </div>
                            <div class="card-body">
                                <ul class="text-muted">
                                    <li>
                                        Taleplerinizi işlemek ve kullanıcı deneyiminizi geliştirmek amacıyla gönüllü
                                        olarak sağladığınız kişisel bilgileri topluyoruz.
                                    </li>
                                    <li>
                                        Verilerinizi etik bir şekilde kullanmayı, yetkisiz erişimden korumayı ve veri
                                        koruma ve gizlilik yasalarına tam uyum sağlamayı taahhüt ederiz.
                                    </li>
                                    <li>
                                        Mevcut yasal hükümler uyarınca kişisel bilgilerinizi istediğiniz zaman
                                        değiştirme, düzeltme veya silme hakkına sahipsiniz.
                                    </li>
                                    <li>
                                        Verilerinizin gizliliğini ve bütünlüğünü sağlamak için gelişmiş güvenlik
                                        önlemleri uyguluyoruz.
                                    </li>
                                </ul>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">Kullanıcı Sorumluluğu</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">
                                    Kullanıcı platformu kullanırken aşağıdaki kurallara uymayı kabul eder:
                                </p>
                                <ol class="text-muted">
                                    <li>Platformu yanlış kullanmayın veya etik kuralları ihlal edecek şekilde
                                        kullanmayın.
                                    </li>
                                    <li>Yasa dışı, saldırgan veya ayrımcı içerikler yayınlamayın.</li>
                                    <li>Güvenlik açıklarını kötüye kullanmaya veya mevcut koruma özelliklerini atlamaya
                                        çalışmayın.
                                    </li>
                                </ol>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">Fikri Mülkiyet</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">
                                    <b>2earn.cash</b> platformunun tüm ticari markaları, logoları, konseptleri ve
                                    içerikleri fikri mülkiyet hakları ile korunmaktadır. Şirketin açık ön izni olmadan
                                    kopyalanamaz, değiştirilemez veya kullanılamaz.
                                </p>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">Genel Şartların İhlali</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">
                                    Bu şartların ihlali durumunda <b>2earn.cash</b>, kullanıcının tüm hizmetlere veya
                                    hizmetlerin bir kısmına erişimini askıya alma veya sınırlandırma hakkını saklı tutar
                                    ve gerekli yasal işlemleri başlatabilir.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                @if(app()->getLocale()=='ru')
                    <div class="row mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">Введение</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">
                                    Платформа <b>2earn.cash</b> позиционируется как централизованный инструмент
                                    управления, позволяющий пользователям отслеживать деятельность и балансы, опираясь
                                    на сеть взаимосвязанных платформ электронной коммерции: <b>learn2earn.cash</b>, <b>move2earn.cash</b>,
                                    <b>belegant2earn.cash</b>, <b>takecare2earn.cash</b>, shop2earn.cash, <b>eat2earn.cash</b>
                                    и <b>travel2earn.cash</b>. Хотя эти платформы работают автономно в рамках своих
                                    функций, они разделяют общую цель и объединены под центральной концепцией <b>2earn.cash</b>.
                                </p>
                                <p class="text-muted">
                                    Настоящие <b>Общие условия использования</b> применяются ко всем пользователям
                                    платформы, независимо от того, являются ли они постоянными пользователями,
                                    инвесторами, купившими акции, или деловыми партнерами, исследующими возможности
                                    через наш <b>Бизнес Центр</b>. Простое использование платформы, просмотр её страниц
                                    или использование её функций является безусловным принятием этих условий
                                    пользователем.
                                </p>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">Регистрация</h6>
                            </div>
                            <div class="card-body">
                                <ul class="text-muted">
                                    <li>
                                        При регистрации на нашей платформе вам будет предложено указать номер телефона,
                                        который будет использоваться как уникальный идентификатор. Для обеспечения
                                        безопасности вашей информации будет отправлен код <b>Одноразового пароля
                                            (OTP)</b> через SMS. Вы должны ввести этот код для подтверждения процесса
                                        регистрации.
                                    </li>
                                    <li>
                                        Вам также будет отправлен временный пароль через SMS, который вы сможете
                                        изменить позже для обеспечения безопасного доступа к своему аккаунту.
                                    </li>
                                    <li>
                                        Ваш аккаунт строго личный и не может быть передан или разделён с третьими
                                        лицами. Любое нарушение этого правила приведёт к приостановке или немедленному
                                        удалению вашего аккаунта.
                                    </li>
                                    <li>
                                        Вы можете обновить часть вашей информации (например, адрес электронной почты,
                                        пароль и т.д.) в любое время, чтобы в полной мере воспользоваться предлагаемыми
                                        услугами. Однако такие данные, как ваше имя, фамилия и дата рождения, не могут
                                        быть изменены после ввода.
                                    </li>
                                    <li>
                                        Если вы хотите закрыть свой аккаунт, просто свяжитесь с нами, используя
                                        предоставленные на платформе контактные данные. Удаление вашего аккаунта
                                        приведёт к окончательной потере всех связанных с ним балансов.
                                    </li>
                                </ul>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">Конфиденциальность и защита данных</h6>
                            </div>
                            <div class="card-body">
                                <ul class="text-muted">
                                    <li>
                                        Мы собираем личную информацию, которую вы предоставляете добровольно, для
                                        обработки ваших запросов и улучшения вашего пользовательского опыта.
                                    </li>
                                    <li>
                                        Мы обязуемся использовать ваши данные этично, защищать их от
                                        несанкционированного доступа и полностью соблюдать законы о защите данных и
                                        конфиденциальности.
                                    </li>
                                    <li>
                                        Вы имеете право изменять, исправлять или удалять свои личные данные в любое
                                        время в соответствии с действующими правовыми положениями.
                                    </li>
                                    <li>
                                        Мы внедряем передовые меры безопасности для обеспечения конфиденциальности и
                                        целостности ваших данных.
                                    </li>
                                </ul>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">Ответственность пользователя</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">
                                    Пользователь обязуется соблюдать следующие правила при использовании платформы:
                                </p>
                                <ol class="text-muted">
                                    <li>Не злоупотреблять платформой и не использовать её в нарушении этических норм.
                                    </li>
                                    <li>Не публиковать контент, который является незаконным, оскорбительным или
                                        дискриминационным.
                                    </li>
                                    <li>Не пытаться использовать уязвимости системы безопасности или обходить
                                        существующие защитные функции.
                                    </li>
                                </ol>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">Интеллектуальная собственность</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">
                                    Все торговые марки, логотипы, концепции и контент платформы <b>2earn.cash</b>
                                    защищены правами интеллектуальной собственности. Они не могут быть воспроизведены,
                                    изменены или использованы без предварительного согласия компании.
                                </p>
                            </div>
                            <div class="card-header">
                                <h6 class="card-title">Нарушение общих условий</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">
                                    В случае нарушения этих условий <b>2earn.cash</b> оставляет за собой право
                                    приостановить или ограничить доступ пользователя ко всем или части услуг, а также
                                    предпринять необходимые юридические действия.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>


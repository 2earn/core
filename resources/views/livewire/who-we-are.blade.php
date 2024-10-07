<div class="row justify-content-center mt-2">
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
                        <h3>{{__('Who are we')}}</h3>
                        <p class="mb-0 text-muted">{{__('Who are we last update')}}</p>
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
                            <div class="card-body">
                                <p class="text-muted">
                                    <strong>2EARN.CASH</strong> est une entreprise innovante et ambitieuse, spécialisée
                                    dans
                                    le domaine du e-Business, avec pour mission de redéfinir les standards du
                                    commerce numérique à travers des concepts novateurs et des approches
                                    axées sur l’excellence et la satisfaction de ses utilisateurs. Son siège
                                    social, stratégiquement situé au sein de l’Université King Abdulaziz à
                                    Jeddah, en Arabie Saoudite, bénéficie d’un cadre institutionnel prestigieux
                                    et d’une infrastructure de premier ordre. Parallèlement, <strong>2EARN.CASH</strong>
                                    est
                                    également implantée en Tunisie et en Égypte, témoignant ainsi de sa
                                    présence croissante sur le marché international et de sa volonté de se
                                    positionner en tant qu’acteur global majeur dans le secteur du
                                    e-Business.
                                </p>
                                <p class="text-muted">
                                    L'entreprise repose sur un concept révolutionnaire, <strong>The Earner
                                        Marketing</strong>, qui transcende les paradigmes traditionnels en n’étant pas
                                    uniquement focalisé sur un produit ou un service spécifique. Il s’agit
                                    d’une philosophie novatrice, orientée vers la création de valeur partagée.
                                    Contrairement aux approches marketing conventionnelles, <strong>EARN.CASH</strong> a
                                    su réconcilier les intérêts des consommateurs et des fournisseurs en
                                    s’appuyant sur le principe du <strong>Partage des Intérêts</strong>. Ce modèle
                                    unique
                                    offre des avantages significatifs et durables aux deux parties prenantes,
                                    établissant ainsi une relation harmonieuse et équilibrée, que l’on ne
                                    retrouve dans aucun autre concept marketing actuel.
                                </p>

                                <p class="text-muted">
                                    Fort de ce concept pionnier, <strong>2EARN.CASH</strong> a mis en place un
                                    écosystème
                                    digital intégré, constitué de plusieurs plateformes spécialisées telles que
                                    <strong>Learn2earn.cash</strong>, <strong>Move2earn.cash</strong>,
                                    <strong>Belegant2earn.cash</strong>,
                                    <strong>Takecare2earn.cash</strong>, <strong>Shop2earn.cash</strong>, <strong>Eat2earn.cash</strong>,
                                    et
                                    <strong>Travel2earn.cash</strong>. Bien que chaque plateforme opère de manière
                                    indépendante dans son secteur d’activité respectif, elles sont toutes unies
                                    par une plateforme technologique centralisée et une vision commune,
                                    conférant à l’ensemble une cohérence et une synergie incomparables.
                                    Ensemble, ces entités forment un réseau dynamique où chaque
                                    composante contribue de manière significative à l’expansion globale, tout
                                    en offrant aux utilisateurs une expérience fluide, intégrée et parfaitement
                                    optimisée.
                                    Cette synergie engendre une dynamique positive et une interaction
                                    mutuellement bénéfique, encourageant ainsi nos utilisateurs à explorer
                                    avec enthousiasme et curiosité l’ensemble de nos services. En
                                    conséquence, ils privilégient nos offres par rapport à celles de la
                                    concurrence, tout en découvrant les multiples opportunités générées par
                                    cet écosystème unique.
                                </p>
                                <p class="text-muted">
                                    Les marques et enseignes commerciales de <strong>2EARN.CASH</strong> ont été dûment
                                    enregistrées et protégées à l’échelle internationale, conformément aux
                                    normes établies par la <strong>Classification de Nice</strong>, garantissant ainsi
                                    leur
                                    sécurité juridique dans le monde entier.
                                </p>
                                <p class="text-muted">
                                    En février 2024, <strong>2EARN.CASH</strong> a franchi une nouvelle étape dans son
                                    développement en adoptant le statut de <strong>Société par Actions Simplifiée
                                        (S.A.S.)</strong>
                                    , une structure juridique qui lui permet de lever des fonds par
                                    l’émission d’actions négociables. Cette évolution stratégique facilite la
                                    mobilisation de capitaux pour soutenir ses ambitions de croissance
                                    accélérée et d’expansion globale.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                @if(app()->getLocale()=='en')
                    <div class="row mt-3">
                        <div class="card">
                            <div class="card-body">
                                <p class="text-muted">
                                    <strong>2EARN.CASH</strong> is an innovative and ambitious company specializing in
                                    the
                                    field of e-Business, with the mission to redefine the standards of digital
                                    commerce through groundbreaking concepts and approaches focused on
                                    excellence and user satisfaction. Its headquarters, strategically located at
                                    King Abdulaziz University in Jeddah, Saudi Arabia, benefits from a
                                    prestigious institutional framework and a top-tier infrastructure. At the
                                    same time,<strong>2EARN.CASH</strong> is also established in Tunisia and Egypt,
                                    reflecting its growing international presence and its aspiration to become
                                    a major global player in the digital commerce sector.
                                </p>
                                <p class="text-muted">
                                    The company is built on a revolutionary concept, <strong>The Earner
                                        Marketing</strong>,
                                    which transcends traditional paradigms by not being focused on a single
                                    product or service. It is a groundbreaking philosophy centered around
                                    the creation of shared value. Unlike conventional marketing approaches,
                                    <strong>2EARN.CASH</strong> has successfully harmonized the interests of both
                                    consumers and suppliers by adopting the principle of <strong>Interest
                                        Sharing</strong>.
                                    This unique model offers significant and sustainable benefits to both
                                    parties, establishing a balanced and harmonious relationship that is
                                    unmatched by any other marketing concept today.
                                </p>

                                <p class="text-muted">
                                    Building on this pioneering concept, <strong>2EARN.CASH</strong> has developed an
                                    integrated digital ecosystem consisting of several specialized platforms:
                                    <strong>Learn2earn.cash</strong>, <strong>Move2earn.cash</strong>,
                                    <strong>Belegant2earn.cash</strong>,
                                    <strong>Takecare2earn.cash</strong>, <strong>Shop2earn.cash</strong>, <strong>Eat2earn.cash</strong>,
                                    and
                                    <strong>Travel2earn.cash</strong>. While each platform operates independently in its
                                    respective sector, they are all united by a centralized technology platform
                                    and a shared vision, providing the entire system with unparalleled
                                    coherence and synergy. Together, these entities form a dynamic network
                                    where each component significantly contributes to the overall growth
                                    while offering users a seamless, integrated, and optimized experience.
                                </p>
                                <p class="text-muted">
                                    This synergy creates a positive dynamic and mutually beneficial
                                    interaction, encouraging our users to explore our entire range of services
                                    with enthusiasm and curiosity. As a result, they consistently prioritize our
                                    offerings over those of our competitors while discovering the various
                                    opportunities generated by this unique ecosystem.
                                </p>
                                <p class="text-muted">
                                    The trademarks and trade names of <strong>2EARN.CASH</strong> have been duly
                                    registered and protected at the international level, in accordance with
                                    the standards established by the <strong>Nice Classification</strong>, thus ensuring
                                    their
                                    legal security worldwide.
                                </p>
                                <p class="text-muted">
                                    In February 2024, <strong>2EARN.CASH</strong> took a significant step forward in its
                                    development by adopting the status of a <strong>Simplified Joint Stock Company
                                        (S.J.S.C)</strong>, a legal structure that enables it to raise funds through the
                                    issuance of tradable shares. This strategic shift facilitates capital
                                    mobilization to support its accelerated growth ambitions and global
                                    expansion.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                @if(app()->getLocale()=='ar')
                    <div class="row mt-3">
                        <div class="card">
                            <div class="card-body">
                                <p class="text-muted">
                                    H.EARN2 هي شركة مبتكرة وطموحة متخصصة في مجال األعمال
                                    اإللكترونية، وتهدف إلى إعادة تعريف معايير التجارة الرقمية من خالل مفاهيم
                                    رائدة ونهج يركز على التميز وإرضاء المستخدمين. يقع مقرها الرئيسي بشكل
                                    استراتيجي في جامعة الملك عبد العزيز بجدة، المملكة العربية السعودية، مما
                                    مرموًق وبنية تحتية من الدرجة األولى. في الوقت نفسه، يمنحها إطاًرا مؤسسًيا ا
                                    أي في تونس ومصر، مما يعكس حضورها الدولي ًض تتواجد CASH.EARN2 ا
                                    المتزايد و طموحها لتصبح العًبا عالمًيا رئيسًيا في قطاع التجارة الرقمية.
                                </p>
                                <p class="text-muted">
                                    تستند الشركة إلى مفهوم ثوري ُيعرف باسم Marketing Earner The ، الذي
                                    يتجاوز النماذج التقليدية بعدم تركيزه على منتج أو خدمة واحدة فقط. إنه
                                    فلسفة مبتكرة تركز على خلق قيمة مشتركة. على عكس نهج التسويق
                                    التقليدي، نجحت CASH.EARN2 في تحقيق التوازن بين مصالح كل من
                                    المستهلكين والموردين من خالل اعتماد مبدأ تقاسم المنافع. هذا النموذج
                                    الفريد يقدم فوائد كبيرة ومستدامة للطرفين، مما يرسخ عالقة متوازنة
                                    ومنسجمة ال مثيل لها في أي مفهوم تسويقي آخر اليوم.
                                </p>

                                <p class="text-muted">
                                    باالعتماد على هذا المفهوم الرائد، قامت CASH.EARN2 بتطوير منظومة رقمية
                                    متكاملة تتألف من عدة منصات متخصصة: ،cash.earn2Learn
                                    Move2earn.cash، Belegant2earn.cash، Takecare2earn.cash،
                                    منصة كل أن ورغم .*ravel2earn.cash و ،Shop2earn.cash، Eat2earn.cash
                                    تعمل بشكل مستقل في قطاعها الخاص، فإنها جمي ًعا موحدة بمنصة تقنية
                                    مركزية ورؤية مشتركة، مما يمنح النظام بأكمله تماس ًكا وتكامًلا ال مثيل لهما.
                                    تشكل هذه الكيانات شبكة ديناميكية حيث تساهم كل مكونة بشكل كبير
                                    ُ
                                    م ًعا،
                                    في النمو الشامل، مع تقديم تجربة سلسة ومتكاملة و ُمح َّسنة للمستخدمين.
                                </p>
                                <p class="text-muted">
                                    تخلق هذه الديناميكية تفاعًلا إيجابًيا ومفي ًدا للطرفين، مما يشجع مستخدمينا
                                    على استكشاف مجموعة خدماتنا بالكامل بحماس وفضول. ونتيجة لذلك،
                                    فإنهم يفضلون باستمرار عروضنا على عروض منافسينا مع اكتشاف الفرص
                                    المتنوعة التي تولدها هذه المنظومة الفريدة
                                </p>
                                <p class="text-muted">
                                    لقد تم تسجيل العالمات التجارية واألسماء التجارية الخاصة بـ CASH.EARN2
                                    وحمايتها على المستوى الدولي، وف ًقا للمعايير التي وضعتها التصنيف الدولي
                                    في نيس، مما يضمن أمنها القانوني في جميع أنحاء العالم.
                                    في فبراير ،2024 اتخذت CASH.EARN2 خطوة كبيرة في تطويرها من خالل
                                    اعتمادها صفة شركة مساهمة مبسطة )S.A.S)، وهي هيكل قانوني يسمح
                                    لها بجمع األموال من خالل إصدار أسهم قابلة للتداول. يسهل هذا التحول
                                    االستراتيجي تعبئة رؤوس األموال لدعم طموحات النمو السريع والتوسع
                                    العالمي.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="card-footer">
                @include('layouts.footer-static', ['pageName' => 'static'])
            </div>
        </div>
    </div>
</div>





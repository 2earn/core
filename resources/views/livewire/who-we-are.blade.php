<div class="row justify-content-center mt-2">
    @section('title')
        {{ __('Who are we') }}
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
                                    <b>2EARN.CASH</b> est une entreprise innovante et ambitieuse, spécialisée
                                    dans
                                    le domaine du e-Business, avec pour mission de redéfinir les standards du
                                    commerce numérique à travers des concepts novateurs et des approches
                                    axées sur l’excellence et la satisfaction de ses utilisateurs. Son siège
                                    social, stratégiquement situé au sein de l’Université King Abdulaziz à
                                    Jeddah, en Arabie Saoudite, bénéficie d’un cadre institutionnel prestigieux
                                    et d’une infrastructure de premier ordre. Parallèlement, <b>2EARN.CASH</b>
                                    est
                                    également implantée en Tunisie et en Égypte, témoignant ainsi de sa
                                    présence croissante sur le marché international et de sa volonté de se
                                    positionner en tant qu’acteur global majeur dans le secteur du
                                    e-Business.
                                </p>
                                <p class="text-muted">
                                    L'entreprise repose sur un concept révolutionnaire, <b>The Earner
                                        Marketing</b>, qui transcende les paradigmes traditionnels en n’étant pas
                                    uniquement focalisé sur un produit ou un service spécifique. Il s’agit
                                    d’une philosophie novatrice, orientée vers la création de valeur partagée.
                                    Contrairement aux approches marketing conventionnelles, <b>2EARN.CASH</b> a
                                    su réconcilier les intérêts des consommateurs et des fournisseurs en
                                    s’appuyant sur le principe du <b>Partage des Intérêts</b>. Ce modèle
                                    unique
                                    offre des avantages significatifs et durables aux deux parties prenantes,
                                    établissant ainsi une relation harmonieuse et équilibrée, que l’on ne
                                    retrouve dans aucun autre concept marketing actuel.
                                </p>

                                <p class="text-muted">
                                    Fort de ce concept pionnier, <b>2EARN.CASH</b> a mis en place un
                                    écosystème
                                    digital intégré, constitué de plusieurs plateformes spécialisées telles que
                                    <b>Learn2earn.cash</b>, <b>Move2earn.cash</b>,
                                    <b>Belegant2earn.cash</b>,
                                    <b>Takecare2earn.cash</b>, <b>Shop2earn.cash</b>, <b>Eat2earn.cash</b>,
                                    et
                                    <b>Travel2earn.cash</b>. Bien que chaque plateforme opère de manière
                                    indépendante dans son secteur d’activité respectif, elles sont toutes unies
                                    par une plateforme technologique centralisée et une vision commune,
                                    conférant à l’ensemble une cohérence et une synergie incomparables.
                                    Ensemble, ces entités forment un réseau dynamique où chaque
                                    composante contribue de manière significative à l’expansion globale, tout
                                    en offrant aux utilisateurs une expérience fluide, intégrée et parfaitement
                                    optimisée.
                                </p>
                                <p class="text-muted">
                                    Cette synergie engendre une dynamique positive et une interaction
                                    mutuellement bénéfique, encourageant ainsi nos utilisateurs à explorer
                                    avec enthousiasme et curiosité l’ensemble de nos services. En
                                    conséquence, ils privilégient nos offres par rapport à celles de la
                                    concurrence, tout en découvrant les multiples opportunités générées par
                                    cet écosystème unique.
                                </p>
                                <p class="text-muted">
                                    Les marques et enseignes commerciales de <b>2EARN.CASH</b> ont été dûment
                                    enregistrées et protégées à l’échelle internationale, conformément aux
                                    normes établies par la <b>Classification de Nice</b>, garantissant ainsi
                                    leur
                                    sécurité juridique dans le monde entier.
                                </p>
                                <p class="text-muted">
                                    En février 2024, <b>2EARN.CASH</b> a franchi une nouvelle étape dans son
                                    développement en adoptant le statut de <b>Société par Actions Simplifiée
                                        (S.A.S.)</b>
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
                                    <b>2EARN.CASH</b> is an innovative and ambitious company specializing in
                                    the
                                    field of e-Business, with the mission to redefine the standards of digital
                                    commerce through groundbreaking concepts and approaches focused on
                                    excellence and user satisfaction. Its headquarters, strategically located at
                                    King Abdulaziz University in Jeddah, Saudi Arabia, benefits from a
                                    prestigious institutional framework and a top-tier infrastructure. At the
                                    same time, <b>2EARN.CASH</b> is also established in Tunisia and Egypt,
                                    reflecting its growing international presence and its aspiration to become
                                    a major global player in the digital commerce sector.
                                </p>
                                <p class="text-muted">
                                    The company is built on a revolutionary concept, <b>The Earner
                                        Marketing</b>,
                                    which transcends traditional paradigms by not being focused on a single
                                    product or service. It is a groundbreaking philosophy centered around
                                    the creation of shared value. Unlike conventional marketing approaches,
                                    <b>2EARN.CASH</b> has successfully harmonized the interests of both
                                    consumers and suppliers by adopting the principle of <b>Interest
                                        Sharing</b>.
                                    This unique model offers significant and sustainable benefits to both
                                    parties, establishing a balanced and harmonious relationship that is
                                    unmatched by any other marketing concept today.
                                </p>

                                <p class="text-muted">
                                    Building on this pioneering concept, <b>2EARN.CASH</b> has developed an
                                    integrated digital ecosystem consisting of several specialized platforms:
                                    <b>Learn2earn.cash</b>, <b>Move2earn.cash</b>,
                                    <b>Belegant2earn.cash</b>,
                                    <b>Takecare2earn.cash</b>, <b>Shop2earn.cash</b>, <b>Eat2earn.cash</b>,
                                    and
                                    <b>Travel2earn.cash</b>. While each platform operates independently in its
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
                                    The trademarks and trade names of <b>2EARN.CASH</b> have been duly
                                    registered and protected at the international level, in accordance with
                                    the standards established by the <b>Nice Classification</b>, thus ensuring
                                    their
                                    legal security worldwide.
                                </p>
                                <p class="text-muted">
                                    In February 2024, <b>2EARN.CASH</b> took a significant step forward in its
                                    development by adopting the status of a <b>Simplified Joint Stock Company
                                        (S.J.S.C)</b>, a legal structure that enables it to raise funds through the
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
                                    أي في تونس ومصر، مما يعكس حضورها الدولي ًض تتواجد <b>2EARN.CASH </b> ا
                                    المتزايد و طموحها لتصبح العًبا عالمًيا رئيسًيا في قطاع التجارة الرقمية.
                                </p>
                                <p class="text-muted">
                                    تستند الشركة إلى مفهوم ثوري يُعرف باسم <b>The Earner Marketing</b> ، الذي يتجاوز
                                    النماذج
                                    التقليدية بعدم تركيزه على منتج أو خدمة واحدة فقط. إنه فلسفة مبتكرة تركز على خلق قيمة
                                    مشتركة. على عكس نهج التسويق التقليدي، نجحت 2EARN.CASH في تحقيق التوازن بين مصالح كل
                                    من المستهلكين والمزودين والتجار من خلال اعتماد مبدأ تقاسم المنافع. هذا النموذج
                                    الفريد يقدم فوائد كبيرة ومستدامة للطرفين، مما يرسخ علاقة متوازنة ومنسجمة لا مثيل لها
                                    في أي مفهوم تسويقي آخر اليوم.
                                </p>

                                <p class="text-muted">
                                    باالعتماد على هذا المفهوم الرائد، قامت <b>2EARN.CASH </b> بتطوير منظومة رقمية
                                    متكاملة تتألف من عدة منصات متخصصة: ،<b><b>2EARN.CASH </b>Learn</b>
                                    <b>Move2earn.cash</b>، <b>Belegant2earn.cash</b>، <b>Takecare2earn.cash</b>،
                                    منصة كل أن ورغم .*<b>ravel2earn.cash</b> و ،Shop2earn.cash، <b>Eat2earn.cash</b>
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
                                    لقد تم تسجيل العلامات التجارية والأسماء التجارية الخاصة بـ <b>2EARN.CASH</b>
                                    وحمايتها على المستوى الدولي، وفقًا للمعايير التي وضعتها التصنيف الدولي في نيس، مما
                                    يضمن أمنها القانوني في جميع أنحاء العالم.

                                </p>
                                <p class="text-muted">
                                    لقد تم تسجيل العالمات التجارية واألسماء التجارية الخاصة بـ <b>2EARN.CASH </b>
                                    وحمايتها على المستوى الدولي، وف ًقا للمعايير التي وضعتها التصنيف الدولي
                                    في نيس، مما يضمن أمنها القانوني في جميع أنحاء العالم.
                                    في فبراير ،2024 اتخذت <b>2EARN.CASH </b> خطوة كبيرة في تطويرها من خالل
                                    اعتمادها <b>صفة شركة مساهمة مبسطة )S.A.S)،</b> وهي هيكل قانوني يسمح
                                    لها بجمع األموال من خالل إصدار أسهم قابلة للتداول. يسهل هذا التحول
                                    االستراتيجي تعبئة رؤوس األموال لدعم طموحات النمو السريع والتوسع
                                    العالمي.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                @if(app()->getLocale()=='tr')
                    <p class="text-muted">
                        Bu öncü konseptten yola çıkarak, <b>2EARN.CASH</b>, birkaç uzmanlaşmış platformdan oluşan
                        entegre bir dijital ekosistem geliştirmiştir: <b>Learn2earn.cash</b>, <b>Move2earn.cash</b>,
                        <b>Belegant2earn.cash</b>, <b>Takecare2earn.cash</b>, <b>Shop2earn.cash</b>,
                        <b>Eat2earn.cash</b>,
                        ve <b>Travel2earn.cash</b>. Her bir platform kendi sektöründe bağımsız olarak faaliyet
                        gösterirken, hepsi merkezi bir teknoloji platformu ve ortak bir vizyonla birleşmiştir. Bu
                        birleşim, bütün ekosisteme benzersiz bir tutarlılık ve sinerji sağlayarak kullanıcılarına
                        sorunsuz, entegre ve optimize edilmiş bir deneyim sunmaktadır. Bu unsurlar bir araya gelerek,
                        genel büyümeye önemli ölçüde katkıda bulunmakta ve kullanıcıları tüm hizmet yelpazesini heyecan
                        ve merakla keşfetmeye teşvik eden dinamik bir ağ oluşturulmaktadır.
                    </p>
                    <p class="text-muted">
                        Bu sinerji, olumlu bir dinamik ve karşılıklı fayda sağlayan etkileşim oluşturarak
                        kullanıcılarımızın rekabetçilerimizin teklifleri yerine kendi hizmetlerimizi keşfetmesini ve
                        önceliklendirmesini sağlamaktadır. Ayrıca bu benzersiz ekosistem tarafından oluşturulan çeşitli
                        fırsatları keşfetmelerine olanak tanımaktadır.
                    </p>
                    <p class="text-muted">
                        <b>2EARN.CASH</b>'in ticari markaları ve ticaret isimleri, uluslararası düzeyde kayıt altına
                        alınmış ve korunmuştur. Bu koruma, <b>Nice Sınıflandırması</b> tarafından belirlenen standartlar
                        doğrultusunda gerçekleştirilmiş olup, dünya çapında yasal güvenlik sağlamaktadır.
                    </p>
                    <p class="text-muted">
                        Şubat 2024'te, <b>2EARN.CASH</b>, kendisini basit bir anonim şirket yapısı olan <b>Basitleşmiş
                            Hisse Senedi Şirketi (Simplified Joint Stock Company - S.J.S.C)</b> statüsü benimseyerek
                        önemli bir adım atmıştır. Bu yasal yapı, ticarete konu olabilecek hisselerin ihraç edilmesi
                        yoluyla fon toplamaya olanak tanımaktadır. Bu stratejik değişim, hızlandırılmış büyüme
                        hedeflerini desteklemek ve küresel genişleme yolunda sermaye mobilizasyonunu
                        kolaylaştırmaktadır.
                    </p>
                @endif
                @if(app()->getLocale()=='es')
                    <p class="text-muted">
                        Basándose en este concepto pionero, <b>2EARN.CASH</b> ha desarrollado un ecosistema digital
                        integrado
                        compuesto por varias plataformas especializadas: <b>Learn2earn.cash</b>, <b>Move2earn.cash</b>,
                        <b>Belegant2earn.cash</b>, <b>Takecare2earn.cash</b>, <b>Shop2earn.cash</b>,
                        <b>Eat2earn.cash</b>,
                        y <b>Travel2earn.cash</b>. Aunque cada plataforma opera de manera independiente en su sector
                        respectivo,
                        todas están unidas por una plataforma tecnológica centralizada y una visión compartida,
                        proporcionando al
                        sistema una coherencia y sinergia sin igual. Juntas, estas entidades forman una red dinámica
                        donde cada
                        componente contribuye significativamente al crecimiento general mientras ofrece a los usuarios
                        una
                        experiencia integrada, fluida y optimizada.
                    </p>
                    <p class="text-muted">
                        Esta sinergia crea una dinámica positiva e interacción mutuamente beneficiosa, alentando a
                        nuestros usuarios
                        a explorar con entusiasmo y curiosidad toda nuestra gama de servicios. Como resultado,
                        consistentemente
                        priorizan nuestras ofertas sobre las de nuestros competidores mientras descubren las diversas
                        oportunidades
                        generadas por este ecosistema único.
                    </p>
                    <p class="text-muted">
                        Las marcas registradas y nombres comerciales de <b>2EARN.CASH</b> han sido debidamente
                        registrados y
                        protegidos a nivel internacional, de acuerdo con los estándares establecidos por la <b>Clasificación
                            de
                            Niza</b>, garantizando así su seguridad legal en todo el mundo.
                    </p>
                    <p class="text-muted">
                        En febrero de 2024, <b>2EARN.CASH</b> dio un paso significativo hacia adelante en su desarrollo
                        al adoptar
                        el estatus de una <b>Sociedad por Acciones Simplificada (S.J.S.C)</b>, una estructura legal que
                        le permite
                        recaudar fondos mediante la emisión de acciones negociables. Este cambio estratégico facilita la
                        movilización de capital para apoyar sus ambiciones de crecimiento acelerado y expansión global.
                    </p>

                @endif
                @if(app()->getLocale()=='de')
                    <p class="text-muted">
                        Aufbauend auf diesem wegweisenden Konzept hat <b>2EARN.CASH</b> ein integriertes digitales
                        Ökosystem entwickelt, das aus mehreren spezialisierten Plattformen besteht:
                        <b>Learn2earn.cash</b>, <b>Move2earn.cash</b>,
                        <b>Belegant2earn.cash</b>, <b>Takecare2earn.cash</b>, <b>Shop2earn.cash</b>,
                        <b>Eat2earn.cash</b>
                        und <b>Travel2earn.cash</b>. Während jede Plattform in ihrem jeweiligen Bereich unabhängig
                        arbeitet, sind sie alle durch eine zentrale Technologieplattform und eine gemeinsame Vision
                        verbunden, die dem gesamten System eine beispiellose Kohärenz und Synergie verleiht. Gemeinsam
                        bilden diese Entitäten ein dynamisches Netzwerk, in dem jede Komponente wesentlich zum
                        Gesamtwachstum beiträgt und den Nutzern eine nahtlose, integrierte und optimierte Erfahrung
                        bietet.
                    </p>
                    <p class="text-muted">
                        Diese Synergie schafft eine positive Dynamik und eine für beide Seiten vorteilhafte Interaktion,
                        die unsere Nutzer ermutigt, mit Begeisterung und Neugier unser gesamtes Dienstleistungsangebot
                        zu erkunden. Dadurch priorisieren sie konsequent unsere Angebote gegenüber denen unserer
                        Wettbewerber und entdecken die verschiedenen Möglichkeiten, die dieses einzigartige Ökosystem
                        bietet.
                    </p>
                    <p class="text-muted">
                        Die Marken und Handelsnamen von <b>2EARN.CASH</b> wurden gemäß den von der <b>Nizza-Klassifikation</b>
                        festgelegten Standards auf internationaler Ebene ordnungsgemäß registriert und geschützt,
                        wodurch ihre rechtliche Sicherheit weltweit gewährleistet wird.
                    </p>
                    <p class="text-muted">
                        Im Februar 2024 hat <b>2EARN.CASH</b> einen bedeutenden Schritt in seiner Entwicklung gemacht,
                        indem es den Status einer <b>Vereinfachten Aktiengesellschaft (S.J.S.C)</b> angenommen hat,
                        einer Rechtsstruktur, die es ermöglicht, Mittel durch die Ausgabe von handelbaren Aktien zu
                        beschaffen. Dieser strategische Wandel erleichtert die Kapitalmobilisierung, um die
                        beschleunigten Wachstumsambitionen und die globale Expansion zu unterstützen.
                    </p>
                @endif
                @if(app()->getLocale()=='ru')
                    <p class="text-muted">
                        Aufbauend auf diesem wegweisenden Konzept hat <b>2EARN.CASH</b> ein integriertes digitales
                        Ökosystem entwickelt, das aus mehreren spezialisierten Plattformen besteht:
                        <b>Learn2earn.cash</b>, <b>Move2earn.cash</b>,
                        <b>Belegant2earn.cash</b>, <b>Takecare2earn.cash</b>, <b>Shop2earn.cash</b>,
                        <b>Eat2earn.cash</b>
                        und <b>Travel2earn.cash</b>. Während jede Plattform in ihrem jeweiligen Bereich unabhängig
                        arbeitet, sind sie alle durch eine zentrale Technologieplattform und eine gemeinsame Vision
                        verbunden, die dem gesamten System eine beispiellose Kohärenz und Synergie verleiht. Gemeinsam
                        bilden diese Entitäten ein dynamisches Netzwerk, in dem jede Komponente wesentlich zum
                        Gesamtwachstum beiträgt und den Nutzern eine nahtlose, integrierte und optimierte Erfahrung
                        bietet.
                    </p>
                    <p class="text-muted">
                        Diese Synergie schafft eine positive Dynamik und eine für beide Seiten vorteilhafte Interaktion,
                        die unsere Nutzer ermutigt, mit Begeisterung und Neugier unser gesamtes Dienstleistungsangebot
                        zu erkunden. Dadurch priorisieren sie konsequent unsere Angebote gegenüber denen unserer
                        Wettbewerber und entdecken die verschiedenen Möglichkeiten, die dieses einzigartige Ökosystem
                        bietet.
                    </p>
                    <p class="text-muted">
                        Die Marken und Handelsnamen von <b>2EARN.CASH</b> wurden gemäß den von der <b>Nizza-Klassifikation</b>
                        festgelegten Standards auf internationaler Ebene ordnungsgemäß registriert und geschützt,
                        wodurch ihre rechtliche Sicherheit weltweit gewährleistet wird.
                    </p>
                    <p class="text-muted">
                        Im Februar 2024 hat <b>2EARN.CASH</b> einen bedeutenden Schritt in seiner Entwicklung gemacht,
                        indem es den Status einer <b>Vereinfachten Aktiengesellschaft (S.J.S.C)</b> angenommen hat,
                        einer Rechtsstruktur, die es ermöglicht, Mittel durch die Ausgabe von handelbaren Aktien zu
                        beschaffen. Dieser strategische Wandel erleichtert die Kapitalmobilisierung, um die
                        beschleunigten Wachstumsambitionen und die globale Expansion zu unterstützen.
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>


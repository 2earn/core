<div>
    @if($enableStaticNews)
        <div class="col-xxl-12 mb-1">
            <div class="card ribbon-box right border shadow-none overflow-hidden mb-lg-0 material-shadow">
                <div class="card-header">
                    <h6 class="card-title text-info mb-0">
                        @if(app()->getLocale()=="en")
                            Communication Board
                        @elseif(app()->getLocale()=="fr")
                            Communication Board
                        @else
                            لوحة التواصل
                        @endif
                    </h6>
                </div>
                <div class="card-body">
                    <div class="ribbon ribbon-danger ribbon-shape trending-ribbon">
                        <i class="ri-flashlight-fill text-white align-bottom float-start me-1"></i> <span
                            class="trending-ribbon-text">{{__('News')}}</span>
                    </div>
                    <blockquote class="card-blockquote mb-0">
                        <p class="text-muted mb-2">
                            @if(app()->getLocale()=="en")
                                Dear Members,
                            @elseif(app()->getLocale()=="fr")
                                Chers adhérents
                            @else
                                الأعضاء الأعزاء،
                            @endif
                        </p>
                        <p class="text-muted mb-2">
                            @if(app()->getLocale()=="en")
                                We extend our most distinguished greetings to you.
                            @elseif(app()->getLocale()=="fr")
                                Nous vous adressons nos salutations les plus distinguées.
                            @else
                                نتقدم إليكم بأطيب التحيات والتقدير.
                            @endif
                        </p>
                        <p class="text-muted mb-2">
                            @if(app()->getLocale()=="en")
                                As part of our new communication strategy, we are pleased to inform you
                                about the development of four communication channels grouped under the name
                                “Communication
                                Board” These channels aim to strengthen our interaction with all our members, especially
                                our
                                shareholders (stockholders or traders). The modules include:
                            @elseif(app()->getLocale()=="fr")
                                Dans le cadre de notre nouvelle stratégie de communication, nous avons le plaisir de
                                vous
                                informer du développement de quatre canaux de communication  regroupés sous
                                l'appellation
                                “Communication Board”. Ces canaux visent à renforcer notre interaction avec l'ensemble
                                de
                                nos membres, en particulier nos actionnaires (acheteurs d'actions ou traders). Les
                                modules
                                incluent :
                            @else
                                في إطار استراتيجيتنا الجديدة للتواصل، يسعدنا إبلاغكم بتطوير أربع قنوات تواصل مجمعة تحت
                                مسمى
                                "لوحة التواصل" (Communication Board). تهدف هذه القنوات إلى تعزيز تفاعلنا مع جميع
                                أعضائنا،
                                وبالأخص المساهمين (المشترين للأسهم أو المتداولين). تشمل هذه القنوات:
                            @endif
                        </p>
                        <ul class="text-muted">
                            <li>
                                @if(app()->getLocale()=="en")
                                    <strong>Surveys:</strong> A tool designed to gather the opinions of our members,
                                    particularly our shareholders, on certain strategic decisions.
                                @elseif(app()->getLocale()=="fr")
                                    <strong>Le Sondage :</strong> un outil permettant de recueillir l'avis de nos
                                    membres,
                                    en particulier
                                    celui des actionnaires, sur certaines décisions stratégiques.
                                @else
                                    <strong>الاستبيانات :</strong> أداة مصممة لجمع آراء الأعضاء، وخاصة المساهمين، حول
                                    بعض
                                    القرارات الاستراتيجية.
                                @endif
                            </li>
                            <li>
                                @if(app()->getLocale()=="en")
                                    <strong>News :</strong> A section dedicated to disseminating important updates
                                    relevant
                                    to our community.
                                @elseif(app()->getLocale()=="fr")
                                    <strong>Les actualités (News) :</strong> une rubrique dédiée à la diffusion de
                                    nouvelles
                                    importantes
                                    concernant notre communauté.
                                @else
                                    <strong>المستجدات :</strong> قسم مخصص لنشر الأخبار المهمة المتعلقة بشركتنا.
                                @endif
                            </li>
                            <li>
                                @if(app()->getLocale()=="en")
                                    <strong>Event Information:</strong> A feature to inform about event launches,
                                    collect
                                    participation intentions, and manage confirmations and reservations.
                                @elseif(app()->getLocale()=="fr")
                                    <strong>Informations événementielles :</strong> une fonctionnalité permettant
                                    d'informer
                                    sur les
                                    lancements d'événements, de recueillir les intentions de participation, ainsi que de
                                    gérer les confirmations et réservations.
                                @else
                                    <strong>الفعاليات:</strong> ميزة للإعلان عن إطلاق الفعاليات، وجمع نوايا المشاركة،
                                    وإدارة
                                    التأكيدات
                                    والحجوزات.
                                @endif
                            </li>
                            <li>
                                @if(app()->getLocale()=="en")
                                    <strong>ChatBot and AI ChatBot:</strong> Support tools to answer all your inquiries,
                                    particularly regarding platform features and usage
                                @elseif(app()->getLocale()=="fr")
                                    <strong> Le ChatBot et l'AI ChatBot :</strong> des outils de support visant à
                                    répondre à
                                    toutes vos
                                    questions, notamment celles concernant les fonctionnalités et l'utilisation de la
                                    plateforme.
                                @else
                                    <strong>روبوت الدردشة والذكاء الاصطناعي (ChatBot & AI ChatBot):</strong> أدوات دعم
                                    تهدف
                                    إلى الإجابة عن
                                    جميع استفساراتكم، خاصة تلك المتعلقة بميزات واستخدام المنصة.
                                @endif
                            </li>
                        </ul>
                        <p class="text-muted mb-2">
                            @if(app()->getLocale()=="en")
                                Best regards
                            @elseif(app()->getLocale()=="fr")
                                Cordialement
                            @else
                                أطيب التحيات
                            @endif</p>
                    </blockquote>
                    <blockquote class="card-blockquote mb-0 float-end">
                        <p class="text-info mb-2">
                            @if(app()->getLocale()=="en")
                                The Management Team
                            @elseif(app()->getLocale()=="fr")
                                L'équipe de direction
                            @else
                                فريق الإدارة
                            @endif.
                        </p>
                    </blockquote>
                </div>
                <div class="card-footer">
                    <p class="text-muted mb-0 float-end">02-10-2024</p>
                </div>
            </div>
        </div>
        <div class="col-xxl-12 my-2">
            <div class="card ribbon-box right border shadow-none overflow-hidden mb-lg-0 material-shadow">

                <div class="card-header">
                    <h6 class="card-title text-info mb-0">
                        @if(app()->getLocale()=="en")
                            the VIP Offer
                        @elseif(app()->getLocale()=="fr")
                            L’offre VIP
                        @else
                            عرض VIP
                        @endif
                    </h6>
                </div>
                <div class="card-body">
                    <div class="ribbon ribbon-danger ribbon-shape trending-ribbon">
                        <i class="ri-flashlight-fill text-white align-bottom float-start me-1"></i> <span
                            class="trending-ribbon-text">{{__('News')}}</span>
                    </div>
                    <img src="{{Vite::asset('resources/images/static-news/VIP ICON.png')}}" alt="VIP ICON"
                         class="d-block img-fluid img-business-square mx-auto rounded float-left">
                    <blockquote class="card-blockquote mb-0">
                        <p class="text-muted mb-2">
                            @if(app()->getLocale()=="en")
                                We would like to inform you about a <strong>VIP offer</strong> starting on <strong>October
                                    3, 2024</strong>, and available for a limited time. If you are eligible, a golden
                                badge
                                will appear when you log in to <strong>2earn.cash</strong>, indicating your selection
                                for
                                this offer.

                            @elseif(app()->getLocale()=="fr")
                                Nous souhaitons vous informer d'une <strong>offre VIP</strong> qui débutera le
                                <stong>03 octobre 2024</stong> et qui sera
                                limitée dans le temps. Si vous êtes éligible, un badge doré apparaîtra lors de votre
                                connexion à <strong>2earn.cash</strong>, indiquant votre sélection pour cette offre.
                            @else
                                نود إبلاغكم بعرض <strong>عرض VIP</strong> ينطلق بتاريخ <strong>2024/10/03</strong> و
                                محدود
                                المدة. إذا كنت مؤهلاً، ستظهر لك
                                شارة ذهبية عند تسجيل دخولك إلى <strong>2earn.cash</strong>، مما يشير إلى اختيارك ضمن
                                العرض.
                            @endif
                        </p>
                        <p class="text-muted mb-2">
                            @if(app()->getLocale()=="en")
                                Many other exciting updates are on the way. We thank you for your trust
                                and continued support.
                            @elseif(app()->getLocale()=="fr")
                                De nombreuses autres bonnes nouvelles vous attendent. Nous vous remercions pour votre
                                confiance et votre soutien.
                            @else
                                هناك العديد من الأخبار الجيدة الأخرى في انتظاركم. نشكركم على ثقتكم ودعمكم المستمر.
                            @endif

                        </p>
                        <p class="text-muted mb-2">     @if(app()->getLocale()=="en")
                                Best regards
                            @elseif(app()->getLocale()=="fr")
                                Cordialement
                            @else
                                أطيب التحيات
                            @endif</p>
                    </blockquote>
                    <blockquote class="card-blockquote mb-0 float-end">
                        <p class="text-info mb-2">       @if(app()->getLocale()=="en")
                                The Management Team
                            @elseif(app()->getLocale()=="fr")
                                L'équipe de direction
                            @else
                                فريق الإدارة
                            @endif.
                        </p>
                    </blockquote>
                </div>
                <div class="card-footer">
                    <p class="text-muted mb-0 float-end">02-10-2024</p>
                </div>
            </div>
        </div>
        <div class="col-xxl-12 my-2">
            <div class="card ribbon-box right border shadow-none overflow-hidden mb-lg-0 material-shadow">

                <div class="card-header">
                    <h6 class="card-title text-info mb-0">
                        @if(app()->getLocale()=="en")
                            The signing of a strategic partnership
                        @elseif(app()->getLocale()=="fr")
                            signature d’un partenariat stratégique
                        @else
                            توقيع شراكة
                        @endif

                    </h6>
                </div>
                <div class="card-body">
                    <div class="ribbon ribbon-danger ribbon-shape trending-ribbon">
                        <i class="ri-flashlight-fill text-white align-bottom float-start me-1"></i> <span
                            class="trending-ribbon-text">{{__('News')}}</span>
                    </div>
                    <blockquote class="card-blockquote mb-0">
                        <p class="text-muted mb-2">
                            @if(app()->getLocale()=="en")
                                We are delighted to announce the signing of a strategic partnership with
                                the <strong>Egyptian Board Academy (EBA)</strong> .
                            @elseif(app()->getLocale()=="fr")
                                Nous sommes heureux d'annoncer la signature d’un partenariat stratégique avec <strong>Egyptian
                                    Board
                                    Academy (EBA)</strong>.
                            @else
                                يسعدنا أن نعلن عن توقيع شراكة استراتيجية مع <strong>أكاديمية البورد المصري
                                    (EBA)</strong>.
                            @endif

                        </p>
                        <img src="{{Vite::asset('resources/images/static-news/EBA.jpg')}}" alt="EBA"
                             style="width: 484px; height: 299px" class="d-block img-fluid mx-auto rounded float-left">
                        <p class="text-muted mb-2 mt-4">
                            @if(app()->getLocale()=="en")
                                With a network of over 1,200 trainers and 200,000 learners, the Egyptian Board Academy
                                (EBA), led by Dr. Mahmoud Saleh, welcomes you. It stands out for the quality of its
                                educational programs, designed to develop skills and enrich knowledge in various fields.
                            @elseif(app()->getLocale()=="fr")
                                Avec un réseau de plus de 1200 formateurs et 200000 apprenants, EBA, dirigée par
                                Dr.Mahmoud
                                SALEH, vous souhaite la bienvenue, elle se distingue ainsi par la qualité de ses
                                programmes
                                éducatifs, conçus pour développer les compétences et enrichir le savoir dans divers
                                domaines.
                            @else
                                مع شبكة تضم أكثر من 1200 مدرب و 200,000 متعلم، ترحب بكم أكاديمية البورد المصري (EBA)
                                بقيادة
                                الدكتور محمود صالح، وتتميز بجودة برامجها التعليمية المصممة لتطوير المهارات وإثراء
                                المعرفة في
                                مختلف المجالات.
                            @endif
                        </p>
                        <img
                            src="{{Vite::asset('resources/images/static-news/Dr.Mahmoud Saleh.jpg')}}"
                            alt="Dr.Mahmoud Saleh" width="250px" height="350px"
                            class="d-block img-fluid mx-auto rounded float-left mb-2">

                        <video style="height: 300px; width: 600px" controls class=" d-block img-fluid mx-auto rounded">
                            <source
                                src="{{Vite::asset('resources/images/static-news/y-ny-yh-tot-z-tkwn-mdrb-hqyqy-wt-ml-flws_video_1080p60.0.mp4')}}"
                                type="video/mp4">
                        </video>

                        <p class="text-muted my-2">     @if(app()->getLocale()=="en")
                                Best regards
                            @elseif(app()->getLocale()=="fr")
                                Cordialement
                            @else
                                أطيب التحيات
                            @endif</p>
                    </blockquote>
                    <blockquote class="card-blockquote mb-0 float-end">
                        <p class="text-info mb-2">
                            @if(app()->getLocale()=="en")
                                The Management Team
                            @elseif(app()->getLocale()=="fr")
                                L'équipe de direction
                            @else
                                فريق الإدارة
                            @endif.
                        </p>
                    </blockquote>
                </div>
                <div class="card-footer">
                    <p class="text-muted mb-0 float-end">02-10-2024</p>
                </div>
            </div>
        </div>
        <div class="col-xxl-12 my-2">
            <div class="card ribbon-box right border shadow-none overflow-hidden mb-lg-0 material-shadow">

                <div class="card-header">
                    <h6 class="card-title text-info mb-0">
                        @if(app()->getLocale()=="en")
                            the official launch of the Learn<strong>2earn.cash</strong>
                        @elseif(app()->getLocale()=="fr")
                            Lancement officiel de la plateforme Learn<strong>2earn.cash</strong>
                        @else
                            الإطلاق الرسمي لمنصة Learn<strong>2earn.cash</strong>
                        @endif
                    </h6>
                </div>
                <div class="card-body">
                    <div class="ribbon ribbon-danger ribbon-shape trending-ribbon">
                        <i class="ri-flashlight-fill text-white align-bottom float-start me-1"></i> <span
                            class="trending-ribbon-text">{{__('News')}}</span>
                    </div>
                    <blockquote class="card-blockquote mb-0">
                        <p class="text-muted mb-2">
                            @if(app()->getLocale()=="en")
                                We are pleased to announce the official launch of the
                                <strong>Learn<strong>2earn.cash</strong></strong>
                                platform, exclusively dedicated to trainers. This event will take place on <strong>October
                                    19,
                                    2024</strong>, in Cairo, as part of the third edition of the <strong>TMC (Trainers
                                    Meetup
                                    Community)</strong>, organized in partnership with the Egyptian Board Academy (EBA),
                                the
                                event's organizer.
                            @elseif(app()->getLocale()=="fr")
                                Nous avons le plaisir de vous annoncer le lancement officiel de la plateforme
                                Learn<strong>2earn.cash</strong>, exclusivement dédiée aux formateurs. Cet événement se
                                tiendra le 19 octobre
                                2024 au Caire, dans le cadre de la Conférence Internationale “L'impact de la formation
                                sur
                                le développement des entrepreneurs”. organisé en partenariat avec EBA, l'organisateur de
                                l'événement.
                            @else
                                يسعدنا أن نعلن عن الإطلاق الرسمي لمنصة Learn<strong>2earn.cash</strong>، المخصصة حصريًا
                                للمدربين. سيُقام هذا
                                الحدث بحول الله تعالى و توفيقا منه في 19 أكتوبر 2024 في القاهرة، ضمن فعالية  دور التدريب
                                في
                                صناعة رواد الأعمال، التي تُنظم بالشراكة مع أكاديمية البورد المصري (EBA) منظم الحدث
                                الرئيسي.
                            @endif
                        </p>
                        <img src="{{Vite::asset('resources/images/static-news/Seminar.png')}}" alt="Seminar"
                             class="d-block img-fluid img-business-square mx-auto rounded float-left mt-2">
                        <p class="text-muted mb-2">
                            @if(app()->getLocale()=="en")
                                This conference will receive comprehensive media coverage from both the
                                press and television channels.
                            @elseif(app()->getLocale()=="fr")
                                Cette conférence bénéficiera d'une couverture médiatique par la presse et les chaînes de
                                télévision.
                            @else
                                سيحظى هذا المؤتمر بتغطية إعلامية من قبل الصحافة والقنوات التلفزيونية.
                            @endif
                        </p>
                        <p class="text-muted mb-2">
                            @if(app()->getLocale()=="en")
                                Best regards
                            @elseif(app()->getLocale()=="fr")
                                Cordialement
                            @else
                                أطيب التحيات
                            @endif</p>
                    </blockquote>
                    <blockquote class="card-blockquote mb-0 float-end">
                        <p class="text-info mb-2">       @if(app()->getLocale()=="en")
                                The Management Team
                            @elseif(app()->getLocale()=="fr")
                                L'équipe de direction
                            @else
                                فريق الإدارة
                            @endif.
                        </p>
                    </blockquote>
                </div>
                <div class="card-footer">
                    <p class="text-muted mb-0 float-end">02-10-2024</p>
                </div>
            </div>
        </div>
        <div class="col-xxl-12 my-2">
            <div class="card ribbon-box right border shadow-none overflow-hidden mb-lg-0 material-shadow">

                <div class="card-header">
                    <h6 class="card-title text-info mb-0">
                        @if(app()->getLocale()=="en")
                            Stock Trading Opening Indicator Between
                            Investors
                        @elseif(app()->getLocale()=="fr")
                            Indicateur d'ouverture des transactions d'actions entre investisseurs
                        @else
                            مؤشر افتتاح معاملات الأسهم بين المستثمرين
                        @endif
                    </h6>
                </div>
                <div class="card-body">
                    <div class="ribbon ribbon-danger ribbon-shape trending-ribbon">
                        <i class="ri-flashlight-fill text-white align-bottom float-start me-1"></i> <span
                            class="trending-ribbon-text">{{__('News')}}</span>
                    </div>
                    <blockquote class="card-blockquote mb-0">
                        <p class="text-muted mb-2">
                            @if(app()->getLocale()=="en")
                                Good news, dear investors!
                            @elseif(app()->getLocale()=="fr")
                                Nous aurons le plaisir de vous communiquer, à partir du 20 octobre 2024, un indicateur
                                précisant la date approximative de l’ouverture des transactions et la vente  d’actions
                                entre
                                investisseurs (trading). Nous vous souhaitons à tous un grand succès.
                            @else
                                خبر سار، أيها المستثمرون الأعزاء!
                        @endif
                        <p class="text-muted mb-2">

                            @if(app()->getLocale()=="en")
                                We are pleased to announce that, starting from October 20, 2024, we will
                                provide you with an indicator specifying the approximate date of the opening of stock
                                transactions between investors (trading). We wish you all great success.
                            @elseif(app()->getLocale()=="fr")
                                Nous aurons le plaisir de vous communiquer, à partir du 20 octobre 2024, un indicateur
                                précisant la date approximative de l’ouverture des transactions et la vente  d’actions
                                entre
                                investisseurs (trading). Nous vous souhaitons à tous un grand succès.
                            @else
                                يسعدنا أن نعلن أنه ابتداءً من 20 أكتوبر 2024، سنقوم بتزويدكم بمؤشر يحدد التاريخ التقريبي
                                لافتتاح معاملات الأسهم بين المستثمرين (التداول). نتمنى لكم جميعاً نجاحاً كبيراً.
                            @endif
                        </p>
                        <p class="text-muted mb-2">
                            @if(app()->getLocale()=="en")
                                Best regards
                            @elseif(app()->getLocale()=="fr")
                                Cordialement
                            @else
                                أطيب التحيات
                            @endif
                        </p>
                    </blockquote>
                    <blockquote class="card-blockquote mb-0 float-end">
                        <p class="text-info mb-2">
                            @if(app()->getLocale()=="en")
                                The Management Team
                            @elseif(app()->getLocale()=="fr")
                                L'équipe de direction
                            @else
                                فريق الإدارة
                            @endif
                        </p>
                    </blockquote>
                </div>
                <div class="card-footer">
                    <p class="text-muted mb-0 float-end">02-10-2024</p>
                </div>
            </div>
        </div>
    @endif
</div>

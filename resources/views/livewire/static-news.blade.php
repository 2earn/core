<div>
    @if($enableStaticNews)
        <div class="col-xxl-12 mb-1">
            <div class="card ribbon-box right border shadow-none overflow-hidden mb-lg-0 material-shadow">
                <div class="card-body">
                    <div class="ribbon ribbon-danger ribbon-shape trending-ribbon">
                        <i class="ri-flashlight-fill text-white align-bottom float-start me-1"></i> <span
                            class="trending-ribbon-text">{{__('News')}}</span>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-muted mb-2">
                                @if(app()->getLocale()=="en")
                                    Exclusive WhatsApp group
                                @elseif(app()->getLocale()=="fr")
                                    Groupe WhatsApp exclusif
                                @else
                                    مجموعة واتساب حصرية
                                @endif
                            </h6>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-sm-12 col-md-4 col-lg-3">
                            <div class="d-flex justify-content-center align-items-center">
                                <img src="{{ Vite::asset('resources/images/WhatsApp.jpg') }}"
                                     class="img-thumbnail">
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-8 col-lg-9">
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
                                        A private and exclusive WhatsApp group has been created for people registered on
                                        the
                                        2earn.cash platform... Thank you to all other members who have not yet joined
                                        the group
                                        to click on <a href="https://chat.whatsapp.com/JRJV7LgsULwGyvIsvvXm9A">this
                                            link</a> to send a membership request.
                                    @elseif(app()->getLocale()=="fr")
                                        Un groupe WhatsApp privé et exclusif a été créé pour les personnes inscrites sur
                                        la
                                        plateforme 2earn.cash... Merci à tous les autres membres qui n'ont pas encore
                                        rejoint le
                                        groupe de cliquer sur <a
                                            href="https://chat.whatsapp.com/JRJV7LgsULwGyvIsvvXm9A">ce lien</a> pour
                                        envoyer une demande d'adhésion.
                                    @else
                                        تم إنشاء مجموعة واتساب خاصة و حصرية للمسجلين في منصة 2earn.cash ... الرجاء من
                                        كل الأخوة الأعضاء الذين لم يلتحقوا بعد بالمجموعة النقر على
                                        <a href="https://chat.whatsapp.com/JRJV7LgsULwGyvIsvvXm9A">هذا الرابط</a>
                                        لإرسال طلب إنضمام
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
                        </div>
                    </div>

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
                    <p class="text-muted mb-0 float-end">28-10-2024</p>
                </div>
            </div>
        </div>
        <div class="col-xxl-12 mb-1">
            <div class="card ribbon-box right border shadow-none overflow-hidden mb-lg-0 material-shadow">
                <div class="card-body">
                    <div class="ribbon ribbon-danger ribbon-shape trending-ribbon">
                        <i class="ri-flashlight-fill text-white align-bottom float-start me-1"></i> <span
                            class="trending-ribbon-text">{{__('News')}}</span>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-muted mb-2">
                                @if(app()->getLocale()=="en")
                                    Expansion of the Cooperation Agreement with International NAGA
                                @elseif(app()->getLocale()=="fr")
                                    Expansion de l’accord de coopération avec International NAGA
                                @else
                                    توسيع اتفاقية التعاون مع  International NAGA

                                @endif
                            </h6>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-sm-12 col-md-4 col-lg-4">
                            <div class="d-flex justify-content-center align-items-center">
                                <img src="{{ Vite::asset('resources/images/static-news/n2.png') }}"
                                     class="img-thumbnail">
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-8">
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
                                    <p class="text-muted">
                                        We are pleased to announce the expansion of the cooperation and partnership
                                        agreement, which initially focused on investment department at International
                                        NAGA, to now include an extended partnership covering training department and
                                        the strategic consulting department within the same institution.
                                    </p>
                                    <p class="text-muted">
                                        It is worth noting that International NAGA is a multi-sector institution,
                                        primarily active in investment, consulting, and training, with its headquarters
                                        located in Istanbul, Turkey.
                                    </p>
                                    <p class="text-muted">
                                        The institution is represented by *Dr. Bilal NAGATI, who serves as the General
                                        Manager of International NAGA. He is also *a member of the Scientific Committee
                                        at the KPI British Academy and a faculty member at the BECERT American Academy.
                                        Dr. Bilal NAGATI is considered an international expert in strategic planning and
                                        is the author of several books on institutional development, personal
                                        development, and entrepreneurship.
                                    </p>

                                @elseif(app()->getLocale()=="fr")
                                    <p class="text-muted">
                                        Nous sommes heureux d'annoncer l'extension de l'accord de coopération et de
                                        partenariat initialement limité à la Direction de l'investissement de
                                        l'institution NAGA vers un partenariat élargi englobant désormais la Direction
                                        de la formation et celle des Conseils stratégiques au sein de la même
                                        institution.
                                    </p>
                                    <p class="text-muted">
                                        À noter que International NAGA est une institution opérant dans divers secteurs,
                                        principalement l'investissement, le conseil et la formation et dont le siège est
                                        situé à Istanbul, Turquie,
                                    </p>
                                    <p class="text-muted">
                                        La société est représentée par Dr. Bilal NAGATI , qui occupe le poste de
                                        Directeur général de International NAGA . Il est également membre du comité
                                        scientifique de l'Académie KPI en Grande-Bretagne et membre du corps professoral
                                        de l'Académie BECERT aux États-Unis. Expert international en planification
                                        stratégique, Dr. Bilal NAGATI est également auteur de plusieurs ouvrages sur le
                                        développement organisationnel, le développement personnel, et l’entrepreneuriat.
                                    </p>

                                @else
                                    <p class="text-muted"> يسعدنا أن نعلن عن توسيع اتفاقية التعاون والشراكة التي كانت
                                        مقتصرة في البداية على
                                        إدارة الاستثمار في مؤسسة NAGA لتصبح الآن شراكة موسّعة تشمل أيضًا إدارة التدريب
                                        و*قسم الاستشارات الاستراتيجية* داخل المؤسسة نفسها.
                                    </p>
                                    <p class="text-muted"> يجدر بالذكر أن International NAGA هي مؤسسة تعمل في قطاعات
                                        متعددة، وأهمها
                                        الاستثمار، الاستشارات، والتدريب، ويقع مقرها الرئيسي في **إسطنبول، تركيا.
                                    </p>
                                    <p class="text-muted"> يمثل المؤسسة الدكتور بلال النقاطي، الذي يشغل منصب **المدير
                                        العام لمؤسسة
                                        International NAGA. وهو أيضًا **عضو في اللجنة العلمية في أكاديمية KPI
                                        البريطانية، وعضو هيئة التدريس في أكاديمية BECERT الأمريكية. يُعتبر الدكتور بلال
                                        النقاطي **خبيرًا دوليًا في التخطيط الاستراتيجي، وهو *مؤلف للعديد من الكتب التي
                                        تتناول **التطوير المؤسسي، التنمية الذاتية، وريادة الأعمال.
                                    </p>
                                    @endif
                                    </p>
                                    <div class="d-flex justify-content-center align-items-center">
                                        <img src="{{ Vite::asset('resources/images/static-news/n1.png') }}"
                                             class="img-thumbnail">
                                    </div>


                            </blockquote>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-8">
                            <p class="text-muted mb-2">
                                @if(app()->getLocale()=="en")
                                    Best regards
                                @elseif(app()->getLocale()=="fr")
                                    Cordialement
                                @else
                                    أطيب التحيات
                                @endif
                            </p>
                        </div>
                    </div>

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
                    <p class="text-muted mb-0 float-end">01-11-2024</p>
                </div>
            </div>
        </div>
        <div class="col-xxl-12 mb-1">
            <div class="card ribbon-box right border shadow-none overflow-hidden mb-lg-0 material-shadow">
                <div class="card-body">
                    <div class="ribbon ribbon-danger ribbon-shape trending-ribbon">
                        <i class="ri-flashlight-fill text-white align-bottom float-start me-1"></i> <span
                            class="trending-ribbon-text">{{__('News')}}</span>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-muted mb-2">
                                @if(app()->getLocale()=="en")
                                    The list of winners
                                @elseif(app()->getLocale()=="fr")
                                    La liste des gagnants
                                @else
                                    قائمة الفائزين
                                @endif
                            </h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-4 col-lg-3">
                            <div class="d-flex justify-content-center align-items-center">
                                <img src="{{ Vite::asset('resources/images/static-news/n3.png') }}"
                                     class="img-thumbnail">
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-8 col-lg-9">
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
                                        Here is the List of winners who won prizes in the competition held within the
                                        activities of the Cairo Conference: The Role of Training in Creating
                                        Entrepreneurs in partnership with the Egyptian Board Academy (EBA)
                                <ol class="text-muted">
                                    <li> - Shorouk Fahmy : Identifier: 197604753
                                    </li>
                                    <li> - Hajar Osama : Identifier: 197604754
                                    </li>
                                    <li> - Muhammad Saeed : Identifier: 197604751
                                    </li>
                                    <li> - Dr. Ahmed Darwich: Identifier: 197604756
                                    </li>
                                    <li> - Hajar Khalaf : Identifier: 197604761
                                    </li>
                                    <li> - Ahmed Imam : Identifier: 197604802
                                    </li>
                                    <li> - Samer Tariq : Identifier: 197604853
                                    </li>
                                    <li> - Shorouk Muhammad: Identifier 197604958
                                    </li>
                                    <li> - Marwa Abdel Rahman : Identifier: 197605073
                                    </li>
                                    <li> - Nada Reda : Identifier : 197605185
                                    </li>
                                </ol>
                                <p class="text-muted"> Congratulations on winning a valuable gift set with us.</p>

                                @elseif(app()->getLocale()=="fr")
                                    Vous trouverez ci-dessous la liste des gagnants dans le cadre de la Conférence du
                                    Caire, intitulée : Le rôle de la formation dans la création d'entrepreneurs en
                                    partenariat avec l'Egyptian Board Academy (EBA).
                                    <ol class="text-muted">
                                        <li> - Shorouk Fahmy : Identifiant: 197604753
                                        </li>
                                        <li> - Hajar Osama : Identifiant: 197604754
                                        </li>
                                        <li> - Muhammad Saeed : Identifiant: 197604751
                                        </li>
                                        <li> - Dr. Ahmed Darwich: Identifiant: 197604756
                                        </li>
                                        <li> - Hajar Khalaf : Identifiant: 197604761
                                        </li>
                                        <li> - Ahmed Imam : Identifiant: 197604802
                                        </li>
                                        <li> - Samer Tariq : Identifiant: 197604853
                                        </li>
                                        <li> - Shorouk Muhammad: Identifiant 197604958
                                        </li>
                                        <li> - Marwa Abdel Rahman : Identifiant: 197605073
                                        </li>
                                        <li> - Nada Reda : Identifiant : 197605185
                                        </li>
                                    </ol>
                                    <p class="text-muted"> Félicitations pour avoir gagné avec nous un précieux ensemble
                                        de cadeaux</p>

                                @else
                                    في ما يلي قائمة الأخوة و الأخوات الفائزين بالجوائز في المسابقة التي أجريت ضمن
                                    فعاليات مؤتمر القاهرة:دور التدريب في صناعة رواد الأعمال بالشراكة مع أكاديمية البورد
                                    المصري (EBA)
                                    <ol class="text-muted">
                                        <li> - شروق فهمي: المعرّف : 197604753
                                        </li>
                                        <li> - هاجر أسامة: المعرّف : 197604754
                                        </li>
                                        <li> - محمد سعيد: المعرّف : 197604751
                                        </li>
                                        <li> - د. أحمد درويش: المعرّف : 197604756
                                        </li>
                                        <li> - هاجر خلف: المعرّف : 197604761
                                        </li>
                                        <li> - أحمد إمام: المعرّف : 197604802
                                        </li>
                                        <li> - سامر طارق: المعرّف : 197604853
                                        </li>
                                        <li> - شروق محمد: المعرّف : 197604958
                                        </li>
                                        <li> - مروى عبد الرحمان: المعرّف : 197605073
                                        </li>
                                        <li> - ندا رضا: المعرّف : 197605185
                                        </li>
                                    </ol>
                                    <p class="text-muted"> مبروك فوزهم معنا بمجموعة قيمة من الهدايا</p>

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
                        </div>
                    </div>

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
                    <p class="text-muted mb-0 float-end">28-10-2024</p>
                </div>
            </div>
        </div>
    @endif
</div>

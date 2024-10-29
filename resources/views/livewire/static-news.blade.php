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
                        <div class="col-2">
                            <div class="d-flex justify-content-center align-items-center w-100 h-100">
                                <img src="{{ Vite::asset('resources/images/WhatsApp.jpg') }}"
                                     alt="Description de l'image"
                                     class="img-thumbnail img-fluid w-100 h-100" style="object-fit: cover;">
                            </div>
                        </div>
                        <div class="col">
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
                                        تم إنشاء مجموعة واتساب خاصة و حصرية للمسجلين في منصة 2earn.cash ...  الرجاء من
                                        كل الأخوة الأعضاء الذين لم يلتحقوا بعد بالمجموعة النقر على
                                        <a href="https://chat.whatsapp.com/JRJV7LgsULwGyvIsvvXm9A"> هذا الرابط </a>
                                        لإرسال طلب إنظمام

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

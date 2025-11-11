<div class="card ribbon-box right border shadow-none overflow-hidden material-shadow">
    <div class="card-body">
        <div class="ribbon ribbon-primary ribbon-shape trending-ribbon">
            <i class="ri-calendar-event-fill text-white align-bottom float-start me-1"></i> <span
                class="trending-ribbon-text text-light">{{__('Event')}}</span>
        </div>
        <div class="row">
            <div class="col-12">
                <h5 class="mb-2 text-info">
                    {{\App\Models\TranslaleModel::getTranslation($event,'title',$event->title)}}
                </h5>

                @if(\App\Models\User::isSuperAdmin())
                    <p class="mx-2">
                        <a class="link-info float-end"
                           href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($event,'title')])}}">
                            <i class="ri-translate-2 align-bottom me-1"></i>{{__('Update Translation')}}</a>
                    </p>
                @endif
            </div>
        </div>
        <div class="row">
            @if($event->hashtags && $event->hashtags->count())
                <div class="mt-2">
                    <span class="fw-semibold">{{ __('Hashtags:') }}</span>
                    <br>
                    @foreach($event->hashtags as $hashtag)
                        <span class="badge bg-info text-light mx-1">#{{ $hashtag->name }}</span>
                    @endforeach
                </div>
            @endif
            @if($event->location)
                <div class="mt-2">
                    <span class="fw-semibold">{{ __('Location:') }}</span>
                    <span> {{\App\Models\TranslaleModel::getTranslation($event,'location',$event->location)}}</span>
                    @if(\App\Models\User::isSuperAdmin())
                        <p class="mx-2">
                            <a class="link-info float-end"
                               href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($event,'location')])}}">
                                <i class="ri-translate-2 align-bottom me-1"></i>{{__('Update Translation')}}</a>
                        </p>
                    @endif
                </div>
            @endif

            <div @if($event->mainImage) class="col-sm-12 col-md-8 col-lg-8 mt-2"
                 @else class="col-sm-12 col-md-12 col-lg-12" @endif>
                <blockquote class="text-muted mt-2">
                    {!! \App\Models\TranslaleModel::getTranslation($event,'content',$event->content) !!}
                </blockquote>
                @if(\App\Models\User::isSuperAdmin())
                    <p class="mx-2">
                        <a class="link-info float-end"
                           href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($event,'content')])}}">
                            <i class="ri-translate-2 align-bottom me-1"></i>{{__('Update Translation')}}</a>
                    </p>
                @endif
            </div>
            @if($event->mainImage)
                <div class="col-sm-12 col-md-4 col-lg-3">
                    <div class="d-flex justify-content-center align-items-center">
                        <img src="{{ asset('uploads/' . $event->mainImage->url) }}"
                             class="img-thumbnail">
                    </div>
                </div>
            @endif
            @if($event->published_at)
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <p class="float-end text-muted">
                        <span class="fw-semibold">{{ __('Published at:') }}</span>
                        <span>{{ $event->published_at }}</span>
                    </p>
                </div>
            @endif
        </div>
    </div>
    <div class="card-footer text-muted">
        <a href="{{ route('event_show', ['locale' => app()->getLocale(), 'id' => $event->id]) }}"
           class="btn btn-outline-primary btn-sm mx-1 float-end">
            {{ __('View details') }}
        </a>

        <div class="d-flex gap-4 align-items-center">
            <div class="d-flex align-items-center">
                <i class="ri-thumb-up-line text-primary fs-5 me-2"></i>
                <span class="fw-medium">{{ $event->likes()->count() ?? 0 }}</span>
                <span class="text-muted ms-1">{{ __('Likes') }}</span>
            </div>
            <div class="d-flex align-items-center">
                <i class="ri-chat-3-line text-info fs-5 me-2"></i>
                <span class="fw-medium">{{ $event->comments()->where('validated',true)->count() ?? 0 }}</span>
                <span class="text-muted ms-1">{{ __('Comments') }}</span>
            </div>
        </div>
    </div>
</div>

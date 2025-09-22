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
                           href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($event,'title')])}}">{{__('See or update Translation')}}</a>
                    </p>
                @endif
            </div>
        </div>
        <div class="row">
            @if($event->hashtags && $event->hashtags->count())
                <div class="mt-2">
                    <h5 class="fw-bold">{{ __('Hashtags:') }}</h5>
                    @foreach($event->hashtags as $hashtag)
                        <span class="badge bg-info text-light mx-1">#{{ $hashtag->name }}</span>
                    @endforeach
                </div>
            @endif
            <div class="mb-2">
                <h5 class="fw-bold">{{ __('Location:') }}</h5>
                <span class="badge bg-secondary">{{ $event->location }}</span>
            </div>
            <div @if($event->mainImage) class="col-sm-12 col-md-8 col-lg-8"
                 @else class="col-sm-12 col-md-12 col-lg-12" @endif>
                <h5 class="fw-bold">{{ __('Content:') }}</h5>
                <blockquote>
                    {!! \App\Models\TranslaleModel::getTranslation($event,'content',$event->content) !!}
                </blockquote>
                @if(\App\Models\User::isSuperAdmin())
                    <p class="mx-2">
                        <a class="link-info float-end"
                           href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($event,'content')])}}">{{__('See or update Translation')}}</a>
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
        </div>
    </div>
    <div class="card-footer text-muted ">
        <span class="mb-0 float-end">
            {{$event->published_at}}
        </span>
        <div class="mt-2">
            <span>
                <i class="fa fa-thumbs-up"></i>
                {{ $event->likes()->count() ?? 0 }} {{ __('Likes') }}
            </span>
            <span class="me-3">
                <i class="fa fa-comments"></i>
                {{ $event->comments()->where('validated',true)->count()  ?? 0 }} {{ __('Comments') }}
            </span>
        </div>
    </div>
</div>

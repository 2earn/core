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
                <div class="mb-2">
                    <span class="badge bg-secondary">{{ $event->location }}</span>
                </div>
                @if(\App\Models\User::isSuperAdmin())
                    <p class="mx-2">
                        <a class="link-info float-end"
                           href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($event,'title')])}}">{{__('See or update Translation')}}</a>
                    </p>
                @endif
            </div>
        </div>
        <div class="row">
            <div @if($event->mainImage) class="col-sm-12 col-md-8 col-lg-8"
                 @else class="col-sm-12 col-md-12 col-lg-12" @endif>
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
    <div class="card-footer">
                 <span class="ms-3 text-muted">
                    <i class="bi bi-hand-thumbs-up"></i> {{ $event->likes()->count() }} {{ __('Likes') }}
                </span>
        <span class="ms-3 text-muted">
                    <i class="bi bi-chat-left-text"></i> {{ $event->comments()->where('validated',true)->count() }} {{ __('Comments') }}
                </span>

        <span class="ms-3 float-end text-muted">
  <strong>
            {{__('Event published at')}}:
        </strong>
        {{$event->published_at}}
        </span>
    </div>
</div>

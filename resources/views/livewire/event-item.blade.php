<div class="card ribbon-box right border shadow-none overflow-hidden material-shadow">
    <div class="card-body">
        <div class="ribbon ribbon-primary ribbon-shape trending-ribbon">
            <i class="ri-calendar-event-fill text-white align-bottom float-start me-1"></i> <span
                class="trending-ribbon-text text-info">{{__('Event')}}</span>
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
            <div class="col-12">
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
        </div>
    </div>
    <div class="card-footer">
        <strong class="text-muted">
            {{__('Event published at')}}:
        </strong>
        {{$event->published_at}}
    </div>
</div>


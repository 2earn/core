<div class="card ribbon-box right border shadow-none overflow-hidden material-shadow">
    <div class="card-body">
        <div class="ribbon ribbon-danger ribbon-shape trending-ribbon">
            <i class="ri-newspaper-fill text-white align-bottom float-start me-1"></i> <span
                class="trending-ribbon-text text-light">{{__('News')}}</span>
        </div>
        <div class="row">
            <div class="col-12">
                <h5 class="mb-2 text-info">
                    {{\App\Models\TranslaleModel::getTranslation($news,'title',$news->title)}}
                </h5>
                @if(\App\Models\User::isSuperAdmin())
                    <p class="mx-2">
                        <a class="link-info float-end"
                           href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($news,'title')])}}">
                            <i class="ri-translate-2 align-bottom me-1"></i>{{__('Update Translation')}}</a>
                    </p>
                @endif
            </div>
        </div>
        <div class="row">
            @if($news->hashtags && $news->hashtags->count())
                <div class="mt-2">
                    <span class="fw-semibold">{{ __('Hashtags:') }}</span>
                    <br>
                    @foreach($news->hashtags as $hashtag)
                        <span class="badge bg-info text-light mx-1">#{{ $hashtag->name }}</span>
                    @endforeach
                </div>
            @endif
            <div @if($news->mainImage) class="col-sm-12 col-md-8 col-lg-8"
                 @else class="col-sm-12 col-md-12 col-lg-12" @endif>
                <blockquote class="text-muted mt-2">
                    {!! \App\Models\TranslaleModel::getTranslation($news,'content',$news->content) !!}
                </blockquote>
                @if(\App\Models\User::isSuperAdmin())
                    <p class="mx-2">
                        <a class="link-info float-end"
                           href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($news,'content')])}}">
                            <i class="ri-translate-2 align-bottom me-1"></i>{{__('Update Translation')}}</a>
                    </p>
                @endif

            </div>

            @if($news->mainImage)
                <div class="col-sm-12 col-md-4 col-lg-3">
                    <div class="d-flex justify-content-center align-items-center">
                        <img src="{{ asset('uploads/' . $news->mainImage->url) }}"
                             class="img-thumbnail">
                    </div>
                </div>
            @endif
            <div class="col-sm-12 col-md-4 col-lg-3">
                <span class="text-muted mb-2">
            {{__('The Management Team')}}
        </span>
            </div>
            @if($news->published_at)
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <p class="float-end text-muted">
                        <span class="fw-semibold">{{ __('Published at:') }}</span>
                        <span>{{ $news->published_at }}</span>
                    </p>
                </div>
            @endif
        </div>
    </div>
    <div class="card-footer text-muted">
        <div class="float-end mx-1">
            <a href="{{ route('news_show', ['locale' => app()->getLocale(), 'id' => $news->id]) }}"
               class="btn btn-outline-secondary  btn-sm">
                {{__('View Details')}}
            </a>
        </div>
        <div class="d-flex gap-4 align-items-center">
            <div class="d-flex align-items-center">
                <i class="ri-thumb-up-line text-primary fs-5 me-2"></i>
                <span class="fw-medium">{{ $news->likes()->count() ?? 0 }}</span>
                <span class="text-muted ms-1">{{ __('Likes') }}</span>
            </div>
            <div class="d-flex align-items-center">
                <i class="ri-chat-3-line text-info fs-5 me-2"></i>
                <span class="fw-medium">{{ $news->comments()->where('validated',true)->count() ?? 0 }}</span>
                <span class="text-muted ms-1">{{ __('Comments') }}</span>
            </div>
        </div>
    </div>
</div>

<div class="card ribbon-box right border shadow-none overflow-hidden material-shadow">
    <div class="card-body">
        <div class="ribbon ribbon-danger ribbon-shape trending-ribbon">
            <i class="ri-flashlight-fill text-white align-bottom float-start me-1"></i> <span
                class="trending-ribbon-text text-info">{{__('News')}}</span>
        </div>
        <div class="row">
            <div class="col-12">
                <h5 class="mb-2 text-info">
                    {{\App\Models\TranslaleModel::getTranslation($news,'title',$news->title)}}
                </h5>
                @if(\App\Models\User::isSuperAdmin())
                    <p class="mx-2">
                        <a class="link-info float-end"
                           href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($news,'title')])}}">{{__('See or update Translation')}}</a>
                    </p>
                @endif
            </div>
        </div>
        <div class="row">
            <div @if($news->mainImage) class="col-sm-12 col-md-8 col-lg-8"
                 @else class="col-sm-12 col-md-12 col-lg-12" @endif>
                <blockquote>
                    {!! \App\Models\TranslaleModel::getTranslation($news,'content',$news->content) !!}
                </blockquote>
                @if(\App\Models\User::isSuperAdmin())
                    <p class="mx-2">
                        <a class="link-info float-end"
                           href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($news,'content')])}}">{{__('See or update Translation')}}</a>
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
        </div>
    </div>
    <div class="card-footer">
            <span class="text-muted mb-2">
                {{__('The Management Team')}}
            </span>
        <span class="text-muted mb-0 float-end">
                {{$news->published_at}}
            </span>
    </div>
</div>

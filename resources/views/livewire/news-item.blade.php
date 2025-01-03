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
                        {{$news->title}}
                        {{\App\Models\TranslaleModel::getTranslation($news,'title',$news->content)}}
                        @if(\App\Models\User::isSuperAdmin())
                          <small class="mx-2">
                                <a class="link-info float-end"
                                   href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($news,'title')])}}">{{__('See or update Translation')}}</a>
                            </small>
                        @endif
                    </h6>
                </div>
            </div>
            <div class="row">
                @if($news->image)
                    <div class="col-sm-12 col-md-4 col-lg-3">
                        <div class="d-flex justify-content-center align-items-center">
                            <img src="{{ Vite::asset('resources/images/WhatsApp.jpg') }}"
                                 class="img-thumbnail">
                        </div>
                    </div>
                @endif
                <div class="col-sm-12 col-md-8 col-lg-9">
                    <blockquote class="card-blockquote mb-0">
                        <p class="text-muted mb-2">
                            {{__('Dear Members')}}
                        </p>
                        <blockquote class="card-blockquote mb-0">
                            {{$news->content}}
                            {{\App\Models\TranslaleModel::getTranslation($news,'content',$news->content)}}
                            @if(\App\Models\User::isSuperAdmin())
                                 <small class="mx-2">
                                    <a class="link-info float-end"
                                       href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($news,'content')])}}">{{__('See or update Translation')}}</a>
                                </small>
                            @endif
                        </blockquote>
                        <p class="text-muted mb-2">
                            {{__('Best regards')}}
                        </p>
                    </blockquote>
                </div>
            </div>

            <blockquote class="card-blockquote mb-0 float-end">
                <p class="text-info mb-2">
                    {{__('The Management Team')}}
                </p>
            </blockquote>
        </div>
        <div class="card-footer">
            <p class="text-muted mb-0 float-end">{{$news->published_at}}</p>
        </div>
    </div>
</div>

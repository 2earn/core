<div class="container-fluid">
@component('components.breadcrumb')
        @slot('title')
            {{ __('News') }}
        @endslot
    @endcomponent
    @include('layouts.flash-messages')
    <div class="card">
        <div class="card-body">
            <div class="card-header border-info">
                <div class="row">
                    <div class="float-end col-sm-12 col-md-6 col-lg-6">
                        <form class="items-center">
                            <label for="simple-search" class="sr-only">{{__('Search')}}</label>
                            <div class="w-full">
                                <input wire:model.live="search" type="text" id="simple-search"
                                       class="form-control float-end"
                                       placeholder="{{__('Search news')}}">
                            </div>
                        </form>
                    </div>
                    @if(\App\Models\User::isSuperAdmin())
                        <div class="col-sm-12 col-md-3  col-lg-6">
                            <a href="{{route('news_create_update', app()->getLocale())}}"
                               class="btn btn-soft-info add-btn float-end"
                               id="create-btn">
                                <i class="ri-add-line align-bottom me-1 ml-2"></i>
                                {{__('Create new news')}}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card-body row">
                @forelse($newss as $news)
                    <div class="col-sm-12 col-lg-12">
                        <div class="card border card-border-light">
                            <div class="card-header">
                                <h5 class="card-title mb-1">
                                    {{$news->id}}
                                    - {{\App\Models\TranslaleModel::getTranslation($news,'title',$news->title)}}
                                </h5>
                                @if(\App\Models\User::isSuperAdmin())
                                    <p class="mx-2 float-end">
                                        <a class="link-info"
                                           href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($news,'title')])}}">{{__('See or update Translation')}}</a>
                                    </p>
                                @endif

                                @if($news->enabled)
                                    <span class="badge bg-success float-end">{{__('Enabled')}}</span>
                                @else
                                    <span class="badge bg-danger float-end">{{__('Disabled')}}</span>
                                @endif

                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <blockquote class="blockquote">
                                            <p class="card-text">
                                                {{\App\Models\TranslaleModel::getTranslation($news,'content',$news->content)}}
                                            </p>

                                            @if(\App\Models\User::isSuperAdmin())
                                                <p class="mx-2 float-end">
                                                    <a class="link-info"
                                                       href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($news,'content')])}}">{{__('See or update Translation')}}</a>
                                                </p>
                                            @endif
                                        </blockquote>
                                    </div>
                                    @if ($news->mainImage)
                                        <div class="col-md-4">
                                            <img src="{{ asset('uploads/' . $news->mainImage->url) }}"
                                                 alt="Business Sector logo Image"
                                                 class="img-thumbnail rounded float-left">
                                        </div>
                                    @endif
                                </div>
                                @if(\App\Models\User::isSuperAdmin())
                                    <a wire:click="delete('{{$news->id}}')"
                                       title="{{__('Delete news')}}"
                                       class="btn btn-soft-danger material-shadow-none float-end mx-2">
                                        {{__('Delete')}}
                                        <div wire:loading wire:target="delete('{{$news->id}}')">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                      aria-hidden="true"></span>
                                            <span class="sr-only">{{__('Loading')}}...</span>
                                        </div>
                                    </a>
                                    <a
                                        href="{{route('news_create_update',['locale'=> app()->getLocale(),'id'=>$news->id])}}"
                                        title="{{__('Edit news')}}"
                                        class="btn btn-soft-primary material-shadow-none float-end">
                                        {{__('Edit')}}

                                    </a>
                                    <p class="mx-2 float-end">
                                        <a class="link-info"
                                           href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($news,'answer')])}}">{{__('See or update Translation')}}</a>
                                    </p>
                                @endif
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col">
                                        <p class="card-text float-end">{{__('Created at')}}: <small
                                                class="text-muted">{{$news->created_at}}</small>
                                        </p>
                                    </div>
                                    <div class="col">
                                        @if(\App\Models\User::isSuperAdmin())
                                            <p class="card-text  float-end">{{__('Updated at')}}: <small
                                                    class="text-muted">{{$news->updated_at}}</small></p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p>{{__('No newss')}}</p>
                @endforelse
                {{ $newss->links() }}
            </div>
        </div>
    </div>
</div>

<div class="{{getContainerType()}}">
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
                                <h4 class="card-title mb-1">
                                    {{$news->id}}
                                    - {{\App\Models\TranslaleModel::getTranslation($news,'title',$news->title)}}
                                    @if($news->enabled)
                                        <span class="badge bg-success float-end">{{__('Enabled')}}</span>
                                    @else
                                        <span class="badge bg-danger float-end">{{__('Disabled')}}</span>
                                    @endif
                                </h4>

                                @if(\App\Models\User::isSuperAdmin())
                                    <p class="mx-2 float-end">
                                        <a class="link-info"
                                           href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($news,'title')])}}">{{__('See or update Translation')}}</a>
                                    </p>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        @if($news->hashtags && $news->hashtags->count())
                                            <div class="mt-2">
                                                <span class="fw-semibold">{{ __('Hashtags:') }}</span>
                                                <br>
                                                @foreach($news->hashtags as $hashtag)
                                                    <span
                                                        class="badge bg-info text-light mx-1">#{{ $hashtag->name }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                        <div class="mt-2">
                                            <span class="fw-semibold">{{ __('Content:') }}</span>
                                            <blockquote class="blockquote">
                                                <p class="card-text">
                                                    {!! \App\Models\TranslaleModel::getTranslation($news,'content',$news->content) !!}
                                                </p>
                                                @if(\App\Models\User::isSuperAdmin())
                                                    <p class="mx-2 float-end">
                                                        <a class="link-info"
                                                           href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($news,'content')])}}">{{__('See or update Translation')}}</a>
                                                    </p>
                                                @endif
                                            </blockquote>
                                        </div>

                                    </div>
                                    @if ($news->mainImage)
                                        <div class="col-md-4">
                                            <img src="{{ asset('uploads/' . $news->mainImage->url) }}"
                                                 alt="Business Sector logo Image"
                                                 class="img-thumbnail rounded float-left">
                                        </div>
                                    @endif
                                </div>
                                <div class="mx-1 text-muted">
                                    <span class="card-text">{{__('Created at')}}: <small
                                            class="text-muted">{{$news->created_at}}</small>
                                    </span>
                                    /
                                    @if(\App\Models\User::isSuperAdmin())
                                        <span class="card-text">{{__('Updated at')}}: <small
                                                class="text-muted">{{$news->updated_at}}</small></span>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer text-muted">
                                <div class="mt-2">
                                    <span>
                                        <i class="fa fa-thumbs-up"></i>
                                        {{ $news->likes()->count() ?? 0 }} {{ __('Likes') }}
                                    </span>
                                    <span class="me-3">
                                        <i class="fa fa-comments"></i>
                                        {{ $news->comments()->where('validated',true)->count()  ?? 0 }} {{ __('Comments') }}
                                    </span>
                                </div>

                                @if(\App\Models\User::isSuperAdmin())
                                    <div class="float-end mx-1">
                                        <button type="button"
                                                title="{{__('Delete news')}}"
                                                class="btn btn-outline-danger btn-sm"
                                                wire:click="confirmDelete({{$news->id}})">
                                            {{__('Delete')}}
                                            <div wire:loading wire:target="delete">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                      aria-hidden="true"></span>
                                                <span class="sr-only">{{__('Loading')}}...</span>
                                            </div>
                                        </button>
                                        <a
                                            href="{{route('news_create_update',['locale'=> app()->getLocale(),'id'=>$news->id])}}"
                                            title="{{__('Edit news')}}"
                                            class="btn btn-outline-primary btn-sm">
                                            {{__('Edit')}}
                                        </a>
                                        <button type="button" class="btn btn-outline-warning btn-sm"
                                                wire:click="duplicateNews({{$news->id}})">
                                            <span>{{__('Duplicate')}}</span>
                                        </button>

                                    </div>
                                @endif
                                <div class="float-end">
                                    <a href="{{ route('news_show', ['locale' => app()->getLocale(), 'id' => $news->id]) }}"
                                       class="btn btn-outline-secondary  btn-sm">
                                        {{__('View Details')}}
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                @empty
                    <p>{{__('No news')}}</p>
                @endforelse
                {{ $newss->links() }}
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteNewsModal" tabindex="-1" aria-labelledby="deleteNewsModalLabel" aria-hidden="true"
         wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteNewsModalLabel">{{ __('Confirm Delete') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('Are you sure you want to delete this news item? This action cannot be undone.') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-danger" wire:click="delete">{{ __('Delete') }}</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            window.addEventListener('showDeleteModal', () => {
                var myModal = new bootstrap.Modal(document.getElementById('deleteNewsModal'));
                myModal.show();
            });
            window.addEventListener('hideDeleteModal', () => {
                var myModalEl = document.getElementById('deleteNewsModal');
                var modal = bootstrap.Modal.getInstance(myModalEl);
                if (modal) modal.hide();
            });
            document.getElementById('deleteNewsModal').addEventListener('hidden.bs.modal', function () {
                Livewire.dispatch('clearDeleteNewsId');
            });
        </script>
    @endpush
</div>

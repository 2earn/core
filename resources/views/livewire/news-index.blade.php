<div class="container">
    @component('components.breadcrumb')
        @slot('title')
            {{ __('News') }}
        @endslot
    @endcomponent
    <div class="row">
        @include('layouts.flash-messages')
    </div>
    <div class="row">
        <div class="col-12 card shadow-sm mb-4">
            <div class="card-body">
                <div class="row align-items-center g-3">
                    <div class="col-md-6">
                        <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fa fa-search text-muted"></i>
                        </span>
                            <input wire:model.live="search" type="text" id="simple-search"
                                   class="form-control"
                                   placeholder="{{__('Search news by title, content or hashtags...')}}">
                        </div>
                    </div>
                    @if(\App\Models\User::isSuperAdmin())
                        <div class="col-md-6 text-md-end">
                            <a href="{{route('news_create_update', app()->getLocale())}}"
                               class="btn btn-outline-primary"
                               id="create-btn">
                                <i class="fa fa-plus me-2"></i>{{__('Create new news')}}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @forelse($newss as $news)
            <div class="col-12 card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                                <span class="badge bg-light text-dark border fs-6">#{{$news->id}}</span>
                                @if($news->enabled)
                                    <span class="badge bg-success">
                                                    <i class="fa fa-check-circle me-1"></i>{{__('Enabled')}}
                                                </span>
                                @else
                                    <span class="badge bg-danger">
                                                    <i class="fa fa-times-circle me-1"></i>{{__('Disabled')}}
                                                </span>
                                @endif
                            </div>
                            <h4 class="card-title mb-0 fw-bold">
                                {{\App\Models\TranslaleModel::getTranslation($news,'title',$news->title)}}
                            </h4>
                        </div>
                        @if(\App\Models\User::isSuperAdmin())
                            <div>
                                <a class="btn btn-sm btn-outline-secondary text-decoration-none"
                                   href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($news,'title')])}}">
                                    <i class="fa fa-language me-1"></i>{{__('Translate Title')}}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-4">
                        <div class="{{$news->mainImage ? 'col-lg-8' : 'col-12'}}">
                            @if($news->hashtags && $news->hashtags->count())
                                <div class="mb-3">
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($news->hashtags as $hashtag)
                                            <span class="badge bg-info text-white">
                                                            <i class="fa fa-hashtag"></i>{{ $hashtag->name }}
                                                        </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="mb-3">
                                <div class="text-muted lh-lg">
                                    {!! Str::limit(strip_tags(\App\Models\TranslaleModel::getTranslation($news,'content',$news->content)), 350, '...') !!}
                                </div>
                            </div>

                            @if(\App\Models\User::isSuperAdmin())
                                <div>
                                    <a class="btn btn-sm btn-link text-decoration-none ps-0"
                                       href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($news,'content')])}}">
                                        <i class="fa fa-language me-1"></i>{{__('Translate Content')}}
                                    </a>
                                </div>
                            @endif
                        </div>

                        @if ($news->mainImage)
                            <div class="col-lg-4">
                                <div class="position-relative overflow-hidden rounded border"
                                     style="height: 250px;">
                                    <img src="{{ asset('uploads/' . $news->mainImage->url) }}"
                                         alt="{{ \App\Models\TranslaleModel::getTranslation($news,'title',$news->title) }}"
                                         class="img-fluid w-100 h-100"
                                         style="object-fit: cover;">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-4 flex-wrap">
                            <div class="d-flex gap-3">
                                            <span class="text-muted">
                                                <i class="fa fa-thumbs-up text-primary"></i>
                                                <strong class="ms-1">{{ $news->likes()->count() ?? 0 }}</strong>
                                                <small class="ms-1">{{ __('Likes') }}</small>
                                            </span>
                                <span class="text-muted">
                                                <i class="fa fa-comments text-info"></i>
                                                <strong
                                                    class="ms-1">{{ $news->comments()->where('validated',true)->count() ?? 0 }}</strong>
                                                <small class="ms-1">{{ __('Comments') }}</small>
                                            </span>
                            </div>
                            <div class="text-muted small">
                                <i class="fa fa-clock me-1"></i>
                                {{ $news->created_at->diffForHumans() }}
                            </div>
                        </div>

                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('news_show', ['locale' => app()->getLocale(), 'id' => $news->id]) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-eye me-1"></i>{{__('View Details')}}
                            </a>
                            @if(\App\Models\User::isSuperAdmin())
                                <a href="{{route('news_create_update',['locale'=> app()->getLocale(),'id'=>$news->id])}}"
                                   title="{{__('Edit news')}}"
                                   class="btn btn-sm btn-outline-secondary">
                                    <i class="fa fa-edit me-1"></i>{{__('Edit')}}
                                </a>
                                <button type="button"
                                        class="btn btn-sm btn-outline-info"
                                        wire:click="duplicateNews({{$news->id}})">
                                    <i class="fa fa-copy me-1"></i>{{__('Duplicate')}}
                                </button>
                                <button type="button"
                                        title="{{__('Delete news')}}"
                                        class="btn btn-sm btn-outline-danger"
                                        wire:click="confirmDelete({{$news->id}})">
                                    <i class="fa fa-trash me-1"></i>{{__('Delete')}}
                                    <span wire:loading wire:target="delete">
                                                    <span class="spinner-border spinner-border-sm ms-1" role="status"
                                                          aria-hidden="true"></span>
                                                </span>
                                </button>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        @empty
            <div class="col-12 card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fa fa-newspaper fa-4x text-muted opacity-50"></i>
                    </div>
                    <h5 class="text-muted mb-3 fw-semibold">{{__('No news found')}}</h5>
                    <p class="text-muted mb-4">
                        @if($search)
                            {{__('No news match your search criteria. Try adjusting your search terms.')}}
                        @else
                            {{__('There are currently no news articles available.')}}
                        @endif
                    </p>
                    @if(\App\Models\User::isSuperAdmin() && !$search)
                        <a href="{{route('news_create_update', app()->getLocale())}}"
                           class="btn btn-primary">
                            <i class="fa fa-plus me-2"></i>{{__('Create your first news')}}
                        </a>
                    @endif
                </div>
            </div>
        @endforelse
    </div>

    <div class="row">
        <div class="col-lg-12 card  p-2">
            {{ $newss->links() }}
        </div>
    </div>

    <div class="modal fade" id="deleteNewsModal" tabindex="-1" aria-labelledby="deleteNewsModalLabel" aria-hidden="true"
         wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="deleteNewsModalLabel">
                        <i class="fa fa-exclamation-triangle me-2"></i>{{ __('Confirm Delete') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <p class="mb-0">{{ __('Are you sure you want to delete this news item? This action cannot be undone.') }}</p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i>{{ __('Cancel') }}
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="delete">
                        <i class="fa fa-trash me-1"></i>{{ __('Delete') }}
                    </button>
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

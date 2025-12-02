<div class="{{getContainerType()}}">
    @section('title')
        {{ __('News') }} : {{ \App\Models\TranslaleModel::getTranslation($news,'title',$news->title) }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('News details') }} : {{ \App\Models\TranslaleModel::getTranslation($news,'title',$news->title) }}
        @endslot
    @endcomponent
    <div class="row">
        @include('layouts.flash-messages')
        <div class="col-12 card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <h3 class="card-title mb-0 fw-bold">{{ \App\Models\TranslaleModel::getTranslation($news,'title',$news->title) }}</h3>
                    <div>
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
                </div>
            </div>

            <div class="card-body">
                @if($news->hashtags && $news->hashtags->count())
                    <div class="mb-4">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($news->hashtags as $hashtag)
                                <span class="badge bg-info text-white">
                                <i class="fa fa-hashtag"></i>{{ $hashtag->name }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="row g-4">
                    <div class="@if ($news->mainImage) col-lg-8 @else col-12 @endif">
                        <div class="content-section">
                            {!! \App\Models\TranslaleModel::getTranslation($news,'content',$news->content) !!}
                        </div>
                    </div>
                    @if ($news->mainImage)
                        <div class="col-lg-4">
                            <div class="position-relative overflow-hidden rounded border">
                                <img src="{{ asset('uploads/' . $news->mainImage->url) }}"
                                     alt="{{ \App\Models\TranslaleModel::getTranslation($news,'title',$news->title) }}"
                                     class="img-fluid w-100">
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card-footer bg-light border-top">
                <div class="d-flex gap-3 text-muted small flex-wrap">
                <span>
                    <i class="fa fa-calendar me-1"></i>
                    <strong>{{__('Created at')}}:</strong> {{ $news->created_at->format(config('app.date_format')) }}
                </span>
                    @if($news->updated_at)
                        <span>
                        <i class="fa fa-edit me-1"></i>
                        <strong>{{__('Updated at')}}:</strong> {{ $news->updated_at->format(config('app.date_format')) }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-12 card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="fa fa-comments me-2"></i>{{__('Engagement')}}
                </h5>
            </div>

            <div class="card-body">
                <div class="mb-4 pb-4 border-bottom">
                    <button wire:click="toggleLike" class="btn btn-outline-primary">
                        <i class="fa fa-thumbs-up me-2"></i>
                        <span>
                        @if($liked)
                                {{__('Unlike')}}
                            @else
                                {{__('Like')}}
                            @endif
                    </span>
                        <span class="badge bg-primary ms-2">{{ $likeCount }}</span>
                    </button>
                </div>

                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fa fa-comments me-2 text-primary"></i>{{__('Comments')}}
                        </h5>
                        <span class="badge bg-secondary">{{ count($comments) }}</span>
                    </div>

                    <div class="mb-4">
                        @forelse($comments as $comment)
                            <div class="card mb-3 border">
                                <div class="card-body py-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <strong
                                                class="text-primary">{{ getUserDisplayedName($comment->user->idUser) }}</strong>
                                        </div>
                                        <small class="text-muted">
                                            <i class="fa fa-clock me-1"></i>{{ $comment->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <div class="text-muted">{!! nl2br(e($comment->content)) !!}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="fa fa-comments fa-3x text-muted opacity-50 mb-3"></i>
                                <p class="text-muted mb-0">{{__('No comments yet.')}}</p>
                                <small class="text-muted">{{__('Be the first to comment!')}}</small>
                            </div>
                        @endforelse
                    </div>

                    @auth
                        <div class="card bg-light border-0 mb-3">
                            <div class="card-body">
                                <form wire:submit.prevent="addComment">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">{{__('Add your comment')}}</label>
                                        <textarea wire:model.defer="comment"
                                                  class="form-control @error('comment') is-invalid @enderror"
                                                  rows="3"
                                                  placeholder="{{__('Share your thoughts...')}}"></textarea>
                                        @error('comment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-outline-primary">
                                            <i class="fa fa-paper-plane me-2"></i>{{__('Post Comment')}}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endauth

                    @guest
                        <div class="alert alert-info border-0 d-flex align-items-center">
                            <i class="fa fa-info-circle fa-2x me-3"></i>
                            <div>
                                <strong>{{__('Want to join the discussion?')}}</strong>
                                <p class="mb-0">{{__('Please log in to comment.')}}</p>
                            </div>
                        </div>
                    @endguest

                    @if(\App\Models\User::isSuperAdmin() && count($unvalidatedComments) > 0)
                        <div class="mt-4 pt-4 border-top">
                            <div class="alert alert-warning border-0 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-exclamation-triangle fa-2x me-3"></i>
                                    <div>
                                        <h5 class="alert-heading mb-1">{{__('Comments awaiting validation')}}</h5>
                                        <small>{{__('Review and approve pending comments')}}</small>
                                    </div>
                                    <span
                                        class="badge bg-warning text-dark ms-auto">{{ count($unvalidatedComments) }}</span>
                                </div>
                            </div>

                            @foreach($unvalidatedComments as $comment)
                                <div class="card border-warning mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <strong
                                                    class="text-dark">{{ getUserDisplayedName($comment->user->idUser) }}</strong>
                                                <span class="badge bg-warning text-dark ms-2">{{__('Pending')}}</span>
                                            </div>
                                            <small class="text-muted">
                                                <i class="fa fa-clock me-1"></i>{{ $comment->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        <div class="text-muted mb-3">{!! nl2br(e($comment->content)) !!}</div>
                                        <div class="d-flex gap-2">
                                            <button wire:click="validateComment({{ $comment->id }})"
                                                    class="btn btn-sm btn-outline-primary">
                                                <i class="fa fa-check me-1"></i>{{__('Validate')}}
                                            </button>
                                            <button wire:click="deleteComment({{ $comment->id }})"
                                                    class="btn btn-sm btn-outline-danger">
                                                <i class="fa fa-trash me-1"></i>{{__('Delete')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

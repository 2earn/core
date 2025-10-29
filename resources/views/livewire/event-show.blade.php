<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Event') }} : {{ \App\Models\TranslaleModel::getTranslation($event,'title',$event->title) }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
                {{ __('Event') }} : {{ \App\Models\TranslaleModel::getTranslation($event,'title',$event->title) }}
        @endslot
    @endcomponent
    @include('layouts.flash-messages')

    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-gradient bg-light border-bottom py-4">
            <div class="row align-items-center g-3">
                <div class="col-lg-8 col-md-7">
                    <h2 class="card-title mb-2 fw-bold text-dark">
                        {{ \App\Models\TranslaleModel::getTranslation($event,'title',$event->title) }}
                    </h2>
                    <div class="d-flex gap-3 text-muted small flex-wrap">
                        <span>
                            <i class="fa fa-calendar text-primary me-1"></i>
                            <strong>{{__('Published at')}}:</strong> {{ \Carbon\Carbon::parse($event->published_at)->format('M d, Y') }}
                        </span>
                        <span>
                            <i class="fa fa-clock text-success me-1"></i>
                            <strong>{{__('Start at')}}:</strong> {{ \Carbon\Carbon::parse($event->start_at)->format('M d, Y H:i') }}
                        </span>
                        <span>
                            <i class="fa fa-clock text-danger me-1"></i>
                            <strong>{{__('End at')}}:</strong> {{ \Carbon\Carbon::parse($event->end_at)->format('M d, Y H:i') }}
                        </span>
                    </div>
                </div>
                <div class="col-lg-4 col-md-5 text-md-end">
                    @if($event->enabled)
                        <span class="badge bg-success fs-6 px-3 py-2">
                            <i class="fa fa-check-circle me-1"></i>{{__('Enabled')}}
                        </span>
                    @else
                        <span class="badge bg-danger fs-6 px-3 py-2">
                            <i class="fa fa-times-circle me-1"></i>{{__('Disabled')}}
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body p-4">
            @if($event->hashtags && $event->hashtags->count())
                <div class="mb-4 pb-4 border-bottom">
                    <h6 class="text-muted mb-3 text-uppercase small fw-bold">
                        <i class="fa fa-tags me-2"></i>{{__('Tags')}}
                    </h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($event->hashtags as $hashtag)
                            <span class="badge bg-info bg-opacity-10 text-info border border-info fs-6 px-3 py-2">
                                <i class="fa fa-hashtag me-1"></i>{{ $hashtag->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="row g-4">
                <div class="@if ($event->mainImage) col-lg-8 col-md-7 @else col-12 @endif">
                    <!-- Content Section -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3 text-uppercase small fw-bold">
                            <i class="fa fa-align-left me-2"></i>{{__('Content')}}
                        </h6>
                        <div class="fs-6 lh-lg text-dark">
                            {!! \App\Models\TranslaleModel::getTranslation($event,'content',$event->content) !!}
                        </div>
                    </div>

                    <!-- Location Section -->
                    @if($event->location)
                        <div class="mb-3">
                            <div class="d-flex align-items-start bg-light rounded p-3">
                                <i class="fa fa-map-marker-alt text-danger me-3 mt-1 fs-5"></i>
                                <div class="flex-grow-1">
                                    <strong class="d-block mb-1 text-dark">{{__('Location')}}</strong>
                                    <span class="text-secondary">
                                        {{\App\Models\TranslaleModel::getTranslation($event,'location',$event->location)}}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                @if ($event->mainImage)
                    <div class="col-lg-4 col-md-5">
                        <div class="position-sticky" style="top: 20px;">
                            <div class="overflow-hidden rounded shadow-sm">
                                <img src="{{ asset('uploads/' . $event->mainImage->url) }}"
                                     alt="{{ \App\Models\TranslaleModel::getTranslation($event,'title',$event->title) }}"
                                     class="img-fluid w-100"
                                     style="object-fit: cover; max-height: 400px;">
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="card-footer bg-light border-top">
            <div class="d-flex gap-3 text-muted small flex-wrap">
                <span>
                    <i class="fa fa-calendar me-1"></i>
                    <strong>{{__('Created at')}}:</strong> {{ $event->created_at->format('M d, Y H:i') }}
                </span>
                @if($event->updated_at)
                    <span>
                        <i class="fa fa-edit me-1"></i>
                        <strong>{{__('Updated at')}}:</strong> {{ $event->updated_at->format('M d, Y H:i') }}
                    </span>
                @endif
            </div>
        </div>
    </div>
    <div class="card shadow border-0">
        <div class="card-header bg-gradient bg-light border-bottom py-3">
            <h5 class="mb-0 fw-bold text-dark">
                <i class="fa fa-heart text-danger me-2"></i>{{__('Engagement')}}
            </h5>
        </div>

        <div class="card-body p-4">
            <!-- Like Section -->
            <div class="mb-4 pb-4 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <button wire:click="toggleLike" class="btn @if($liked) btn-primary @else btn-outline-primary @endif px-4 py-2">
                        <i class="fa fa-thumbs-up me-2"></i>
                        <span>
                            @if($liked)
                                {{__('Unlike')}}
                            @else
                                {{__('Like')}}
                            @endif
                        </span>
                    </button>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary fs-6 px-3 py-2">
                            <i class="fa fa-thumbs-up me-1"></i>
                            <strong>{{ $likeCount }}</strong> {{__('Likes')}}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fa fa-comments me-2 text-primary"></i>{{__('Comments')}}
                    </h5>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary fs-6 px-3 py-2">
                        <strong>{{ $event->comments()->where('validated',true)->count() }}</strong>
                    </span>
                </div>

                <div class="mb-4">
                    @forelse($event->comments()->where('validated',true)->get() as $comment)
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <div>
                                            <strong class="text-dark d-block">{{ getUserDisplayedName($comment->user->idUser) }}</strong>
                                            <small class="text-muted">
                                                <i class="fa fa-clock me-1"></i>{{ $comment->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="ms-5 ps-2 text-secondary lh-lg">{!! nl2br(e($comment->content)) !!}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 bg-light rounded">
                            <div class="mb-3">
                                <i class="fa fa-comments fa-4x text-muted opacity-25"></i>
                            </div>
                            <h6 class="text-muted mb-1">{{__('No comments yet.')}}</h6>
                            <small class="text-muted">{{__('Be the first to comment!')}}</small>
                        </div>
                    @endforelse
                </div>
                @auth
                    <div class="card bg-light border-0 shadow-sm mb-3">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3">
                                <i class="fa fa-pencil-alt me-2 text-primary"></i>{{__('Add your comment')}}
                            </h6>
                            <form wire:submit.prevent="addComment">
                                <div class="mb-3">
                                    <textarea wire:model.defer="commentContent"
                                              class="form-control @error('commentContent') is-invalid @enderror"
                                              rows="4"
                                              placeholder="{{__('Add a comment')}}"></textarea>
                                    @error('commentContent')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fa fa-paper-plane me-2"></i>{{__('Post Comment')}}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endauth

                @guest
                    <div class="alert alert-info border-0 shadow-sm d-flex align-items-center">
                        <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; min-width: 50px;">
                            <i class="fa fa-info-circle fa-2x text-info"></i>
                        </div>
                        <div>
                            <h6 class="alert-heading mb-1">{{__('Want to join the discussion?')}}</h6>
                            <p class="mb-0 small">{{__('Please log in to comment.')}}</p>
                        </div>
                    </div>
                @endguest
                @if(auth()->check() && \App\Models\User::isSuperAdmin() && count($unvalidatedComments) > 0)
                    <hr>
                    <div class="row">
                        <h5 class="text-danger">{{__('Comments awaiting validation')}}</h5>
                        @foreach($unvalidatedComments as $comment)
                            <div class="border rounded p-2 mb-2 bg-warning-subtle">
                                <strong>{{ getUserDisplayedName($comment->user->idUser) }}</strong>
                                <span class="text-muted small">{{ $comment->created_at->diffForHumans() }}</span>
                                <div>{!! nl2br(e($comment->content)) !!}</div>
                                <button wire:click="validateComment({{ $comment->id }})"
                                        class="btn btn-success btn-sm m-2 float-end">{{__('Validate')}}</button>
                                <button wire:click="deleteComment({{ $comment->id }})"
                                        class="btn btn-danger btn-sm m-2 float-end">{{__('Delete')}}</button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

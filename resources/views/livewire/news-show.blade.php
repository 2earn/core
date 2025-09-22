<div class="container-fluid">
    @component('components.breadcrumb')
        @slot('title')
            {{ \App\Models\TranslaleModel::getTranslation($news,'title',$news->title) }}
        @endslot
    @endcomponent
    <div class="card">
        <div class="card-header">
            <h3>{{ \App\Models\TranslaleModel::getTranslation($news,'title',$news->title) }}
            @if($news->enabled)
                <span class="badge bg-success float-end">{{__('Enabled')}}</span>
            @else
                <span class="badge bg-danger float-end">{{__('Disabled')}}</span>
            @endif
            </h3>
        </div>
        <div class="card-body row">
            @if($news->hashtags && $news->hashtags->count())
                <div class="mt-2">
                    <h5 class="fw-bold">{{ __('Hashtags:') }}</h5>
                    @foreach($news->hashtags as $hashtag)
                        <span class="badge bg-info text-light mx-1">#{{ $hashtag->name }}</span>
                    @endforeach
                </div>
            @endif
                <div
                class="  @if ($news->mainImage)  col-md-7 @else  col-md-12 @endif">
                    <h5 class="fw-bold">{{ __('Content:') }}</h5>
                    {!! \App\Models\TranslaleModel::getTranslation($news,'content',$news->content) !!}</div>
            @if ($news->mainImage)
                <div class="col-md-5"><img src="{{ asset('uploads/' . $news->mainImage->url) }}" alt="News Image"
                                           class="img-thumbnail mb-3">
                </div>
            @endif
        </div>
        <div class="card-footer text-muted">
            {{__('Created at')}}: {{ $news->created_at }}
            @if($news->updated_at)
                | {{__('Updated at')}}: {{ $news->updated_at }}
            @endif
        </div>
    </div>

    <!-- Likes and Comments Section -->
    <div class="card mt-4">
        <div class="card-body">
            <div class="my-3">
                <button wire:click="toggleLike" class="btn btn-outline-primary">
                <span>@if($liked)
                        {{__('Unlike')}}
                    @else
                        {{__('Like')}}
                    @endif</span>
                    <span class="badge bg-primary">{{ $likeCount }}</span>
                </button>
            </div>
            <div class="my-4">
                <div class="row">
                    <h5>{{__('Comments')}}</h5>
                    @forelse($comments as $comment)
                        <div class="border rounded p-2 mb-2">
                            <strong>{{ getUserDisplayedName($comment->user->idUser) }}</strong>
                            <span class="text-muted small">{{ $comment->created_at->diffForHumans() }}</span>
                            <div>{!! nl2br(e($comment->content)) !!}</div>
                        </div>
                    @empty
                        <p class="text-muted">{{__('No comments yet.')}}</p>
                    @endforelse
                </div>
                @auth
                    <div class="row">
                        <form wire:submit.prevent="addComment" class="mb-3">
                            <div class="mb-2">
                                <textarea wire:model.defer="comment" class="form-control" rows="2"
                                          placeholder="{{__('Add a comment')}}"></textarea>
                                @error('comment') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <button type="submit" class="btn btn-success float-end">{{__('Post Comment')}}</button>
                        </form>
                    </div>
                @endauth
                @guest
                    <div class="row text-muted">
                        <p>{{__('Please log in to comment.')}}</p>
                    </div>
                @endguest
                @if(\App\Models\User::isSuperAdmin() && count($unvalidatedComments) > 0)
                    <hr>
                    <div class="row">
                        <h5 class="text-danger">{{__('Comments awaiting validation')}}</h5>
                        @foreach($unvalidatedComments as $comment)
                            <div class="border rounded p-2 mb-2 bg-warning-subtle">
                                <strong>{{ getUserDisplayedName($comment->user->idUser) }}</strong>
                                <span class="text-muted small">{{ $comment->created_at->diffForHumans() }}</span>
                                <div>{!! nl2br(e($comment->content)) !!}</div>
                                <button wire:click="validateComment({{ $comment->id }})"
                                        class="btn btn-success btn-sm mt-2 float-end">{{__('Validate')}}</button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

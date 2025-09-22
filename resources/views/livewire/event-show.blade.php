<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h2>{{ \App\Models\TranslaleModel::getTranslation($event,'title',$event->title) }}</h2>
            @if($event->enabled)
                <span class="badge bg-success float-end">{{__('Enabled')}}</span>
            @else
                <span class="badge bg-danger float-end">{{__('Disabled')}}</span>
            @endif
        </div>
        <div class="card-body row">
            @if ($event->mainImage)
                <div class="col-md-5"><img src="{{ asset('uploads/' . $event->mainImage->url) }}" alt="Event Image"
                                           class="img-thumbnail mb-3">
                </div>
            @endif
            <div
                class="  @if ($event->mainImage)  col-md-7 @else  col-md-12 @endif"> {!! \App\Models\TranslaleModel::getTranslation($event,'content',$event->content) !!}</div>
        </div>
        <div class="card-footer text-muted">
            {{__('Created at')}}: {{ $event->created_at }}
            @if($event->updated_at)
                | {{__('Updated at')}}: {{ $event->updated_at }}
            @endif
        </div>
        <div class="card-body row">
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
                <h5>{{__('Comments')}}</h5>
                @auth
                    <form wire:submit.prevent="addComment" class="mb-3">
                        <div class="mb-2">
                        <textarea wire:model.defer="commentContent" class="form-control" rows="2"
                                  placeholder="{{__('Add a comment')}}"></textarea>
                            @error('commentContent') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="btn btn-success">{{__('Post Comment')}}</button>
                    </form>
                @endauth
                @guest
                    <p>{{__('Please log in to comment.')}}</p>
                @endguest
                <div>
                    @forelse($comments as $comment)
                        <div class="border rounded p-2 mb-2">
                            <strong>{{ getUserDisplayedName($comment->user->idUser) }}</strong>
                            <span class="text-muted small">{{ $comment->created_at->diffForHumans() }}</span>
                            <div>{!! nl2br(e($comment->content)) !!}</div>
                        </div>
                    @empty
                        <p>{{__('No comments yet.')}}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

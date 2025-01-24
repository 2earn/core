@if(\App\Models\User::isSuperAdmin())
    <a href="{{route('news_create_update', ['locale' => app()->getLocale(), 'idNews' => $newsId])}}"
   class="btn btn-xs btn-primary btn2earnTable  m-1">{{__('Edit')}}</a>

<a data-id="{{$newsId}}" data-name="{{$newstitle }}" title="{{$newstitle }}"
   class="btn btn-xs btn-danger btn2earnTable deleteNews m-1">{{__('Delete')}}</a>
@endif

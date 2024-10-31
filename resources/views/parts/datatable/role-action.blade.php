<a href="{{route('role_create_update', ['locale' => app()->getLocale(), 'id' => $roleId])}}"
   class="btn btn-xs btn-primary btn2earnTable  m-1">{{__('Edit')}}</a>

<a data-id="{{$roleId}}" data-name="{{$roleName }}" title="{{$roleName }}"
   class="btn btn-xs btn-danger btn2earnTable deleterole m-1">{{__('Delete')}}</a>

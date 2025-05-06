{{\App\Models\TranslaleModel::getTranslation($platform,'name',$platform->name)}}
<br>
@if(\App\Models\User::isSuperAdmin())
    <small class="mx-2">
        <a class="link-info"
           href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($platform,'name')])}}">{{__('See or update Translation')}}</a>
    </small>
@endif
<br>
@if($platform->enabled)
    <span class="badge bg-soft-success text-success  fs-14">{{__('Enabled')}}</span>
@else
    <span class="badge bg-soft-warning text-warning  fs-14">{{__('Disabled')}}</span>
@endif
@if($platform->show_profile)
    <span class="badge bg-soft-success text-success  fs-14">{{__('Show Profile')}}</span>
@else
    <span class="badge bg-soft-warning text-warning  fs-14">{{__('Show Profile')}}</span>
@endif

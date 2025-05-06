{{\App\Models\TranslaleModel::getTranslation($platform,'name',$platform->name)}}
<br>
@if(\App\Models\User::isSuperAdmin())
    <small class="mx-2">
        <a class="link-info"
           href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($platform,'name')])}}">{{__('See or update Translation')}}</a>
    </small>
@endif

@if(!is_null($businessSector))
    {{$businessSector->id}} -
    {{\App\Models\TranslaleModel::getTranslation($businessSector,'name',$businessSector->name)}}
    <br>
    @if(\App\Models\User::isSuperAdmin())
        <small class="mx-2">
            <a class="link-info"
               href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($businessSector,'name')])}}">{{__('See or update Translation')}}</a>
        </small>
    @endif
@else
    <div class="alert alert-link">
        {{__('No Sector')}}
    </div>
@endif

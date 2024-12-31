<div>

    @section('title')
        {{ __('Faqs') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
                {{ __('Faqs') }}
        @endslot
    @endcomponent
    <div class="card">
        <div class="card-body">
            <div class="card-header border-info">
                <div class="row">
                    <div class="float-end mt-1 col-sm-12 col-md-6  col-lg-6">
                        <form class="items-center">
                            <label for="simple-search" class="sr-only">{{__('Search')}}</label>
                            <div class="w-full">
                                <input wire:model.live="search" type="text" id="simple-search"
                                       class="form-control float-end"
                                       placeholder="{{__('Search faq')}}">
                            </div>
                        </form>
                    </div>
                    @if(\App\Models\User::isSuperAdmin())
                        <div class="col-sm-12 col-md-3  col-lg-6">
                            <a href="{{route('faq_create_update', app()->getLocale())}}"
                               class="btn btn-info add-btn float-end"
                               id="create-btn">
                                <i class="ri-add-line align-bottom me-1 ml-2"></i>
                                {{__('Create new faq')}}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            <div class="card-body row m-1">
                @forelse($faqs as $faq)
                    <div class="col-sm-12 col-lg-12">
                        <div class="card border card-border-light">
                            <div class="card-header">
                                <h5 class="card-title mb-1">
                                    {{$faq->id}}
                                    - {{\App\Models\TranslaleModel::getTranslation($faq,'question',$faq->question)}}
                                </h5>
                                @if(\App\Models\User::isSuperAdmin())
                                    <small class="mx-2">
                                        <a class="link-info"
                                           href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($faq,'question')])}}">{{__('See or update Translation')}}</a>
                                    </small>
                                @endif
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                    {{\App\Models\TranslaleModel::getTranslation($faq,'answer',$faq->answer)}}
                                </p>
                                @if(\App\Models\User::isSuperAdmin())
                                    <small class="mx-2">
                                        <a class="link-info"
                                           href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($faq,'answer')])}}">{{__('See or update Translation')}}</a>
                                    </small>
                                @endif
                                <a wire:click="deleteFaq('{{$faq->id}}')"
                                   title="{{__('Delete Faq')}}"
                                   class="btn btn-soft-danger material-shadow-none float-end">
                                    {{__('Delete')}}
                                    <div wire:loading wire:target="deleteFaq('{{$faq->id}}')">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                      aria-hidden="true"></span>
                                        <span class="sr-only">{{__('Loading')}}...</span>
                                    </div>
                                </a>
                                <a
                                    href="{{route('faq_create_update',['locale'=> app()->getLocale(),'idFaq'=>$faq->id])}}"
                                    title="{{__('Edit Faq')}}"
                                    class="btn btn-soft-primary material-shadow-none float-end">
                                    {{__('Edit')}}

                                </a>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col">
                                        <p class="card-text">{{__('Created at')}}:<small
                                                class="text-muted float-end">{{$faq->created_at}}</small>
                                        </p>
                                    </div>
                                    <div class="col">
                                        @if(\App\Models\User::isSuperAdmin())
                                            <p class="card-text">{{__('Updated at')}}:<small
                                                    class="text-muted float-end">{{$faq->updated_at}}</small></p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p>{{__('No faqs')}}</p>
                @endforelse
                {{ $faqs->links() }}
            </div>
        </div>
        </div>
    </div>
</div>

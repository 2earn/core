<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Frequently asked questions') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Frequently asked questions') }}
        @endslot
    @endcomponent
    <div class="row">
        @include('layouts.flash-messages')
        <div class="col-12 card">
            <div class="card-body">
                <div class="card-header border-info">
                    <div class="row">
                        <div class="float-end col-sm-12 col-md-6 col-lg-6">
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
                                   class="btn btn-outline-info add-btn float-end"
                                   id="create-btn">
                                    {{__('Create new faq')}}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card-body row">
                    <div class="col-12 mb-2 d-flex justify-content-between align-items-center">
                        <div class="small text-muted">{{ __('Showing') }} {{ $faqs->count() }}
                            / {{ $faqs->total() }} {{ __('faqs') }}</div>
                        <div></div>
                    </div>

                    @if($faqs->count())
                        <div class="col-12">
                            @foreach($faqs as $faq)
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h5 class="mb-0">{!! \App\Models\TranslaleModel::getTranslation($faq,'question',$faq->question) !!}</h5>
                                        @if(\App\Models\User::isSuperAdmin())
                                            <a href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($faq,'question')])}}"
                                               class="me-2 link-info">
                                                <i class="ri-translate-2 align-bottom me-1"></i>{{__('Update Translation')}}
                                            </a>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <div
                                            class="mb-3">{!! \App\Models\TranslaleModel::getTranslation($faq,'answer',$faq->answer) !!}</div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="small text-muted">{{__('Created at')}}
                                                : {{ $faq->created_at }}</div>
                                            <div>
                                                @if(\App\Models\User::isSuperAdmin())
                                                    <a href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($faq,'answer')])}}"
                                                       class="me-2 link-info">
                                                        <i class="ri-translate-2 align-bottom me-1"></i>{{__('Update Translation')}}
                                                    </a>
                                                    <a href="{{route('faq_create_update',['locale'=> app()->getLocale(),'idFaq'=>$faq->id])}}"
                                                       class="btn btn-sm btn-soft-primary me-2">{{__('Edit')}}</a>
                                                    <button wire:click="deleteFaq('{{ $faq->id }}')"
                                                            class="btn btn-sm btn-soft-danger"
                                                            title="{{__('Delete Faq')}}">
                                                        {{__('Delete')}}
                                                        <span wire:loading wire:target="deleteFaq('{{ $faq->id }}')"
                                                              class="spinner-border spinner-border-sm ms-1"
                                                              role="status" aria-hidden="true"></span>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-12 mt-3">{{ $faqs->links() }}</div>
                    @else
                        <div class="col-12 py-5 text-center">
                            <h5 class="text-muted">{{ __('No faqs') }}</h5>
                            <p class="text-muted">{{ __('There are no frequently asked questions yet.') }}</p>
                            <p class="text-muted">{{ __('Use the "Create new faq" button above to add FAQs.') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

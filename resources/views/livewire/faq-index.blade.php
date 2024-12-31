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
                    @if(\App\Models\User::isSuperAdmin())
                        <div class="col-sm-12 col-md-3  col-lg-6">
                            <a href="{{route('faq_create_update', app()->getLocale())}}" class="btn btn-info add-btn"
                               id="create-btn">
                                <i class="ri-add-line align-bottom me-1 ml-2"></i>
                                {{__('Create new faq')}}
                            </a>
                        </div>
                    @endif
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
                </div>
            </div>
            <div class="card-body row ml-1">
                @forelse($faqs as $faq)
                    <div class="col-sm-12 col-lg-12">
                        <div class="card border card-border-info">
                            <div class="card-header">
                                <h5 class="card-title mb-1">{{$faq->question}}</h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text">{{$faq->answer}}</p>
                                <p class="card-text">{{__('Created at')}}:<small
                                        class="text-muted float-end">{{$faq->created_at}}</small>
                                </p>
                                @if(\App\Models\User::isSuperAdmin())
                                    <p class="card-text">{{__('Updated at')}}:<small
                                            class="text-muted float-end">{{$faq->updated_at}}</small></p>
                                @endif
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

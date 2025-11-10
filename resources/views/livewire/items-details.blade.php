<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Items details') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Items details') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-12">
            @include('layouts.flash-messages')
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h4 class="card-title mb-0">
                            <span class="badge bg-primary me-2">{{$item->id}}</span>
                            {{$item->name}}
                        </h4>
                    </div>
                    <div>
                        <button type="button" class="btn btn-outline-info">
                            {{__('Total ordered quantity')}}
                            <span class="badge bg-info ms-2">{{$sumOfItemIds}}</span>
                        </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Item Image and Quick Info Section -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-4 text-center mb-3 mb-md-0">
                            <div class="position-relative">
                                @if($item->photo_link)
                                    <img src="{{$item->photo_link}}"
                                         class="img-fluid rounded shadow-sm w-100"
                                         alt="{{$item->name}}"
                                         style="max-width: 300px;">
                                @elseif($item->thumbnailsImage)
                                    <img src="{{ asset('uploads/' . $item->thumbnailsImage->url) }}"
                                         alt="{{$item->name}}"
                                         class="img-fluid rounded shadow-sm w-100"
                                         style="max-width: 300px;">
                                @else
                                    <img src="{{Vite::asset(\App\Models\Item::DEFAULT_IMAGE_TYPE_THUMB)}}"
                                         class="img-fluid rounded shadow-sm w-100"
                                         alt="{{$item->name}}"
                                         style="max-width: 300px;">
                                @endif
                            </div>
                            <div class="mt-3">
                                <span class="badge bg-secondary fs-6 px-3 py-2">#{{$item->ref}}</span>
                            </div>
                        </div>

                        <div class="col-lg-9 col-md-8">
                            <div class="list-group list-group-flush">
                                <div
                                    class="list-group-item px-0 d-flex justify-content-between align-items-center py-3 border-bottom">
                                    <div>
                                        <h6 class="mb-0 fw-semibold">{{__('Price')}}</h6>
                                    </div>
                                    <span
                                        class="badge bg-success fs-5 px-3 py-2">{{$item->price}} {{config('app.currency')}}</span>
                                </div>

                                <div
                                    class="list-group-item px-0 d-flex justify-content-between align-items-center py-3 border-bottom">
                                    <div>
                                        <h6 class="mb-0 fw-semibold">{{__('Discount')}}</h6>
                                    </div>
                                    <span
                                        class="badge bg-warning text-dark fs-5 px-3 py-2">{{$item->discount}} {{config('app.percentage')}}</span>
                                </div>

                                <div
                                    class="list-group-item px-0 d-flex justify-content-between align-items-center py-3 border-bottom">
                                    <div>
                                        <h6 class="mb-0 fw-semibold">{{__('Discount 2earn')}}</h6>
                                    </div>
                                    <span
                                        class="badge bg-info fs-5 px-3 py-2">{{$item->discount_2earn}} {{config('app.percentage')}}</span>
                                </div>

                                @if ($item->stock)
                                    <div
                                        class="list-group-item px-0 d-flex justify-content-between align-items-center py-3 border-bottom">
                                        <div>
                                            <h6 class="mb-0 fw-semibold">{{__('Stock')}}</h6>
                                        </div>
                                        <span class="badge bg-dark fs-5 px-3 py-2">{{$item->stock}}</span>
                                    </div>
                                @endif

                                <div
                                    class="list-group-item px-0 d-flex justify-content-between align-items-center py-3 border-bottom">
                                    <div>
                                        <h6 class="mb-0 fw-semibold">{{__('Deal')}}</h6>
                                    </div>
                                    <div class="text-end">
                                        @if ($item->deal()->exists())
                                            @if(\App\Models\User::isSuperAdmin())
                                                <a href="{{route('deals_show',['locale'=>app()->getLocale(),'id'=>$item->deal->id])}}"
                                                   class="text-decoration-none">
                                                    <span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2">
                                                        {{$item->deal->id}} - {{$item->deal->name}}
                                                    </span>
                                                </a>
                                            @else
                                                <span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2">
                                                    {{$item->deal->id}} - {{$item->deal->name}}
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary fs-6 px-3 py-2">{{__('No deal')}}</span>
                                        @endif
                                    </div>
                                </div>

                                <div
                                    class="list-group-item px-0 d-flex justify-content-between align-items-center py-3">
                                    <div>
                                        <h6 class="mb-0 fw-semibold">{{__('Platform')}}</h6>
                                    </div>
                                    <div class="text-end">
                                        @if ($item->platform()->exists())
                                            @if(\App\Models\User::isSuperAdmin())
                                                <a href="{{route('platform_show',['locale'=>app()->getLocale(),'id'=>$item->platform->id])}}"
                                                   class="text-decoration-none">
                                                    <span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2">
                                                        {{$item->platform->id}} - {{$item->platform->name}}
                                                    </span>
                                                </a>
                                            @else
                                                <span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2">
                                                    {{$item->platform->id}} - {{$item->platform->name}}
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary fs-6 px-3 py-2">{{__('No Platform')}}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description Section -->
                    <div class="row">
                        <div class="col-12">
                            <div class="border-top pt-4 mt-2">
                                <h5 class="mb-3 fw-bold">{{__('Description')}}</h5>
                                <div class="alert alert-light border mb-0" role="alert">
                                    <p class="text-muted mb-0 lh-lg">
                                        {{$item->description}}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light border-top">
                    <div class="row align-items-center g-3">
                        <div class="col-lg-4 col-md-6">
                            <div class="d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                     class="bi bi-calendar-event text-muted me-2" viewBox="0 0 16 16">
                                    <path
                                        d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
                                    <path
                                        d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                </svg>
                                <small class="text-muted">
                                    {{__('Created at')}}: <strong class="text-dark">{{$item->created_at}}</strong>
                                </small>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6">
                            @if(\App\Models\User::isSuperAdmin())
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                         class="bi bi-calendar-check text-muted me-2" viewBox="0 0 16 16">
                                        <path
                                            d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                        <path
                                            d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                    </svg>
                                    <small class="text-muted">
                                        {{__('Updated at')}}: <strong class="text-dark">{{$item->updated_at}}</strong>
                                    </small>
                                </div>
                            @endif
                        </div>

                        <div class="col-lg-4 col-12">
                            @if(\App\Models\User::isSuperAdmin())
                                <div class="d-flex gap-2 justify-content-lg-end">
                                    <a href="{{route('items_create_update',['locale'=>app()->getLocale(), 'id' => $item->id])}}"
                                       class="btn btn-soft-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                             fill="currentColor" class="bi bi-pencil-square me-1" viewBox="0 0 16 16">
                                            <path
                                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                            <path fill-rule="evenodd"
                                                  d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                        </svg>
                                        {{__('Update Item')}}
                                    </a>
                                    @if($item->ref!='#0001')
                                        <button class="btn btn-soft-danger"
                                                wire:click="delete({{$item->id}})">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                 fill="currentColor" class="bi bi-trash me-1" viewBox="0 0 16 16">
                                                <path
                                                    d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                <path fill-rule="evenodd"
                                                      d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                            </svg>
                                            {{__('Delete')}}
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

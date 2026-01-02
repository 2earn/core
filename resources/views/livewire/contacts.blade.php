<div class="container">
    <style>
        .iti {
            width: 100% !important;
        }
    </style>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('You Contacts') }}
        @endslot
    @endcomponent
    <div class="row">
        @include('layouts.flash-messages')
    </div>
    <div class="row">
        <div class="card">
            <div class="card-header border-0">
                <div class="row g-3 align-items-center">
                    <div class="col-sm-6 col-lg-3">
                        <label class="form-label mb-1 fw-semibold">{{ __('Item per page') }}</label>
                        <select wire:model.live="pageCount" class="form-select"
                                aria-label="Default select example">
                            <option @if($pageCount=="10") selected @endif value="10">10</option>
                            <option @if($pageCount=="25") selected @endif value="25">25</option>
                            <option @if($pageCount=="50") selected @endif value="100">50</option>
                        </select>
                    </div>

                    <div class="col-sm-6 col-lg-4">
                        <label class="form-label mb-1 fw-semibold">{{ __('Search') }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="ri-search-line"></i></span>
                            <input wire:model.live="search" type="search"
                                   class="form-control"
                                   placeholder="{{ __('Search') }}..." aria-label="Search"/>
                        </div>
                    </div>

                    <div class="col-sm-12 col-lg-5 text-end">
                        <label class="form-label mb-1 d-block">&nbsp;</label>
                        <a href="{{ route('contacts_add', app()->getLocale()) }}" class="btn btn-outline-success">
                            {{ __('Add a contact') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                @forelse($contactUsers as $contact)
                    <div class="card border shadow-none mb-3">
                        <div class="card-body">
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0">
                                    <img
                                        src="{{ Vite::asset('resources/images/flags/'. Illuminate\Support\Str::lower($contact->apha2) .'.svg') }}"
                                        alt="" class="avatar-sm rounded-circle">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="fs-15 mb-1">{{$contact->name}} {{$contact->lastName}}</h5>
                                    <p class="text-muted mb-0">
                                        <i class="ri-phone-line align-middle me-1"></i>
                                        {{$contact->mobile}}
                                    </p>
                                    <p class="text-muted mb-0 mt-1">
                                        <i class="ri-global-line align-middle me-1"></i>
                                        {{getCountryByIso($contact->apha2)}}
                                    </p>
                                </div>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <div class="p-2 bg-light rounded">
                                        <p class="text-primary fs-12 mb-1">{{__('Status')}}</p>
                                        @if($contact->status<\App\Enums\StatusRequest::OptValidated->value)
                                            <span class="badge badge-soft-warning fs-11">
                                                    <i class="ri-time-line align-middle"></i> {{__('Not confirmed user')}}
                                                </span>
                                        @else
                                            <span class="badge badge-soft-success fs-11">
                                                    <i class="ri-checkbox-circle-line align-middle"></i> {{__('Confirmed user')}}
                                                </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 bg-light rounded">
                                        <p class="text-primary fs-12 mb-1">{{__('Availability')}}</p>
                                        <span class="badge badge-soft-{{$contact->sponsoredStatus}} fs-11">
                                                {{$contact->sponsoredMessage}}
                                            </span>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <div class="p-2 bg-light rounded">
                                        <p class="text-muted fs-11 mb-0">
                                            <i class="ri-calendar-line align-middle me-1"></i>
                                            {{__('Created')}}
                                        </p>
                                        <p class="text-dark fs-12 mb-0 mt-1 fw-medium">
                                            {{ $contact->created_at ? $contact->created_at : '-' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 bg-light rounded">
                                        <p class="text-muted fs-11 mb-0">
                                            <i class="ri-refresh-line align-middle me-1"></i>
                                            {{__('Updated')}}
                                        </p>
                                        <p class="text-dark fs-12 mb-0 mt-1 fw-medium">
                                            {{ $contact->updated_at ? $contact->updated_at : '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('contacts_edit', ['locale' =>  app()->getLocale(), 'contact'=>  $contact->id  ]) }}"
                                   class="btn btn-sm btn-soft-primary flex-fill">
                                    <i class="ri-pencil-fill align-bottom me-1"></i>
                                    {{__('Edit')}}
                                </a>
                                <a onclick="confirmDeleteContact({{$contact->id}},'{{$contact->name .' ' . $contact->lastName}}')"
                                   class="btn btn-sm btn-soft-danger flex-fill">
                                        <span wire:loading wire:target="deleteId('{{$contact->id}}')">
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                  aria-hidden="true"></span>
                                        </span>
                                    <span wire:loading.remove wire:target="deleteId('{{$contact->id}}')">
                                            <i class="ri-delete-bin-fill align-bottom me-1"></i>
                                            {{__('Delete')}}
                                        </span>
                                </a>
                            </div>

                            @if($contact->canBeSponsored || $contact->canBeDisSponsored)
                                <div class="d-flex gap-2 flex-wrap mt-2">
                                    @if($contact->canBeSponsored)
                                        <button wire:click="sponsorId({{$contact->id}})"
                                                class="btn btn-sm btn-soft-info flex-fill">
                                                <span wire:loading wire:target="sponsorId('{{$contact->id}}')">
                                                    <span class="spinner-border spinner-border-sm" role="status"
                                                          aria-hidden="true"></span>
                                                </span>
                                            <span wire:loading.remove wire:target="sponsorId('{{$contact->id}}')">
                                                    <i class="ri-user-add-line align-bottom me-1"></i>
                                                    {{__('Sponsor it')}}
                                                </span>
                                        </button>
                                    @endif
                                    @if($contact->canBeDisSponsored)
                                        <button wire:click="removeSponsoring({{$contact->id}})"
                                                class="btn btn-sm btn-soft-secondary flex-fill">
                                                <span wire:loading wire:target="removeSponsoring('{{$contact->id}}')">
                                                    <span class="spinner-border spinner-border-sm" role="status"
                                                          aria-hidden="true"></span>
                                                </span>
                                            <span wire:loading.remove
                                                  wire:target="removeSponsoring('{{$contact->id}}')">
                                                    <i class="ri-user-unfollow-line align-bottom me-1"></i>
                                                    {{__('Remove sponsoring')}}
                                                </span>
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <div class="text-muted">
                            <i class="ri-information-line fs-18 align-middle me-2"></i>
                            {{__('No records')}}
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="card-body pt-0">
                {{ $contactUsers->links() }}
            </div>
        </div>
    </div>


    <script>
        function confirmDeleteContact(contactId, ContactFullName) {
            Swal.fire({
                title: '{{ __('delete_contact') }}' + '<br>' + ContactFullName,
                text: '{{ __('operation_irreversible') }}',
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: '{{__('ok')}}',
                cancelButtonText: '{{__('cancel !')}}',
                denyButtonText: '{{__('no')}}',
                customClass: {
                    actions: 'my-actions',
                    cancelButton: 'order-1 right-gap',
                    confirmButton: 'order-2',
                    denyButton: 'order-3',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.Livewire.dispatch('deleteContact', [contactId]);
                }
            });
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var existeUserContact = '{{Session::has('existeUserContact')}}';

            if (existeUserContact) {
                Swal.fire({
                    title: '{{trans('user exist')}}',
                    text: '{{trans('changer contact')}}',
                    icon: "warning",
                    showCancelButton: true,
                    cancelButtonText: '{{trans('cancel')}}',
                    confirmButtonText: '{{trans('Yes')}}',
                }).then((result) => {
                    if (result.isConfirmed) {
                        const iddd = '{{Session::get('sessionIdUserExiste')}}';
                        var url = "{{ route('contacts_edit', ['locale' =>  app()->getLocale(), 'contact'=> Session::get('sessionIdUserExiste')]) }}";
                        document.location.href = url;
                    }
                });
            }
        });
    </script>
</div>

<div class="{{getContainerType()}}">
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
                            <option @if($pageCount=="100") selected @endif value="100">100</option>
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
                        <a href="{{ route('contacts_add', app()->getLocale()) }}" class="btn btn-success">
                            <i class="ri-add-line align-bottom me-1"></i>
                            {{ __('Add a contact') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="d-none d-lg-block">
                    <div class="table-responsive table-card">
                        <table class="table table-hover table-nowrap align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th scope="col" class="fw-semibold">{{ __('FirstName') }}</th>
                                <th scope="col" class="fw-semibold">{{ __('LastName') }}</th>
                                <th scope="col" class="fw-semibold">{{ __('Phone') }}</th>
                                <th scope="col" class="fw-semibold">{{__('Country')}}</th>
                                <th scope="col" class="text-center fw-semibold">{{__('Status')}}</th>
                                <th scope="col" class="text-center fw-semibold">{{__('Availability')}}</th>
                                <th scope="col" class="text-center fw-semibold" style="min-width: 200px;">{{ __('Actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($contactUsers as $contact)
                                <tr>
                                    <td title="{{$contact->updated_at}}">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="fs-14 mb-0">{{$contact->name}}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h6 class="fs-14 mb-0">{{$contact->lastName}}</h6>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{$contact->mobile}}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img
                                                src="{{ Vite::asset('resources/images/flags/'. Illuminate\Support\Str::lower($contact->apha2) .'.svg') }}"
                                                alt=""
                                                class="avatar-xs rounded-circle me-2">
                                            <span class="text-muted">{{getCountryByIso($contact->apha2)}}</span>
                                        </div>
                                    </td>
                                    <td class="text-center" title="{{$contact->status}}">
                                        @if($contact->status<\Core\Enum\StatusRequest::OptValidated->value)
                                            <span class="badge badge-soft-warning fs-12">
                                                <i class="ri-time-line align-middle me-1"></i>{{__('Not confirmed user')}}
                                            </span>
                                        @else
                                            <span class="badge badge-soft-success fs-12">
                                                <i class="ri-checkbox-circle-line align-middle me-1"></i>{{__('Confirmed user')}}
                                            </span>
                                        @endif
                                    </td>
                                    @php
                                        $disableUntil = getSwitchBlock($contact->id);
                                        if($contact->availablity == 1) $disableUntil = now();
                                        else $disableUntil = getSwitchBlock($contact->id);
                                    @endphp
                                    <td class="text-center">
                                        <span class="badge badge-soft-{{$contact->sponsoredStatus}} fs-12">
                                            {{$contact->sponsoredMessage}}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('contacts_edit', ['locale' =>  app()->getLocale(), 'contact'=>  $contact->id  ]) }}"
                                                   class="btn btn-soft-primary" title="{{__('Edit')}}">
                                                    <i class="ri-pencil-fill align-bottom"></i>
                                                </a>
                                                <a onclick="confirmDeleteContact({{$contact->id}},'{{$contact->name .' ' . $contact->lastName}}')"
                                                   class="btn btn-soft-danger" title="{{__('Delete')}}">
                                                    <div wire:loading wire:target="deleteId('{{$contact->id}}')">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                              aria-hidden="true"></span>
                                                    </div>
                                                    <i wire:loading.remove wire:target="deleteId('{{$contact->id}}')"
                                                       class="ri-delete-bin-fill align-bottom"></i>
                                                </a>
                                            </div>
                                            @if($contact->canBeSponsored)
                                                <button wire:click="sponsorId({{$contact->id}})"
                                                        class="btn btn-sm btn-soft-info" title="{{__('Sponsor it')}}">
                                                    <span wire:loading wire:target="sponsorId('{{$contact->id}}')">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                              aria-hidden="true"></span>
                                                    </span>
                                                    <span wire:loading.remove wire:target="sponsorId('{{$contact->id}}')">
                                                        <i class="ri-user-add-line align-bottom"></i>
                                                    </span>
                                                </button>
                                            @endif
                                            @if($contact->canBeDisSponsored)
                                                <button wire:click="removeSponsoring({{$contact->id}})"
                                                        class="btn btn-sm btn-soft-secondary"
                                                        title="{{__('Remove sponsoring')}}">
                                                    <span wire:loading wire:target="removeSponsoring('{{$contact->id}}')">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                              aria-hidden="true"></span>
                                                    </span>
                                                    <span wire:loading.remove
                                                          wire:target="removeSponsoring('{{$contact->id}}')">
                                                        <i class="ri-user-unfollow-line align-bottom"></i>
                                                    </span>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="ri-information-line fs-18 align-middle me-2"></i>
                                            {{__('No records')}}
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
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
    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {

            $('#contacts_table').DataTable({
                retrieve: true,
                searching: true,
                "bLengthChange": false,
                "processing": true,
                paging: true,
                "pageLength": 100,
                "aLengthMenu": [[100, 500, 1000], [100, 500, 1000]],
                "columns": [
                    {"data": "name"},
                    {"data": "lastName"},
                    {"data": "mobile"},
                    {"data": "flag"},
                    {"data": "status"},
                    {"data": "availablity"},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                "columnDefs":
                    [
                        {
                            "targets": [5],
                            render: function (data, type, row) {
                                var givenDate = new Date(row.reserved_at);
                                var delai = (Date.now() - givenDate) / (1000 * 60 * 60);
                                if (Number(row.idUpline) !== 0) {
                                    if (row.idUpline == row.idUser)
                                        return '<span class="badge bg-info-subtle text-info fs-14" data-id="' + row.id + '" data-phone="' + row.mobile +
                                            '">{{__('i am his sponsor')}}</span>';
                                    else
                                        return '<span class="badge bg-danger-subtle text-danger" data-id="' + row.id + '" data-phone="' + row.mobile +
                                            '">{{__('Already has a sponsor')}}</span>';
                                } else {
                                    if (Number(row.availablity) === 0)
                                        return '<span class="badge bg-success-subtle text-success" data-id="' + row.id + '" data-phone="' + row.mobile +
                                            '">{{__('Available')}}</span>';
                                    else {
                                        if (row.reserved_by == row.idUser) {
                                            if (delai < 72) {
                                                var reste = 72 - delai;
                                                return '<span class="badge bg-warning-subtle text-warning" data-id="' + row.id + '" data-phone="' + row.mobile +
                                                    '">{{__('reserved for')}} ' + reste.toFixed(0) + ' {{__('hours')}}</span>';
                                            } else {
                                                var reste = 72 + 168 - delai;
                                                return '<span class="badge bg-primary-subtle text-primary" data-id="' + row.id + '" data-phone="' + row.mobile +
                                                    '">{{__('blocked for')}} ' + reste.toFixed(0) + ' {{__('hours')}}</span>';
                                            }

                                        } else {
                                            if (delai < 72) {
                                                var reste = 72 - delai;
                                                return '<span class="badge bg-warning-subtle text-warning" data-id="' + row.id + '" data-phone="' + row.mobile +
                                                    '">{{__('reserved by other user for')}} ' + reste.toFixed(0) + ' {{__('hours')}}</span>';
                                            } else {
                                                return '<span class="badge bg-success-subtle text-success" data-id="' + row.id + '" data-phone="' + row.mobile +
                                                    '">{{__('Available')}}</span>';
                                            }
                                        }
                                    }
                                }
                            },
                        },
                    ],
                "language": {"url": urlLang},
            });
        });

    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/list.js/1.0.2/list.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/list.pagination.js/0.1.1/list.pagination.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
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

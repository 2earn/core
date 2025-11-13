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
    <div class="row ">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-2">
                        <label class="col-form-label">{{ __('Item per page') }}</label>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-3 mt-1">
                        <select wire:model.live="pageCount" class="form-select livewire-param"
                                aria-label="Default select example">
                            <option @if($pageCount=="10") selected @endif value="10">10</option>
                            <option @if($pageCount=="25") selected @endif value="25">25</option>
                            <option @if($pageCount=="100") selected @endif value="100">100</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-2">
                        <label class="col-form-label">{{ __('Search') }}</label>
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-3 mt-1">
                        <input wire:model.live="search" type="search"
                               class="form-control rounded  mr-2 ml-2"
                               placeholder="{{ __('Search') }}" aria-label="Search"
                               aria-describedby="search-addon"/>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-2 mt-2">
                        <a href="{{ route('contacts_add', app()->getLocale()) }}" class="btn btn-soft-secondary add-btn float-end">
                            <i class="ri-add-line align-bottom me-1"></i>
                            {{ __('Add a contact') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive table-card">
                    <table class="table table-striped table-bordered table-nowrap">
                        <thead>
                        <tr class="tabHeader2earn">
                            <th>{{ __('FirstName') }}</th>
                            <th>{{ __('LastName') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{__('Country')}}</th>
                            <th>{{__('Status')}}</th>
                            <th>{{__('Availability')}}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                        <tbody class="list form-check-all">
                        @forelse($contactUsers as $value)
                            <tr>
                                <td title="{{$value->updated_at}}">{{$value->name}}</td>
                                <td>{{$value->lastName}}</td>
                                <td>{{$value->mobile}}</td>
                                <td>
                                    <div class="d-flex align-items-center fw-medium">
                                        <img
                                            src="{{ Vite::asset('resources/images/flags/'. Illuminate\Support\Str::lower($value->apha2) .'.svg') }}"
                                            alt=""
                                            class="avatar-xs me-2 rounded-circle">
                                        <a href="javascript:void(0);"
                                           class="currency_name"> {{getCountryByIso($value->apha2)}}</a>
                                    </div>
                                </td>
                                <td title="{{$value->status}}">
                                    @if($value->status<\Core\Enum\StatusRequest::OptValidated->value)
                                        <span
                                            class="text-warning btn btn-soft-warning">{{__('Not confirmed user')}}</span>
                                    @else
                                        <span
                                            class="text-info btn btn-soft-primary">{{__('Confirmed user')}}</span>
                                    @endif
                                </td>
                                @php
                                    $disableUntil = getSwitchBlock($value->id);
                                    if($value->availablity == 1) $disableUntil = now();
                                    else $disableUntil = getSwitchBlock($value->id);
                                @endphp
                                <td>
                                    <button type="button"
                                            class="btn btn-outline-{{$value->sponsoredStatus}}">{{$value->sponsoredMessage}}</button>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-toggle " data-toggle="buttons">
                                        <a href="{{ route('user_contact_edit', ['locale' =>  app()->getLocale(), 'UserContact'=>  $value->id  ]) }}"
                                           class="btn btn-outline-primary ">
                                            {{__('Edit')}}
                                        </a>
                                        <a onclick="confirmDeleteContact({{$value->id}},'{{$value->name .' ' . $value->lastName}}')"
                                           class="btn btn-outline-danger">
                                            <div wire:loading wire:target="deleteId('{{$value->id}}')">
                                              <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                                <span class="sr-only">{{__('Loading')}}</span>
                                            </div>
                                            {{__('Delete')}}
                                        </a>
                                    </div>
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        @if($value->canBeSponsored)
                                            <a wire:click="sponsorId({{$value->id}})"
                                               class="btn btn-info">
                                                <div wire:loading wire:target="sponsorId('{{$value->id}}')">
                                              <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                                    <span class="sr-only">{{__('Loading')}}</span>
                                                </div>
                                                {{__('Sponsor it')}}
                                            </a>
                                        @endif
                                        @if($value->canBeDisSponsored)
                                            <a wire:click="removeSponsoring({{$value->id}})"
                                               class="btn btn-outline-dark">
                                                <div wire:loading
                                                     wire:target="removeSponsoring('{{$value->id}}')">
                                              <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                                    <span class="sr-only">{{__('Loading')}}</span>
                                                </div>
                                                {{__('Remove sponsoring')}}
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">{{__('No records')}}.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{ $contactUsers->links() }}
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
                        var url = "{{ route('user_contact_edit', ['locale' =>  app()->getLocale(), 'UserContact'=> Session::get('sessionIdUserExiste')]) }}";
                        document.location.href = url;
                    }
                });
            }
        });
    </script>
</div>

<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Roles') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Roles') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-12">
            @include('layouts.flash-messages')
        </div>
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
                                           placeholder="{{__('Search roles')}}">
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <a href="{{route('role_create_update', app()->getLocale())}}"
                               class="btn btn-outline-info add-btn float-end"
                               id="create-btn">
                                {{__('Create new role')}}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body row">
                    <div class="col-12 mb-2 d-flex justify-content-between align-items-center">
                        <div class="small text-muted">{{ __('Showing') }} {{ $roles->count() }}
                            / {{ $roles->total() }} {{ __('roles') }}</div>
                        <div></div>
                    </div>

                    @if($roles->count())
                        <div class="col-12">
                            @foreach($roles as $role)
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <h5 class="mb-0">{{ $role->name }}</h5>
                                                <span class="badge bg-secondary">{{ $role->guard_name }}</span>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <span class="badge bg-info">ID: {{ $role->id }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <small class="text-muted">{{__('Created at')}}</small>
                                                <div>{{ $role->created_at->format('Y-m-d H:i:s') }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted">{{__('Updated at')}}</small>
                                                <div>{{ $role->updated_at->format('Y-m-d H:i:s') }}</div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end align-items-center">
                                            <a href="{{route('role_create_update',['locale'=> app()->getLocale(),'idRole'=>$role->id])}}"
                                               class="btn btn-sm btn-soft-primary me-2">
                                                <i class="ri-edit-line align-bottom me-1"></i>{{__('Edit')}}
                                            </a>
                                            @if($role->id > 4)
                                                <button wire:click="$dispatch('confirmDelete', { id: {{ $role->id }}, name: '{{ $role->name }}' })"
                                                        class="btn btn-sm btn-soft-danger"
                                                        title="{{__('Delete Role')}}">
                                                    <i class="ri-delete-bin-line align-bottom me-1"></i>{{__('Delete')}}
                                                </button>
                                            @else
                                                <span class="badge bg-warning text-dark">{{__('System Role')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-12 mt-3">{{ $roles->links() }}</div>
                    @else
                        <div class="col-12 py-5 text-center">
                            <h5 class="text-muted">{{ __('No roles found') }}</h5>
                            <p class="text-muted">{{ __('There are no roles matching your search criteria.') }}</p>
                            @if($search)
                                <button wire:click="$set('search', '')" class="btn btn-sm btn-outline-primary">
                                    {{__('Clear search')}}
                                </button>
                            @else
                                <p class="text-muted">{{ __('Use the "Create new role" button above to add roles.') }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {
            window.addEventListener('confirmDelete', event => {
                Swal.fire({
                    title: '{{__('Are you sure to delete this role')}}? <h5 class="float-end">' + event.detail.name + ' </h5>',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: "Delete",
                    denyButtonText: `Cancel`
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.Livewire.dispatch("delete", [event.detail.id]);
                    }
                });
            });
        });
    </script>
</div>

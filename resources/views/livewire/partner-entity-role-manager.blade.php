<div class="container">
    @section('title')
        {{ __('Manage Partner Entity Roles') }} - {{ $partner->company_name }}
    @endsection

    @component('components.breadcrumb')
        @slot('li_1')
            <a href="{{route('partner_index', app()->getLocale())}}">{{ __('Partners') }}</a>
        @endslot
        @slot('title')
            {{ __('Manage Partner Entity Roles') }} - {{ $partner->company_name }}
        @endslot
    @endcomponent
    <div class="row">
        @include('layouts.flash-messages')
        <div class="col-12 card shadow-sm border-0">
            <div class="card-body">
<<<<<<< HEAD
                <div class="d-flex align-items-center">
                    <i class="ri-shield-user-line fs-4 text-primary me-2"></i>
                    <div>
                        <h5 class="card-title mb-0">{{ __('Entity Roles for') }}:
                            <strong>{{ $partner->name }}</strong></h5>
                        <small class="text-muted">{{ __('Manage roles and assign users') }}</small>
=======
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="ri-shield-user-line fs-4 text-primary me-2"></i>
                        <div>
                            <h5 class="card-title mb-0">{{ __('Entity Roles for') }}:
                                <strong>{{ $partner->name }}</strong></h5>
                            <small class="text-muted">{{ __('Manage roles and assign users') }}</small>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('partner_role_requests', app()->getLocale()) }}"
                           class="btn btn-outline-primary btn-sm">
                            <i class="ri-file-list-3-line me-1"></i>{{ __('Partner Role Requests') }}
                        </a>
>>>>>>> a0ca8bd14039262ce34260e1f62943e751b132cb
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="addRole">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label for="newRoleName" class="form-label">{{ __('Role Name') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('newRoleName') is-invalid @enderror"
                                   id="newRoleName"
                                   wire:model="newRoleName"
                                   placeholder="{{ __('e.g., Admin, Manager, Viewer') }}">
                            @error('newRoleName')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-5">
                            <label for="userSearch" class="form-label">{{ __('Assign User (Optional)') }}</label>
                            <div class="position-relative">
                                @if(!$newRoleUserId)
                                    <input type="text"
                                           class="form-control @error('newRoleUserId') is-invalid @enderror"
                                           id="userSearch"
                                           wire:model.live="userSearch"
                                           placeholder="{{ __('Search user by name, email, phone or ID') }}"
                                           autocomplete="off">

                                    @if($showUserDropdown && $this->searchedUsers->count() > 0)
                                        <div class="position-absolute w-100 bg-white border rounded shadow-sm mt-1"
                                             style="z-index: 1000; max-height: 200px; overflow-y: auto;">
                                            @foreach($this->searchedUsers as $user)
                                                <div class="px-3 py-2 cursor-pointer hover-bg-light"
                                                     wire:click="selectUser({{ $user->id }})"
                                                     style="cursor: pointer;">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-xs me-2">
                                                                <span
                                                                    class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                                    {{ substr($user->name, 0, 1) }}
                                                                </span>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div class="fw-semibold">{{ $user->name }}</div>
                                                            <small class="text-muted d-block">{{ $user->email }}</small>
                                                            <div class="d-flex gap-2 mt-1">
                                                                @if($user->idUser)
                                                                    <small
                                                                        class="badge bg-soft-info text-info">ID: {{ $user->idUser }}</small>
                                                                @endif
                                                                @if($user->contactUser && $user->contactUser->mobile)
                                                                    <small class="badge bg-soft-success text-success">
                                                                        <i class="ri-phone-line"></i> {{ $user->contactUser->mobile }}
                                                                    </small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @else
                                    @php
                                        $selectedUser = \App\Models\User::find($newRoleUserId);
                                    @endphp
                                    @if($selectedUser)
                                        <div class="d-flex align-items-center bg-light p-2 rounded">
                                            <div class="avatar-xs me-2">
                                                <span class="avatar-title rounded-circle bg-soft-success text-success">
                                                    {{ substr($selectedUser->name, 0, 1) }}
                                                </span>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-semibold">{{ $selectedUser->name }}</div>
                                                <small class="text-muted">{{ $selectedUser->email }}</small>
                                            </div>
                                            <button type="button"
                                                    class="btn btn-sm btn-soft-danger"
                                                    wire:click="$set('newRoleUserId', '')">
                                                <i class="ri-close-line"></i>
                                            </button>
                                        </div>
                                    @endif
                                @endif

                                @error('newRoleUserId')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ri-add-line me-1"></i>{{ __('Add Role') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-12 card shadow-sm border-0">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="ri-list-check-2 me-2"></i>{{ __('Existing Roles') }}
                    <span class="badge bg-primary ms-2">{{ $roles->total() }}</span>
                </h6>
            </div>
            <div class="card-body p-0">
                @forelse($roles as $role)
                    <div class="border-bottom p-3 hover-bg-light">
                        @if($editingRoleId === $role->id)
                            <!-- Edit Mode -->
                            <form wire:submit.prevent="updateRole">
                                <div class="row g-3">
                                    <div class="col-md-5">
                                        <label class="form-label small">{{ __('Role Name') }}</label>
                                        <input type="text"
                                               class="form-control form-control-sm @error('editRoleName') is-invalid @enderror"
                                               wire:model="editRoleName">
                                        @error('editRoleName')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-5">
                                        <label class="form-label small">{{ __('Assigned User') }}</label>
                                        <div class="position-relative">
                                            @if(!$editRoleUserId)
                                                <input type="text"
                                                       class="form-control form-control-sm"
                                                       wire:model.live="userSearch"
                                                       placeholder="{{ __('Search user by name, email, phone or ID') }}"
                                                       autocomplete="off">

                                                @if($showUserDropdown && $this->searchedUsers->count() > 0)
                                                    <div
                                                        class="position-absolute w-100 bg-white border rounded shadow-sm mt-1"
                                                        style="z-index: 1000; max-height: 200px; overflow-y: auto;">
                                                        @foreach($this->searchedUsers as $user)
                                                            <div class="px-3 py-2 cursor-pointer hover-bg-light"
                                                                 wire:click="selectUser({{ $user->id }})"
                                                                 style="cursor: pointer;">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="avatar-xs me-2">
                                                                            <span
                                                                                class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                                                {{ substr($user->name, 0, 1) }}
                                                                            </span>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <div
                                                                            class="fw-semibold small">{{ $user->name }}</div>
                                                                        <small
                                                                            class="text-muted d-block">{{ $user->email }}</small>
                                                                        <div class="d-flex gap-2 mt-1">
                                                                            @if($user->idUser)
                                                                                <small
                                                                                    class="badge bg-soft-info text-info">ID: {{ $user->idUser }}</small>
                                                                            @endif
                                                                            @if($user->contactUser && $user->contactUser->mobile)
                                                                                <small
                                                                                    class="badge bg-soft-success text-success">
                                                                                    <i class="ri-phone-line"></i> {{ $user->contactUser->mobile }}
                                                                                </small>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            @else
                                                @php
                                                    $editSelectedUser = \App\Models\User::find($editRoleUserId);
                                                @endphp
                                                @if($editSelectedUser)
                                                    <div class="d-flex align-items-center bg-light p-2 rounded">
                                                        <div class="avatar-xs me-2">
                                                            <span
                                                                class="avatar-title rounded-circle bg-soft-success text-success">
                                                                {{ substr($editSelectedUser->name, 0, 1) }}
                                                            </span>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div
                                                                class="fw-semibold small">{{ $editSelectedUser->name }}</div>
                                                            <small
                                                                class="text-muted">{{ $editSelectedUser->email }}</small>
                                                        </div>
                                                        <button type="button"
                                                                class="btn btn-sm btn-soft-danger"
                                                                wire:click="$set('editRoleUserId', '')">
                                                            <i class="ri-close-line"></i>
                                                        </button>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-2 d-flex align-items-end gap-2">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="ri-check-line"></i>
                                        </button>
                                        <button type="button"
                                                class="btn btn-secondary btn-sm"
                                                wire:click="cancelEdit">
                                            <i class="ri-close-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @else
                            <!-- View Mode -->
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        <h6 class="mb-0 me-3">
                                            <i class="ri-shield-check-line text-primary me-2"></i>{{ $role->name }}
                                        </h6>
                                        <span
                                            class="badge bg-soft-secondary text-secondary">ID: {{ $role->id }}</span>
                                    </div>

                                    <div class="row g-2 mt-2">
                                        <div class="col-md-4">
                                            <small class="text-muted d-block">{{ __('Assigned User') }}</small>
                                            @if($role->user)
                                                <div class="d-flex align-items-center mt-1">
                                                    <div class="avatar-xs me-2">
                                                            <span
                                                                class="avatar-title rounded-circle bg-soft-info text-info">
                                                                {{ substr($role->user->name, 0, 1) }}
                                                            </span>
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold small">{{ $role->user->name }}</div>
                                                        <small class="text-muted">{{ $role->user->email }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="badge bg-soft-warning text-warning mt-1">
                                                        <i class="ri-user-unfollow-line me-1"></i>{{ __('Not Assigned') }}
                                                    </span>
                                            @endif
                                        </div>

                                        <div class="col-md-4">
                                            <small class="text-muted d-block">{{ __('Created') }}</small>
                                            <div class="mt-1">
                                                <small>{{ $role->created_at->format(config('app.date_format')) }}</small>
                                                @if($role->creator)
                                                    <br><small
<<<<<<< HEAD
                                                        class="text-muted">{{ __('by') }} {{ $role->creator->name }}</small>
=======
                                                        class="text-muted">{{ __('by') }} {{ getUserDisplayedNameFromId($role->creator->id) }}</small>
>>>>>>> a0ca8bd14039262ce34260e1f62943e751b132cb
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <small class="text-muted d-block">{{ __('Last Updated') }}</small>
                                            <div class="mt-1">
                                                <small>{{ $role->updated_at->format(config('app.date_format')) }}</small>
                                                @if($role->updater)
                                                    <br><small
                                                        class="text-muted">{{ __('by') }} {{ $role->updater->name }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-1 ms-3">
                                    <button class="btn btn-soft-info btn-sm"
                                            wire:click="editRole({{ $role->id }})"
                                            title="{{ __('Edit') }}">
                                        <i class="ri-pencil-line"></i>
                                    </button>
                                    <button class="btn btn-soft-danger btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#revokeModal{{ $role->id }}"
                                            title="{{ __('Revoke') }}">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Revoke Confirmation Modal -->
                    <div class="modal fade" id="revokeModal{{ $role->id }}" tabindex="-1" aria-hidden="true"
                         wire:ignore.self>
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-danger-subtle">
                                    <h5 class="modal-title text-danger">
                                        <i class="ri-alert-line me-2"></i>{{ __('Confirm Revoke') }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="mb-3">{{ __('Are you sure you want to revoke this role?') }}</p>
                                    <div class="bg-light p-3 rounded">
                                        <strong>{{ __('Role Name') }}:</strong> {{ $role->name }}<br>
                                        @if($role->user)
                                            <strong>{{ __('Assigned User') }}:</strong> {{ $role->user->name }}
                                            ({{ $role->user->email }})
                                        @endif
                                    </div>
                                    <div class="alert alert-warning mt-3 mb-0">
                                        <i class="ri-error-warning-line me-2"></i>{{ __('This action cannot be undone.') }}
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="ri-close-line me-1"></i>{{ __('Cancel') }}
                                    </button>
                                    <button type="button" class="btn btn-danger"
                                            wire:click="revokeRole({{ $role->id }})"
                                            data-bs-dismiss="modal">
                                        <i class="ri-delete-bin-line me-1"></i>{{ __('Revoke Role') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <div class="avatar-xl mx-auto mb-4">
                            <div class="avatar-title bg-soft-info text-info rounded-circle">
                                <i class="ri-shield-user-line display-4"></i>
                            </div>
                        </div>
                        <h5 class="mb-2">{{ __('No Roles Found') }}</h5>
                        <p class="text-muted">{{ __('Add your first role using the form above') }}</p>
                    </div>
                @endforelse
            </div>

            @if($roles->hasPages())
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            {{ __('Showing') }} {{ $roles->firstItem() ?? 0 }} {{ __('to') }} {{ $roles->lastItem() ?? 0 }}
                            {{ __('of') }} {{ $roles->total() }} {{ __('roles') }}
                        </div>
                        <div>
                            {{ $roles->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
<<<<<<< HEAD
        <div class="col-12">
=======
        <div class="col-12 mb-2">
>>>>>>> a0ca8bd14039262ce34260e1f62943e751b132cb
            <a href="{{ route('partner_index', app()->getLocale()) }}" class="btn btn-secondary">
                <i class="ri-arrow-left-line me-1"></i>{{ __('Back to Partners') }}
            </a>
        </div>
    </div>
</div>

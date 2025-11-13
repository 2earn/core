<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Platform') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Platform') }}
        @endslot
    @endcomponent

    <div class="row">
        @include('layouts.flash-messages')
    </div>

    {{-- Header Card with Search and Create Button --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="ri-search-line"></i>
                                </span>
                                <input type="text"
                                       class="form-control border-start-0 ps-0"
                                       wire:model.live.debounce.300ms="search"
                                       placeholder="{{__('Search by name, type or ID...')}}">
                            </div>
                        </div>
                        <div class="col-md-6 text-end mt-2 mt-md-0">
                            <a href="{{route('platform_create_update', app()->getLocale())}}"
                               class="btn btn-soft-info material-shadow-none">
                                <i class="ri-add-line align-middle me-1"></i>
                                {{__('Create platform')}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Platform Cards Grid --}}
    <div class="row">
        @forelse($platforms as $platform)
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-3">
                            <div class="flex-shrink-0">
                                @if($platform->image_link)
                                    <img src="{{asset($platform->image_link)}}"
                                         alt="{{$platform->name}}"
                                         class="rounded"
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="avatar-sm">
                                        <span class="avatar-title rounded bg-soft-info text-info fs-3">
                                            {{strtoupper(substr($platform->name, 0, 1))}}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="card-title mb-1">{{$platform->name}}</h5>
                                <p class="text-muted mb-0">
                                    <small>{{__('ID')}}: #{{$platform->id}}</small>
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="dropdown">
                                    <button class="btn btn-link text-muted p-1 dropdown-toggle shadow-none"
                                            type="button"
                                            data-bs-toggle="dropdown">
                                        <i class="ri-more-2-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item"
                                               href="{{route('platform_create_update', ['locale' => app()->getLocale(), 'id' => $platform->id])}}">
                                                <i class="ri-pencil-line me-2"></i>{{__('Edit')}}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger"
                                               href="#"
                                               onclick="confirmDelete('{{$platform->id}}', '{{$platform->name}}')">
                                                <i class="ri-delete-bin-line me-2"></i>{{__('Delete')}}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">{{__('Type')}}</span>
                                <span class="badge bg-soft-primary text-primary">{{$platform->type ?? 'N/A'}}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">{{__('Business sector')}}</span>
                                <span class="text-dark">{{$platform->businessSector->name ?? 'N/A'}}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">{{__('Created at')}}</span>
                                <span class="text-dark">{{$platform->created_at->format('Y-m-d')}}</span>
                            </div>
                        </div>

                        @if($platform->description)
                            <div class="mt-3 pt-3 border-top">
                                <p class="text-muted mb-0 small">
                                    {{Str::limit($platform->description, 100)}}
                                </p>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer bg-light border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{route('platform_show', ['locale' => app()->getLocale(), 'id' => $platform->id])}}"
                               class="btn btn-sm btn-soft-secondary">
                                <i class="ri-eye-line me-1"></i>{{__('View Details')}}
                            </a>
                            <a href="{{route('platform_create_update', ['locale' => app()->getLocale(), 'id' => $platform->id])}}"
                               class="btn btn-sm btn-soft-info">
                                <i class="ri-pencil-line me-1"></i>{{__('Edit')}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <div class="mb-3">
                            <i class="ri-file-list-3-line display-4 text-muted"></i>
                        </div>
                        <h5 class="text-muted">{{__('No platforms found')}}</h5>
                        <p class="text-muted">{{__('Try adjusting your search or create a new platform')}}</p>
                        <a href="{{route('platform_create_update', app()->getLocale())}}"
                           class="btn btn-soft-info mt-2">
                            <i class="ri-add-line me-1"></i>{{__('Create platform')}}
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    {{__('Showing')}} {{$platforms->firstItem() ?? 0}} {{__('to')}} {{$platforms->lastItem() ?? 0}}
                    {{__('of')}} {{$platforms->total()}} {{__('results')}}
                </div>
                <div>
                    {{$platforms->links()}}
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id, name) {
            event.preventDefault();
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '{{__('Are you sure to delete this platform')}}?',
                    html: '<h5>' + name + '</h5>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: '{{__('Delete')}}',
                    cancelButtonText: '{{__('Cancel')}}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('delete', id);
                    }
                });
            } else {
                if (confirm('{{__('Are you sure to delete this platform')}}? ' + name)) {
                    @this.call('delete', id);
                }
            }
        }
    </script>

</div>

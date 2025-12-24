<div class="container">
    <div>
        @component('components.breadcrumb')
            @slot('title')
                {{ __('ConfigurationHA') }}
            @endslot
        @endcomponent
        <div class="row">
            @include('layouts.flash-messages')
        </div>
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <input type="text" wire:model.live="search" class="form-control" placeholder="{{ __('Search...') }}">
                    </div>

                    <div class="list-group">
                        @forelse($actionHistories as $share)
                            <div class="list-group-item list-group-item-action mb-2 border rounded">
                                <div class="row align-items-center">
                                    <div class="col-md-5">
                                        <div class="d-flex flex-column">
                                            <small class="text-muted">{{ __('Name of setting') }}</small>
                                            <span class="fw-semibold">{{ $share->title }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex flex-column">
                                            <small class="text-muted">{{ __('reponce') }}</small>
                                            <div class="mt-1">
                                                @if ($share->reponce == 1)
                                                    <span class="badge bg-success">{{__('create reponse')}}</span>
                                                @else
                                                    <span class="badge bg-info">{{__('sans reponse')}}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <a wire:click="editHA({{ $share->id }})" data-bs-toggle="modal" data-bs-target="#HistoryActionModal"
                                           class="btn btn-sm btn-primary btn2earnTable" style="cursor: pointer;">
                                            <i class="glyphicon glyphicon-edit"></i>
                                            {{__('Update')}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info text-center" role="alert">
                                <i class="mdi mdi-information-outline me-2"></i>
                                {{ __('No records found') }}
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-3">
                        {{ $actionHistories->links() }}
                    </div>
                </div>
            </div>
        </div>
        <div wire:ignore.self class="modal fade" id="HistoryActionModal" tabindex="-1" style="z-index: 200000"
             aria-labelledby="HistoryActionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="HistoryActionModalLabel">{{__('Edit HA')}}</h5>
                        <button type="button" class="btn-close btn-close-ha" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-xl-12">
                                    <label class="me-sm-2">{{ __('backand.Name') }}</label>
                                    <input type="text" class="form-control" placeholder="name" name="ParameterName"
                                           disabled wire:model.live="titleHA">
                                </div>
                                <div class="col-xl-6">
                                    <label class="me-sm-2">{{ __('backand.reponce') }}</label>
                                    <select wire:model.live="reponceHA" class="form-control" name="reponce">
                                        <option value="0">{{ __('backand.sans reponce') }}</option>
                                        <option value="1">{{ __('backand.create reponce') }}</option>
                                        <option value="2">{{ __('backand.list reponce') }}</option>
                                    </select>
                                </div>
                                <div class="col-xl-6">
                                    <label class="me-sm-2">{{ __('backand.list reponce') }}</label>
                                    <input data-role="tagsinput" id="tags" name='tags' wire:model.live="list_reponceHA"
                                           class="form-control" autofocus>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="saveHA()"
                                class="btn btn-primary">{{__('Save changes')}}</button>
                        <button type="button" class="btn btn-secondary btn-close-amount"
                                data-bs-dismiss="modal">{{__('Close')}}</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>









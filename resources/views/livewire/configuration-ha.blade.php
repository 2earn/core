<div class="{{getContainerType()}}">
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

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead class="table-light">
                            <tr>
                                <th>{{ __('Name of setting') }}</th>
                                <th>{{ __('reponce') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                            </thead>
                            <tbody class="list form-check-all">
                            @forelse($actionHistories as $share)
                                <tr>
                                    <td>{{ $share->title }}</td>
                                    <td>
                                        @if ($share->reponce == 1)
                                            <span class="badge bg-success">{{__('create reponse')}}</span>
                                        @else
                                            <span class="badge bg-info">{{__('sans reponse')}}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a wire:click="editHA({{ $share->id }})" data-bs-toggle="modal" data-bs-target="#HistoryActionModal"
                                           class="btn btn-xs btn-primary btn2earnTable" style="cursor: pointer;">
                                            <i class="glyphicon glyphicon-edit"></i>
                                            {{__('Update')}}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">{{ __('No records found') }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
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









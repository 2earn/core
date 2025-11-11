<div class="card mb-3 shadow-sm border">
    {{-- Header --}}
    <div class="card-header text- border-info">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <span class="badge bg-secondary me-2">ID: {{ $target->id }}</span>
                <span class="text-dark">{{ $target->name }}</span>
            </h4>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="card-body">
        <div class="row g-4">
            {{-- Details Section --}}
            <div class="col-md-6">
                <h5 class="text-info border-bottom pb-2 mb-3">
                    <i class="bi bi-info-circle"></i> {{ __('Details') }}
                </h5>

                <div class="mb-3">
                    <h6 class="text-secondary mb-2">{{ __('Description') }}:</h6>
                    <p class="text-muted">{{ $target->description ?: __('No description available') }}</p>
                </div>

                <div class="mb-3">
                    <h6 class="text-secondary mb-2">{{ __('Dates') }}:</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless">
                            <tbody>
                                @if($target->created_at)
                                    <tr>
                                        <td class="fw-semibold text-muted">{{ __('Creation date') }}:</td>
                                        <td>{{ \Carbon\Carbon::parse($target->created_at)->format(\App\Livewire\SurveyCreateUpdate::DATE_FORMAT) ?? __('Not set') }}</td>
                                    </tr>
                                @endif
                                @if($target->updated_at)
                                    <tr>
                                        <td class="fw-semibold text-muted">{{ __('Update date') }}:</td>
                                        <td>{{ \Carbon\Carbon::parse($target->updated_at)->format(\App\Livewire\SurveyCreateUpdate::DATE_FORMAT) ?? __('Not set') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Links Section --}}
            <div class="col-md-6">
                <h5 class="text-info border-bottom pb-2 mb-3">
                    <i class="bi bi-link-45deg"></i> {{ __('Links') }}
                </h5>

                <div class="mb-3">
                    <h6 class="text-secondary mb-2">{{ __('Surveys') }}:</h6>
                    @forelse($target->surveys as $surveysItem)
                        @if($loop->first)
                            <ul class="list-group list-group-flush">
                        @endif
                                <li class="list-group-item px-0">
                                    <a class="link-info text-decoration-none"
                                       href="{{ route('surveys_show', ['locale' => app()->getLocale(), 'idSurvey' => $surveysItem->id]) }}">
                                        <i class="bi bi-file-text"></i>
                                        <strong>#{{ $surveysItem->id }}</strong> - {{ \App\Models\TranslaleModel::getTranslation($surveysItem, 'name', $surveysItem->name) }}
                                    </a>
                                </li>
                        @if($loop->last)
                            </ul>
                        @endif
                    @empty
                        <p class="text-muted fst-italic">{{ __('No Surveys') }}</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Admin Actions --}}
        @if(\App\Models\User::isSuperAdmin())
            <hr class="my-4">
            <div class="row g-3">
                <div class="col-12">
                    <h6 class="text-muted mb-3">{{ __('Actions') }}:</h6>
                </div>

                {{-- Primary Actions --}}
                <div class="col-md-6">
                    <div class="btn-group flex-wrap" role="group" aria-label="{{ __('Target actions') }}">
                        @if($currentRouteName !== "target_show")
                            <a href="{{ route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $target->id]) }}"
                               class="btn btn-outline-info"
                               title="{{ __('Show target') }}">
                                <i class="bi bi-eye"></i> {{ __('Details') }}
                            </a>
                        @endif
                        <a href="{{ route('target_create_update', ['locale' => app()->getLocale(), 'idTarget' => $target->id]) }}"
                           class="btn btn-outline-info"
                           title="{{ __('Edit target') }}">
                            <i class="bi bi-pencil"></i> {{ __('Edit') }}
                        </a>
                        <button type="button"
                                wire:click="deleteTarget('{{ $target->id }}')"
                                class="btn btn-outline-danger"
                                title="{{ __('Delete target') }}">
                            <span wire:loading.remove wire:target="deleteTarget('{{ $target->id }}')">
                                <i class="bi bi-trash"></i> {{ __('Delete') }}
                            </span>
                            <span wire:loading wire:target="deleteTarget('{{ $target->id }}')">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                {{ __('Loading') }}...
                            </span>
                        </button>
                        <a href="{{ route('surveys_create_update', ['locale' => app()->getLocale(), 'idTarget' => $target->id]) }}"
                           class="btn btn-outline-primary"
                           title="{{ __('Create matched target Survey') }}">
                            <i class="bi bi-plus-circle"></i> {{ __('Create Survey') }}
                        </a>
                    </div>
                </div>

                {{-- Secondary Actions (Show Page Only) --}}
                @if($currentRouteName == "target_show")
                    <div class="col-md-6">
                        <div class="btn-group flex-wrap" role="group" aria-label="{{ __('Condition and group actions') }}">
                            <a href="{{ route('target_condition_create_update', ['locale' => app()->getLocale(), 'idTarget' => $target->id]) }}"
                               class="btn btn-outline-success"
                               title="{{ __('Add Condition') }}">
                                <i class="bi bi-plus"></i> {{ __('Add Condition') }}
                            </a>
                            <a href="{{ route('target_group_create_update', ['locale' => app()->getLocale(), 'idTarget' => $target->id]) }}"
                               class="btn btn-outline-success"
                               title="{{ __('Add Group') }}">
                                <i class="bi bi-plus"></i> {{ __('Add Group') }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        {{-- Conditions Section --}}
        @if($currentRouteName == "target_show" && $target->condition->isNotEmpty())
            <hr class="my-4">
            <div class="mb-4">
                <h5 class="text-info border-bottom pb-2 mb-3">
                    <i class="bi bi-filter"></i> {{ __('Conditions details') }}
                </h5>

                <div class="list-group">
                    @foreach($target->condition as $condition)
                        <div class="list-group-item">
                            <div class="row align-items-center g-3">
                                {{-- Index --}}
                                <div class="col-auto">
                                    <span class="badge bg-secondary rounded-circle" style="width: 2rem; height: 2rem; display: inline-flex; align-items: center; justify-content: center;">
                                        {{ $loop->iteration }}
                                    </span>
                                </div>

                                {{-- Condition Display --}}
                                <div class="col-md-6">
                                    <div class="d-flex flex-wrap gap-2 align-items-center">
                                        <span class="badge text- text-dark border fs-6">{{ $condition->operand }}</span>
                                        <span class="badge bg-danger fs-6">{{ $condition->operator }}</span>
                                        <span class="badge text- text-dark border fs-6">{{ $condition->value }}</span>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="col-md-auto ms-auto">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('target_condition_create_update', ['locale' => app()->getLocale(), 'idTarget' => $condition->target_id, 'idCondition' => $condition->id]) }}"
                                           class="btn btn-outline-info"
                                           title="{{ __('Edit Condition') }}">
                                            <i class="bi bi-pencil"></i> {{ __('Edit') }}
                                        </a>
                                        <button type="button"
                                                wire:click="removeCondition('{{ $condition->id }}', '{{ $target->id }}')"
                                                class="btn btn-outline-danger"
                                                title="{{ __('Remove Condition') }}">
                                            <span wire:loading.remove wire:target="removeCondition('{{ $condition->id }}', '{{ $target->id }}')">
                                                <i class="bi bi-trash"></i> {{ __('Remove') }}
                                            </span>
                                            <span wire:loading wire:target="removeCondition('{{ $condition->id }}', '{{ $target->id }}')">
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                {{ __('Loading') }}...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Groups Section --}}
        @if($currentRouteName == "target_show" && $target->group->isNotEmpty())
            <hr class="my-4">
            <div class="mb-4">
                <h5 class="text-info border-bottom pb-2 mb-3">
                    <i class="bi bi-collection"></i> {{ __('Groups details') }}
                </h5>

                <div class="list-group">
                    @foreach($target->group as $group)
                        <div class="list-group-item">
                            <div class="row g-3">
                                {{-- Group Header --}}
                                <div class="col-12">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="badge bg-secondary rounded-circle" style="width: 2rem; height: 2rem; display: inline-flex; align-items: center; justify-content: center;">
                                                {{ $loop->iteration }}
                                            </span>
                                            <h5 class="mb-0">
                                                <span class="badge bg-danger fs-6">{{ __('Operator') }}: {{ $group->operator }}</span>
                                            </h5>
                                        </div>

                                        {{-- Group Actions --}}
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('target_group_create_update', ['locale' => app()->getLocale(), 'idTarget' => $group->target_id, 'idGroup' => $group->id]) }}"
                                               class="btn btn-outline-info"
                                               title="{{ __('Edit Group') }}">
                                                <i class="bi bi-pencil"></i> {{ __('Edit') }}
                                            </a>
                                            <button type="button"
                                                    wire:click="removeGroup('{{ $group->id }}', '{{ $target->id }}')"
                                                    class="btn btn-outline-danger"
                                                    title="{{ __('Remove group') }}">
                                                <span wire:loading.remove wire:target="removeGroup('{{ $group->id }}', '{{ $target->id }}')">
                                                    <i class="bi bi-trash"></i> {{ __('Remove') }}
                                                </span>
                                                <span wire:loading wire:target="removeGroup('{{ $group->id }}', '{{ $target->id }}')">
                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                    {{ __('Loading') }}...
                                                </span>
                                            </button>
                                            <a href="{{ route('target_condition_create_update', ['locale' => app()->getLocale(), 'idTarget' => $group->target_id, 'idGroup' => $group->id]) }}"
                                               class="btn btn-outline-success"
                                               title="{{ __('Add Condition') }}">
                                                <i class="bi bi-plus"></i> {{ __('Add Condition') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                {{-- Group Conditions --}}
                                @if($group->condition->isNotEmpty())
                                    <div class="col-12">
                                        <div class="ms-4 border-start border-3 border-info ps-3">
                                            <h6 class="text-muted mb-3">
                                                <i class="bi bi-diagram-3"></i> {{ __('Conditions details') }}:
                                            </h6>

                                            <div class="list-group">
                                                @foreach($group->condition as $conditionItem)
                                                    <div class="list-group-item text-">
                                                        <div class="row align-items-center g-3">
                                                            {{-- Condition Index --}}
                                                            <div class="col-auto">
                                                                <span class="badge text- text-dark border" style="width: 1.75rem; height: 1.75rem; display: inline-flex; align-items: center; justify-content: center;">
                                                                    {{ $loop->iteration }}
                                                                </span>
                                                            </div>

                                                            {{-- Condition Display --}}
                                                            <div class="col-md-6">
                                                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                                                    <span class="badge bg-white text-dark border">{{ $conditionItem->operand }}</span>
                                                                    <span class="badge bg-danger">{{ $conditionItem->operator }}</span>
                                                                    <span class="badge bg-white text-dark border">{{ $conditionItem->value }}</span>
                                                                </div>
                                                            </div>

                                                            {{-- Condition Actions --}}
                                                            <div class="col-md-auto ms-auto">
                                                                <div class="btn-group btn-group-sm" role="group">
                                                                    <a href="{{ route('target_condition_create_update', ['locale' => app()->getLocale(), 'idTarget' => $group->target_id, 'idGroup' => $conditionItem->target_group_id, 'idCondition' => $conditionItem->id]) }}"
                                                                       class="btn btn-outline-info"
                                                                       title="{{ __('Edit Condition') }}">
                                                                        <i class="bi bi-pencil"></i> {{ __('Edit') }}
                                                                    </a>
                                                                    <button type="button"
                                                                            wire:click="removeCondition('{{ $conditionItem->id }}', '{{ $target->id }}')"
                                                                            class="btn btn-outline-danger"
                                                                            title="{{ __('Remove Condition') }}">
                                                                        <span wire:loading.remove wire:target="removeCondition('{{ $conditionItem->id }}', '{{ $target->id }}')">
                                                                            <i class="bi bi-trash"></i> {{ __('Remove') }}
                                                                        </span>
                                                                        <span wire:loading wire:target="removeCondition('{{ $conditionItem->id }}', '{{ $target->id }}')">
                                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                            {{ __('Loading') }}...
                                                                        </span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<div class="container-fluid">
    @component('components.breadcrumb')
        @slot('title')
            {{ __('User Guide Details') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header border-info d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">{{ $guide->title }}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>{{ __('Created by:') }}</strong> {{ $guide->user->name ?? __('Unknown') }}</p>
                            <p><strong>{{ __('Created at:') }}</strong> {{ $guide->created_at ? $guide->created_at->format('Y-m-d H:i') : __('N/A') }}</p>
                            <p><strong>{{ __('Updated at:') }}</strong> {{ $guide->updated_at ? $guide->updated_at->format('Y-m-d H:i') : __('N/A') }}</p>
                            @if($guide->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($guide->file_path))
                                <p><strong>{{ __('Attachment:') }}</strong> <a href="{{ asset('storage/' . $guide->file_path) }}" target="_blank">{{ __('Download') }}</a></p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <p><strong>{{ __('Description:') }}</strong></p>
                            <div class="bg-light p-2 rounded">{{ $guide->description }}</div>
                        </div>
                    </div>
                    @if($routeDetails && count($routeDetails))
                        <hr>
                        <div>
                            <strong>{{ __('Routes:') }}</strong>
                            <ul>
                                @foreach($routeDetails as $route)
                                    <li>{{ $route['name'] }}@if($route['uri']) ({{ $route['uri'] }})@endif</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('user_guides_index', app()->getLocale()) }}" class="btn btn-secondary">{{ __('Back to list') }}</a>
                    <a href="{{ route('user_guides_edit', [app()->getLocale(), $guide->id]) }}" class="btn btn-warning ms-2">{{ __('Edit') }}</a>
                    <button type="button" class="btn btn-danger ms-2" wire:click="confirmDelete({{ $guide->id }})">{{ __('Delete') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

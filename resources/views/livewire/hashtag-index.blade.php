<div class="container-fluid">
    @section('title')
        {{ __('Hashtags') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Hashtags') }}
        @endslot
    @endcomponent
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="float-end col-sm-12 col-md-6 col-lg-6">
                    <input type="text" wire:model.debounce.500ms="search" class="form-control input-sm col-auto"
                           placeholder="{{__('Search')}}"/>
                </div>
                <div class="float-end col-sm-12 col-md-6 col-lg-6">
                    <a href="{{ route('hashtags_create',app()->getLocale()) }}"
                       class="btn btn-primary float-end col-auto">{{__('Create new hashtag')}}</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table mt-3">
                <thead>
                <tr>
                    <th>{{__('ID')}}</th>
                    <th>{{__('Name')}}</th>
                    <th>{{__('Slug')}}</th>
                    <th>{{__('Actions')}}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($hashtags as $hashtag)
                    <tr>
                        <td>{{ $hashtag->id }}</td>
                        <td>{{ $hashtag->name }}</td>
                        <td>{{ $hashtag->slug }}</td>
                        <td>
                            <a href="{{ route('hashtags_edit', ['locale'=>app()->getLocale(),'id'=>$hashtag->id]) }}"
                               class="btn btn-sm btn-outline-primary">{{__('Edit')}}</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-muted">
                            {{__('No hashtags found')}}
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $hashtags->links() }}
        </div>
    </div>
</div>

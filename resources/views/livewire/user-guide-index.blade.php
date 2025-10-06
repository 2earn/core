<div class="container-fluid">
    @section('title')
        {{ __('User Guides') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('User Guides') }}
        @endslot
    @endcomponent
    <div class="row card">
        <div class="col-md-12 mb-4 card-body">
            @forelse($userGuides as $guide)
                <div class="card">
                    <div class="card-title">
                        <h5 class="text-info m-2">{{ $guide->title }}</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">{{ $guide->description }}</p>
                        @if($guide->file_path)
                            <a href="{{ asset('storage/' . $guide->file_path) }}" target="_blank">Download
                                Attachment</a>
                        @endif
                    </div>
                    <div class="card-footer">
                        <p class="text-muted">Created by: {{ $guide->user->name ?? __('Unknown') }}</p>
                    </div>
                </div>
            @empty
                <p class="text-muted">No user guides found.</p>
            @endforelse
            <div class="mt-3">
                {{ $userGuides->onEachSide(1)->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

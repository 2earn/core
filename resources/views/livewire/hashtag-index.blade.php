<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            @include('layouts.flash-messages')
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="float-end col-sm-12 col-md-6 col-lg-6">
                    <input type="text" wire:model.live="search" class="form-control input-sm col-auto"
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
                    <th>{{__('Translations')}}</th>
                    <th>{{__('Slug')}}</th>
                    <th>{{__('Actions')}}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($hashtags as $hashtag)
                    <tr>
                        <td>{{ $hashtag->id }}</td>
                        <td>{{ $hashtag->name }}</td>
                        <td>
                            @php $translations = $hashtag->getAllTranslations(); @endphp

                            <div class="row">
                                <div class="list-group list-group-flush col-auto">
                                    <div href="#" class="list-group-item list-group-item-action">
                                        <strong>
                                            <img
                                                src="{{Vite::asset("resources/images/flags/" . strtolower('sa') . ".svg")}}"
                                                alt="{{__('Arabe')}}" title="{{__('Arabe')}}"
                                                class="avatar-xxs m-2">

                                        </strong> {{ $translations['ar'] }}
                                    </div>
                                    <div href="#" class="list-group-item list-group-item-action">
                                        <strong>
                                            <img
                                                src="{{Vite::asset("resources/images/flags/" . strtolower('fr') . ".svg")}}"
                                                alt="{{__('Francais')}}" title="{{__('Francais')}}"
                                                class="avatar-xxs m-2">
                                        </strong> {{ $translations['fr'] }}
                                    </div>
                                    <div href="#" class="list-group-item list-group-item-action">
                                        <strong>
                                            <img
                                                src="{{Vite::asset("resources/images/flags/" . strtolower('gb') . ".svg")}}"
                                                alt="{{__('English')}}" title="{{__('English')}}"
                                                class="avatar-xxs m-2">

                                        </strong> {{ $translations['en'] }}
                                    </div>
                                </div>

                                <div class="list-group list-group-flush col-auto">
                                    <div href="#" class="list-group-item list-group-item-action">
                                        <strong>
                                            <img
                                                src="{{Vite::asset("resources/images/flags/" . strtolower('es') . ".svg")}}"
                                                alt="{{__('Spanish')}}" title="{{__('Spanish')}}"
                                                class="avatar-xxs m-2">
                                        </strong> {{ $translations['es'] }}
                                    </div>
                                    <div href="#" class="list-group-item list-group-item-action">
                                        <strong>
                                            <img
                                                src="{{Vite::asset("resources/images/flags/" . strtolower('tr') . ".svg")}}"
                                                alt="{{__('Turkish')}}" title="{{__('Turkish')}}"
                                                class="avatar-xxs m-2">
                                        </strong> {{ $translations['tr'] }}
                                    </div>

                                    <div href="#" class="list-group-item list-group-item-action">
                                        <strong>
                                            <img
                                                src="{{Vite::asset("resources/images/flags/" . strtolower('ru') . ".svg")}}"
                                                alt="{{__('Russian')}}" title="{{__('Russian')}}"
                                                class="avatar-xxs m-2">
                                        </strong> {{ $translations['ru'] }}
                                    </div>
                                </div>
                                <div class="list-group list-group-flush col-auto">
                                    <div href="#" class="list-group-item list-group-item-action">
                                        <strong> <img
                                                src="{{Vite::asset("resources/images/flags/" . strtolower('de') . ".svg")}}"
                                                alt="{{__('German')}}" title="{{__('German')}}"
                                                class="avatar-xxs m-2">
                                        </strong> {{ $translations['de'] }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $hashtag->slug }}</td>
                        <td>
                            <a href="{{ route('hashtags_edit', ['locale'=>app()->getLocale(),'id'=>$hashtag->id]) }}"
                               class="btn btn-sm btn-outline-primary">{{__('Edit')}}</a>
                            <button type="button" class="btn btn-sm btn-outline-danger ms-1"
                                    wire:click="delete({{ $hashtag->id }})"
                                    onclick="return confirm('{{__('Are you sure you want to delete this hashtag?')}}')">{{__('Delete')}}</button>
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

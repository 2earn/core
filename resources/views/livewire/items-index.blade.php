<div>
    @section('title')
        {{ __('Items') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Items') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-12">
            @include('layouts.flash-messages')
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="card-header border-info">
                <div class="row">
                    <div class="float-end col-sm-12 col-md-6 col-lg-6">
                        <form class="items-center">
                            <label for="simple-search" class="sr-only">{{__('Search')}}</label>
                            <div class="w-full">
                                <input wire:model.live="search" type="text" id="simple-search"
                                       class="form-control float-end"
                                       placeholder="{{__('Search item')}}">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card-body row">
                @forelse($items as $item)
                    <div class="col-sm-12 col-lg-12">
                        <div class="card border card-border-light">
                            <div class="card-header">
                                <h5 class="card-title mb-1">
                                    {{$item->id}}
                                    - {{$item->name}}
                                </h5>
                            </div>
                            <div class="card-body">

                                <table class="table table-nowrap">
                                    <thead>
                                    <tr>
                                        <th scope="col">{{__('Ref')}}</th>
                                        <th scope="col">{{__('Price')}}</th>
                                        <th scope="col">{{__('Discount')}}</th>
                                        @if ($item->deal()->exists())
                                            <th scope="col">{{__('Deal')}}</th>
                                        @endif
                                        @if ($item->stock)
                                            <th scope="col">{{__('Deal')}}</th>
                                        @endif
                                        <th scope="col">{{__('Description')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <th scope="row"><a href="#" class="fw-semibold">#{{$item->ref}}</a></th>
                                        <td>{{$item->price}}</td>
                                        <td>{{$item->discount}}</td>
                                        @if ($item->deal()->exists())
                                            <td>{{$item->deal->id}} - {{$item->deal->name}}</td>
                                        @endif
                                        @if ($item->stock)
                                            <td>{{$item->stock}}</td>
                                        @endif
                                        <td>{{$item->description}}</td>
                                    </tr>
                                    </tbody>
                                </table>


                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col">
                                        <p class="card-text float-end">{{__('Created at')}}: <small
                                                class="text-muted">{{$item->created_at}}</small>
                                        </p>
                                    </div>
                                    <div class="col">
                                        @if(\App\Models\User::isSuperAdmin())
                                            <p class="card-text  float-end">{{__('Updated at')}}: <small
                                                    class="text-muted">{{$item->updated_at}}</small></p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p>{{__('No items')}}</p>
                @endforelse
                {{ $items->links() }}
            </div>
        </div>
    </div>
</div>

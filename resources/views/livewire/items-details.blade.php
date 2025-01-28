<div>
    @section('title')
        {{ __('Items details') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Items details') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-12">
            @include('layouts.flash-messages')
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="card-body row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card border card-border-light">
                        <div class="card-header">
                            <h4 class="card-title mb-1">
                                {{$item->id}}
                                - {{$item->name}}
                            </h4>
                            <button type="button" class="btn btn-sm btn-outline-info  float-end m-1">
                                {{__('Total ordered quantity')}} <span
                                    class="badge bg-info ms-1">{{$sumOfItemIds}}</span>
                            </button>
                        </div>
                        <div class="card-body">

                            <table class="table table-nowrap">
                                <thead>
                                <tr>
                                    <th scope="col">{{__('Ref')}}</th>
                                    <th scope="col">{{__('Price')}}</th>
                                    <th scope="col">{{__('Discount')}}</th>
                                    <th scope="col">{{__('Discount 2earn')}}</th>
                                    @if ($item->deal()->exists())
                                        <th scope="col">{{__('Deal')}}</th>
                                    @endif
                                    @if ($item->stock)
                                        <th scope="col">{{__('Deal')}}</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th scope="row"><a href="#" class="fw-semibold">#{{$item->ref}}</a></th>
                                    <td>{{$item->price}} {{config('app.currency')}}</td>
                                    <td>{{$item->discount}} {{config('app.percentage')}}</td>
                                    <td>{{$item->discount_2earn}} {{config('app.percentage')}}</td>
                                    @if ($item->deal()->exists())
                                        <td>{{$item->deal->id}} - {{$item->deal->name}}</td>
                                    @endif
                                    @if ($item->stock)
                                        <td>{{$item->stock}}</td>
                                    @endif
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                            <h5>{{__('Description')}}</h5>
                            <p class="text-muted">
                                {{$item->description}}
                            </p>
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
            </div>
        </div>
    </div>
</div>

<div class="card border card-border-light">
    <div class="card-header">
        <h5 class="card-title mb-1">
            <span class="badge rounded-pill bg-primary">{{$order->id}}</span>
        </h5>
    </div>
    <div class="card-body">
        @if($order->note)
            <blockquote>
                <strong>{{__('Note')}}: </strong> {{$order->note}}
            </blockquote>
        @endif
        <span class="float-end m-2"> <strong>{{__('Created at')}}: </strong>  {{$order->created_at}}</span>
        <span class="float-end m-2"><strong>{{__('Updated at')}}: </strong>  {{$order->updated_at}}</span>
    </div>
    <div class="card-footer">
    </div>
</div>

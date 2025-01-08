<div class="card border card-border-light">
    <div class="card-header">
            <span class="badge rounded-pill bg-primary">{{$order->id}}</span>
        <span class="badge border border-primary text-primary float-end">{{__($order->status->name)}}</span>
    </div>
    <div class="card-body">
        @if($order->note)
            <blockquote class="text-muted">
                <strong>{{__('Note')}}: </strong><br>{{$order->note}}
            </blockquote>
        @endif
        <span class="float-end m-2"> <strong>{{__('Created at')}}: </strong>  {{$order->created_at}}</span>
        <span class="float-end m-2"><strong>{{__('Updated at')}}: </strong>  {{$order->updated_at}}</span>
    </div>
    <div class="card-footer">
    </div>
</div>

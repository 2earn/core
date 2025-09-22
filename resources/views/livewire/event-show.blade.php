<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h2>{{ \App\Models\TranslaleModel::getTranslation($event,'title',$event->title) }}</h2>
            @if($event->enabled)
                <span class="badge bg-success float-end">{{__('Enabled')}}</span>
            @else
                <span class="badge bg-danger float-end">{{__('Disabled')}}</span>
            @endif
        </div>
        <div class="card-body row">
            @if ($event->mainImage)
                <div class="col-md-5">      <img src="{{ asset('uploads/' . $event->mainImage->url) }}" alt="Event Image"
                     class="img-thumbnail mb-3">
                </div>
            @endif
                <div
                    class="  @if ($event->mainImage)  col-md-7 @else  col-md-12 @endif"> {!! \App\Models\TranslaleModel::getTranslation($event,'content',$event->content) !!}</div>
        </div>
        <div class="card-footer text-muted">
            {{__('Created at')}}: {{ $event->created_at }}
            @if($event->updated_at)
                | {{__('Updated at')}}: {{ $event->updated_at }}
            @endif
        </div>
    </div>
</div>


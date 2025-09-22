<div class="container-fluid">
    @component('components.breadcrumb')
        @slot('title')
            {{ \App\Models\TranslaleModel::getTranslation($news,'title',$news->title) }}
        @endslot
    @endcomponent
    <div class="card">
        <div class="card-header">
            <h2>{{ \App\Models\TranslaleModel::getTranslation($news,'title',$news->title) }}</h2>
            @if($news->enabled)
                <span class="badge bg-success float-end">{{__('Enabled')}}</span>
            @else
                <span class="badge bg-danger float-end">{{__('Disabled')}}</span>
            @endif
        </div>
        <div class="card-body row">
            <div
                class="  @if ($news->mainImage)  col-md-7 @else  col-md-12 @endif">  {!! \App\Models\TranslaleModel::getTranslation($news,'content',$news->content) !!}</div>
            @if ($news->mainImage)
                <div class="col-md-5"><img src="{{ asset('uploads/' . $news->mainImage->url) }}" alt="News Image"
                                           class="img-thumbnail mb-3">
                </div>
            @endif
        </div>
        <div class="card-footer text-muted">
            {{__('Created at')}}: {{ $news->created_at }}
            @if($news->updated_at)
                | {{__('Updated at')}}: {{ $news->updated_at }}
            @endif
        </div>
    </div>
</div>

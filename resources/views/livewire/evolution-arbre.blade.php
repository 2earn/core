<div>
    @component('components.breadcrumb')
        @slot('title') {{ __('Evolution_arbre') }} @endslot
    @endcomponent
</div>
<div class="row" style="margin-top:0px ; display: flex;flex-direction: row">
    <div class="col-12" style="display: flex;justify-content:center">
        <div>
            <img src="{{ URL::asset('assets/images/arbre.gif') }}" class="img-fluid" alt="Responsive image">
        </div>
    </div>
</div>

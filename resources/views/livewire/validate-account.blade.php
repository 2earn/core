<div>
    {{--    <livewire:edit-profile  :tovalidate="1" , :idUser = "1222"   />--}}
    <div class="card">
        @livewire('account',[ 'tovalidate' =>"1", 'paramIdUser'=> $paramIdUser ])
    </div>

</div>

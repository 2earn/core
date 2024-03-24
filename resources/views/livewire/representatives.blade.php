<div>
    <div class="card">

        <ul class="nav nav-pills" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a href="" class="nav-link active"
                   id="pills-RepresentativesManagement-tab" data-bs-toggle="pill" data-bs-target="#tabRepresentativesManagement"
                   type="button"
                   role="tab"
                   aria-controls="pills-RepresentativesManagement" aria-selected="true">{{ __('representatives Management') }}</a>
            </li>
        </ul>

        <div wire:ignore class="tab-content" id="pills-tabContent">
            <div wire:ignore class="tab-pane fade show active" id="tabListeAdmin" role="tabpanel"
                 aria-labelledby="tabListeContact-tab">
                <div class="cardCustom" id="contact">
                    <div class="d-flex card-header">
                        <h4 class="card-title">{{ __('You Contacts') }}</h4>

                        <button onclick="initNewUserContact()" id="addcategorie"
                                class="btn2earn-primary mb-1 text-end btn2earnNew" type=""
                                data-bs-toggle="modal" data-bs-target="#modalcontact">
                            <i class="icon wb-plus" aria-hidden="true"></i>{{ __('Add Representative') }}
                        </button>
                    </div>
        <div class="card-body pt-0">
            <div class="transaction-table">
                <div class="table-responsive">
                    <table id="RepresentativesTable" class="table2earn stripe flex-table" style="width: 100%">
                        <thead>
                        <tr class="head2earn">
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
{{--                        <tbody class="body2earn" >--}}
{{--                        <?php if (!($representatives->isEmpty())){ ?>--}}
{{--                        @foreach($representatives as $rep)--}}
{{--                            <tr id="reptt-{{$rep->idUser}}">--}}
{{--                                <td>{{$rep->name}}</td>--}}
{{--                                <td>{{$rep->fullphone_number}}</td>--}}
{{--                                <td><a onclick="deletedata({{$rep->idUser}})" ><i class="fa fa-trash-o" aria-hidden="true" style="cursor: pointer;color: red; padding-left: 10px;" ></i></a></td>--}}
{{--                            </tr>--}}
{{--                        @endforeach--}}
{{--                        <?php } ?>--}}
{{--                        </tbody>--}}
                    </table>
                </div>
            </div>
        </div>
    </div>
</div></div>
    </div>
</div>


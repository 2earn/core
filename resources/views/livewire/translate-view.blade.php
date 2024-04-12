<div>
    <style>
        .btnTrans{
            background-color: #3595f6;
            padding: 5px;
            color: #FFFFFF;
            border-radius: 5px;
        }
        .loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-content: center;
            background-color: #0d6efd;
            transition: opacity 0.75s, visibility 0.75s;
        }
        .loader-hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loader::after {
            content: "";
            width: 100px;
            height: 100px;
            border: 15px solid #DDDDDD;
            border-top-color: #7449f5;
            border-radius: 50%;
            animation: loading 0.75s ease infinite;
        }

        @keyframes loading {
            from {
                transform: rotate(0turn);
            }
            to {
                transform: rotate(1turn);
            }
        }

       
        nav svg {
            max-height: 20px;
        }
    </style>
    <div wire:loading>
        <div style="display: flex;justify-content: center;
align-items: center;background-color: black;position: fixed;top: 0px;left: 0px;z-index: 9999;width: 100%;height: 100%;opacity: 0.75">
            <div class="la-ball-pulse-rise">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="row">
                <div>
                    <a href="{{route('home',app()->getLocale())}}" class=" btnTrans" type=" ">home</a>
                    <a class="btnTrans " type="button" wire:click="PreImport('arToData')">Arabic field To
                        base
                    </a>
                    <a class=" btnTrans" type="button" wire:click="PreImport('enToData')">English field To
                        base
                    </a>
                    <a class=" btnTrans" type="button" wire:click="PreImport('mergeToData')">merge field To
                        base
                    </a>
                    <a class="btnTrans " type="button" wire:click="PreImport('databaseToFile')">Database To
                        file
                    </a>
                </div>
            </div>
            <div style="margin-top: 10px" class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header headerTranslate">
                            <div style="margin-bottom: 10px">
                                <label>Show </label>
                                <select  wire:model="nbrPagibation" id="cars">
                                    @for($i=5;$i<=50;$i+=5)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <a class="btnTrans" type="" wire:click="PreAjout">Ajouter</a>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" placeholder="Search..."
                                           wire:model="search"/>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class=" table table-responsive tableEditAdmin">
                                <thead>
                                <tr>
                                    <th scope="Id">Id</th>
                                    <th scope="Name">Name</th>
                                    <th scope="Francais">english</th>
                                    <th scope="Arabe">Arabe</th>
                                    <th scope="Francais">Francais</th>
                                    <th scope=" "></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($translates as $value)
                                    <tr>
                                        <td><span> {{$value->id}}</span></td>
                                        <td><span>{{$value->name}}</span></td>
                                        <td><span>{{$value->valueEn}}</span></td>
                                        <td><span> {{$value->value}}</span></td>
                                        <td><span>{{$value->valueFr}}</span></td>
                                        <td>
                                            <a type=" " wire:click="initTranslate({{$value->id}})"
                                               data-bs-toggle="modal" data-bs-target="#exampleModal"
                                               class="">Edit
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{$translates->links()}}
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div wire:ignore.self class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit field</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label for="recipient-name" class="col-form-label">Arabe:</label>
                                <input type="text" class="form-control" wire:model.defer="arabicValue">
                            </div>
                            <div class="mb-3">
                                <label for="message-text" class="col-form-label">Francais:</label>
                                <input type="text" class="form-control" wire:model.defer="frenchValue">
                                {{--                                <textarea class="form-control" id="message-text"></textarea>--}}
                            </div>
                            <div class="mb-3">
                                <label for="message-text" class="col-form-label">English:</label>
                                <input type="text" class="form-control" wire:model.defer="englishValue">
                                {{--                                <textarea class="form-control" id="message-text"></textarea>--}}
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" wire:click="saveTranslate" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>


        window.addEventListener('closeModal', event => {

            $("#exampleModal").modal('hide');
        })

        window.addEventListener('PassEnter', event => {
            Swal.fire({
                title: '{{ __('Pass') }}',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Confirm',
            }).then((resultat) => {
                if (resultat.value) {
                    if (event.detail.ev == 'arToData')
                        window.livewire.emit('addArabicField', resultat.value);
                    else if (event.detail.ev == 'enToData')
                        window.livewire.emit('addEnglishField', resultat.value);
                    else if (event.detail.ev == 'mergeToData')
                        window.livewire.emit('mergeTransaction', resultat.value);
                    else if (event.detail.ev == 'databaseToFile')
                        window.livewire.emit('databaseToFile', resultat.value);
                    //
                }
                if (resultat.isDismissed) {
                    location.reload();
                }
            })
        })
        window.addEventListener('PreAjoutTrans', event => {
            Swal.fire({
                title: 'Enter field name',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Confirm',
            }).then((resultat) => {
                if (resultat.value) {
                    window.livewire.emit('AddFieldTranslate', resultat.value);
                }
                if (resultat.isDismissed) {
                    location.reload();
                }
            })
        })
    </script>
</div>

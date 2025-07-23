<div class="tab-pane" id="bfs_sms" role="tabpanel">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('backand_BFS_Account_Funding') }}</h4>
        </div>
        <div class="card-body">
            <div
                class="alert alert-info material-shadow border-0 rounded-top rounded-0 m-0 d-flex align-items-center mb-3">
                <div class="flex-grow-1 text-truncate ">
                    {{ __('SMS price') }} <b>{{ $prix_sms}} </b> {{__('DPC')}}
                </div>
            </div>
            <div class="row gy-4">
                <div class="col-xxl-8 mx-auto ">
                    <div class="input-group">
                                                <span class="input-group-text"
                                                      id="inputGroup-sizing-default">{{ __('Enter number of SMS') }}</span>
                        <input type="number"
                               oninput="this.value = this.value.replace(/[^0-9]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                               name="NSMS" id="NSMS"
                               class="form-control text-center" placeholder=""
                               onpaste="handlePaste(event)">
                        <span class="input-group-text"
                              id="inputGroup-sizing-default">{{ __('Enter your amount') }}</span>
                        <input type="number" name="soldeSMS" id="soldeSMS"
                               class="form-control text-center"
                               placeholder="{{ __('Enter your amount') }}"
                               onpaste="handlePaste(event)">
                    </div>
                </div>
                <div class="col-xxl-8 mx-auto text-center ">
                    <div class="input-group">
                                                <span class="input-group-text"
                                                      id="inputGroup-sizing-default">{{ __('Balance For Shopping') }} : (100.00%)</span>
                        <input type="number" name="soldeBFSSMS" id="soldeBFSSMS"
                               class="form-control text-center" disabled>
                    </div>
                </div>

            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-soft-primary float-end mt-3" id="submitExchangeSms"
                    onclick="ConfirmExchangeSms()">
                {{ __('Exchange Now') }}
            </button>
        </div>
    </div>
</div>

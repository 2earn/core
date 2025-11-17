<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Users List') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Users List') }}
        @endslot
    @endcomponent

    <div class="row">
        @include('layouts.flash-messages')
    </div>

    <div class="row">
        <div class="card">
            <div class="card-header border-0">
                <div class="row g-3 align-items-center">
                    <div class="col-sm-6 col-lg-3">
                        <label class="form-label mb-1 fw-semibold">{{ __('Item per page') }}</label>
                        <select wire:model.live="pageCount" class="form-select"
                                aria-label="Default select example">
                            <option @if($pageCount=="20") selected @endif value="20">20</option>
                            <option @if($pageCount=="50") selected @endif value="50">50</option>
                            <option @if($pageCount=="100") selected @endif value="100">100</option>
                        </select>
                    </div>

                    <div class="col-sm-6 col-lg-4">
                        <label class="form-label mb-1 fw-semibold">{{ __('Search') }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="ri-search-line"></i></span>
                            <input wire:model.live="search" type="search"
                                   class="form-control"
                                   placeholder="{{ __('Search by name, mobile, or ID') }}..." aria-label="Search"/>
                        </div>
                    </div>

                    <div class="col-sm-12 col-lg-5 text-end">
                        <label class="form-label mb-1 d-block">&nbsp;</label>
                        <span class="badge bg-white text-dark fs-6">
                            {{$users->total()}} {{__('User(s)')}}
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                @forelse($users as $user)
                    @php
                        $hasVip = \App\Models\vip::Where('idUser', '=', $user->idUser)->where('closed', '=', false)->get();
                        $isVip = null;
                        if ($hasVip->isNotEmpty()) {
                            $dateStart = new \DateTime($hasVip->first()->dateFNS);
                            $dateEnd = $dateStart->modify($hasVip->first()->flashDeadline . ' hour');
                            $isVip = $dateEnd > now();
                        }
                        $uplineRegister = \App\Models\User::where('idUser', $user->idUplineRegister)->first();
                        $registerUplineName = $user->idUplineRegister == 11111111 ? trans("system") : getRegisterUpline($user->idUplineRegister);
                        $flagSrc = \Illuminate\Support\Facades\Vite::asset('resources/images/flags/'. \Illuminate\Support\Str::lower($user->apha2) .'.svg');
                    @endphp
                    <div class="card border shadow-none mb-3">
                        <div class="card-body">
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0">
                                    <img src="{{$flagSrc}}" alt="{{$user->country}}" class="avatar-sm rounded-circle"
                                         title="{{__($user->country)}}">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <span class="badge bg-light text-dark border mb-1">
                                                #{{$user->idUser}}
                                            </span>
                                            <h5 class="fs-15 mb-1">{{$user->name}}</h5>
                                            <p class="text-muted mb-0">
                                                <i class="ri-phone-line align-middle me-1"></i>
                                                {{$user->mobile}}
                                            </p>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge badge-soft-primary fs-11" title="{{$user->status}}">
                                                {{__(\Core\Enum\StatusRequest::from($user->status)->name)}}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <div class="p-2 bg-light rounded">
                                        <p class="text-muted fs-11 mb-0">
                                            <i class="ri-calendar-line align-middle me-1"></i>
                                            {{__('Created')}}
                                        </p>
                                        <p class="text-dark fs-12 mb-0 mt-1 fw-medium">
                                            {{ $user->created_at ? $user->created_at->format('Y-m-d H:i') : '-' }}
                                        </p>
                                    </div>
                                </div>
                                @if($user->pass)
                                    <div class="col-6">
                                        <div class="p-2 bg-light rounded">
                                            <p class="text-muted fs-11 mb-0">
                                                <i class="ri-lock-password-line align-middle me-1"></i>
                                                {{__('Password')}}
                                            </p>
                                            <p class="text-dark fs-12 mb-0 mt-1 fw-medium font-monospace position-relative">
                                            <span class="password-hidden-{{$user->id}}" style="cursor: pointer;"
                                                  title="{{__('Click to reveal password')}}">
                                                ••••••••
                                            </span>
                                                <span class="password-visible-{{$user->id}} d-none"
                                                      style="cursor: pointer;" title="{{__('Click to hide password')}}">
                                                {{ $user->pass }}
                                            </span>
                                                <i class="ri-eye-line password-toggle-icon-{{$user->id}} ms-2 text-primary"
                                                   style="cursor: pointer; font-size: 14px;"
                                                   onclick="togglePassword({{$user->id}})"
                                                   title="{{__('Toggle password visibility')}}"></i>
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <p class="text-primary fs-12 mb-2 fw-semibold">{{__('Soldes')}}</p>
                                        <div class="d-flex flex-wrap gap-2">
                                            {{-- Cash Balance --}}
                                            <button type="button"
                                                    class="btn btn-soft-info btn-sm d-flex align-items-center shadow-sm cb"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#detail"
                                                    data-amount="1"
                                                    data-reciver="{{$user->idUser}}"
                                                    title="{{__('SoldeCB')}}">
                                                <i class="ri-money-dollar-circle-line me-1 fs-5" aria-hidden="true"></i>
                                                <span
                                                    class="fw-semibold">{{number_format(getUserBalanceSoldes($user->idUser, 1), 2)}}</span>
                                            </button>

                                            {{-- BFS Balance --}}
                                            <button type="button"
                                                    class="btn btn-soft-secondary btn-sm d-flex align-items-center shadow-sm bfs"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#detail"
                                                    data-amount="2"
                                                    data-reciver="{{$user->idUser}}"
                                                    title="{{__('SoldeBFS')}}">
                                                <i class="ri-shopping-cart-line me-1 fs-5" aria-hidden="true"></i>
                                                <span
                                                    class="fw-semibold">{{number_format(getUserBalanceSoldes($user->idUser, 2), 2)}}</span>
                                            </button>

                                            {{-- Discount Balance --}}
                                            <button type="button"
                                                    class="btn btn-soft-primary btn-sm d-flex align-items-center shadow-sm db"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#detail"
                                                    data-amount="3"
                                                    data-reciver="{{$user->idUser}}"
                                                    title="{{__('SoldeDB')}}">
                                                <i class="ri-coupon-4-line me-1 fs-5" aria-hidden="true"></i>
                                                <span
                                                    class="fw-semibold">{{number_format(getUserBalanceSoldes($user->idUser, 3), 2)}}</span>
                                            </button>

                                            {{-- SMS Balance --}}
                                            <button type="button"
                                                    class="btn btn-soft-warning btn-sm d-flex align-items-center shadow-sm smsb"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#detail"
                                                    data-amount="5"
                                                    data-reciver="{{$user->idUser}}"
                                                    title="{{__('SoldeSMS')}}">
                                                <i class="ri-message-3-line me-1 fs-5" aria-hidden="true"></i>
                                                <span
                                                    class="fw-semibold">{{number_format(getUserBalanceSoldes($user->idUser, 5), 0)}}</span>
                                            </button>

                                            {{-- Shares Balance --}}
                                            <button type="button"
                                                    class="btn btn-soft-success btn-sm d-flex align-items-center shadow-sm sh"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#detailsh"
                                                    data-amount="7"
                                                    data-reciver="{{$user->idUser}}"
                                                    title="{{__('SoldeShares')}}">
                                                <i class="ri-bar-chart-box-line me-1 fs-5" aria-hidden="true"></i>
                                                <span
                                                    class="fw-semibold">{{number_format(getUserBalanceSoldes($user->idUser, 7), 2)}}</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    @if($user->periode || $user->minshares || $user->coeff || $user->date || $user->note)
                                        <div class="mb-3">
                                            <p class="text-primary fs-12 mb-2 fw-semibold">{{__('VIP history')}}</p>
                                            <div class="row g-2">
                                                @if($user->periode && $user->periode != '##')
                                                    <div class="col-6 col-md-4">
                                                        <div class="p-2 bg-primary-subtle rounded">
                                                            <p class="text-primary fs-11 mb-0">
                                                                <i class="ri-time-line me-1"></i>{{__('Periode')}}
                                                            </p>
                                                            <p class="text-dark fs-12 mb-0 mt-1 fw-medium">{{$user->periode}}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($user->minshares && $user->minshares != '##')
                                                    <div class="col-6 col-md-4">
                                                        <div class="p-2 bg-success-subtle rounded">
                                                            <p class="text-success fs-11 mb-0">
                                                                <i class="ri-stock-line me-1"></i>{{__('Minshares')}}
                                                            </p>
                                                            <p class="text-dark fs-12 mb-0 mt-1 fw-medium">{{$user->minshares}}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($user->coeff && $user->coeff != '##')
                                                    <div class="col-6 col-md-4">
                                                        <div class="p-2 bg-warning-subtle rounded">
                                                            <p class="text-warning fs-11 mb-0">
                                                                <i class="ri-percent-line me-1"></i>{{__('Coeff')}}
                                                            </p>
                                                            <p class="text-dark fs-12 mb-0 mt-1 fw-medium">{{$user->coeff}}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($user->date && $user->date != '##')
                                                    <div class="col-6 col-md-4">
                                                        <div class="p-2 bg-info-subtle rounded">
                                                            <p class="text-info fs-11 mb-0">
                                                                <i class="ri-calendar-check-line me-1"></i>{{__('Date')}}
                                                            </p>
                                                            <p class="text-dark fs-12 mb-0 mt-1 fw-medium">{{$user->date}}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($user->note && $user->note != '##')
                                                    <div class="col-12">
                                                        <div class="p-2 bg-secondary-subtle rounded">
                                                            <p class="text-secondary fs-11 mb-0">
                                                                <i class="ri-sticky-note-line me-1"></i>{{__('Note')}}
                                                            </p>
                                                            <p class="text-dark fs-12 mb-0 mt-1">{{$user->note}}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-6">
                                    @if($user->OptActivation || $registerUplineName)
                                        <div class="mb-3">
                                            <p class="text-primary fs-12 mb-2 fw-semibold">{{__('More details')}}</p>
                                            <div class="row g-2">
                                                @if($user->OptActivation)
                                                    <div class="col-12 col-md-6">
                                                        <div class="p-2 bg-light rounded">
                                                            <p class="text-muted fs-11 mb-0">
                                                                <i class="ri-key-2-line me-1"></i>{{__('Opt activation code')}}
                                                            </p>
                                                            <p class="text-dark fs-12 mb-0 mt-1 fw-medium">{{$user->OptActivation}}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($registerUplineName)
                                                    <div class="col-12 col-md-6">
                                                        <div class="p-2 bg-light rounded">
                                                            <p class="text-muted fs-11 mb-0">
                                                                <i class="ri-user-follow-line me-1"></i>{{__('Register upline')}}
                                                            </p>
                                                            <p class="text-dark fs-12 mb-0 mt-1 fw-medium">{{$registerUplineName}}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col-6 col-md-4 col-lg-3">
                                    <button type="button"
                                            class="btn btn-soft-primary btn-sm w-100 addCash"
                                            data-bs-toggle="modal"
                                            data-bs-target="#AddCash"
                                            data-phone="{{$user->mobile}}"
                                            data-country="{{$flagSrc}}"
                                            data-reciver="{{$user->idUser}}">
                                        <i class="ri-money-dollar-circle-line me-1"></i>
                                        {{__('Add cash')}}
                                    </button>
                                </div>
                                <div class="col-6 col-md-4 col-lg-3">
                                    <a href="{{route('platform_promotion',['locale'=>app()->getLocale(),'userId'=>$user->id])}}"
                                       class="btn btn-soft-secondary btn-sm w-100">
                                        <i class="ri-megaphone-line me-1"></i>
                                        {{__('Promote')}}
                                    </a>
                                </div>
                                <div class="col-6 col-md-4 col-lg-3">
                                    <button type="button"
                                            class="btn btn-flash btn-sm w-100 vip"
                                            data-bs-toggle="modal"
                                            data-bs-target="#vip"
                                            data-phone="{{$user->mobile}}"
                                            data-country="{{$flagSrc}}"
                                            data-reciver="{{$user->idUser}}">
                                        <i class="ri-vip-crown-fill me-1"></i>
                                        {{__('VIP')}}
                                        @if(!is_null($isVip) && $isVip)
                                            <i class="ri-checkbox-circle-fill ms-1"></i>
                                        @endif
                                    </button>
                                </div>
                                <div class="col-6 col-md-4 col-lg-3">
                                    <button type="button"
                                            class="btn btn-soft-danger btn-sm w-100"
                                            data-bs-toggle="modal"
                                            data-id="{{$user->id}}"
                                            data-phone="{{$user->mobile}}"
                                            id="updatePasswordBtn"
                                            data-bs-target="#updatePassword">
                                        <i class="ri-lock-password-line me-1"></i>
                                        {{__('Update password')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="ri-user-search-line fs-1 text-muted"></i>
                        <p class="text-muted mt-3">{{__('No users found')}}</p>
                    </div>
                @endforelse

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="AddCash" tabindex="-1" aria-labelledby="AddCashModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="AddCashModalLabel">{{ __('Transfert Cash') }}</h2>
                    <button type="button" class="btn-close btn-vip-close" data-bs-dismiss="modal"
                            aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0);">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label" for="userlist-phone">
                                    {{__('Phone')}}
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <img id="userlist-country"
                                             class="avatar-xxs me-2"
                                             src=""
                                             alt="{{ __('Country flag') }}"
                                             loading="lazy">
                                    </span>
                                    <input type="text"
                                           class="form-control"
                                           disabled
                                           id="userlist-phone"
                                           aria-describedby="userlist-phone-help">
                                </div>
                            </div>
                            <div class="col-12">
                                <input id="userlist-reciver" type="hidden">
                                <label class="form-label" for="ammount">
                                    {{__('Amount')}}
                                    <span class="text-danger" aria-label="{{ __('required') }}">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number"
                                           class="form-control"
                                           id="ammount"
                                           required
                                           min="0"
                                           step="0.01"
                                           aria-describedby="ammount-currency">
                                    <span class="input-group-text"
                                          id="ammount-currency">{{config('app.currency')}}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light"
                                            data-bs-dismiss="modal">{{ __('Cancel') }}
                                    </button>
                                    <button type="button" id="userlist-submit"
                                            class="btn btn-primary">{{ __('Transfer du cash') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Update Password Modal --}}
    <div class="modal fade" id="updatePassword" tabindex="-1" aria-labelledby="updatePasswordModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="updatePasswordModalLabel">
                        {{ __('Update Password for') }}:
                        <span class="text-warning mx-2" id="userIdMark"></span>
                    </h2>
                    <button type="button" class="btn-close btn-vip-close" data-bs-dismiss="modal"
                            aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0);">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label" for="updatePasswordInput">
                                    {{__('New Password')}}
                                    <span class="text-danger" aria-label="{{ __('required') }}">*</span>
                                </label>
                                <input type="password"
                                       class="form-control"
                                       id="updatePasswordInput"
                                       required
                                       minlength="6"
                                       autocomplete="new-password">
                            </div>
                            <div class="col-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light"
                                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                    <button type="button" id="password-update-submit"
                                            class="btn btn-soft-danger">{{ __('Update Password') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- VIP Modal --}}
    <div class="modal fade" id="vip" tabindex="-1" aria-labelledby="vipModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="vipModalLabel">{{ __('VIP') }}</h2>
                    <button type="button" class="btn-close btn-vip-close" data-bs-dismiss="modal"
                            aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0);">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <img id="vip-country"
                                             class="avatar-xxs me-2"
                                             src=""
                                             alt="{{ __('Country flag') }}"
                                             loading="lazy">
                                    </span>
                                    <input type="text"
                                           class="form-control"
                                           disabled
                                           id="vip-phone"
                                           aria-describedby="vip-phone-help">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <input id="vip-reciver" type="hidden">
                                <input type="hidden" id="created_at">
                                <label class="form-label" for="minshares">
                                    {{__('Minshares')}}
                                    <span class="text-danger" aria-label="{{ __('required') }}">*</span>
                                </label>
                                <input type="number"
                                       class="form-control-flash form-control"
                                       id="minshares"
                                       required
                                       min="0">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="periode">
                                    {{__('Periode')}}
                                    <span class="text-danger" aria-label="{{ __('required') }}">*</span>
                                </label>
                                <input type="number"
                                       class="form-control-flash form-control"
                                       id="periode"
                                       required
                                       min="0">
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="coefficient">
                                    {{__('Coefficient')}}
                                    <span class="text-danger" aria-label="{{ __('required') }}">*</span>
                                </label>
                                <input type="number"
                                       class="form-control-flash form-control"
                                       id="coefficient"
                                       required
                                       min="0"
                                       step="0.01">
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="note">{{__('Note')}}</label>
                                <textarea class="form-control-flash form-control"
                                          id="note"
                                          rows="3"></textarea>
                            </div>
                            <div class="col-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light"
                                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                    <button type="button" id="vip-submit"
                                            class="btn btn-flash">{{ __('Submit') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Balance Details Modal --}}
    <div class="modal fade modal-xl" id="detail" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title text-info h4" id="modalTitle">{{ __('Transfert Cash') }}</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <input id="balances-reciver" type="hidden">
                        <input id="balances-amount" type="hidden">
                        <table
                            class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap"
                            id="ub_table_list" style="width: 100%">
                            <thead class="table-light">
                            <tr class="head2earn tabHeader2earn">
                                <th scope="col">{{ __('ranks') }}</th>
                                <th scope="col">{{ __('id') }}</th>
                                <th scope="col">{{ __('ref') }}</th>
                                <th scope="col">{{ __('date') }}</th>
                                <th scope="col">{{ __('Operation Designation') }}</th>
                                <th scope="col">{{ __('Value') }}</th>
                                <th scope="col">{{ __('Balance') }}</th>
                            </tr>
                            </thead>
                            <tbody class="body2earn">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Shares Balance Details Modal --}}
    <div class="modal fade modal-xl" id="detailsh" tabindex="-1" aria-labelledby="detailshModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title text-info h5" id="detailshModalLabel">{{ __('Shares balances') }}</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <input id="balances-reciversh" type="hidden">
                        <input id="balances-amountsh" type="hidden">
                        <table
                            class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap"
                            id="ub_table_listsh" style="width: 100%">
                            <thead class="table-light">
                            <tr class="head2earn tabHeader2earn">
                                <th scope="col">{{__('Reference')}}</th>
                                <th scope="col">{{__('Created_at')}}</th>
                                <th scope="col">{{__('Value')}}</th>
                                <th scope="col">{{__('Real amount')}}</th>
                                <th scope="col">{{__('Current balance')}}</th>
                                <th scope="col">{{__('Unit price')}}</th>
                                <th scope="col">{{__('Total amount')}}</th>
                            </tr>
                            </thead>
                            <tbody class="body2earn">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="module">
        var ammount = 0;

        // Toggle password visibility
        function togglePassword(userId) {
            const hiddenEl = document.querySelector(`.password-hidden-${userId}`);
            const visibleEl = document.querySelector(`.password-visible-${userId}`);
            const iconEl = document.querySelector(`.password-toggle-icon-${userId}`);

            if (hiddenEl.classList.contains('d-none')) {
                // Currently showing password, hide it
                hiddenEl.classList.remove('d-none');
                visibleEl.classList.add('d-none');
                iconEl.classList.remove('ri-eye-off-line');
                iconEl.classList.add('ri-eye-line');
            } else {
                // Currently hiding password, show it
                hiddenEl.classList.add('d-none');
                visibleEl.classList.remove('d-none');
                iconEl.classList.remove('ri-eye-line');
                iconEl.classList.add('ri-eye-off-line');
            }
        }

        // Make function globally available
        window.togglePassword = togglePassword;

        function fireSwalInformMessage(iconSwal, titleSwal, textSwal) {
            Swal.fire({
                position: 'center',
                icon: iconSwal,
                title: titleSwal,
                html: textSwal,
                showConfirmButton: true,
                confirmButtonText: '{{__('ok')}}',
                showCloseButton: true
            });
        }

        function transferCash(ammount) {
            let reciver = $('#userlist-reciver').val();
            let msg = "{{__('You transferred')}} " + ammount + " $ {{__('For')}} " + reciver;
            let user = 126;
            if (ammount > 0) {
                $.ajax({
                    url: "{{ route('add_cash', app()->getLocale()) }}",
                    type: "POST",
                    headers: {
                        'Authorization': 'Bearer ' + "{{ generateUserToken() }}"
                    },
                    data: {amount: ammount, reciver: reciver, "_token": "{{ csrf_token() }}"},
                    success: function (dataTransfert) {
                        $.ajax({
                            url: "{{ route('send_sms',app()->getLocale()) }}",
                            type: "POST",
                            headers: {
                                'Authorization': 'Bearer ' + "{{ generateUserToken() }}"
                            },
                            data: {user: user, msg: msg, "_token": "{{ csrf_token() }}"},
                            success: function (dataMessage) {
                                fireSwalInformMessage('success', '{{__('Transfer success')}}', dataTransfert + ' ' + dataMessage);
                                // Refresh the page to show updated balances
                                setTimeout(() => window.location.reload(), 1500);
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                fireSwalInformMessage('success', xhr.status, dataTransfert + ' ' + xhr.responseJSON);
                            }
                        });
                        $('.btn-vip-close').trigger('click');
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        fireSwalInformMessage('error', '{{__('error')}}', xhr.responseJSON);
                    }
                });
            } else {
                fireSwalInformMessage('error', '{{__('wrong amount value')}}', '{{__('wrong amount value')}}')
            }

            $(this).prop("disabled", false);
        }

        function createOrUpdateDataTable(data) {
            try {
                if ($.fn.DataTable.isDataTable('#ub_table_list')) {
                    $('#ub_table_list').DataTable().destroy();
                }
            } catch (error) {
                console.error('Error destroying DataTable:', error);
            }

            $('#ub_table_list').DataTable({
                ordering: false,
                retrieve: true,
                searching: false,
                "fixedHeader": true,
                "processing": true,
                "data": data,
                "columns": [
                    {data: 'ranks'},
                    {data: 'id'},
                    {data: 'reference'},
                    {data: 'created_at'},
                    {data: 'operation'},
                    {data: 'value', className: window.classAl},
                    {data: 'current_balance', className: window.classAl},
                ],
            });
        }

        document.addEventListener("DOMContentLoaded", function () {

            $(document).on("click", ".cb", function () {
                let reciver = $(this).data('reciver');
                let amount = $(this).data('amount');
                $('#balances-amount').attr('value', amount);
                $('#balances-reciver').attr('value', reciver);

                window.url = "{{ route('api_user_balances_list', ['locale'=> app()->getLocale(),'idUser' => 'idUser1', 'idAmounts' => 'idamount1']) }}";
                window.url = window.url.replace('idUser1', reciver);
                window.url = window.url.replace('idamount1', amount);

                $(document).ready(function () {
                    $.ajax({
                        url: window.url,
                        method: 'GET',
                        headers: {
                            'Authorization': 'Bearer ' + "{{ generateUserToken() }}"
                        },
                        success: function (data) {
                            console.log(data)
                            $('#modalTitle').html('{{__('Cash bbalance')}}');
                            createOrUpdateDataTable(data);
                        },
                        error: function (xhr, status, error) {
                            console.error('Error fetching data:', error);
                        }
                    });
                });
            });

            $(document).on("click", ".bfs", function () {
                let reciver = $(this).data('reciver');
                let amount = $(this).data('amount');
                $('#balances-amount').attr('value', amount);
                $('#balances-reciver').attr('value', reciver);
                window.url = "{{ route('api_user_balances_list', ['locale'=> app()->getLocale(),'idUser' => 'idUser1', 'idAmounts' => 'idamount1']) }}";
                window.url = window.url.replace('idUser1', reciver);
                window.url = window.url.replace('idamount1', amount);
                $(document).ready(function () {
                    $.ajax({
                        url: window.url,
                        method: 'GET',
                        headers: {
                            'Authorization': 'Bearer ' + "{{ generateUserToken() }}"
                        },
                        success: function (data) {
                            console.log(data)
                            $('#modalTitle').html('{{__('BFSs balance')}}');
                            createOrUpdateDataTable(data);
                        },
                        error: function (xhr, status, error) {
                            console.error('Error fetching data:', error);
                        }
                    });
                });
            });
            $(document).on("click", ".db", function () {
                let reciver = $(this).data('reciver');
                let amount = $(this).data('amount');
                $('#balances-amount').attr('value', amount);
                $('#balances-reciver').attr('value', reciver);
                window.url = "{{ route('api_user_balances_list', ['locale'=> app()->getLocale(),'idUser' => 'idUser1', 'idAmounts' => 'idamount1']) }}";
                window.url = window.url.replace('idUser1', reciver);
                window.url = window.url.replace('idamount1', amount);
                $(document).ready(function () {
                    $.ajax({
                        url: window.url,
                        method: 'GET',
                        headers: {
                            'Authorization': 'Bearer ' + "{{ generateUserToken() }}"
                        },
                        success: function (data) {
                            $('#modalTitle').html('{{ __("Discount balance") }}');
                            console.log(data)
                            createOrUpdateDataTable(data);
                        },
                        error: function (xhr, status, error) {
                            console.error('Error fetching data:', error);
                        }
                    });
                });
            });
            $(document).on("click", ".smsb", function () {
                let reciver = $(this).data('reciver');
                let amount = $(this).data('amount');
                $('#balances-amount').attr('value', amount);
                $('#balances-reciver').attr('value', reciver);

                window.url = "{{ route('api_user_balances_list', ['locale'=> app()->getLocale(),'idUser' => 'idUser1', 'idAmounts' => 'idamount1']) }}";
                window.url = window.url.replace('idUser1', reciver);
                window.url = window.url.replace('idamount1', amount);

                $(document).ready(function () {
                    $.ajax({
                        url: window.url,
                        method: 'GET',
                        headers: {
                            'Authorization': 'Bearer ' + "{{ generateUserToken() }}"
                        },
                        success: function (data) {
                            console.log(data)
                            $('#modalTitle').html('{{ __("Sms balance") }}');
                            createOrUpdateDataTable(data);
                        },
                        error: function (xhr, status, error) {
                            console.error('Error fetching data:', error);
                        }
                    });

                });
            });
        });

        function createOrUpdateDataTablesh(data) {
            if ($.fn.DataTable.isDataTable('#ub_table_listsh')) {
                $('#ub_table_listsh').DataTable().destroy();
            }

            $('#ub_table_listsh').DataTable({
                ordering: true,
                retrieve: true,
                searching: false,
                "orderCellsTop": true,
                "fixedHeader": true,
                "order": [[1, 'asc']],
                "processing": true,
                "data": data,
                "columns": [
                    {data: 'reference'},
                    {data: 'created_at'},
                    {data: 'value'},
                    {data: 'real_amount'},
                    {data: 'current_balance'},
                    {data: 'unit_price'},
                    {data: 'total_amount'},
                ],
                "columnDefs": [],
            });
        }

        document.addEventListener("DOMContentLoaded", function () {

            $(document).on("click", ".sh", function () {
                let reciver = $(this).data('reciver');
                let amount = $(this).data('amount');
                $('#balances-amountsh').attr('value', amount);
                $('#balances-reciversh').attr('value', reciver);
                window.url = "{{ route('api_shares_solde_list', ['locale'=> app()->getLocale(),'amount' => 'amount1','idUser' => 'idUser1']) }}";
                window.url = window.url.replace('idUser1', reciver);
                window.url = window.url.replace('amount1', amount);

                $(document).ready(function () {
                    $.ajax({
                        url: window.url,
                        method: 'GET',
                        headers: {
                            'Authorization': 'Bearer ' + "{{ generateUserToken() }}"
                        },
                        success: function (data) {
                            console.log(data)
                            createOrUpdateDataTablesh(data);
                        },
                        error: function (xhr, status, error) {
                            console.error('Error fetching data:', error);
                        }
                    });
                });
            });
        });

        $(document).on("click", ".addCash", function () {
            let reciver = $(this).data('reciver');
            let phone = $(this).data('phone');
            let country = $(this).data('country');
            $('#userlist-country').attr('src', country);
            $('#userlist-reciver').attr('value', reciver);
            $('#userlist-phone').attr('value', phone);
        });

        $("#userlist-submit").on('click', function (eventUserListTransfert) {
            $(this).prop("disabled", true);
            eventUserListTransfert.preventDefault();
            eventUserListTransfert.stopImmediatePropagation();
            transferCash(parseInt($('#ammount').val()));
            $('#ammount').val(0);
            $(this).prop("disabled", false);
        });

        $(document).on("click", ".vip", function () {
            let reciver = $(this).data('reciver');
            let phone = $(this).data('phone');
            let country = $(this).data('country');
            $('#vip-country').attr('src', country);
            $('#vip-reciver').attr('value', reciver);
            $('#vip-phone').attr('value', phone);
        });

        $(document).on("click", "#updatePasswordBtn", function () {
            let id = $(this).data('id');
            let phone = $(this).data('phone');
            $('#updatePassword').attr('data-id', id);
            $('#userIdMark').html(phone);
        });

        $(document).on("click", "#password-update-submit", function () {
            window.Livewire.dispatch('changePassword', [$('#updatePassword').attr('data-id'), $('#updatePasswordInput').val()]);
        });

        $("#vip-submit").one("click", function () {
            console.log($('#vip-reciver').val())
            let reciver = $('#vip-reciver').val();
            let minshares = $('#minshares').val();
            let periode = $('#periode').val();
            let coefficient = $('#coefficient').val();
            let note = $('#note').val();
            let date = Date.now();
            let msgvip = "{{__('The user')}} " + reciver + " {{__('is VIP(x')}}" + coefficient + " {{__(') for a period of')}} " + periode + " {{__('from')}} " + Date().toLocaleString() + " {{__('with a minimum of')}} " + minshares + " {{__('shares bought')}}";
            let swalTitle = "{{__('VIP mode')}}";
            let user = 126;
            if (minshares && periode && coefficient) {
                $.ajax({
                    url: "{{ route('vip',app()->getLocale()) }}",
                    headers: {'Authorization': 'Bearer ' + "{{generateUserToken()}}"},
                    type: "POST",
                    data: {
                        reciver: reciver,
                        minshares: minshares,
                        periode: periode,
                        coefficient: coefficient,
                        note: note,
                        date: date,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function (data) {
                        $.ajax({
                            url: "{{ route('send_sms',app()->getLocale()) }}",
                            type: "POST",
                            data: {user: user, msg: msgvip, "_token": "{{ csrf_token() }}"},
                            success: function (data) {
                                fireSwalInformMessage('success', swalTitle, msgvip + '<br> <span class="text-success">{{__('SMS sending succeeded')}}</span>');
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                fireSwalInformMessage('warning', swalTitle, msgvip + '<br> <span class="text-danger">{{__('SMS sending failed')}}</span>')
                            }
                        });
                        $('.btn-vip-close').trigger('click');
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        fireSwalInformMessage('error', swalTitle, '{{__('VIP mode activation failed')}}')
                    }
                });
            } else {
                fireSwalInformMessage('error', swalTitle, '{{__('Please check form data')}}')
            }
        });
    </script>
</div>

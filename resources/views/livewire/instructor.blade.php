<div class="{{getContainerType()}}">
    <div>
        @section('title')
            {{ __('Instructor requests') }}
        @endsection
        @component('components.breadcrumb')
            @slot('title')
                {{ __('Instructor requests') }}
            @endslot
        @endcomponent
        <div class="row">
            @include('layouts.flash-messages')
        </div>
        <div class="row">
            @forelse($instructorRequests as $instructorRequest)
                <div class="col-sm-12 col-md-6 col-lg-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 position-relative">
                                    <img src="{{\App\Models\User::getUserProfileImage($instructorRequest->user->idUser)}}"
                                         alt="{{getUserDisplayedName($instructorRequest->user->idUser)}}"
                                         class="avatar-lg rounded-circle border border-3 border-light shadow-sm"
                                         onerror="this.src='{{asset('images/users/default-avatar.png')}}'"/>
                                    <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-light rounded-circle">
                                        <span class="visually-hidden">{{__('Active')}}</span>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="card-title mb-1 fw-semibold">
                                        {{getUserDisplayedName($instructorRequest->user->idUser)}}
                                    </h5>
                                    <p class="text-muted mb-0 small">
                                        <i class="ri-presentation-line me-1"></i>{{__('Instructor')}}
                                    </p>
                                </div>
                            </div>

                            <div class="border-top pt-3 mt-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ri-phone-line text-primary me-2"></i>
                                    <span class="text-muted small">{{__('Phone')}}</span>
                                    <span class="ms-auto fw-medium">{{$instructorRequest->fullphone_number}}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="ri-calendar-line text-primary me-2"></i>
                                    <span class="text-muted small">{{__('Request Date')}}</span>
                                    <span class="ms-auto fw-medium">{{$instructorRequest->request_date}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <div class="d-grid">
                                <a href="{{route('requests_instructor_show', ['locale' => app()->getLocale(), 'id' => $instructorRequest->id]) }}"
                                   class="btn btn-primary btn-sm waves-effect waves-light">
                                    <i class="ri-eye-line me-1 align-bottom"></i>
                                    {{__('See Details')}}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body text-center py-5">
                            <div class="mb-4">
                                <i class="ri-inbox-line display-4 text-muted"></i>
                            </div>
                            <h5 class="mb-2">{{__('No Instructor requests found')}}</h5>
                            <p class="text-muted mb-0">{{__('There are no instructor requests at the moment.')}}</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

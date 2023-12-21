@extends('layouts.employer.master')

@section('title', 'User Profile - ')

@section('breadcrumb', 'User Profile')

@push('styles')
    <link href="{{ asset('backendfiles/assets/css/users/user-profile.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backendfiles/assets/css/users/account-setting.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backendfiles/plugins/dropify/dropify.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .form {
            width: 100% !important;
        }

        .user-profile .widget-content-area h3::after {
            bottom: 2px;
            left: -5px;
        }
        .general-info .info .upload {
            border-right: 2px solid #ebedf2 !important;
            padding: 8px !important;
        }
        .user-profile .widget-content-area .user-info-list ul.contacts-block {
            max-width: 300px !important;
        }
    </style>
@endpush

@section('content')

    @php
    $user = Auth::guard('web')->user();
    @endphp
        <div class="row layout-spacing">
            <div class="col-xl-4 col-lg-6 col-md-5 col-sm-12 layout-top-spacing">
                <div class="user-profile layout-spacing">
                    <div class="widget-content widget-content-area">
                        <div class="d-flex justify-content-between">
                            <h3 class="">Profile Info</h3>

                        </div>
                        <div class="text-center user-info">
                            @if (isset($user->employer->profile_id)  && $user->employer->profile_id != null)
                                <img src="{{ getThumbnailUrl($user->employer->profile_id) }}" alt="avatar"
                                    style="width: 125px; height:auto;">
                            @else
                                <div class="logotext mx-auto" style="width:26%;">
                                    {{-- {{ substr($user->name, 0, 1) }} --}}
                                </div>
                            @endif
                            <p class="">{{ $user->firstname ?? '' }}</p>
                        </div>
                        <div class="user-info-list">

                            <div class="">
                                <ul class="contacts-block list-unstyled">
                                    
                                    <li class="contacts-block__item">
                                        <a href="mailto:example@mail.com"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="feather feather-mail">
                                                <path
                                                    d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                                </path>
                                                <polyline points="22,6 12,13 2,6"></polyline>
                                            </svg>{{ $user->email ?? 'N/A'}}</a>
                                    </li>
                                    <li class="contacts-block__item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>{{ $user->address ?? 'N/A' }}
                                    </li>

                                    <li class="contacts-block__item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone">
                                            <path
                                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                            </path>
                                        </svg>{{ $user->phone ?? 'N/A'}}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-lg-8 col-md-8 layout-top-spacing">
                <form action="{{ route('employer.userProfileUpdate') }}" method="POST" id="general-info"
                    class="section general-info" enctype="multipart/form-data">
                    @csrf
                    <div class="info">
                        <h6 class="mb-4">General Information</h6>
                        <div class="row">

                            <div class="col-xl-2 col-lg-12 col-md-2 pr-0">
                                <div class="upload mt-2 mx-auto">
                                    <input type="file" id="input-file-max-fs" name="profile_image"
                                        value="{{ old('profile_image') ?? ''}}" class="dropify"
                                        data-default-file="{{ getThumbnailUrl($user->profile_image_id) ?? asset('backendfiles/assets/img/200x200.jpg') }}"
                                        data-max-file-size="2M" />
                                    <p class="mt-2">Upload Picture
                                    </p>
                                </div>
                            </div>
                            <div class="col-xl-10 col-lg-10 col-md-10 mt-md-0 mt-2">
                                <div class="form">
                                    <div class="row">
                                        <div class="col-sm-6 mb-4">
                                            {{-- @dd($user); --}}
                                            <label for="fullName">Full Name</label>
                                            <input type="text" name="firstname" class="form-control" id="fullName"
                                                placeholder="Enter Your Full Name" value="{{ $user->firstname ?? old('firstname') }}"
                                                required>
                                            @error('firstname')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror

                                        </div>

                                        <div class="col-sm-6 mb-4">
                                            <label class="dob-input">Email</label>
                                            <input type="text" class="form-control" name="email" id="fullName"
                                                placeholder="Enter Your Email" value="{{ $user->email ?? old('email') }}"
                                                required>
                                            @error('email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        {{-- <div class="col-sm-6 mb-4">
                                            <label class="dob-input">Phone No.</label>
                                            <input type="text" name="phone" class="form-control" id="fullName"
                                                placeholder="Enter Your Phone No."
                                                value="{{ $user->phone ?? old('phone') }}" required>
                                            @error('phone')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror

                                        </div> --}}

                                        <div class="col-sm-6 mb-4">
                                            <label class="dob-input">Address</label>
                                            <input type="text" name="address" class="form-control" id="fullName"
                                                placeholder="Enter Your Address"
                                                value="{{ $user->address ?? old('address') }}" required>
                                            @error('address')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 mb-4">
                                            <label class="dob">Date Of Birth</label>
                                            <input type="date" name="dob" class="form-control" id="dob"
                                                placeholder="Enter Your Date Of Birth"
                                                value="{{ $user->employer->dob != null ? $user->employer->dob->format('Y-m-d') : (old('dob',date('Y-m-d'))??'') }}" required>
                                            @error('dob')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- @dd($user) --}}
                                </div>
                            </div>
                            <div class="col-md-7 col-lg-7 col-xl-7">
                                <button type="submit" class="btn btn-primary float-right">Update</button>
                            </div>

                        </div>

                    </div>
                </form>

                <br>
                {{-- passwor Change --}}
                {{-- <form id="general-info" action="{{ route('backend.passwordUpdate') }}" method="POST"
                    class="section general-info w-100">
                    @csrf
                    <div class="info">
                        <h6 class="">Change Password</h6>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 mx-auto">

                                <div class="form">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label class="dob-input">Current Password</label>
                                                <input type="password" class="form-control pwds" name="current_password"
                                                    id="current_password " placeholder="Current Password">
                                                @error('current_password')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label for="new_password">New Password</label>
                                                <input type="password" class="form-control pwds" name="new_password"
                                                    id="new_password" placeholder="New Password">
                                                @error('new_password')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label class="dob-input">Confirm Password</label>
                                                <input type="password" class="form-control pwds" name="confirm_password"
                                                    id="confirm_password" placeholder="Confirm Password">
                                                @error('confirm_password')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror

                                            </div>
                                        </div>

                                        <div class="col-md-7 col-lg-7 col-xl-7">
                                            <div class="d-sm-flex justify-content-between">
                                                <div class="field-wrapper toggle-pass">
                                                    <p class="d-inline-block text-primary">Show Password</p>
                                                    <label class="switch s-primary">
                                                        <input type="checkbox" class="password_show">
                                                        <span class="slider round"></span>
                                                    </label>
                                                </div>
                                                <button type="submit" class="btn btn-primary float-right">Update</button>
                                            </div>

                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </form> --}}
            </div>


        </div>
    </div>



@endsection

@push('scripts')
    <script src="{{ asset('backendfiles/plugins/dropify/dropify.min.js') }}"></script>
    <script src="{{ asset('backendfiles/plugins/blockui/jquery.blockUI.min.js') }}"></script>
    <script src="{{ asset('backendfiles/assets/js/users/account-settings.js') }}"></script>

    <script>
        if ($('.password_show').is(':checked')) {
            $('.pwds').attr('type', 'text');
        } else {
            $('.pwds').attr('type', 'password')
        }

        $(document).on('click', '.password_show', function() {
            if ($('.password_show').is(':checked')) {
                $('.pwds').attr('type', 'text');
            } else {
                $('.pwds').attr('type', 'password')
            }
        })
    </script>
@endpush

@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="pagetitle">
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item">Users</li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>
    </div>

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">

                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                        <img src="{{ $user->image ? asset('storage/' . $user->image) : asset('niceadmin/assets/img/agent-dummy.webp') }}"
                            alt="Profile" class="rounded-circle rounded-circle border border-dark border-1">
                        <h2>{{ $user->name }}</h2>
                        <h3>{{ $user->getRoleNames()->first() }}</h3>
                    </div>
                </div>

            </div>

            <div class="col-xl-8">

                <div class="card">
                    <div class="card-body pt-3">
                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered">

                            <li class="nav-item">
                                <button class="nav-link {{ request('tab') == 'profile-overview' || empty(request('tab')) ? 'active' : '' }}" onclick="changeTabs('profile-overview')" data-bs-toggle="tab"
                                    data-bs-target="#profile-overview">Overview</button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link {{ request('tab') == 'profile-edit' ? 'active' : '' }}" onclick="changeTabs('profile-edit')" data-bs-toggle="tab"
                                    data-bs-target="#profile-edit">Edit Profile</button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link {{ request('tab') == 'profile-change-password' ? 'active' : '' }}" onclick="changeTabs('profile-change-password')"
                                    data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link {{ request('tab') == 'profile-settings' ? 'active' : '' }}" data-bs-toggle="tab" onclick="changeTabs('profile-settings')"
                                    data-bs-target="#profile-settings">Settings</button>
                            </li>

                        </ul>
                        <div class="tab-content pt-2">

                            <div class="tab-pane fade {{ request('tab') == 'profile-overview' || empty(request('tab')) ? 'show active' : '' }} profile-overview" id="profile-overview">

                                <h5 class="card-title">Profile Details</h5>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label ">Full Name</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->name }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Phone</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->phone }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Email</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->email }}</div>
                                </div>

                            </div>

                            <div class="tab-pane fade {{ request('tab') == 'profile-edit' ? 'show active' : '' }} profile-edit pt-3" id="profile-edit">

                                <!-- Profile Edit Form -->
                                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" novalidate class="needs-validation">
                                    @csrf
                                    @method('PATCH')
                                    <div class="row mb-3">
                                        <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile
                                            Image</label>
                                        <div class="col-md-8 col-lg-9">
                                            <img src="{{ $user->image ? asset('storage/' . $user->image) : asset('niceadmin/assets/img/agent-dummy.webp') }}"
                                                alt="Profile" id="profileImg">
                                            <div class="pt-2 d-flex justify-between gap-2">
                                                <input type="hidden" name="delete_image" value=false id="imageInput">
                                                <input class="form-control" type="file" name="image" id="imageVal">
                                                <a href="#" class="btn btn-danger btn-sm pt-2 pb-2" onclick="removeImage()"
                                                    title="Remove my profile image"><i class="bi bi-trash"></i></a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                                        <div class="col-md-8 col-lg-9 has-validation">
                                            <input name="name" type="text" class="form-control" id="fullName"
                                                value="{{ $user->name }}" required>
                                            <div class="invalid-feedback">Please enter your name.</div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="phone" type="text" class="form-control" id="Phone"
                                                value="{{ $user->phone }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="email" type="email" class="form-control" id="Email"
                                                value="{{ $user->email }}" required>
                                            <div class="invalid-feedback">Please enter your email.</div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-end">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form><!-- End Profile Edit Form -->

                            </div>

                            <div class="tab-pane fade {{ request('tab') == 'profile-settings' ? 'show active' : '' }} pt-3" id="profile-settings">

                                <!-- Settings Form -->
                                <form>

                                    <div class="row mb-3">
                                        <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Email
                                            Notifications</label>
                                        <div class="col-md-8 col-lg-9">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="changesMade" checked>
                                                <label class="form-check-label" for="changesMade">
                                                    Changes made to your account
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="newProducts" checked>
                                                <label class="form-check-label" for="newProducts">
                                                    Information on new products and services
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="proOffers">
                                                <label class="form-check-label" for="proOffers">
                                                    Marketing and promo offers
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="securityNotify"
                                                    checked disabled>
                                                <label class="form-check-label" for="securityNotify">
                                                    Security alerts
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-end">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form><!-- End settings Form -->

                            </div>

                            <div class="tab-pane fade {{ request('tab') == 'profile-change-password' ? 'show active' : '' }} pt-3" id="profile-change-password">
                                <!-- Change Password Form -->
                                <form method="POST" class="needs-validation" novalidate
                                    action="{{ route('password.update') }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="row mb-3">
                                        <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current
                                            Password</label>
                                        <div class="col-md-8 col-lg-9 has-validation">
                                            <input name="current_password" type="password" class="form-control"
                                                id="currentPassword" required>
                                            <div class="invalid-feedback">Please enter your password.</div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New
                                            Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="password" type="password" class="form-control" id="newPassword"
                                                required>
                                            <div class="invalid-feedback">Please enter your new password.</div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New
                                            Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="password_confirmation" type="password" class="form-control"
                                                id="renewPassword" required>
                                            <div class="invalid-feedback">Please re-enter your new password.</div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-end">
                                        <button type="submit" class="btn btn-primary">Change Password</button>
                                    </div>
                                </form><!-- End Change Password Form -->

                            </div>

                        </div><!-- End Bordered Tabs -->

                    </div>
                </div>

            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Add event listeners to nav links for dynamic tab changes
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function () {
                    const targetTab = link.getAttribute('data-bs-target')?.replace('#', '');
                    if (targetTab) changeTabs(targetTab);
                });
            });
        });

        // Function to handle tab changes
        function changeTabs(tabId) {
            changeUrl(`?tab=${tabId}`);
        }

        // Function to change the URL without refreshing the page
        function changeUrl(newPath) {
            if (window.history.pushState) {
                window.history.pushState(null, null, newPath);
            } else {
                console.warn("Your browser does not support history manipulation.");
            }
        }
        
        function removeImage() {
            let imageInput = document.getElementById('imageInput');
            let imageVal = document.getElementById('imageVal');
            let profileImg = document.getElementById('profileImg');
            
            // Update the profile image to the placeholder
            profileImg.src = 'niceadmin/assets/img/agent-dummy.webp';

            // Mark the input for deletion
            imageInput.value = true;
            imageVal.value = '';
        }
    </script>
@endsection

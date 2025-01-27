@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="pagetitle">
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item">Users</li>
                <li class="breadcrumb-item active">List</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-12 mt-3 d-flex" style="justify-content: flex-end">
                                <button class="btn btn-small btn-primary d-flex" style="align-items: center" data-bs-toggle="modal" data-bs-target="#addModal">
                                    <i class="bx bxs-plus-circle me-1"></i>
                                    <small>Add User</small>
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="custom-table table-striped mt-4">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="border-0 rounded-start">#</th>
                                        <th class="border-0 text-center">Action</th>
                                        <th class="border-0 text-center" width="25%">Name</th>
                                        <th class="border-0 text-center" width="10%">Role</th>
                                        <th class="border-0 text-center">Email</th>
                                        <th class="border-0 rounded-end text-center">Phone</th>
                                    </tr>
                                </thead>
                                <tbody style="vertical-align: middle">
                                    @forelse ($users as $index => $user)
                                    <tr>
                                            <td>{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                                            <td width="10%" class="text-center">
                                                <button type="button" class="btn btn-secondary btn-xs d-block d-sm-inline mb-sm-0 mb-1" onclick="editProfile({{ $user->id }})"><i class="bx bxs-user-detail"></i></button>
                                                <button type="button" class="btn btn-outline-danger btn-xs d-block d-sm-inline mb-sm-0 mb-1"><i class="bi bi-trash" onclick="openDeleteModal({{ $user->id }})"></i></button>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2" style="align-items: center">
                                                    <img src="{{ $user->image ? asset('storage/' . $user->image) : asset('niceadmin/assets/img/agent-dummy.webp') }}" alt="profile-image" class="img-fluid rounded-circle border border-dark border-1" width="20%"> 
                                                    {{ $user->name }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if ($user->getRoleNames()->first())
                                                    <span class="badge bg-secondary">{{ $user->getRoleNames()->first() }}</span>
                                                @else
                                                    <small class="text-secondary">none</small>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $user->email }}</td>
                                            <td class="text-center">
                                                @if ($user->phone)
                                                    {{ $user->phone }}
                                                @else
                                                    <small class="text-secondary">none</small>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        No data available
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- update user modal --}}
    <div class="modal fade" id="userModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">User Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="contentModal">
                    
                </div>
            </div>
        </div>
    </div>
    {{-- end update user modal --}}

    <!-- delete modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this user? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <!-- Form to handle delete action -->
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- end delete modal --}}

        <!-- delete modal -->
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Add User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="/users" method="POST" enctype="multipart/form-data" novalidate class="needs-validation">
                        @csrf
                        <div class="modal-body">
                            <div class="row mb-3">
                                <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                                <div class="col-md-8 col-lg-9">
                                    <input class="form-control" type="file" name="image" id="imageVal">
                                </div>
                            </div>
    
                            <div class="row mb-3">
                                <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                                <div class="col-md-8 col-lg-9 has-validation">
                                    <input name="name" type="text" class="form-control" id="fullName" required>
                                    <div class="invalid-feedback">Please enter your name.</div>
                                </div>
                            </div>
    
                            <div class="row mb-3">
                                <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                                <div class="col-md-8 col-lg-9">
                                    <input name="phone" type="text" class="form-control" id="Phone">
                                </div>
                            </div>
    
                            <div class="row mb-3">
                                <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                <div class="col-md-8 col-lg-9">
                                    <input name="email" type="email" class="form-control" id="Email" required>
                                    <div class="invalid-feedback">Please enter your email.</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-lg-3 col-form-label">Password</label>
                                <div class="col-md-8 col-lg-9">
                                    <input name="password" type="password" class="form-control" id="password" required>
                                    <div class="invalid-feedback">Please enter your password.</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="Role" class="col-md-4 col-lg-3 col-form-label">Role</label>
                                <div class="col-md-8 col-lg-9 has-validation">
                                    <select name="role" id="Role" class="form-select" required>
                                        <option value="">select a role</option>
                                        @forelse ($roles as $role)
                                            <option value="{{ $role->name }}">
                                                {{ $role->name }}
                                            </option>
                                        @empty
                                        @endforelse
                                    </select>
                                    <div class="invalid-feedback">Please select your role.</div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- end delete modal --}}


    <script>
        function editProfile(id) {
            const responseModal = new bootstrap.Modal(document.getElementById('userModal'));
            const contentModal = document.getElementById('contentModal');

            axios.get(`/api/users/${id}`)
                .then(response => {
                    
                    contentModal.innerHTML = `<section class="section profile">
                                                <div class="row">
                                                    <div class="col-xl-4">

                                                        <div class="card">
                                                            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                                                                <img src="${ response.data.image_path }"
                                                                    alt="Profile" class="rounded-circle rounded-circle border border-dark border-1">
                                                                <h2>${response.data.name}</h2>
                                                                <h3>${response.data.role ?? ""}</h3>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="col-xl-8">

                                                        <div class="card">
                                                            <div class="card-body pt-3">
                                                                <ul class="nav nav-tabs nav-tabs-bordered">

                                                                    <li class="nav-item">
                                                                        <button class="nav-link active" data-bs-toggle="tab"
                                                                            data-bs-target="#profile-overview">Overview</button>
                                                                    </li>

                                                                    <li class="nav-item">
                                                                        <button class="nav-link" data-bs-toggle="tab"
                                                                            data-bs-target="#profile-edit">Edit Profile</button>
                                                                    </li>

                                                                    <li class="nav-item">
                                                                        <button class="nav-link"
                                                                            data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                                                                    </li>

                                                                    <li class="nav-item">
                                                                        <button class="nav-link" data-bs-toggle="tab"
                                                                            data-bs-target="#profile-settings">Settings</button>
                                                                    </li>

                                                                </ul>
                                                                <div class="tab-content pt-2">

                                                                    <div class="tab-pane fade show active profile-overview" id="profile-overview">

                                                                        <h5 class="card-title">Profile Details</h5>

                                                                        <div class="row">
                                                                            <div class="col-lg-3 col-md-4 label ">Full Name</div>
                                                                            <div class="col-lg-9 col-md-8">${ response.data.name }</div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="col-lg-3 col-md-4 label">Phone</div>
                                                                            <div class="col-lg-9 col-md-8">${ response.data.phone ?? "-"}</div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="col-lg-3 col-md-4 label">Email</div>
                                                                            <div class="col-lg-9 col-md-8">${ response.data.email }</div>
                                                                        </div>

                                                                    </div>

                                                                    <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                                                                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" novalidate class="needs-validation">
                                                                            @csrf
                                                                            @method('PATCH')
                                                                            <input name="id" type="hidden" value="${response.data.id}">
                                                                            <div class="row mb-3">
                                                                                <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile
                                                                                    Image</label>
                                                                                <div class="col-md-8 col-lg-9">
                                                                                    <img src="${ response.data.image_path }"
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
                                                                                        value="${ response.data.name }" required>
                                                                                    <div class="invalid-feedback">Please enter your name.</div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-3">
                                                                                <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                                                                                <div class="col-md-8 col-lg-9">
                                                                                    <input name="phone" type="text" class="form-control" id="Phone"
                                                                                        value="${ response.data.phone ?? "" }">
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-3">
                                                                                <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                                                                <div class="col-md-8 col-lg-9">
                                                                                    <input name="email" type="email" class="form-control" id="Email"
                                                                                        value="${ response.data.email }" required>
                                                                                    <div class="invalid-feedback">Please enter your email.</div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-3">
                                                                                <label for="Role" class="col-md-4 col-lg-3 col-form-label">Role</label>
                                                                                <div class="col-md-8 col-lg-9 has-validation">
                                                                                    <select name="role" id="Role" class="form-select" required>
                                                                                        <option value="">select a role</option>
                                                                                        @forelse ($roles as $role)
                                                                                            <option value="{{ $role->name }}" 
                                                                                                    ${response.data.role === "{{ $role->name }}" ? 'selected' : ''}>
                                                                                                {{ $role->name }}
                                                                                            </option>
                                                                                        @empty
                                                                                        @endforelse
                                                                                    </select>
                                                                                    <div class="invalid-feedback">Please select your role.</div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="d-flex justify-end">
                                                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                                                            </div>
                                                                        </form><!-- End Profile Edit Form -->

                                                                    </div>

                                                                    <div class="tab-pane fade profile-settings pt-3" id="profile-settings">

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

                                                                    <div class="tab-pane fade pt-3" id="profile-change-password">
                                                                        <!-- Change Password Form -->
                                                                        <form method="POST" class="needs-validation" novalidate
                                                                            action="{{ route('password.update') }}">
                                                                            @csrf
                                                                            @method('PUT')
                                                                            <input name="id" type="hidden" value="${response.data.id}">
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
                                            </section>`
                    responseModal.show();
                })
                .catch(error => {
                    responseModal.show();
                });
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

        function openDeleteModal(userId) {
            const form = document.getElementById('deleteForm');
            form.action = `/users/${userId}`; // Dynamically set the delete route

            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }
    </script>
@endsection

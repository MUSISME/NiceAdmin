@extends('layouts.app')

@section('title', 'Roles')

@section('content')
    <div class="pagetitle">
        <h1>Roles</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item">Roles</li>
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
                                    <small>Add Role</small>
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="custom-table table-striped mt-4">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="border-0 rounded-start" width="10%">#</th>
                                        <th class="border-0 text-center" width="10%">Action</th>
                                        <th class="border-0 text-center" width="30%">Name</th>
                                        <th class="border-0 text-center rounded-end" width="50%">Permissions</th>
                                    </tr>
                                </thead>
                                <tbody style="vertical-align: middle">
                                    @forelse ($roles as $index => $role)
                                    <tr>
                                            <td>{{ ($roles->currentPage() - 1) * $roles->perPage() + $index + 1 }}</td>
                                            <td width="10%" class="text-center">
                                                <button type="button" class="btn btn-secondary btn-xs d-block d-sm-inline mb-sm-0 mb-1" onclick="edit({{ $role->id }})"><i class="bi bi-pencil-square"></i></button>
                                                <button type="button" class="btn btn-outline-danger btn-xs d-block d-sm-inline mb-sm-0 mb-1"><i class="bi bi-trash" onclick="openDeleteModal({{ $role->id }})"></i></button>
                                            </td>
                                            <td class="text-center">{{ $role->name }}</td>
                                            <td class="text-start" style="white-space: wrap">
                                                @forelse ($role->permissions as $permission)
                                                    <span class="badge bg-secondary" style="font-size: 10px">{{ $permission->name }}</span>
                                                @empty
                                                no permission
                                                @endforelse
                                            </td>
                                        </tr>
                                    @empty
                                        No data available
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        {{ $roles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- add modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Add Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/roles" method="POST" novalidate class="needs-validation">
                    @csrf
                    <div class="modal-body">

                        <div class="row mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label">Name</label>
                            <div class="col-md-8 col-lg-9 has-validation">
                                <input name="name" type="text" class="form-control" required>
                                <div class="invalid-feedback">Please enter role name.</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="permissionSelect" class="col-md-4 col-lg-3 col-form-label">Permissions</label>
                            <div class="col-sm-12 col-lg-8 has-validation">
                                <div class="row m-2">
                                    @forelse ($permissions as $permission)
                                        <div class="form-check col-6 col-sm-6 col-lg-4">
                                            <input class="form-check-input" type="checkbox" value="{{ $permission->name }}" name="permissions[]" id="flexCheckDefault{{ $permission->id }}" >
                                            <label class="form-check-label" for="flexCheckDefault{{ $permission->id }}">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @empty
                                    @endforelse
                                </div>
                                <div class="invalid-feedback">Please select your desired permission.</div>
                            </div>
                        </div>`
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- end add modal --}}

    {{-- update user modal --}}
    <div class="modal fade" id="userModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="roleForm">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body" id="contentModal">
                    
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
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

    <script>                  
        
        function edit(id) {
            const responseModal = new bootstrap.Modal(document.getElementById('userModal'));
            const contentModal = document.getElementById('contentModal');
            const name = document.getElementById('name');

            axios.get(`/api/roles/${id}`)
                .then(response => {
                    let name = response.data.name
                    let permissions = response.data.permissions

                    contentModal.innerHTML = `<div class="row mb-3">
                        <label class="col-md-4 col-lg-3 col-form-label">Name</label>
                        <div class="col-md-8 col-lg-9 has-validation">
                            <input name="name" type="text" class="form-control" required id="name" value="${name}">
                            <div class="invalid-feedback">Please enter role name.</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="permissionSelect" class="col-md-4 col-lg-3 col-form-label">Permissions</label>
                        <div class="col-sm-12 col-lg-8 has-validation">
                            <div class="row m-2">
                                @forelse ($permissions as $permission)
                                    <div class="form-check col-6 col-sm-6 col-lg-4">
                                        <input class="form-check-input" type="checkbox" value="{{ $permission->name }}" name="permissions[]" id="flexCheckDefault{{ $permission->id }}" ${permissions.some(permission => permission.name === '{{ $permission->name }}') ? "checked" : ""} >
                                        <label class="form-check-label" for="flexCheckDefault{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                @empty
                                @endforelse
                            </div>
                            <div class="invalid-feedback">Please select your desired permission.</div>
                        </div>
                    </div>`

                    const form = document.getElementById('roleForm');
                    form.action = `/roles/${id}`;

                    responseModal.show();
                })
                .catch(error => {
                    // responseModal.show();
                });
        }

        function openDeleteModal(id) {
            const form = document.getElementById('deleteForm');
            form.action = `/api/roles/${id}`; // Dynamically set the delete route

            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }
    </script>
@endsection

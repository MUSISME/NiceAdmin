@extends('layouts.app')

@section('title', 'Permissions')

@section('content')
    <div class="pagetitle">
        <h1>Permissions</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item">Permissions</li>
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
                                <button class="btn btn-small btn-primary d-flex" style="align-items: center" data-bs-toggle="modal" data-bs-target="#permissionModal">
                                    <i class="bx bxs-plus-circle me-1"></i>
                                    <small>Add Permission</small>
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="custom-table table-striped mt-4">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="border-0 rounded-start" width="10%">#</th>
                                        <th class="border-0 text-center" width="10%">Action</th>
                                        <th class="border-0 text-center" width="60%">Name</th>
                                        <th class="border-0 text-center rounded-end" width="20%">Guard Type</th>
                                    </tr>
                                </thead>
                                <tbody style="vertical-align: middle">
                                    @forelse ($permissions as $index => $permission)
                                    <tr class="text-center">
                                            <td>{{ ($permissions->currentPage() - 1) * $permissions->perPage() + $index + 1 }}</td>
                                            <td width="10%" class="text-center">
                                                <button type="button" class="btn btn-secondary btn-xs d-block d-sm-inline mb-sm-0 mb-1" onclick="edit({{ $permission->id }})"><i class="bi bi-pencil-square"></i></button>
                                                <button type="button" class="btn btn-outline-danger btn-xs d-block d-sm-inline mb-sm-0 mb-1"><i class="bi bi-trash" onclick="openDeleteModal({{ $permission->id }})"></i></button>
                                            </td>
                                            <td>{{ $permission->name }}</td>
                                            <td>{{ $permission->guard_name }}</td>
                                        </tr>
                                    @empty
                                        No data available
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        {{ $permissions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- add modal -->
    <div class="modal fade" id="permissionModal" tabindex="-1" aria-labelledby="permissionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="permissionLabel">Add permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/permissions" method="POST" novalidate class="needs-validation">
                    @csrf
                    <div class="modal-body">

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-lg-3 col-form-label">Name</label>
                            <div class="col-md-8 col-lg-9 has-validation">
                                <input name="name" type="text" class="form-control" required>
                                <div class="invalid-feedback">Please enter your permission name.</div>
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
    {{-- end add modal --}}

    {{-- update user modal --}}
    <div class="modal fade" id="updatePermissionModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editForm" method="POST" novalidate class="needs-validation">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body" id="contentModal">
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-lg-3 col-form-label">Name</label>
                            <div class="col-md-8 col-lg-9 has-validation">
                                <input name="name" type="text" class="form-control" id="name" required>
                                <div class="invalid-feedback">Please enter your permission name.</div>
                            </div>
                        </div>
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
                    Are you sure you want to delete this permission? This action cannot be undone.
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
            const responseModal = new bootstrap.Modal(document.getElementById('updatePermissionModal'));
            const contentModal = document.getElementById('contentModal');
            const editform = document.getElementById('editForm');
            const name = document.getElementById('name');

            axios.get(`/api/permissions/${id}`)
                .then(response => {
                    name.value = response.data.name
                    editform.action = `/permissions/${id}`; // Direct way
                    responseModal.show();
                })
                .catch(error => {
                    responseModal.show();
                });
        }

        function openDeleteModal(id) {
            const form = document.getElementById('deleteForm');
            form.action = `/permissions/${id}`; // Dynamically set the delete route

            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }
    </script>
@endsection

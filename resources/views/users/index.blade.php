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
                                <button class="btn btn-small btn-primary d-flex" style="align-items: center">
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
                                                <button type="button" class="btn btn-secondary btn-xs d-block d-sm-inline mb-sm-0 mb-1"><i class="bx bxs-user-detail"></i></button>
                                                <button type="button" class="btn btn-outline-danger btn-xs d-block d-sm-inline mb-sm-0 mb-1"><i class="bi bi-trash"></i></button>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2 justify-between" style="align-items: center">
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
                                            <td class="text-center">{{ $user->phone }}</td>
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
@endsection

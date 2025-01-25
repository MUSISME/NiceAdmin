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
                        <table class="table table-striped mt-2">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="text-center">Action</th>
                                    <th class="text-center" width="20%">Profile Image</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                </tr>
                            </thead>
                            <tbody style="vertical-align: middle">
                                @forelse ($users as $index => $user)
                                <tr>
                                        <td>{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                                        <td width="10%" class="text-center">
                                            <button type="button" class="btn btn-outline-secondary btn-sm"><i class="bi bi-pencil-square"></i></button>
                                            <button type="button" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                                        </td>
                                        <td class="text-center">
                                            <img src="{{ $user->image ? asset('storage/' . $user->image) : asset('niceadmin/assets/img/agent-dummy.webp') }}" alt="profile-image" class="img-fluid rounded-circle border border-dark border-1" width="30%">
                                        </td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone }}</td>
                                    </tr>
                                @empty
                                    No data available
                                @endforelse
                            </tbody>
                        </table>
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

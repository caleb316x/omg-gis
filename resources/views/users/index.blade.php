@extends('layouts.appbs')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">User</h1>
    </div>

    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">User List</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @php
                                foreach ($users as $key => $user) {
                                    echo "<tr>";
                                        echo "<td>$user->full_name</td>";
                                        echo "<td>$user->role_name</td>";
                                        echo "<td>";
                                            echo "<button class = 'btn btn-sm btn-info'>View</button> ";
                                            echo "<button class = 'btn btn-sm btn-success'>Update</button> ";
                                            echo "<button class = 'btn btn-sm btn-danger'>Delete</button>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                            @endphp
                        </tbody>
                    </table>

                    {{$users->links()}}
                </div>
            </div>
        </div>

    </div>

@endsection

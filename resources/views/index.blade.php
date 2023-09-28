@extends('layout')
@section('content')
    <div class="card push-top mt-5">
        <div class="card-header">
            Student List
            <span class="float-right">You are logged in {{ auth()->user()->name }}</span><br>
            <a class="float-right" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                          document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form><br><a href="{{ route('students.create') }}" class="btn btn-danger btn-sm float-right">Add New</a>
            <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- <input type="file" name="file" class="form-control">
                <br> --}}
                {{-- <button class="btn btn-success">Import Student Data</button>
                <a class="btn btn-warning" href="{{ route('export') }}">Export Student Data</a> --}}
            </form>
        </div>
        <div class="card-body">
            @if (session()->get('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div><br />
            @endif
            <table class="table">
                <thead>
                    <tr class="table-primary">
                        <td>Id</td>
                        <td>Name</td>
                        <td>Email</td>
                        <td>Phone</td>
                        <td>Password</td>
                        <td>Image</td>
                        <td class="text-center">Action</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($student as $key => $students)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $students->name }}</td>
                            <td>{{ $students->email }}</td>
                            <td>{{ $students->phone }}</td>
                            <td>{{ $students->password }}</td>
                            <td><img src="{{ asset('/Images/' .  $students->photo) }}" style="width: 50px;" class="show-image"></td>
                            <td class="text-center">
                                <a href="{{ route('students.edit', $students->student_id) }}"
                                    class="btn btn-primary btn-sm">Edit</a>
                                <form action="{{ route('students.destroy', $students->student_id) }}" method="post"
                                    style="display: inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure to delete?')" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@extends('admin.main')

@section('content')
    <table class="table" id="myTable" class="m-t-4">
        <thead>
        <tr>
            <th>Email</th>
            <th>Admin</th>
            <th>Biên tập</th>
            <th>Soạn</th>
            <th>Cấp quyền</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
            @foreach($users as $key => $user)
                <form action="{{route('assign-roles')}}" method="POST">
                @csrf
                    <tr>
                        <td>{{ $user->email }} <input type="hidden" name="email" value="{{ $user->email }}"></td>
                        <td><input type="checkbox" name="author_role" {{$user->hasRole('author') ? 'checked' : ''}}></td>
                        <td><input type="checkbox" name="admin_role"  {{$user->hasRole('admin') ? 'checked' : ''}}></td>
                        <td><input type="checkbox" name="user_role"  {{$user->hasRole('user') ? 'checked' : ''}}></td>

                        <td>                                                            
                            <input type="submit" value="Cấp quyền" class="btn btn-sm btn-default">
                            <a class="btn btn-sm btn-default" href="{{url('admin/users/delete/' .$user->id )}}">Xóa user</a>                          
                        </td>

                    </tr>
                </form>
            @endforeach
        </tbody>
    </table>
@endsection 



@extends('admin.main')

@section('content')
    <table class="table" id="myTable">
        <thead>
            <tr>
                <th style="width: 20px">ID</th>
                <th>Tên</th>
                <th>latitude</th>
                <th>longitude sửa</th>
                <th style="width: 100px">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shops as $key => $shop)
            <tr>             
                <td>{{ $shop->id }}</td>
                <td>{{ $shop->name }}</td>
                <td>{{ $shop->latitude}}</td>
                <td>{{ $shop->longitude}}</td>

                <td>
                    <a class="btn btn-primary btn-sm" href="/admin/shop/edit/{{ $shop->id }}">
                        <i class="fas fa-edit"></i>                       
                    </a>
                    <a href="#" class="btn btn-danger btn-sm" onclick="removeRow({{ $shop->id }}, '/admin/shop/destroy')">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection

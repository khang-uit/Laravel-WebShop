@extends('admin.main')

@section('content')
    <table class="table" id="myTable">
        <thead>
            <tr>
                <th style="width: 20px">ID</th>
                <th>Tiêu đề</th>
                <th>Link</th>
                <th>Ảnh</th>
                <th>Trạng thái</th>
                <th>Chỉnh sửa</th>
                <th style="width: 100px">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sliders as $key => $slider)
            <tr>             
                <td>{{ $slider->id }}</td>
                <td>{{ $slider->name }}</td>
                <td>{{ $slider->url}}</td>
                <td>
                    <a href="{{ $slider->thumb }}" target="_blank">
                        <img src="{{ $slider->thumb }}" height="40px">
                    </a>
                </td>
                <td>{!! \App\Helpers\Helper::active($slider->active) !!}</td>
                <td>{{ $slider->updated_at }}</td>
                <td>
                    <a class="btn btn-primary btn-sm" href="/admin/slider/edit/{{ $slider->id }}">
                        <i class="fas fa-edit"></i>                       
                    </a>
                    <a href="#" class="btn btn-danger btn-sm" onclick="removeRow({{ $slider->id }}, '/admin/slider/destroy')">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
<!-- 
    {!! $sliders->links() !!} -->
@endsection

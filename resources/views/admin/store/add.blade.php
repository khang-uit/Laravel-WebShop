@extends('admin.main')

@section('head')
    <script src="/ckeditor/ckeditor.js"></script>
@endsection

@section('content')
    <form action="" method="POST">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="menu">Địa chỉ</label>
                        <input type="text" name="address" value="{{ old('price_sale') }}"  class="form-control" >
                    </div>
                </div>
            </div>
        
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="menu">Thành phố</label>
                        <input type="text" name="city" value="{{ old('price_sale') }}"  class="form-control" >
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="menu">Tỉnh</label>
                        <input type="text" name="state" value="{{ old('price') }}"  class="form-control" >
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="menu">Zipcode</label>
                        <input type="text" name="zipcode" value="{{ old('price_sale') }}"  class="form-control" >
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="menu">Tên chi nhánh</label>
                        <input type="text" name="name" value="{{ old('price') }}"  class="form-control" >
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="menu">Số điện thoại</label>
                        <input type="text" name="phonenumber" value="{{ old('price_sale') }}"  class="form-control" >
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="menu">Nhóm</label>
                        <input type="text" name="group" value="{{ old('price') }}"  class="form-control" >
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="menu">Link website</label>
                        <input type="text" name="url" value="{{ old('price_sale') }}"  class="form-control" >
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="menu">Email</label>
                        <input type="text" name="email" value="{{ old('price') }}"  class="form-control" >
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="menu">Mã latitude trên bản đồ</label>
                        <input type="text" name="latitude" value="{{ old('price_sale') }}"  class="form-control" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="menu">Mã longitude trên bản đồ</label>
                        <input type="text" name="longitude" value="{{ old('price') }}"  class="form-control" >
                    </div>
                </div>
            </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Tạo Chi Nhánh</button>
        </div>
        @csrf
    </form>
@endsection

@section('footer')
    <script>
        CKEDITOR.replace('content');
    </script>
@endsection

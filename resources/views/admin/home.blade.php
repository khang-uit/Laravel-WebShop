@extends('admin.main')

@section('content')
    <div class="container-fluid">
        <style type="text/css">
            p.title_thongke {
                text-align: center;
                font-size: 20px;
                font-weight: bold;
            }
        </style>
        <p class="title_thongke">Thống kê đơn hàng doanh số</p>

        <div class="row-md">
            <form autocomplete="off">
                @csrf
                <div class="col-md-6">
                    <p>Từ ngày: <input type="text" id="datepicker" class="form-control"></p>           
                </div>
                <div class="col-md-6">
                    <p>Đến ngày: <input type="text" id="datepicker2" class="form-control"></p>
                    <input type="button" id="btn-dashboard-filter" class="btn btn-primary btn-sm" value="Lọc kết quả">
                </div>
            </form>
        </div>
        <div class="row-md">
                <div class="col-md-12">
                    <div id="myfirstchart" style="height: 250px;"></div>
                </div>
        </div>
        <div class="row-md">
                <br>

                <div class="col-md-12 mt-5">
                    <h3>Sản phẩm xem nhiều</h3>
                    <ol class="list_views">
                        @foreach($product_views as $key => $product)
                        <li>
                            <a target="_blank" href="/san-pham/{{ $product->id }}-{{ Str::slug($product->name, '-') }}.html"> 
                                <span style="color:black">({{ $product->views }}) </span>{{ $product->name }}
                            </a> 
                        </li>
                        @endforeach
                    </ol>
                </div>
        </div>
    </div>
@endsection